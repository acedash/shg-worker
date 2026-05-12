@extends('layouts.app')

@section('content')
    <style>
        .export-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 20px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .loader-text {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
    </style>

    <div id="exportLoader" class="export-loader-overlay">
        <div class="spinner"></div>
        <div class="loader-text">Generating Report...</div>
        <p style="margin-top: 10px; opacity: 0.8; font-size: 0.9rem;">This may take a minute for large ULBs.</p>
    </div>

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

    <!-- ULB Export Panel -->
    <div class="card" style="margin-bottom: 32px; padding: 0; overflow: hidden; border: 2px solid #c7d2fe;">
        <div style="padding: 20px 24px; background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-bottom: 1px solid #c7d2fe; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: #4f46e5; color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M16 12l-4 4-4-4M12 3v13"/></svg>
            </div>
            <div>
                <h3 style="margin:0; font-size: 1.1rem; color: #3730a3;">Export ULB-wise Compiled Reports</h3>
                <p style="margin: 2px 0 0; font-size: 0.88rem; color: #6366f1;">Download a compiled PDF or Excel sheet containing all workers' monthly reports for a selected ULB.</p>
            </div>
        </div>
        <div style="padding: 20px 24px;">
            <form id="ulbExportForm" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
                <div style="display: flex; flex-direction: column; gap: 6px; min-width: 200px;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;">District</label>
                    <select id="export_district_id" style="background: #fff; border: 1px solid var(--line); padding: 10px 12px; border-radius: 12px; font-size: 0.92rem;">
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; min-width: 220px;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;">ULB <span style="color:#ef4444;">*</span></label>
                    <select id="export_ulb_id" style="background: #fff; border: 1px solid var(--line); padding: 10px 12px; border-radius: 12px; font-size: 0.92rem;">
                        <option value="">— Select a ULB —</option>
                        <option value="all" 
                            data-pdf-url="{{ route('admin.all-ulbs.reports.monthly.pdf', ['month' => '__MONTH__']) }}"
                            data-csv-url="{{ route('admin.all-ulbs.reports.monthly',     ['month' => '__MONTH__']) }}">
                            All ULBs (Global Report)
                        </option>
                        @foreach ($allUlbs as $ulb)
                            <option value="{{ $ulb->id }}" data-district-id="{{ $ulb->district_id }}"
                                data-pdf-url="{{ route('admin.ulb.reports.monthly.pdf', ['ulb' => $ulb->id, 'month' => '__MONTH__']) }}"
                                data-csv-url="{{ route('admin.ulb.reports.monthly',     ['ulb' => $ulb->id, 'month' => '__MONTH__']) }}">
                                {{ $ulb->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px; min-width: 180px;">
                    <label style="font-size: 0.8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em;">Month</label>
                    <input type="month" id="export_month" value="{{ $selectedMonth->format('Y-m') }}"
                           style="background: #fff; border: 1px solid var(--line); padding: 10px 12px; border-radius: 12px; font-size: 0.92rem;">
                </div>
                <div style="display: flex; gap: 10px; padding-bottom: 1px;">
                    <button type="button" id="exportPdfBtn"
                        style="display:inline-flex; align-items:center; gap:8px; padding: 10px 20px; background: #ef4444; color: #fff; border-radius: 12px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: opacity 0.2s;"
                        onclick="triggerUlbExport('pdf')">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg>
                        Download PDF
                    </button>
                    <button type="button" id="exportCsvBtn"
                        style="display:inline-flex; align-items:center; gap:8px; padding: 10px 20px; background: #16a34a; color: #fff; border-radius: 12px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: opacity 0.2s;"
                        onclick="triggerUlbExport('csv')">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M16 12l-4 4-4-4M12 3v13"/></svg>
                        Download Excel
                    </button>
                </div>
            </form>
            <p id="export_error" style="display:none; margin: 10px 0 0; color: #ef4444; font-size: 0.88rem; font-weight: 500;">⚠ Please select a ULB before downloading.</p>
        </div>
    </div>

    <script>
        (function () {
            var exportDistrict = document.getElementById('export_district_id');
            var exportUlb      = document.getElementById('export_ulb_id');
            var exportOpts     = Array.from(exportUlb.querySelectorAll('option[data-district-id]'));

            exportDistrict.addEventListener('change', function () {
                var did = exportDistrict.value;
                exportOpts.forEach(function (o) { o.hidden = did && o.dataset.districtId !== did; });
                exportUlb.value = '';
            });
        })();

        function triggerUlbExport(type) {
            var ulbSel  = document.getElementById('export_ulb_id');
            var errEl   = document.getElementById('export_error');
            var month   = document.getElementById('export_month').value;
            var opt     = ulbSel.options[ulbSel.selectedIndex];

            if (!ulbSel.value) {
                errEl.style.display = 'block';
                return;
            }
            errEl.style.display = 'none';

            var url = type === 'pdf'
                ? opt.dataset.pdfUrl.replace('__MONTH__', month)
                : opt.dataset.csvUrl.replace('__MONTH__', month);

            showLoader();
            window.location.href = url;
        }

        function showLoader() {
            var loader = document.getElementById('exportLoader');
            loader.style.display = 'flex';
            
            // Auto-hide loader when the window regains focus 
            // (this happens after the browser starts the download)
            window.addEventListener('focus', hideLoader, { once: true });
            
            // Backup hide after 15 seconds in case focus event doesn't fire
            setTimeout(hideLoader, 15000);
        }

        function hideLoader() {
            document.getElementById('exportLoader').style.display = 'none';
        }

        // Also add loader to individual download buttons in the table
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[title="Download PDF"], a.button-primary').forEach(function(btn) {
                if (btn.innerText.trim() === 'Download' || btn.title === 'Download PDF') {
                    btn.addEventListener('click', function() {
                        showLoader();
                    });
                }
            });
        });
    </script>

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
                        @foreach ($allUlbs as $ulb)
                            <option value="{{ $ulb->id }}" data-district-id="{{ $ulb->district_id }}" {{ (string) $filters['ulb_id'] === (string) $ulb->id ? 'selected' : '' }}>
                                {{ $ulb->name }}
                            </option>
                        @endforeach
                    </select>
                    <button class="button button-primary" type="submit" style="min-height: 42px; padding: 10px 14px; font-size: 0.9rem;">Filter</button>
                    <a class="button button-secondary" href="{{ route('admin.dashboard', ['month' => $selectedMonth->format('Y-m')]) }}" style="min-height: 42px; padding: 10px 14px; font-size: 0.9rem;">Clear</a>
                </form>
            </div>

            {{-- ULB Export Bar: shown only when a specific ULB is filtered --}}
            @if($filters['ulb_id'])
                @php
                    $selectedUlb = null;
                    foreach ($districts as $d) {
                        foreach ($d->ulbs as $u) {
                            if ((string)$u->id === (string)$filters['ulb_id']) { $selectedUlb = $u; break 2; }
                        }
                    }
                @endphp
                @if($selectedUlb)
                <div style="padding: 14px 24px; background: #eef2ff; border-bottom: 1px solid #c7d2fe; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg style="width:18px; height:18px; color: #4f46e5; flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        <span style="font-weight: 600; color: #3730a3; font-size: 0.95rem;">
                            Export compiled report for <strong>{{ $selectedUlb->name }}</strong> — {{ $selectedMonth->format('F Y') }}
                        </span>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('admin.ulb.reports.monthly.pdf', ['ulb' => $selectedUlb->id, 'month' => $selectedMonth->format('Y-m')]) }}"
                           class="button"
                           style="display:inline-flex; align-items:center; gap:7px; padding: 9px 18px; background: #ef4444; color: #fff; border-radius: 10px; font-weight: 600; font-size: 0.88rem; text-decoration:none; border:none;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6M9 9h1"/></svg>
                            Download PDF
                        </a>
                        <a href="{{ route('admin.ulb.reports.monthly', ['ulb' => $selectedUlb->id, 'month' => $selectedMonth->format('Y-m')]) }}"
                           class="button"
                           style="display:inline-flex; align-items:center; gap:7px; padding: 9px 18px; background: #16a34a; color: #fff; border-radius: 10px; font-weight: 600; font-size: 0.88rem; text-decoration:none; border:none;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M16 12l-4 4-4-4M12 3v13"/></svg>
                            Download Excel
                        </a>
                    </div>
                </div>
                @endif
            @endif

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
                                            <a href="{{ route('admin.workers.profile', $worker->id) }}" style="text-decoration: none; display: block;">
                                                <strong style="display: block; color: var(--text); transition: color 0.2s;" onmouseover="this.style.color='var(--brand)'" onmouseout="this.style.color='var(--text)'">{{ $worker->name }}</strong>
                                            </a>
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
                                        <a class="button button-secondary button-icon" style="padding: 8px; border-radius: 10px;" href="{{ route('admin.workers.profile', $worker->id) }}" title="View Profile">
                                            <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
                                        </a>
                                        <a class="button button-secondary button-icon" style="padding: 8px; border-radius: 10px;" href="{{ route('admin.workers.monthly.whatsapp', ['user' => $worker->id, 'month' => $selectedMonth->format('Y-m')]) }}" title="Share via WhatsApp">
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
                                            <a href="{{ route('admin.workers.profile', $activity->user_id) }}" style="text-decoration: none; display: block;">
                                                <strong style="display: block; color: var(--text); font-size: 1rem; transition: color 0.2s;" onmouseover="this.style.color='var(--brand)'" onmouseout="this.style.color='var(--text)'">{{ $activity->user->name }}</strong>
                                            </a>
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
