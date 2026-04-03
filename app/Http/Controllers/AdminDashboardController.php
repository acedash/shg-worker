<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
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

        $workers = User::query()
            ->where('role', 'worker')
            ->withCount([
                'dailyActivities as submissions_this_month' => fn ($query) => $query->whereBetween('activity_date', [
                    $selectedMonth->copy()->startOfMonth()->toDateString(),
                    $selectedMonth->copy()->endOfMonth()->toDateString(),
                ]),
            ])
            ->orderBy('name')
            ->get();

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
