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
        .page-break { page-break-before: always; }
        .remark-box { border: 1px solid #d1d5db; padding: 10px; min-height: 44px; background: #f9fafb; }
        .question-block { margin-top: 10px; padding: 10px 12px; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 8px; page-break-inside: avoid; break-inside: avoid; }
        .question-block strong { display: block; margin-bottom: 6px; font-size: 12px; }
        .question-block p { margin: 0; color: #374151; line-height: 1.55; }
        .question-list { page-break-inside: auto; }
        .signature-row { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .signature-row td { width: 50%; vertical-align: bottom; padding-top: 30px; }
        .signature-line { border-top: 1px solid #9ca3af; width: 220px; margin-bottom: 8px; }
        .signature-label { font-size: 12px; font-weight: 600; color: #374151; }
        .signature-right { text-align: right; }
        .signature-right .signature-line { margin-left: auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Community Mobilizer Monthly Report</h1>
        <p>{{ $report['month']->format('F Y') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Community Mobilizer Name</strong></td>
            <td>{{ $report['user']->name }}</td>
            <td><strong>District</strong></td>
            <td>{{ $report['user']->district_name }}</td>
        </tr>
        <tr>
            <td><strong>ULB</strong></td>
            <td>{{ $report['user']->ulb_name }}</td>
            <td><strong>Assigned Ward</strong></td>
            <td>{{ $report['user']->assigned_ward ?: 'Not added' }}</td>
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

    <div class="page-break"></div>
    <h3 class="section-title">Monthly Progress Report</h3>
    <div class="question-list">
        @foreach ($monthlyNarrativeLabels as $field => $label)
            <div class="question-block">
                <strong>{{ $label }}</strong>
                <p>{{ $report['monthly_narrative'][$field] ?: 'Not added.' }}</p>
            </div>
        @endforeach
    </div>

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
</body>
</html>
