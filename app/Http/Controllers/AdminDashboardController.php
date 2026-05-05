<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\District;
use App\Models\User;
use App\Services\MonthlyReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly MonthlyReportService $reports)
    {
    }

    public function index(Request $request)
    {
        $selectedMonth = $this->resolveMonth($request->query('month'));
        $start = $selectedMonth->copy()->startOfMonth()->toDateString();
        $end = $selectedMonth->copy()->endOfMonth()->toDateString();

        $search = trim((string) $request->query('q', ''));
        $districtId = $request->query('district_id');
        $ulbId = $request->query('ulb_id');

        $totalWorkers = User::query()
            ->where('role', 'worker')
            ->count();

        $monthlyLogs = DailyActivity::query()
            ->whereBetween('activity_date', [$start, $end])
            ->count();

        $activeWorkersThisMonth = DailyActivity::query()
            ->whereBetween('activity_date', [$start, $end])
            ->distinct('user_id')
            ->count('user_id');

        $workers = User::query()
            ->where('role', 'worker')
            ->withCount([
                'dailyActivities as submissions_this_month' => fn ($query) => $query->whereBetween('activity_date', [$start, $end]),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->when($districtId, fn ($query) => $query->where('district_id', $districtId))
            ->when($ulbId, fn ($query) => $query->where('ulb_id', $ulbId))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        $recentActivities = DailyActivity::query()
            ->with('user')
            ->latest('activity_date')
            ->limit(10)
            ->get();
        $recentActivities->each(function (DailyActivity $activity): void {
            $activity->setAttribute('whatsapp_url', $this->reports->whatsappUrlForDaily($activity));
        });

        return view('admin.dashboard', [
            'selectedMonth' => $selectedMonth,
            'workers' => $workers,
            'recentActivities' => $recentActivities,
            'districts' => District::query()->with('ulbs')->orderBy('name')->get(),
            'filters' => [
                'q' => $search,
                'district_id' => $districtId,
                'ulb_id' => $ulbId,
            ],
            'stats' => [
                'total_workers' => $totalWorkers,
                'monthly_logs' => $monthlyLogs,
                'active_workers' => $activeWorkersThisMonth,
            ],
        ]);
    }

    public function downloadWorkerMonthlyReport(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 404);

        $month = $this->resolveMonth($request->query('month'));

        return $this->reports->streamCsv(
            $this->reports->buildForUserMonth($user, $month),
            $user->name.'-'.$month->format('Y-m').'-report.csv'
        );
    }

    public function downloadWorkerMonthlyPdfReport(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 404);

        $month = $this->resolveMonth($request->query('month'));

        return $this->reports->downloadPdf(
            $this->reports->buildForUserMonth($user, $month),
            $user->name.'-'.$month->format('Y-m').'-report.pdf'
        );
    }

    public function showWorkerDailyReport(User $user, DailyActivity $activity)
    {
        abort_if($user->isAdmin() || $activity->user_id !== $user->id, 404);
        $activity->loadMissing('user');

        return view('reports.daily', [
            'dailyReport' => $this->reports->buildDailyReport($activity),
            'metricLabels' => MonthlyReportService::metricLabels(),
            'whatsAppUrl' => $this->reports->whatsappUrlForDaily($activity),
            'backUrl' => route('admin.dashboard', ['month' => $activity->activity_date->format('Y-m')]),
            'editUrl' => null,
        ]);
    }

    public function shareWorkerMonthlyReportOnWhatsApp(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 404);

        $month = $this->resolveMonth($request->query('month'));
        $report = $this->reports->buildForUserMonth($user, $month);

        return redirect()->away($this->reports->whatsappUrlForMonthly($report));
    }

    private function resolveMonth(?string $month): Carbon
    {
        return $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();
    }
}
