<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\MonthlyFinalRemark;
use App\Services\MonthlyReportService;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class WorkerDashboardController extends Controller
{
    public function __construct(private readonly MonthlyReportService $reports)
    {
    }

    public function index(Request $request)
    {
        $selectedMonth = $this->resolveMonth($request->query('month'));
        $report = $this->reports->buildForUserMonth($request->user(), $selectedMonth);
        $recentActivities = $request->user()
            ->dailyActivities()
            ->latest('activity_date')
            ->limit(10)
            ->get();
        $recentActivities->each(function (DailyActivity $activity): void {
            $activity->setAttribute('whatsapp_url', $this->reports->whatsappUrlForDaily($activity));
        });

        return view('worker.dashboard', [
            'selectedMonth' => $selectedMonth,
            'report' => $report,
            'metricLabels' => MonthlyReportService::metricLabels(),
            'recentActivities' => $recentActivities,
            'monthlyWhatsAppUrl' => route('worker.reports.monthly.whatsapp', ['month' => $selectedMonth->format('Y-m')]),
        ]);
    }

    public function showForm(Request $request)
    {
        $selectedDate = Carbon::parse($request->query('date', now()->toDateString()))->startOfDay();
        $selectedMonth = $this->resolveMonth($request->query('month'));
        $activity = $request->user()
            ->dailyActivities()
            ->whereDate('activity_date', $selectedDate->toDateString())
            ->first();

        return view('worker.form', [
            'selectedDate' => $selectedDate,
            'selectedMonth' => $selectedMonth,
            'activity' => $activity,
            'metricLabels' => MonthlyReportService::metricLabels(),
            'sections' => MonthlyReportService::sections(),
            'dailyWhatsAppUrl' => $activity ? $this->reports->whatsappUrlForDaily($activity) : null,
            'monthlyWhatsAppUrl' => route('worker.reports.monthly.whatsapp', ['month' => $selectedMonth->format('Y-m')]),
        ]);
    }

    public function showDailyReport(Request $request, DailyActivity $activity)
    {
        abort_if($activity->user_id !== $request->user()->id, 404);
        $activity->loadMissing('user');

        return view('reports.daily', [
            'dailyReport' => $this->reports->buildDailyReport($activity),
            'metricLabels' => MonthlyReportService::metricLabels(),
            'whatsAppUrl' => $this->reports->whatsappUrlForDaily($activity),
            'backUrl' => route('worker.dashboard', ['month' => $activity->activity_date->format('Y-m')]),
            'editUrl' => route('worker.daily-activity.form', [
                'date' => $activity->activity_date->format('Y-m-d'),
                'month' => $activity->activity_date->format('Y-m'),
            ]),
        ]);
    }

    public function showPhoto(Request $request, DailyActivity $activity, int $photoIndex)
    {
        abort_if(! $request->user()->isAdmin() && $activity->user_id !== $request->user()->id, 404);

        $photoPath = $activity->photo_paths[$photoIndex] ?? null;

        abort_unless(is_string($photoPath) && filled($photoPath), 404);
        abort_unless(Storage::disk('public')->exists($photoPath), 404);

        return response()->file(Storage::disk('public')->path($photoPath), [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function showDocument(Request $request, DailyActivity $activity, int $documentIndex)
    {
        abort_if(! $request->user()->isAdmin() && $activity->user_id !== $request->user()->id, 404);

        $documentPath = $activity->document_paths[$documentIndex] ?? null;

        abort_unless(is_string($documentPath) && filled($documentPath), 404);
        abort_unless(Storage::disk('public')->exists($documentPath), 404);

        return response()->file(Storage::disk('public')->path($documentPath), [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function submissions(Request $request)
    {
        $selectedMonth = $this->resolveMonth($request->query('month'));
        $activities = $request->user()
            ->dailyActivities()
            ->whereBetween('activity_date', [
                $selectedMonth->copy()->startOfMonth()->toDateString(),
                $selectedMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->latest('activity_date')
            ->paginate(20)
            ->withQueryString();

        $activities->getCollection()->each(function (DailyActivity $activity): void {
            $activity->setAttribute('whatsapp_url', $this->reports->whatsappUrlForDaily($activity));
        });

        return view('worker.submissions', [
            'selectedMonth' => $selectedMonth,
            'activities' => $activities,
            'monthlyWhatsAppUrl' => route('worker.reports.monthly.whatsapp', ['month' => $selectedMonth->format('Y-m')]),
            'monthlyNarrative' => $this->reports->buildForUserMonth($request->user(), $selectedMonth)['monthly_narrative'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateActivity($request);
        unset($validated['photos'], $validated['documents']);
        $activityDate = Carbon::parse($validated['activity_date'])->toDateString();
        $existingActivity = DailyActivity::query()
            ->where('user_id', $request->user()->id)
            ->whereDate('activity_date', $activityDate)
            ->first();
        $photoPaths = $existingActivity?->photo_paths ?? [];
        $documentPaths = $existingActivity?->document_paths ?? [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos', []) as $photo) {
                $photoPaths[] = $photo->store('daily-activity-proofs', 'public');
            }
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents', []) as $document) {
                $documentPaths[] = $document->store('daily-activity-documents', 'public');
            }
        }

        $userId = $request->user()->id;

        $values = [
            ...$validated,
            'user_id' => $userId,
            'activity_date' => $activityDate,
            'photo_paths' => $photoPaths,
            'document_paths' => $documentPaths,
            'updated_at' => now(),
        ];

        // Avoid unique-constraint crashes on double-submit or slow networks:
        // do update first; if no row exists, create; if a race happens, retry update.
        try {
            $updated = DailyActivity::query()
                ->where('user_id', $userId)
                ->whereDate('activity_date', $activityDate)
                ->update($values);

            if ($updated === 0) {
                DailyActivity::create($values);
            }
        } catch (UniqueConstraintViolationException) {
            DailyActivity::query()
                ->where('user_id', $userId)
                ->whereDate('activity_date', $activityDate)
                ->update($values);
        }

        return redirect()
            ->route('worker.daily-activity.form', [
                'date' => $activityDate,
                'month' => Carbon::parse($activityDate)->format('Y-m'),
            ])
            ->with('status', 'Daily activity saved successfully.');
    }

    public function downloadMonthlyReport(Request $request)
    {
        $month = $this->resolveMonth($request->query('month'));

        return $this->reports->streamCsv(
            $this->reports->buildForUserMonth($request->user(), $month),
            $request->user()->name.'-'.$month->format('Y-m').'-report.csv'
        );
    }

    public function downloadMonthlyPdfReport(Request $request)
    {
        $month = $this->resolveMonth($request->query('month'));

        return $this->reports->downloadPdf(
            $this->reports->buildForUserMonth($request->user(), $month),
            $request->user()->name.'-'.$month->format('Y-m').'-report.pdf'
        );
    }

    public function shareMonthlyReportOnWhatsApp(Request $request)
    {
        $month = $this->resolveMonth($request->query('month'));
        $report = $this->reports->buildForUserMonth($request->user(), $month);

        return redirect()->away($this->reports->whatsappUrlForMonthly($report));
    }

    public function saveMonthlyFinalRemark(Request $request)
    {
        $selectedMonth = $this->resolveMonth($request->input('month'));
        $rules = ['month' => ['required', 'date_format:Y-m']];
        $messages = [];

        foreach (MonthlyReportService::monthlyNarrativeLabels() as $field => $label) {
            $rules[$field] = ['nullable', 'string', 'max:3000'];
            $messages["{$field}.max"] = "{$label} must be 3000 characters or less.";
        }

        $validated = $request->validate($rules, $messages);
        $narrativeValues = collect(MonthlyReportService::monthlyNarrativeLabels())
            ->keys()
            ->mapWithKeys(fn (string $field) => [$field => filled($validated[$field] ?? null) ? trim($validated[$field]) : null])
            ->all();

        if (! collect($narrativeValues)->filter()->isNotEmpty()) {
            throw ValidationException::withMessages([
                'source_segregation' => 'Please fill at least one monthly progress answer before saving.',
            ]);
        }

        $monthlyRemark = MonthlyFinalRemark::query()
            ->where('user_id', $request->user()->id)
            ->whereDate('report_month', $selectedMonth->toDateString())
            ->first();

        $payload = [
            ...$narrativeValues,
            'remark' => collect(MonthlyReportService::monthlyNarrativeLabels())
                ->map(fn (string $label, string $field) => filled($narrativeValues[$field]) ? $label.': '.$narrativeValues[$field] : null)
                ->filter()
                ->implode("\n"),
        ];

        if ($monthlyRemark) {
            $monthlyRemark->update($payload);
        } else {
            MonthlyFinalRemark::create([
                'user_id' => $request->user()->id,
                'report_month' => $selectedMonth->toDateString(),
                ...$payload,
            ]);
        }

        return redirect()
            ->route('worker.submissions', ['month' => $selectedMonth->format('Y-m')])
            ->with('status', 'Monthly progress report saved successfully.');
    }

    private function validateActivity(Request $request): array
    {
        $photoFiles = collect($request->file('photos', []))
            ->filter(fn ($photo) => $photo !== null && $photo->isValid())
            ->values()
            ->all();

        if ($photoFiles === []) {
            $request->files->remove('photos');
            $request->request->remove('photos');
        } else {
            $request->files->set('photos', $photoFiles);
        }

        $documentFiles = collect($request->file('documents', []))
            ->filter(fn ($document) => $document !== null && $document->isValid())
            ->values()
            ->all();

        if ($documentFiles === []) {
            $request->files->remove('documents');
            $request->request->remove('documents');
        } else {
            $request->files->set('documents', $documentFiles);
        }

        $rules = ['activity_date' => ['required', 'date', 'before_or_equal:today']];
        $messages = [
            'activity_date.required' => 'Please select the activity date.',
            'activity_date.date' => 'Please choose a valid activity date.',
            'activity_date.before_or_equal' => 'Future dates are not allowed for daily activity entries.',
            'remarks.max' => 'Remarks must be 2000 characters or less.',
            'photos.array' => 'Upload valid attachments only.',
            'photos.max' => 'You can upload up to 5 image attachments for one day.',
            'photos.*.image' => 'Each image attachment must be an image.',
            'photos.*.max' => 'Each photo must be 4 MB or less.',
            'documents.array' => 'Upload valid documents only.',
            'documents.max' => 'You can upload up to 5 documents for one day.',
            'documents.*.mimes' => 'Each document must be a PDF, DOC, DOCX, JPG, JPEG, or PNG file.',
            'documents.*.max' => 'Each document must be 5 MB or less.',
        ];

        foreach (array_keys(MonthlyReportService::metricLabels()) as $field) {
            $rules[$field] = ['nullable', 'integer', 'min:0', 'max:99999'];
            $messages["{$field}.integer"] = 'Enter a whole number only.';
            $messages["{$field}.min"] = 'Numbers cannot be negative.';
            $messages["{$field}.max"] = 'This number looks too large. Please check it.';
        }

        $rules['remarks'] = ['nullable', 'string', 'max:2000'];
        $rules['photos'] = ['sometimes', 'array', 'max:5'];
        $rules['photos.*'] = ['sometimes', 'image', 'max:4096'];
        $rules['documents'] = ['sometimes', 'array', 'max:5'];
        $rules['documents.*'] = ['sometimes', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'];

        $validated = $request->validate($rules, $messages);

        foreach (array_keys(MonthlyReportService::metricLabels()) as $field) {
            $validated[$field] = (int) ($validated[$field] ?? 0);
        }

        $hasActivityCount = collect(array_keys(MonthlyReportService::metricLabels()))
            ->contains(fn (string $field) => $validated[$field] > 0);

        if (! $hasActivityCount && blank($validated['remarks'] ?? null)) {
            throw ValidationException::withMessages([
                'remarks' => 'Enter at least one activity count or add a remark before saving.',
            ]);
        }

        return $validated;
    }

    private function resolveMonth(?string $month): Carbon
    {
        return $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();
    }
}
