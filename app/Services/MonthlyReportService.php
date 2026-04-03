<?php

namespace App\Services;

use App\Models\DailyActivity;
use App\Models\MonthlyFinalRemark;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class MonthlyReportService
{
    public static function metricLabels(): array
    {
        return [
            'households_visited' => 'Households visited',
            'households_started_segregation' => 'Households started waste segregation',
            'households_started_home_composting' => 'Households started home composting',
            'open_burning_issues_found' => 'Open burning issues found',
            'gvp_points_found' => 'Garbage vulnerable points found',
            'illegal_dumping_points_found' => 'Illegal dumping points found',
            'cd_waste_points_found' => 'C&D waste points found',
            'littering_points_found' => 'Littering points found',
            'open_defecation_points_found' => 'Open defecation points found',
            'yellow_red_spots_found' => 'Yellow/red spots found',
            'polluted_water_bodies_found' => 'Polluted water bodies found',
            'complaints_resolved' => 'Complaints resolved',
            'swm_assets_visited' => 'SWM assets visited',
            'non_functional_assets_made_functional' => 'Non-functional assets made functional',
            'toilets_visited' => 'CT/PT/Aspirational toilets visited',
            'toilet_issues_reported' => 'Toilet issues reported',
            'toilet_issues_resolved' => 'Toilet issues resolved',
            'institutions_visited' => 'Institutions visited',
            'institutions_started_composting' => 'Institutions started pit/drum composting',
            'religious_places_visited' => 'Religious places visited',
            'religious_places_with_separate_bins' => 'Religious places with separate bins',
            'religious_places_with_composting' => 'Religious places with pit/drum composting',
            'transit_locations_visited' => 'Transit locations visited',
            'transit_locations_with_gvp' => 'Transit locations with GVP found',
            'gvp_removed' => 'GVP removed',
            'yellow_red_spots_identified' => 'Yellow/red spots identified',
            'yellow_red_spots_removed' => 'Yellow/red spots removed',
            'households_sensitized_rrr' => 'Households sensitized about RRR centre',
        ];
    }

    public static function sections(): array
    {
        return [
            'Door to Door IEC & Segregation Promotion' => [
                'households_visited',
                'households_started_segregation',
                'households_started_home_composting',
            ],
            'SWM Issues Identified and Reporting' => [
                'open_burning_issues_found',
                'gvp_points_found',
                'illegal_dumping_points_found',
                'cd_waste_points_found',
                'littering_points_found',
                'open_defecation_points_found',
                'yellow_red_spots_found',
                'polluted_water_bodies_found',
            ],
            'Public Grievance Redressal' => [
                'complaints_resolved',
            ],
            'Monitoring of SWM Infrastructure' => [
                'swm_assets_visited',
                'non_functional_assets_made_functional',
            ],
            'Monitoring of CT/PT/Aspirational Toilets' => [
                'toilets_visited',
                'toilet_issues_reported',
                'toilet_issues_resolved',
            ],
            'Institution & BWG IEC Awareness' => [
                'institutions_visited',
                'institutions_started_composting',
            ],
            'Religious Places IEC Awareness' => [
                'religious_places_visited',
                'religious_places_with_separate_bins',
                'religious_places_with_composting',
            ],
            'Transit Location IEC Awareness' => [
                'transit_locations_visited',
                'transit_locations_with_gvp',
                'gvp_removed',
                'yellow_red_spots_identified',
                'yellow_red_spots_removed',
            ],
            'Promotion of RRR Centre' => [
                'households_sensitized_rrr',
            ],
        ];
    }

    public function buildDailyReport(DailyActivity $activity): array
    {
        $totals = [];
        $filledCount = 0;

        foreach (self::metricLabels() as $field => $label) {
            $value = (int) $activity->{$field};
            $totals[$field] = $value;

            if ($value > 0) {
                $filledCount++;
            }
        }

        return [
            'activity' => $activity,
            'user' => $activity->user,
            'sections' => self::sections(),
            'totals' => $totals,
            'filled_count' => $filledCount,
        ];
    }

    public function buildForUserMonth(User $user, Carbon|string $month): array
    {
        $month = $month instanceof Carbon
            ? $month->copy()->startOfMonth()
            : Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();
        $activities = $user->dailyActivities()
            ->whereBetween('activity_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('activity_date')
            ->get();
        $finalRemark = $user->monthlyFinalRemarks()
            ->whereDate('report_month', $month->toDateString())
            ->first();

        $totals = array_fill_keys(array_keys(self::metricLabels()), 0);

        foreach ($activities as $activity) {
            foreach (array_keys($totals) as $field) {
                $totals[$field] += (int) $activity->{$field};
            }
        }

        return [
            'user' => $user,
            'month' => $month,
            'activities' => $activities,
            'totals' => $totals,
            'submitted_days' => $activities->count(),
            'final_remark' => $finalRemark?->remark,
        ];
    }

    public function whatsappUrlForDaily(DailyActivity $activity): string
    {
        return 'https://wa.me/?text='.rawurlencode($this->dailyWhatsappText($activity));
    }

    public function whatsappUrlForMonthly(array $report): string
    {
        return 'https://wa.me/?text='.rawurlencode($this->monthlyWhatsappText($report));
    }

    public function streamCsv(array $report, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return Response::stream(function () use ($report): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Worker Name', $report['user']->name]);
            fputcsv($handle, ['District', $report['user']->district_name]);
            fputcsv($handle, ['ULB', $report['user']->ulb_name]);
            fputcsv($handle, ['Month', $report['month']->format('F Y')]);
            fputcsv($handle, ['Final Monthly Remark', $report['final_remark'] ?: '']);
            fputcsv($handle, []);
            fputcsv($handle, ['Monthly Overview']);
            fputcsv($handle, ['Metric', 'Monthly Total']);

            foreach (self::metricLabels() as $field => $label) {
                fputcsv($handle, [$label, $report['totals'][$field]]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Day-wise Detailed Report']);

            foreach ($report['activities'] as $activity) {
                fputcsv($handle, ['Date', $activity->activity_date->format('d M Y')]);
                fputcsv($handle, ['Remarks', $activity->remarks ?: 'No remarks added.']);
                fputcsv($handle, ['Metric', 'Daily Value']);

                foreach (self::metricLabels() as $field => $label) {
                    fputcsv($handle, [$label, (int) $activity->{$field}]);
                }

                fputcsv($handle, []);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function downloadPdf(array $report, string $filename)
    {
        return Pdf::loadView('reports.monthly-pdf', [
            'report' => $report,
            'metricLabels' => self::metricLabels(),
        ])->setPaper('a4')->download($filename);
    }

    private function dailyWhatsappText(DailyActivity $activity): string
    {
        $report = $this->buildDailyReport($activity);
        $lines = [
            'SHG Daily Activity Report',
            'Worker: '.$activity->user->name,
            'District: '.$activity->user->district_name,
            'ULB: '.$activity->user->ulb_name,
            'Date: '.$activity->activity_date->format('d M Y'),
            '',
            'Activity Summary:',
        ];

        foreach (self::metricLabels() as $field => $label) {
            if ($report['totals'][$field] > 0) {
                $lines[] = $label.': '.$report['totals'][$field];
            }
        }

        if ($report['filled_count'] === 0) {
            $lines[] = 'No activity counts entered.';
        }

        if (filled($activity->remarks)) {
            $lines[] = '';
            $lines[] = 'Remarks: '.$activity->remarks;
        }

        return implode("\n", $lines);
    }

    private function monthlyWhatsappText(array $report): string
    {
        $lines = [
            'SHG Monthly Report',
            'Worker: '.$report['user']->name,
            'District: '.$report['user']->district_name,
            'ULB: '.$report['user']->ulb_name,
            'Month: '.$report['month']->format('F Y'),
            'Days Submitted: '.$report['submitted_days'],
            '',
            'Monthly Totals:',
        ];

        foreach (self::metricLabels() as $field => $label) {
            if (($report['totals'][$field] ?? 0) > 0) {
                $lines[] = $label.': '.$report['totals'][$field];
            }
        }

        if (count($lines) === 7) {
            $lines[] = 'No activity totals recorded for this month.';
        }

        if (filled($report['final_remark'] ?? null)) {
            $lines[] = '';
            $lines[] = 'Final Monthly Remark: '.$report['final_remark'];
        }

        return implode("\n", $lines);
    }
}
