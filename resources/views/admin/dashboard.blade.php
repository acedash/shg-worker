@extends('layouts.app')

@section('content')
    <!-- Admin Hero Section -->
    <div class="hero admin-hero" style="border:none; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: white; padding: 40px; margin-bottom: 32px; box-shadow: var(--shadow-lg); overflow: hidden; position: relative;">
        <!-- Glassmorphism decorative element -->
        <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; blur: 80px; z-index: 0;"></div>
        
        <div class="spaced" style="position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <span class="page-kicker" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white;">Global Operations</span>
                <h2 style="font-size: 2.5rem; margin-bottom: 12px; color: white;">Admin Control Center</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem; max-width: 600px; margin-bottom: 12px;">Monitor field worker performance, manage registered users, and generate comprehensive monthly overview reports.</p>
            </div>
            
            <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(20px); border-radius: 16px; padding: 20px; min-width: 260px;">
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: rgba(255,255,255,0.8);">Reporting Month</span>
                    <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 100px; font-size: 0.8rem; font-weight: 700;">{{ $selectedMonth->format('F Y') }}</span>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <input type="month" name="month" value="{{ $selectedMonth->format('Y-m') }}" onchange="this.form.submit()" style="background: rgba(255,255,255,0.9); border: none; color: var(--text); padding: 10px 14px; border-radius: 12px; font-size: 0.95rem; width: 100%; box-shadow: var(--shadow-sm);">
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-3" style="margin-bottom: 32px; gap: 24px;">
        <div class="card" style="padding: 24px; border-left: 4px solid var(--brand); transition: transform 0.2s;">
            <div style="display: flex; align-items: start; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--brand-soft); color: var(--brand); display: flex; align-items: center; justify-content: center;">
                    <svg style="width:24px; height:24px; stroke: currentColor; fill: none; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" /></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.8rem; color: var(--brand); font-weight: 700;">{{ $stats['total_workers'] }}</strong>
                    <span style="color: var(--muted); font-size: 0.95rem; font-weight: 500;">Total Community Mobilizers</span>
                </div>
            </div>
        </div>
        
        <div class="card" style="padding: 24px; border-left: 4px solid #3b82f6; transition: transform 0.2s;">
            <div style="display: flex; align-items: start; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                    <svg style="width:24px; height:24px; stroke: currentColor; fill: none; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" /><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" /></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.8rem; color: #3b82f6; font-weight: 700;">{{ $stats['monthly_logs'] }}</strong>
                    <span style="color: var(--muted); font-size: 0.95rem; font-weight: 500;">Total Submissions (Month)</span>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 24px; border-left: 4px solid #10b981; transition: transform 0.2s;">
            <div style="display: flex; align-items: start; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center;">
                    <svg style="width:24px; height:24px; stroke: currentColor; fill: none; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2" /></svg>
                </div>
                <div>
                    <strong style="display: block; font-size: 1.8rem; color: #10b981; font-weight: 700;">{{ $stats['active_workers'] }}</strong>
                    <span style="color: var(--muted); font-size: 0.95rem; font-weight: 500;">Active Mobilizers (Month)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Split View -->
    <div class="grid grid-2" style="gap: 24px; align-items: start;">
        <!-- Registered Workers Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 24px; background: var(--surface-soft); border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin:0; font-size: 1.2rem;">Registered Field Force</h3>
                    <p style="margin: 4px 0 0; color: var(--muted); font-size: 0.9rem;">View and manage registered workers' data access.</p>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}" style="display:flex; gap:10px; flex-wrap: wrap; justify-content: flex-end; align-items: center;">
                    <input type="hidden" name="month" value="{{ $selectedMonth->format('Y-m') }}">
                    <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Search name / email / phone" style="min-width: 220px; background: #fff; border: 1px solid var(--line); padding: 10px 12px; border-radius: 12px; font-size: 0.92rem;">
                    <select id="admin_district_id" name="district_id" style="min-width: 180px; background: #fff; border: 1px solid var(--line); padding: 10px 12px; border-radius: 12px; font-size: 0.92rem;">
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ (string) $filters['district_id'] === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                    <select id="admin_ulb_id" name="ulb_id" style="min-width: 200px; background: #fff; border: 1px solid var(--line); padding: 10px 12px; border-radius: 12px; font-size: 0.92rem;">
                        <option value="">All ULBs</option>
                        @foreach ($districts as $district)
                            @foreach ($district->ulbs as $ulb)
                                <option value="{{ $ulb->id }}" data-district-id="{{ $district->id }}" {{ (string) $filters['ulb_id'] === (string) $ulb->id ? 'selected' : '' }}>
                                    {{ $ulb->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <button class="button button-primary" type="submit" style="min-height: 42px; padding: 10px 14px; font-size: 0.9rem;">Filter</button>
                    <a class="button button-secondary" href="{{ route('admin.dashboard', ['month' => $selectedMonth->format('Y-m')]) }}" style="min-height: 42px; padding: 10px 14px; font-size: 0.9rem;">Clear</a>
                </form>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: white;">
                            <th style="padding: 16px 24px;">Worker Details</th>
                            <th style="padding: 16px 24px; text-align: center;">Logs ({{ $selectedMonth->format('M') }})</th>
                            <th style="padding: 16px 24px; text-align: right;">Reports</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($workers as $worker)
                            <tr style="border-top: 1px solid var(--line); transition: background 0.1s;">
                                <td style="padding: 16px 24px;">
                                    <div style="display: flex; align-items: center; gap: 14px;">
                                        <div style="width: 42px; height: 42px; border-radius: 12px; background: var(--brand-soft); color: var(--brand); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                            {{ substr($worker->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong style="display: block; color: var(--text);">{{ $worker->name }}</strong>
                                            <span style="color: var(--muted); font-size: 0.85rem;">{{ $worker->district_name }} • {{ $worker->ulb_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px 24px; text-align: center;">
                                    <span style="display: inline-block; padding: 4px 12px; border-radius: 100px; background: {{ $worker->submissions_this_month > 0 ? '#ecfdf5' : '#fef2f2' }}; color: {{ $worker->submissions_this_month > 0 ? '#10b981' : '#ef4444' }}; font-weight: 700; font-size: 0.9rem;">
                                        {{ $worker->submissions_this_month }}
                                    </span>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a class="button button-secondary button-icon" style="padding: 8px; border-radius: 10px;" href="{{ route('admin.workers.reports.monthly.whatsapp', ['user' => $worker->id, 'month' => $selectedMonth->format('Y-m')]) }}" title="Share via WhatsApp">
                                            <svg style="color: #25D366;" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                                        </a>
                                        <a class="button button-secondary button-icon" style="padding: 8px; border-radius: 10px;" href="{{ route('admin.workers.reports.monthly.pdf', ['user' => $worker->id, 'month' => $selectedMonth->format('Y-m')]) }}" title="Download PDF">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14" /></svg>
                                        </a>
                                        <a class="button button-primary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: 10px;" href="{{ route('admin.workers.reports.monthly', ['user' => $worker->id, 'month' => $selectedMonth->format('Y-m')]) }}">Download</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 40px; text-align: center; color: var(--muted);">No workers registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 14px 24px; border-top: 1px solid var(--line); background: #fff; display:flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="color: var(--muted); font-size: 0.9rem;">
                    Showing {{ $workers->firstItem() ?: 0 }}-{{ $workers->lastItem() ?: 0 }} of {{ $workers->total() }} workers
                </span>
                <div style="display:flex; gap:8px;">
                    @if ($workers->onFirstPage())
                        <span class="button button-secondary" style="opacity:0.55; pointer-events:none; min-height: 38px; padding: 8px 12px; font-size: 0.88rem;">Previous</span>
                    @else
                        <a class="button button-secondary" style="min-height: 38px; padding: 8px 12px; font-size: 0.88rem;" href="{{ $workers->previousPageUrl() }}">Previous</a>
                    @endif

                    @if ($workers->hasMorePages())
                        <a class="button button-secondary" style="min-height: 38px; padding: 8px 12px; font-size: 0.88rem;" href="{{ $workers->nextPageUrl() }}">Next</a>
                    @else
                        <span class="button button-secondary" style="opacity:0.55; pointer-events:none; min-height: 38px; padding: 8px 12px; font-size: 0.88rem;">Next</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Submissions Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 24px; background: var(--surface-soft); border-bottom: 1px solid var(--line);">
                <h3 style="margin:0; font-size: 1.2rem;">Live Activity Stream</h3>
                <p style="margin: 4px 0 0; color: var(--muted); font-size: 0.9rem;">Most recent daily reports across all field workers.</p>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: white;">
                            <th style="padding: 16px 24px;">Date / Worker</th>
                            <th style="padding: 16px 24px;">Remarks Summary</th>
                            <th style="padding: 16px 24px; text-align: right;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentActivities as $activity)
                            <tr style="border-top: 1px solid var(--line);">
                                <td style="padding: 16px 24px;">
                                    <div style="display: flex; align-items: start; gap: 14px;">
                                        <div style="background: #f1f5f9; width: 42px; height: 42px; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; line-height: 1; flex-shrink: 0;">
                                            <span style="font-size: 0.95rem; font-weight: 700; color: var(--text);">{{ $activity->activity_date->format('d') }}</span>
                                            <span style="font-size: 0.65rem; font-weight: 800; color: var(--muted); text-transform: uppercase;">{{ $activity->activity_date->format('M') }}</span>
                                        </div>
                                        <div>
                                            <strong style="display: block; color: var(--text); font-size: 1rem;">{{ $activity->user->name }}</strong>
                                            <span style="color: var(--muted); font-size: 0.85rem;">{{ $activity->user->district_name }} • {{ $activity->user->ulb_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <div style="font-size: 0.9rem; color: var(--muted); max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $activity->remarks }}">
                                        {{ $activity->remarks ?: 'Entry saved without remarks.' }}
                                    </div>
                                </td>
                                <td style="padding: 16px 24px; text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a class="button button-secondary button-icon" style="padding: 8px; border-radius: 10px;" href="{{ route('admin.workers.reports.daily', ['user' => $activity->user_id, 'activity' => $activity->id]) }}" title="View Entry Detail">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" /><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>
                                        </a>
                                        <a class="button button-secondary button-icon" style="padding: 8px; border-radius: 10px;" href="{{ $activity->whatsapp_url }}" target="_blank" rel="noopener">
                                            <svg style="color: #25D366;" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 01-11.7 7.1L4 20l1-4.1A8 8 0 1120 12z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 40px; text-align: center; color: var(--muted);">No activities submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var districtSelect = document.getElementById('admin_district_id');
            var ulbSelect = document.getElementById('admin_ulb_id');

            if (!districtSelect || !ulbSelect) {
                return;
            }

            var options = Array.from(ulbSelect.querySelectorAll('option[data-district-id]'));

            function syncUlbs() {
                var districtId = districtSelect.value;
                var currentValue = ulbSelect.value;
                var hasVisibleSelected = false;

                options.forEach(function (option) {
                    var visible = !districtId || option.dataset.districtId === districtId;
                    option.hidden = !visible;

                    if (visible && option.value === currentValue) {
                        hasVisibleSelected = true;
                    }
                });

                if (!hasVisibleSelected) {
                    ulbSelect.value = '';
                }
            }

            districtSelect.addEventListener('change', syncUlbs);
            syncUlbs();
        })();
    </script>
@endsection
