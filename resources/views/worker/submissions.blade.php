@extends('layouts.app')

@section('content')
    <div class="hero worker-submissions-hero">
        <div class="worker-submissions-hero-grid">
            <div>
                <span class="page-kicker">Monthly Reports</span>
                <h2>Daily Activity Logs</h2>
                <p>Review every saved day for {{ $selectedMonth->format('F Y') }}, then complete the monthly progress report before exporting.</p>
            </div>

            <div class="worker-submissions-actions">
                <form method="GET" action="{{ route('worker.submissions') }}" class="worker-submissions-filter">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3v3M16 3v3M4 9h16M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" /></svg>
                    </span>
                    <input id="month-select" type="month" name="month" value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()">
                </form>
                <a class="button button-secondary button-icon" href="{{ $monthlyWhatsAppUrl }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                    <span>WhatsApp</span>
                </a>
                <a class="button button-secondary button-icon" href="{{ route('worker.reports.monthly.pdf', ['month' => $selectedMonth->format('Y-m')]) }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                    <span>PDF</span>
                </a>
                <a class="button button-secondary button-icon" href="{{ route('worker.reports.monthly', ['month' => $selectedMonth->format('Y-m')]) }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                    <span>CSV</span>
                </a>
            </div>
        </div>
    </div>

    <div class="worker-submissions-stats">
        <div class="worker-submission-stat">
            <strong>{{ $activities->total() }}</strong>
            <span>Total logs in {{ $selectedMonth->format('F Y') }}</span>
        </div>
        <div class="worker-submission-stat">
            <strong>{{ $activities->firstItem() ?: 0 }}-{{ $activities->lastItem() ?: 0 }}</strong>
            <span>Entries on this page</span>
        </div>
        <div class="worker-submission-stat">
            <strong>{{ collect($monthlyNarrative)->filter()->isNotEmpty() ? 'Saved' : 'Pending' }}</strong>
            <span>Monthly progress status</span>
        </div>
    </div>

    <div class="card worker-final-remark-card">
        <div class="worker-final-remark-head">
            <span class="worker-final-remark-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
            </span>
            <div>
                <h3>Monthly Progress Report</h3>
                <p>Fill the descriptive answers for {{ $selectedMonth->format('F Y') }}. These notes will appear in PDF, CSV, and WhatsApp monthly reports.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('worker.reports.monthly.final-remark') }}" class="stack">
            @csrf
            <input type="hidden" name="month" value="{{ $selectedMonth->format('Y-m') }}">

            <div class="grid grid-2">
                @foreach (\App\Services\MonthlyReportService::monthlyNarrativeLabels() as $field => $label)
                    <div>
                        <label for="{{ $field }}">{{ $label }}</label>
                        <textarea id="{{ $field }}" class="@error($field) input-error @enderror" name="{{ $field }}" placeholder="Write your update for {{ strtolower($label) }}...">{{ old($field, $monthlyNarrative[$field] ?? '') }}</textarea>
                        @error($field)
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div>
                <button class="button button-primary" type="submit">Save Monthly Progress</button>
            </div>
        </form>
    </div>

    <div class="card worker-submissions-table-card">
        <div class="section-head">
            <div>
                <h3>Saved Daily Logs</h3>
                <p>Open, review, and edit your submitted reports.</p>
            </div>
        </div>

        @if ($activities->isEmpty())
            <p class="muted" style="margin: 0;">No submissions found for this month.</p>
        @else
            <table class="desktop-only submissions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td class="submissions-date-cell">
                                <span class="submissions-date-chip">
                                    <span class="submissions-date-day">{{ $activity->activity_date->format('d') }}</span>
                                    <span class="submissions-date-meta">
                                        <strong>{{ $activity->activity_date->format('M Y') }}</strong>
                                        <small>{{ $activity->activity_date->format('l') }}</small>
                                    </span>
                                </span>
                            </td>
                            <td class="submissions-remarks-cell">
                                <strong>{{ $activity->remarks ? 'Note added' : 'No note added' }}</strong>
                                <span>{{ $activity->remarks ?: 'No notes added for this entry.' }}</span>
                            </td>
                            <td>
                                <span class="submission-badge">{{ ! empty($activity->photo_paths) ? 'With photos' : 'Saved entry' }}</span>
                            </td>
                            <td class="submissions-actions-cell">
                                <div class="submissions-actions-inline">
                                    <a class="button button-primary" href="{{ route('worker.daily-activity.form', ['date' => $activity->activity_date->format('Y-m-d'), 'month' => $activity->activity_date->format('Y-m')]) }}">Open</a>
                                    <a class="button button-secondary button-icon" href="{{ route('worker.reports.daily', ['activity' => $activity->id]) }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                        <span>View</span>
                                    </a>
                                    <a class="button button-secondary button-icon" href="{{ $activity->whatsapp_url }}" target="_blank" rel="noopener">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                                        <span>WhatsApp</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="table-stack submissions-stack">
                @foreach ($activities as $activity)
                    <div class="table-stack-card submissions-card">
                        <div class="submission-card-head">
                            <div>
                                <h4>{{ $activity->activity_date->format('d M Y') }}</h4>
                                <span class="submission-subtitle">{{ $activity->activity_date->format('l') }}</span>
                            </div>
                            <span class="submission-badge">{{ ! empty($activity->photo_paths) ? 'With photos' : 'Saved entry' }}</span>
                        </div>
                        <p class="submission-remark">{{ $activity->remarks ?: 'No notes added for this entry.' }}</p>
                        <div class="submission-actions">
                            <a class="button button-primary" href="{{ route('worker.daily-activity.form', ['date' => $activity->activity_date->format('Y-m-d'), 'month' => $activity->activity_date->format('Y-m')]) }}">Open</a>
                            <a class="button button-secondary button-icon" href="{{ route('worker.reports.daily', ['activity' => $activity->id]) }}">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                <span>View</span>
                            </a>
                            <a class="button button-secondary button-icon" href="{{ $activity->whatsapp_url }}" target="_blank" rel="noopener">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="submissions-footer">
                <span class="muted">Showing {{ $activities->firstItem() }}-{{ $activities->lastItem() }} of {{ $activities->total() }} entries</span>
                <div class="action-row submissions-pagination">
                    @if ($activities->onFirstPage())
                        <span class="button button-secondary" style="opacity:0.55; pointer-events:none;">Previous</span>
                    @else
                        <a class="button button-secondary" href="{{ $activities->previousPageUrl() }}">Previous</a>
                    @endif

                    @if ($activities->hasMorePages())
                        <a class="button button-secondary" href="{{ $activities->nextPageUrl() }}">Next</a>
                    @else
                        <span class="button button-secondary" style="opacity:0.55; pointer-events:none;">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
