@extends('layouts.app')

@section('content')
    <div class="hero">
        <div class="spaced">
            <div>
                <span class="page-kicker">Daily Field Visit Report</span>
                <h2>Activity Form</h2>
                <p>Fill the updated field visit format for the selected day in a simple step-by-step layout.</p>
            </div>
            <div class="action-row-inline">
                <a class="button button-secondary" href="{{ route('worker.dashboard', ['month' => $selectedMonth->format('Y-m')]) }}">Back</a>
                @if ($activity)
                    <a class="button button-secondary button-icon" href="{{ route('worker.reports.daily', ['activity' => $activity->id]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                        <span>View Report</span>
                    </a>
                @endif
                <a class="button button-secondary button-icon" href="{{ route('worker.reports.monthly.pdf', ['month' => $selectedMonth->format('Y-m')]) }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                    <span>Monthly PDF</span>
                </a>
            </div>
        </div>
    </div>

    <div class="form-layout-minimal">
        <div class="card" style="margin-bottom: 18px;">
            <div class="mini-stats-inline">
                <div class="mini-stat">
                    <strong>{{ $selectedDate->format('d M Y') }}</strong>
                    <span>Selected date</span>
                </div>
                <div class="mini-stat">
                    <strong>{{ $selectedMonth->format('F Y') }}</strong>
                    <span>Report month</span>
                </div>
                <div class="mini-stat">
                    <strong>{{ $activity ? 'Saved' : 'Not started' }}</strong>
                    <span>Entry status</span>
                </div>
            </div>

            <form method="GET" action="{{ route('worker.daily-activity.form') }}" class="action-row-inline" style="margin-top: 16px;">
                <input type="hidden" name="month" value="{{ $selectedMonth->format('Y-m') }}">
                <label for="activity-date-switch">Change date</label>
                <div class="input-with-icon" style="max-width: 220px;">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3v3M16 3v3M4 9h16M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" /></svg>
                    </span>
                    <input id="activity-date-switch" type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>

        <form method="POST" action="{{ route('worker.daily-activity.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="activity_date" value="{{ $selectedDate->format('Y-m-d') }}">

            @foreach ($sections as $sectionTitle => $fields)
                <div class="card" style="margin-bottom: 18px;">
                    <div class="section-head">
                        <div>
                            <h3>{{ $sectionTitle }}</h3>
                            <p>Enter counts for this section.</p>
                        </div>
                    </div>

                    <div class="metric-grid">
                        @foreach ($fields as $field)
                            <div class="metric-card">
                                <label for="{{ $field }}">{{ $metricLabels[$field] }}</label>
                                <input
                                    id="{{ $field }}"
                                    class="@error($field) input-error @enderror"
                                    type="number"
                                    min="0"
                                    max="99999"
                                    step="1"
                                    inputmode="numeric"
                                    name="{{ $field }}"
                                    value="{{ old($field, (($activity?->{$field} ?? 0) > 0 ? $activity->{$field} : '')) }}"
                                    placeholder="Enter count"
                                >
                                @error($field)
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="card" style="margin-bottom: 18px;">
                <div class="section-head">
                    <div>
                        <h3>Proof Photos</h3>
                        <p>Optional. Add up to 5 photos as supporting proof for the day.</p>
                    </div>
                </div>

                <div class="subtle-panel">
                    <input id="photos" class="@error('photos') input-error @enderror @error('photos.*') input-error @enderror" type="file" name="photos[]" accept="image/*" multiple>
                    <p class="muted" style="margin: 8px 0 0;">Photos are optional. Upload only if available. Max 4MB each.</p>
                </div>

                @error('photos')
                    <div class="field-error">{{ $message }}</div>
                @enderror
                @error('photos.*')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                @if (! empty($activity?->photo_paths))
                    <div class="photo-proof-grid" style="margin-top: 16px;">
                        @foreach ($activity->photo_paths as $photoPath)
                            <a class="photo-proof-card" href="{{ route('daily-activity.photos.show', ['activity' => $activity->id, 'photoIndex' => $loop->index]) }}" target="_blank" rel="noopener">
                                <img src="{{ route('daily-activity.photos.show', ['activity' => $activity->id, 'photoIndex' => $loop->index]) }}" alt="Proof photo">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card" style="margin-bottom: 18px;">
                <div class="section-head">
                    <div>
                        <h3>Daily Notes</h3>
                        <p>Optional notes for any special activity, challenge, or follow-up.</p>
                    </div>
                </div>

                <textarea id="remarks" class="@error('remarks') input-error @enderror" name="remarks" placeholder="Add any extra notes for this date...">{{ old('remarks', $activity?->remarks) }}</textarea>
                @error('remarks')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="sticky-action-bar">
                <button class="button button-primary" type="submit">Save Daily Report</button>
                <a class="button button-secondary" href="{{ route('worker.dashboard', ['month' => $selectedMonth->format('Y-m')]) }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
