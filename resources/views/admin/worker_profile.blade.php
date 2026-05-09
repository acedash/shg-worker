@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <!-- Back Button -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--muted); text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--brand)'" onmouseout="this.style.color='var(--muted)'">
            <svg style="width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-3" style="gap: 24px; align-items: start;">
        <!-- Profile Sidebar -->
        <div class="card" style="grid-column: span 1; padding: 32px; text-align: center; position: sticky; top: 24px;">
            <div style="width: 100px; height: 100px; border-radius: 32px; background: var(--brand-soft); color: var(--brand); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 700; margin: 0 auto 20px; box-shadow: var(--shadow-md);">
                {{ substr($worker->name, 0, 1) }}
            </div>
            <h2 style="margin: 0 0 8px; font-size: 1.5rem; color: var(--text);">{{ $worker->name }}</h2>
            <span class="badge" style="background: var(--brand-soft); color: var(--brand); font-weight: 600; padding: 6px 12px; border-radius: 100px;">Community Mobilizer</span>
            
            <div style="margin-top: 32px; text-align: left; border-top: 1px solid var(--line); padding-top: 24px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">Phone Number</label>
                    <div style="font-weight: 500; color: var(--text);">{{ $worker->phone ?: 'Not provided' }}</div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">Email Address</label>
                    <div style="font-weight: 500; color: var(--text);">{{ $worker->email ?: 'Not provided' }}</div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">Assigned Ward</label>
                    <div style="font-weight: 500; color: var(--text);">{{ $worker->assigned_ward ?: 'Not assigned' }}</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 24px;">
            <!-- Location Card -->
            <div class="card" style="padding: 24px;">
                <h3 style="margin: 0 0 20px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                    <svg style="width: 20px; height: 20px; color: var(--brand);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Service Location
                </h3>
                <div class="grid grid-2" style="gap: 20px;">
                    <div style="background: var(--surface-soft); padding: 16px; border-radius: 12px; border: 1px solid var(--line);">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 4px;">District</label>
                        <div style="font-weight: 600; font-size: 1.05rem;">{{ $worker->district_name }}</div>
                    </div>
                    <div style="background: var(--surface-soft); padding: 16px; border-radius: 12px; border: 1px solid var(--line);">
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--muted); margin-bottom: 4px;">ULB / City</label>
                        <div style="font-weight: 600; font-size: 1.05rem;">{{ $worker->ulb_name }}</div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card" style="padding: 24px; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; color: rgba(255,255,255,0.9);">Total Contributions</h3>
                        <div style="font-size: 2.5rem; font-weight: 800; margin-top: 4px;">{{ $totalSubmissions }}</div>
                        <p style="margin: 8px 0 0; font-size: 0.9rem; color: rgba(255,255,255,0.7);">Daily activity reports submitted till date.</p>
                    </div>
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1.1rem;">Recent Activity</h3>
                </div>
                <div style="padding: 0;">
                    @forelse ($recentActivities as $activity)
                        <div style="padding: 16px 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="background: var(--surface-soft); padding: 8px 12px; border-radius: 10px; text-align: center; min-width: 60px;">
                                    <div style="font-weight: 700; color: var(--text);">{{ $activity->activity_date->format('d') }}</div>
                                    <div style="font-size: 0.7rem; font-weight: 800; color: var(--muted); text-transform: uppercase;">{{ $activity->activity_date->format('M') }}</div>
                                </div>
                                <div>
                                    <div style="font-weight: 500; font-size: 0.95rem;">Daily Report Submitted</div>
                                    <div style="font-size: 0.85rem; color: var(--muted);">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.workers.reports.daily', ['user' => $worker->id, 'activity' => $activity->id]) }}" class="button button-secondary" style="padding: 6px 12px; font-size: 0.8rem;">View</a>
                        </div>
                    @empty
                        <div style="padding: 40px; text-align: center; color: var(--muted);">
                            No recent activity found.
                        </div>
                    @endforelse
                </div>
                @if($totalSubmissions > 5)
                    <div style="padding: 16px; text-align: center; background: var(--surface-soft);">
                        <p style="margin: 0; font-size: 0.85rem; color: var(--muted);">Viewing last 5 submissions.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
