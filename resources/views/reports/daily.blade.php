@extends('layouts.app')

@section('content')
    <div class="hero">
        <div class="spaced">
            <div>
                <span class="page-kicker">Detailed Report</span>
                <h2>Daily Activity Report</h2>
                <p>Review the full day-wise activity details and share them on WhatsApp when needed.</p>
            </div>
            <div class="action-row-inline">
                <a class="button button-secondary" href="{{ $backUrl }}">Back</a>
                @if ($editUrl)
                    <a class="button button-secondary" href="{{ $editUrl }}">Edit Entry</a>
                @endif
                <a class="button button-primary button-icon" href="{{ $whatsAppUrl }}" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                    <span>Send On WhatsApp</span>
                </a>
            </div>
        </div>
    </div>

    <div class="form-layout-minimal">
        <div class="subtle-panel">
            <div class="mini-stats-inline">
                <div class="mini-stat">
                    <strong>{{ $dailyReport['activity']->activity_date->format('d M Y') }}</strong>
                    <span>Report date</span>
                </div>
                <div class="mini-stat">
                    <strong>{{ $dailyReport['user']->name }}</strong>
                    <span>Worker name</span>
                </div>
                <div class="mini-stat">
                    <strong>{{ $dailyReport['filled_count'] }}</strong>
                    <span>Metrics with values</span>
                </div>
            </div>

            <div class="grid grid-2" style="margin-top: 16px;">
                <div class="mini-stat">
                    <strong>{{ $dailyReport['user']->district_name }}</strong>
                    <span>District</span>
                </div>
                <div class="mini-stat">
                    <strong>{{ $dailyReport['user']->ulb_name }}</strong>
                    <span>ULB</span>
                </div>
            </div>
        </div>

        <div class="card">
            @foreach ($dailyReport['sections'] as $sectionTitle => $fields)
                <div class="form-section">
                    <h4 class="section-title">{{ $sectionTitle }}</h4>
                    <div class="metric-grid">
                        @foreach ($fields as $field)
                            <div class="metric-card">
                                <label>{{ $metricLabels[$field] }}</label>
                                <div class="compact-value">{{ $dailyReport['totals'][$field] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="form-section">
                <h4 class="section-title">Remarks</h4>
                <p class="muted" style="margin: 0;">{{ $dailyReport['activity']->remarks ?: 'No remarks added for this day.' }}</p>
            </div>

            <div class="form-section">
                <h4 class="section-title">Photo Proof</h4>
                @if (! empty($dailyReport['activity']->photo_paths))
                    <div class="photo-proof-grid">
                        @foreach ($dailyReport['activity']->photo_paths as $photoPath)
                            <a class="photo-proof-card" href="{{ route('daily-activity.photos.show', ['activity' => $dailyReport['activity']->id, 'photoIndex' => $loop->index]) }}" target="_blank" rel="noopener">
                                <img src="{{ route('daily-activity.photos.show', ['activity' => $dailyReport['activity']->id, 'photoIndex' => $loop->index]) }}" alt="Uploaded proof photo">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="muted" style="margin: 0;">No proof photos uploaded for this day.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
