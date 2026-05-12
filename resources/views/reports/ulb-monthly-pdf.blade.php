<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $ulbName }} – {{ $monthLabel }} Compiled Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        h1, h2, h3 { margin: 0; }

        /* Cover page */
        .cover { text-align: center; padding-top: 160px; }
        .cover h1 { font-size: 26px; margin-bottom: 12px; }
        .cover h2 { font-size: 18px; color: #4b5563; margin-bottom: 8px; }
        .cover p  { font-size: 13px; color: #6b7280; margin: 4px 0; }

        .page-break { page-break-before: always; }

        /* Worker section */
        .worker-header { margin-bottom: 18px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .worker-header h2 { font-size: 18px; color: #4f46e5; margin-bottom: 4px; }
        .worker-header p  { color: #6b7280; font-size: 12px; margin: 2px 0; }

        .meta-table, .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .meta-table td { padding: 6px 8px; border: 1px solid #d1d5db; }
        .data-table th, .data-table td { padding: 7px 8px; border: 1px solid #d1d5db; vertical-align: top; }
        .data-table th { background: #eff6ff; text-align: left; font-size: 11px; }

        .section-title { margin-top: 16px; margin-bottom: 8px; font-size: 14px; page-break-after: avoid; }

        .question-block { margin-top: 8px; padding: 10px 12px; border: 1px solid #d1d5db; background: #f9fafb; break-inside: avoid; }
        .question-block strong { display: block; margin-bottom: 4px; font-size: 11px; }
        .question-block p { margin: 0; color: #374151; line-height: 1.5; }

        .signature-row { width: 100%; margin-top: 28px; }
        .signature-row td { width: 50%; vertical-align: bottom; padding-top: 28px; }
        .signature-line { border-top: 1px solid #9ca3af; width: 220px; margin-bottom: 6px; }
        .signature-label { font-size: 11px; font-weight: 600; color: #374151; }
        .signature-right { text-align: right; }
        .signature-right .signature-line { margin-left: auto; }

        .no-workers { text-align: center; padding: 60px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>

    {{-- ── Cover Page ── --}}
    <div class="cover">
        <h1>Compiled Monthly Report</h1>
        <h2>{{ $ulbName }}</h2>
        <p>{{ $districtName }}</p>
        <p><strong>{{ $monthLabel }}</strong></p>
        <p style="margin-top:20px; color:#6b7280;">Total Workers: {{ count($reports) }}</p>
    </div>

    @if(count($reports) === 0)
        <div class="page-break"></div>
        <div class="no-workers">No worker data found for this ULB in {{ $monthLabel }}.</div>
    @elseif(isset($isGlobal) && $isGlobal)
        {{-- ── Global Summary Table ── --}}
        <div class="page-break"></div>
        <h2 style="margin-bottom: 20px; color: #4f46e5;">Worker Performance Summary – {{ $monthLabel }}</h2>
        <table class="data-table" style="font-size: 10px;">
            <thead>
                <tr>
                    <th style="width: 120px;">Worker Name</th>
                    <th>ULB</th>
                    <th style="width: 40px; text-align:center;">Days</th>
                    @foreach($metricLabels as $field => $label)
                        <th style="font-size: 8px; text-align: center;">{{ explode(' ', $label)[2] ?? substr($label, 0, 8) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td><strong>{{ $report['user']->name }}</strong></td>
                        <td>{{ $report['user']->ulb_name }}</td>
                        <td style="text-align:center;">{{ $report['submitted_days'] }}</td>
                        @foreach($metricLabels as $field => $label)
                            <td style="text-align: center;">{{ $report['totals'][$field] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 40px; font-size: 11px; color: #6b7280;">
            <p>* Full day-wise logs and narrative answers are available in the CSV export or individual worker PDFs.</p>
        </div>
    @else
        @foreach($reports as $report)
            <div class="page-break"></div>

            {{-- Worker Header --}}
            <div class="worker-header">
                <h2>{{ $report['user']->name }}</h2>
                <p>{{ $report['user']->district_name }} &bull; {{ $report['user']->ulb_name }} &bull; Ward: {{ $report['user']->assigned_ward ?: 'N/A' }}</p>
                <p>{{ $monthLabel }} &bull; Days Submitted: {{ $report['submitted_days'] }}</p>
            </div>

            {{-- Monthly Totals Table --}}
            <h3 class="section-title">Monthly Field Visits</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Particulars</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metricLabels as $field => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ $report['totals'][$field] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Monthly Progress Report --}}
            <h3 class="section-title">Monthly Progress Report</h3>
            @foreach($monthlyNarrativeLabels as $field => $label)
                <div class="question-block">
                    <strong>{{ $label }}</strong>
                    <p>{{ $report['monthly_narrative'][$field] ?: 'Not added.' }}</p>
                </div>
            @endforeach

            {{-- Signatures --}}
            <table class="signature-row">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-label">Signature Community Mobilizer</div>
                    </td>
                    <td class="signature-right">
                        <div class="signature-line"></div>
                        <div class="signature-label">Signature Nigran/Supervisor</div>
                    </td>
                </tr>
            </table>
        @endforeach
    @endif

</body>
</html>
