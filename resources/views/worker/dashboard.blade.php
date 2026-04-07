@extends('layouts.app')

@section('content')
    <div class="hero worker-dashboard-hero">
        <div class="worker-dashboard-hero-grid">
            <div>
                <span class="page-kicker">Monthly Overview</span>
                <h2>Community Mobilizer Dashboard</h2>
                <p>Use this page to enter today&apos;s activity, review this month&apos;s progress, and open saved reports quickly.</p>

                <div class="worker-dashboard-actions">
                    <a class="button button-primary button-icon" href="{{ route('worker.daily-activity.form', ['date' => now()->toDateString(), 'month' => $selectedMonth->format('Y-m')]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                        <span>Fill Today&apos;s Activity</span>
                    </a>
                    <a class="button button-secondary button-icon" href="{{ route('worker.submissions', ['month' => $selectedMonth->format('Y-m')]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" /></svg>
                        <span>All Reports</span>
                    </a>
                </div>
            </div>

            <div class="worker-month-card">
                <div class="worker-month-card-head">
                    <div>
                        <span class="worker-month-label">{{ $selectedMonth->format('F Y') }}</span>
                        <strong>{{ $report['submitted_days'] }}</strong>
                        <p>days submitted</p>
                    </div>
                    <form method="GET" action="{{ route('worker.dashboard') }}">
                        <input type="month" name="month" value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()">
                    </form>
                </div>

                <div class="worker-month-actions">
                    <a class="button button-secondary button-icon" href="{{ route('worker.reports.monthly.pdf', ['month' => $selectedMonth->format('Y-m')]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                        <span>PDF</span>
                    </a>
                    <a class="button button-secondary button-icon" href="{{ route('worker.reports.monthly', ['month' => $selectedMonth->format('Y-m')]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                        <span>CSV</span>
                    </a>
                    <a class="button button-secondary button-icon" href="{{ $monthlyWhatsAppUrl }}" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="worker-dashboard-layout">
        <div class="card worker-summary-card">
            <div class="section-head">
                <div>
                    <h3>Monthly Summary</h3>
                    <p>Clean month totals for {{ $selectedMonth->format('F Y') }}.</p>
                </div>
            </div>

            <div class="worker-summary-list">
                @foreach ($metricLabels as $field => $label)
                    <div class="worker-summary-row">
                        <span>{{ $label }}</span>
                        <strong>{{ $report['totals'][$field] }}</strong>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="worker-side-column">
            <div class="card worker-quick-card">
                <div class="section-head">
                    <div>
                        <h3>Open By Date</h3>
                        <p>Go directly to a report for any date.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('worker.daily-activity.form') }}" class="stack">
                    <div>
                        <label for="quick-date">Activity Date</label>
                        <input id="quick-date" type="date" name="date" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label for="quick-month">Month</label>
                        <input id="quick-month" type="month" name="month" value="{{ $selectedMonth->format('Y-m') }}">
                    </div>
                    <button class="button button-primary" type="submit">Open Daily Form</button>
                </form>
            </div>

            <div class="card worker-recent-card" id="recent-submissions">
                <div class="section-head">
                    <div>
                        <h3>Recent Reports</h3>
                        <p>Your latest saved entries.</p>
                    </div>
                    <a class="button button-secondary" href="{{ route('worker.submissions', ['month' => $selectedMonth->format('Y-m')]) }}">View All</a>
                </div>

                @if ($recentActivities->isEmpty())
                    <p class="muted" style="margin: 0;">No daily activities submitted yet.</p>
                @else
                    <div class="worker-recent-list">
                        @foreach ($recentActivities as $row)
                            <div class="worker-recent-item">
                                <div class="worker-recent-top">
                                    <div>
                                        <strong>{{ $row->activity_date->format('d M Y') }}</strong>
                                        <span>{{ $row->activity_date->diffForHumans() }}</span>
                                    </div>
                                    <span class="worker-recent-badge">{{ $row->remarks ? 'With note' : 'Saved' }}</span>
                                </div>
                                <p>{{ $row->remarks ?: 'No remarks added for this entry.' }}</p>
                                <div class="worker-recent-actions">
                                    <a class="button button-primary" href="{{ route('worker.daily-activity.form', ['date' => $row->activity_date->format('Y-m-d'), 'month' => $row->activity_date->format('Y-m')]) }}">Open</a>
                                    <a class="button button-secondary button-icon" href="{{ route('worker.reports.daily', ['activity' => $row->id]) }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                        <span>View</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
