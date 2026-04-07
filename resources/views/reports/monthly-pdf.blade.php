<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Community Mobilizer Monthly Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        h1, h2, h3 { margin: 0; }
        .header { margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin-bottom: 6px; }
        .header p { margin: 2px 0; color: #4b5563; }
        .meta-table, .data-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .meta-table td { padding: 6px 8px; border: 1px solid #d1d5db; }
        .data-table th, .data-table td { padding: 7px 8px; border: 1px solid #d1d5db; vertical-align: top; }
        .data-table th { background: #eff6ff; text-align: left; font-size: 11px; }
        .section-title { margin-top: 18px; margin-bottom: 8px; font-size: 15px; page-break-after: avoid; }
        .remark-box { border: 1px solid #d1d5db; padding: 10px; min-height: 44px; background: #f9fafb; }
        .day-card { margin-top: 18px; border: 1px solid #d1d5db; padding: 12px; page-break-inside: avoid; }
        .day-card h4 { margin: 0 0 6px; font-size: 14px; }
        .day-meta { margin: 0 0 10px; color: #6b7280; }
        .muted { color: #6b7280; }
        .photo-grid { margin-top: 10px; }
        .photo-grid td { border: none; padding: 0 8px 8px 0; width: 110px; }
        .photo-thumb { width: 100px; height: 100px; object-fit: cover; border: 1px solid #d1d5db; border-radius: 8px; }
        .photo-note { margin: 8px 0 0; color: #6b7280; font-size: 11px; }
        .question-block { margin-top: 10px; padding: 10px 12px; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 8px; page-break-inside: avoid; break-inside: avoid; }
        .question-block strong { display: block; margin-bottom: 6px; font-size: 12px; }
        .question-block p { margin: 0; color: #374151; line-height: 1.55; }
        .question-list { page-break-inside: auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Community Mobilizer Monthly Report</h1>
        <p>{{ $report['month']->format('F Y') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Worker Name</strong></td>
            <td>{{ $report['user']->name }}</td>
            <td><strong>District</strong></td>
            <td>{{ $report['user']->district_name }}</td>
        </tr>
        <tr>
            <td><strong>ULB</strong></td>
            <td>{{ $report['user']->ulb_name }}</td>
            <td><strong>Days Submitted</strong></td>
            <td>{{ $report['submitted_days'] }}</td>
        </tr>
    </table>

    <h3 class="section-title">Monthly Totals</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Metric</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metricLabels as $field => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $report['totals'][$field] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="section-title">Monthly Progress Report</h3>
    <div class="question-list">
        @foreach ($monthlyNarrativeLabels as $field => $label)
            <div class="question-block">
                <strong>{{ $label }}</strong>
                <p>{{ $report['monthly_narrative'][$field] ?: 'Not added.' }}</p>
            </div>
        @endforeach
    </div>

    <h3 class="section-title">Day-wise Detailed Report</h3>
    @forelse ($report['activities'] as $activity)
        <div class="day-card">
            <h4>{{ $activity->activity_date->format('d M Y') }}</h4>
            <p class="day-meta">{{ $activity->remarks ?: 'No remarks added for this day.' }}</p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metricLabels as $field => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ (int) $activity->{$field} }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (! empty($activity->photo_paths))
                <h4 class="section-title" style="font-size: 13px; margin-top: 12px;">Proof Photos</h4>
                <table class="photo-grid">
                    <tr>
                        @foreach ($activity->photo_paths as $photoPath)
                            @php
                                $absolutePath = \Illuminate\Support\Facades\Storage::disk('public')->path($photoPath);
                            @endphp
                            @if (is_string($photoPath) && file_exists($absolutePath))
                                <td>
                                    <img class="photo-thumb" src="{{ $absolutePath }}" alt="Proof photo">
                                </td>
                                @if (($loop->iteration % 4) === 0 && ! $loop->last)
                                    </tr><tr>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                </table>
            @else
                <p class="photo-note">No proof photos uploaded for this day.</p>
            @endif
        </div>
    @empty
        <p class="muted">No daily entries found for this month.</p>
    @endforelse
</body>
</html>
