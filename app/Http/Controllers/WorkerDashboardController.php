<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\MonthlyFinalRemark;
use App\Services\MonthlyReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $selectedDate = Carbon::parse($request->query('date', now()->toDateString()));
        $selectedMonth = $this->resolveMonth($request->query('month'));
        $activity = $request->user()
            ->dailyActivities()
            ->whereDate('activity_date', $selectedDate)
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
            'finalRemark' => $request->user()->monthlyFinalRemarks()
                ->whereDate('report_month', $selectedMonth->toDateString())
                ->value('remark'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateActivity($request);
        $existingActivity = DailyActivity::query()
            ->where('user_id', $request->user()->id)
            ->whereDate('activity_date', $validated['activity_date'])
            ->first();
        $photoPaths = $existingActivity?->photo_paths ?? [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos', []) as $photo) {
                $photoPaths[] = $photo->store('daily-activity-proofs', 'public');
            }
        }

        DailyActivity::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'activity_date' => $validated['activity_date'],
            ],
            [
                ...$validated,
                'user_id' => $request->user()->id,
                'photo_paths' => $photoPaths,
            ]
        );

        return redirect()
            ->route('worker.daily-activity.form', [
                'date' => $validated['activity_date'],
                'month' => Carbon::parse($validated['activity_date'])->format('Y-m'),
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
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'final_remark' => ['required', 'string', 'max:3000'],
        ], [
            'final_remark.required' => 'Please add the final monthly remark before saving.',
            'final_remark.max' => 'The final monthly remark must be 3000 characters or less.',
        ]);

        $monthlyRemark = MonthlyFinalRemark::query()
            ->where('user_id', $request->user()->id)
            ->whereDate('report_month', $selectedMonth->toDateString())
            ->first();

        if ($monthlyRemark) {
            $monthlyRemark->update([
                'remark' => $validated['final_remark'],
            ]);
        } else {
            MonthlyFinalRemark::create([
                'user_id' => $request->user()->id,
                'report_month' => $selectedMonth->toDateString(),
                'remark' => $validated['final_remark'],
            ]);
        }

        return redirect()
            ->route('worker.submissions', ['month' => $selectedMonth->format('Y-m')])
            ->with('status', 'Final monthly remark saved successfully.');
    }

    private function validateActivity(Request $request): array
    {
        if (! $request->hasFile('photos')) {
            $request->request->remove('photos');
        }

        $rules = ['activity_date' => ['required', 'date', 'before_or_equal:today']];
        $messages = [
            'activity_date.required' => 'Please select the activity date.',
            'activity_date.date' => 'Please choose a valid activity date.',
            'activity_date.before_or_equal' => 'Future dates are not allowed for daily activity entries.',
            'remarks.max' => 'Remarks must be 2000 characters or less.',
            'photos.array' => 'Upload valid proof photos only.',
            'photos.max' => 'You can upload up to 5 proof photos for one day.',
            'photos.*.image' => 'Each proof file must be an image.',
            'photos.*.max' => 'Each photo must be 4 MB or less.',
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
