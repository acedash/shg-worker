<?php

namespace App\Services;

use App\Models\DailyActivity;
use App\Models\MonthlyFinalRemark;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class MonthlyReportService
{
    public static function metricLabels(): array
    {
        return [
            'households_visited' => 'No. of Households Visited',
            'commercial_shops_visited' => 'No. of Commercial Shops Visited',
            'institutions_visited' => 'No. of Public and Private Institutions (Schools, Colleges, Offices, Hospitals, Hotels, Mall etc.) Visited',
            'religious_places_visited' => 'No. of Religious Places (Temple, Gurudwara, Mosque, Church) Visited',
            'transit_locations_visited' => 'No. of Transit Location (Bus Stand/ Taxi Stand/ Railway Station) Visited',
            'toilets_visited' => 'No. of CT/PT/Aspirational Toilets Visited',
            'swm_assets_visited' => 'No. of SWM Assets (Twin Bins, Compost Pit, Transfer Station) visited',
            'open_burning_issues_found' => 'No. of Open Burning of Garbage Points Found',
            'gvp_points_found' => 'No. of Garbage Vulnerable Points (GVP) Found',
            'illegal_dumping_points_found' => 'No. of Illegal Dumping Points Found',
            'cd_waste_points_found' => 'No. of C&D Waste Points Found',
            'littering_points_found' => 'No. of Littering Points Found',
            'open_defecation_points_found' => 'No. of Open Defecation Points Found',
            'yellow_red_spots_found' => 'No. of Yellow/Red Spot Found',
            'polluted_water_bodies_found' => 'No. of Polluted Water Bodies Found',
            'complaints_received' => 'No. of complaints received from the resident about SWM related issues',
        ];
    }

    public static function sections(): array
    {
        return [
            'Household & Shops' => [
                'households_visited',
                'commercial_shops_visited',
            ],
            'Institution & Bulk Waste Generators (BWG)' => [
                'institutions_visited',
            ],
            'Religious Places' => [
                'religious_places_visited',
            ],
            'Transit Locations' => [
                'transit_locations_visited',
            ],
            'CT/PT/Aspirational Toilets' => [
                'toilets_visited',
            ],
            'Solid Waste Management (SWM) Assets /Infrastructure' => [
                'swm_assets_visited',
            ],
            'SWM Issues' => [
                'open_burning_issues_found',
                'gvp_points_found',
                'illegal_dumping_points_found',
                'cd_waste_points_found',
                'littering_points_found',
                'open_defecation_points_found',
                'yellow_red_spots_found',
                'polluted_water_bodies_found',
            ],
            'Public Grievances' => [
                'complaints_received',
            ],
        ];
    }

    public static function monthlyNarrativeLabels(): array
    {
        return [
            'source_segregation' => 'Source Segregation',
            'home_composting' => 'Home Composting',
            'swm_infrastructure_functionality' => 'SWM Assets / Infrastructure Functionality',
            'rrr_centre_awareness' => 'RRR Centre Awareness & Utilization',
            'public_grievance_redressal' => 'Public Grievance Redressal',
            'change_in_public_behavior' => 'Change in Public Behavior',
            'overall_improvement' => 'Overall improvement in waste management and sanitation',
            'other_feedback' => 'Any Other Feedback',
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

        $monthlyNarrative = $this->monthlyNarrativeValues($finalRemark);

        return [
            'user' => $user,
            'month' => $month,
            'activities' => $activities,
            'totals' => $totals,
            'submitted_days' => $activities->count(),
            'monthly_narrative' => $monthlyNarrative,
            'final_remark' => $this->monthlyNarrativeText($monthlyNarrative),
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
            fputcsv($handle, []);
            fputcsv($handle, ['Monthly Progress Report']);

            foreach (self::monthlyNarrativeLabels() as $field => $label) {
                fputcsv($handle, [$label, $report['monthly_narrative'][$field] ?: '']);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Monthly Overview']);
            fputcsv($handle, ['Particulars', 'Monthly Total']);

            foreach (self::metricLabels() as $field => $label) {
                fputcsv($handle, [$label, $report['totals'][$field]]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Day-wise Detailed Report']);

            foreach ($report['activities'] as $activity) {
                fputcsv($handle, ['Date', $activity->activity_date->format('d M Y')]);
                fputcsv($handle, ['Remarks', $activity->remarks ?: 'No remarks added.']);
                fputcsv($handle, ['Proof Photos', $this->csvPhotoSummary($activity)]);
                fputcsv($handle, ['Particulars', 'Daily Value']);

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
            'monthlyNarrativeLabels' => self::monthlyNarrativeLabels(),
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
            'Community Mobilizer Monthly Report',
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
            $lines[] = 'Monthly Progress Report:';

            foreach (self::monthlyNarrativeLabels() as $field => $label) {
                if (filled($report['monthly_narrative'][$field] ?? null)) {
                    $lines[] = $label.': '.$report['monthly_narrative'][$field];
                }
            }
        }

        return implode("\n", $lines);
    }

    private function monthlyNarrativeValues(?MonthlyFinalRemark $finalRemark): array
    {
        $values = [];

        foreach (self::monthlyNarrativeLabels() as $field => $label) {
            $values[$field] = $finalRemark?->{$field} ?? null;
        }

        if (! collect($values)->filter()->isNotEmpty() && filled($finalRemark?->remark)) {
            $values['other_feedback'] = $finalRemark->remark;
        }

        return $values;
    }

    private function monthlyNarrativeText(array $monthlyNarrative): ?string
    {
        $lines = [];

        foreach (self::monthlyNarrativeLabels() as $field => $label) {
            if (filled($monthlyNarrative[$field] ?? null)) {
                $lines[] = $label.': '.$monthlyNarrative[$field];
            }
        }

        return $lines === [] ? null : implode("\n", $lines);
    }

    private function csvPhotoSummary(DailyActivity $activity): string
    {
        if (empty($activity->photo_paths)) {
            return 'No proof photos uploaded.';
        }

        return collect($activity->photo_paths)
            ->filter(fn ($path) => is_string($path) && filled($path))
            ->map(function (string $path): string {
                return basename($path);
            })
            ->implode('; ');
    }
}
