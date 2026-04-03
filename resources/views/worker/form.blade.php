@extends('layouts.app')

@section('content')
    <div class="hero daily-form-hero" style="border:none; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: white; padding: 40px; margin-bottom: 32px; box-shadow: var(--shadow-lg); overflow: hidden; position: relative;">
        <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; blur: 80px; z-index: 0;"></div>
        
        <div class="daily-form-hero-grid" style="position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <span class="page-kicker" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">Daily Reporting</span>
                <h2 style="font-size: 2.25rem; margin-bottom: 8px; color: white;">Activity Form</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.05rem; max-width: 500px;">Record your progress for the selected day accurately.</p>
            </div>
            <div class="daily-form-top-actions" style="display: flex; gap: 12px;">
                <a class="button" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px);" href="{{ route('worker.dashboard', ['month' => $selectedMonth->format('Y-m')]) }}">Back to Dashboard</a>
                @if ($activity)
                    <a class="button" style="background: white; color: var(--brand); border: none; font-weight: 600;" href="{{ route('worker.reports.daily', ['activity' => $activity->id]) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true" style="margin-right: 8px;"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                        <span>View Report</span>
                    </a>
                @endif
                <a class="button" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px);" href="{{ route('worker.reports.monthly.pdf', ['month' => $selectedMonth->format('Y-m')]) }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true" style="margin-right: 8px;"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                    <span>Monthly PDF</span>
                </a>
            </div>
        </div>
    </div>

    <div class="form-layout-minimal" style="max-width: 900px; margin: 0 auto;">
        <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
            <div style="background: var(--surface-soft); padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: white; display: flex; align-items: center; justify-content: center; color: var(--brand); box-shadow: var(--shadow-sm);">
                        <svg style="width: 24px; height: 24px;" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3v3M16 3v3M4 9h16M5 5h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" /></svg>
                    </div>
                    <div>
                        <strong style="display: block; font-size: 1.1rem;">{{ $selectedDate->format('d M Y') }}</strong>
                        <span style="color: var(--muted); font-size: 0.9rem;">Reporting Date</span>
                    </div>
                </div>
                
                <form method="GET" action="{{ route('worker.daily-activity.form') }}" style="display: flex; align-items: center; gap: 12px; background: white; padding: 6px 12px; border-radius: 12px; border: 1px solid var(--line);">
                    <input type="hidden" name="month" value="{{ $selectedMonth->format('Y-m') }}">
                    <label for="activity-date-switch" style="margin:0; font-size: 0.85rem; color: var(--muted); font-weight: 600;">Switch Date:</label>
                    <input id="activity-date-switch" type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()" style="border: none; padding: 4px; font-weight: 500; font-size: 0.9rem; width: auto; background: transparent;">
                </form>
            </div>

            <div style="padding: 24px;">
                <div class="mini-stats-inline" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
                    <div class="mini-stat" style="background: var(--surface-soft); border: none;">
                        <span style="display: block; color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Month</span>
                        <strong style="color: var(--text);">{{ $selectedMonth->format('F Y') }}</strong>
                    </div>
                    <div class="mini-stat" style="background: var(--surface-soft); border: none;">
                        <span style="display: block; color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Status</span>
                        <strong style="color: {{ $activity ? '#10b981' : '#f59e0b' }};">{{ $activity ? 'Saved' : 'Not Started' }}</strong>
                    </div>
                    <div class="mini-stat" style="background: var(--surface-soft); border: none;">
                        <span style="display: block; color: var(--muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Entry ID</span>
                        <strong style="color: var(--text);">{{ $activity ? '#' . $activity->id : '---' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('worker.daily-activity.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="activity_date" value="{{ $selectedDate->format('Y-m-d') }}">

            @foreach ($sections as $sectionTitle => $fields)
                <div class="card" style="margin-bottom: 24px; padding: 0; overflow: hidden;">
                    <div style="padding: 16px 24px; background: var(--surface-soft); border-bottom: 1px solid var(--line);">
                        <h4 style="margin:0; font-size: 1rem; color: var(--brand);">{{ $sectionTitle }}</h4>
                    </div>
                    <div style="padding: 24px;">
                        <div class="metric-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                            @foreach ($fields as $field)
                                <div class="metric-card">
                                    <label for="{{ $field }}" style="font-size: 0.9rem; color: var(--muted); margin-bottom: 8px;">{{ $metricLabels[$field] }}</label>
                                    <input
                                        id="{{ $field }}"
                                        class="@error($field) input-error @enderror"
                                        type="number"
                                        min="0"
                                        max="99999"
                                        step="1"
                                        inputmode="numeric"
                                        name="{{ $field }}"
                                        value="{{ old($field, $activity?->{$field} ?? 0) }}"
                                        style="border-radius: 12px; font-weight: 600; font-size: 1.1rem; color: var(--brand);"
                                    >
                                    @error($field)
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="card" style="margin-bottom: 24px; padding: 24px;">
                <h4 style="margin-top:0; font-size: 1rem; color: var(--brand); margin-bottom: 16px;">Photo Evidence</h4>
                <div style="background: var(--surface-soft); border-radius: 16px; padding: 20px; text-align: center; border: 2px dashed var(--line);">
                    <input id="photos" class="@error('photos') input-error @enderror @error('photos.*') input-error @enderror" type="file" name="photos[]" accept="image/*" multiple style="margin-bottom: 12px;">
                    <p class="muted" style="margin: 0; font-size: 0.85rem;">Upload up to 5 photos. Max 4MB each.</p>
                </div>
                
                @error('photos')
                    <div class="field-error">{{ $message }}</div>
                @enderror
                @error('photos.*')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                @if (! empty($activity?->photo_paths))
                    <div class="photo-proof-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-top: 20px;">
                        @foreach ($activity->photo_paths as $photoPath)
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($photoPath) }}" target="_blank" rel="noopener" style="display: block; border-radius: 12px; overflow: hidden; aspect-ratio: 1; border: 1px solid var(--line); position: relative;">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($photoPath) }}" alt="Proof" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card" style="margin-bottom: 32px; padding: 24px;">
                <h4 style="margin-top:0; font-size: 1rem; color: var(--brand); margin-bottom: 16px;">Remarks & Feedback</h4>
                <textarea id="remarks" class="@error('remarks') input-error @enderror" name="remarks" placeholder="Add any additional notes or feedback here..." style="border-radius: 16px; min-height: 120px; padding: 16px;">{{ old('remarks', $activity?->remarks) }}</textarea>
                @error('remarks')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="background: white; position: sticky; bottom: 20px; padding: 20px; border-radius: 20px; box-shadow: var(--shadow-lg); border: 1px solid var(--line); display: flex; gap: 12px; z-index: 100;">
                <button class="button-primary" type="submit" style="flex: 2; border-radius: 12px; font-weight: 600; font-size: 1.1rem; padding: 14px;">Save Activity Report</button>
                <a class="button button-secondary" href="{{ route('worker.dashboard', ['month' => $selectedMonth->format('Y-m')]) }}" style="flex: 1; border-radius: 12px;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
