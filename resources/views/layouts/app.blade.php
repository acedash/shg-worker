<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Community Mobilizer Dashboard') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface-soft: #f1f5f9;
            --line: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --brand: #4f46e5;
            --brand-dark: #4338ca;
            --brand-soft: #eef2ff;
            --danger: #ef4444;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --nav-soft: rgba(248, 250, 252, 0.8);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Instrument Sans', 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            background-image:
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a { color: inherit; }
        .shell { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .nav-shell {
            position: relative;
            margin-bottom: 20px;
        }
        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            gap: 16px; margin-bottom: 0; padding: 10px 16px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .brand-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            min-width: 0;
        }
        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: #eff6ff;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-dark);
            font-size: 0.78rem;
            font-weight: 800;
            flex-shrink: 0;
        }
        .brand h1 { margin: 0; font-size: 0.98rem; font-weight: 600; }
        .brand p { margin: 2px 0 0; color: var(--muted); font-size: 0.8rem; }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .topbar-mobile-bar {
            display: none;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            width: 100%;
        }
        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px;
            border-radius: 999px;
            background: var(--nav-soft);
            border: 1px solid var(--line);
        }
        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 999px;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.5);
        }
        .nav-link.active {
            background: #ffffff;
            color: var(--brand);
            box-shadow: var(--shadow-sm);
        }

        .menu-toggle {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 0;
            color: var(--text);
        }
        .hamburger {
            display: inline-flex;
            flex-direction: column;
            gap: 4px;
        }
        .hamburger span {
            width: 16px;
            height: 2px;
            background: currentColor;
            border-radius: 999px;
            display: block;
        }
        .mobile-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 280px;
            max-width: calc(100vw - 24px);
            z-index: 40;
        }
        .mobile-menu.open {
            display: block;
        }
        .mobile-menu-panel {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 16px;
            padding: 10px;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        }
        .mobile-menu-profile {
            padding: 10px 12px 12px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 10px;
        }
        .mobile-menu-profile strong {
            display: block;
            font-size: 0.95rem;
        }
        .mobile-menu-profile span {
            color: var(--muted);
            font-size: 0.82rem;
        }
        .mobile-menu .nav-links {
            display: grid;
            grid-template-columns: 1fr;
            background: transparent;
            border: 0;
            padding: 0;
            gap: 8px;
            overflow: visible;
            width: 100%;
        }
        .mobile-menu .nav-link {
            justify-content: flex-start;
            width: 100%;
            border-radius: 10px;
            padding: 11px 12px;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: none;
            overflow: visible;
        }
        .mobile-menu .nav-link.active {
            background: #eef4ff;
            border-color: #cfe0ff;
            box-shadow: none;
        }
        .mobile-menu-actions {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--line);
        }
        .mobile-menu-actions a {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            padding: 11px 12px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid var(--line);
            color: var(--text);
            text-decoration: none;
            box-shadow: none;
        }
        .mobile-menu-actions button {
            width: 100%;
            justify-content: flex-start;
            min-height: 0;
            padding: 11px 12px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid var(--line);
            color: var(--text);
            box-shadow: none;
        }
        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: transparent;
            border: 1px solid var(--line);
        }
        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #eef2ff;
            color: var(--brand-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
        }
        .user-meta {
            line-height: 1.2;
        }
        .user-meta strong {
            display: block;
            font-size: 0.84rem;
            font-weight: 600;
        }
        .user-meta span {
            color: var(--muted);
            font-size: 0.74rem;
        }
        .profile-menu {
            position: relative;
        }
        .profile-toggle {
            background: #fff;
        }
        .profile-caret {
            color: var(--muted);
            font-size: 0.8rem;
            margin-left: 2px;
        }
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 220px;
            padding: 10px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: #fff;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
            display: none;
            z-index: 20;
        }
        .profile-dropdown.open {
            display: block;
        }
        .profile-dropdown-header {
            padding: 8px 8px 12px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 8px;
        }
        .profile-dropdown-header strong {
            display: block;
            font-size: 0.92rem;
        }
        .profile-dropdown-header span {
            color: var(--muted);
            font-size: 0.8rem;
        }
        .profile-dropdown-note {
            padding: 8px 8px 12px;
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.45;
            border-bottom: 1px solid var(--line);
            margin-bottom: 8px;
            word-break: break-word;
        }
        .profile-dropdown a,
        .profile-dropdown button {
            width: 100%;
            justify-content: flex-start;
            border-radius: 12px;
            padding: 10px 12px;
            background: transparent;
            border: 0;
            color: var(--text);
            text-decoration: none;
            box-shadow: none;
        }
        .profile-dropdown button {
            cursor: pointer;
        }
        .profile-dropdown a:hover,
        .profile-dropdown button:hover {
            background: var(--nav-soft);
        }
        .button, button, input, select, textarea { font: inherit; }
        .button, button {
            display: inline-flex; align-items: center; justify-content: center;
            text-decoration: none; border-radius: 999px; border: 1px solid transparent;
            padding: 11px 16px; cursor: pointer; font-weight: 500;
        }
        .button svg,
        button svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .button-icon {
            gap: 8px;
        }
        .button-icon span {
            min-width: 0;
        }
        .button-primary, button.button-primary { background: var(--brand); color: #fff; }
        .button-secondary, button.button-secondary { background: #fff; border-color: var(--line); color: var(--text); }
        .hero, .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .hero { padding: 28px; margin-bottom: 20px; }
        .hero h2 { margin: 0 0 8px; font-size: 1.8rem; font-weight: 600; }
        .hero p { margin: 0; max-width: 760px; color: var(--muted); line-height: 1.6; }
        .dashboard-hero {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 28%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(247, 250, 255, 0.98) 100%);
        }
        .dashboard-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(280px, 0.75fr);
            gap: 22px;
            align-items: stretch;
        }
        .dashboard-spotlight {
            border: 1px solid rgba(191, 219, 254, 0.9);
            background: linear-gradient(180deg, #ffffff 0%, #eef4ff 100%);
            border-radius: 22px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
        }
        .dashboard-spotlight-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .spotlight-label {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: #eff6ff;
            color: var(--brand-dark);
            font-size: 0.82rem;
            font-weight: 600;
        }
        .dashboard-spotlight strong {
            font-size: 3rem;
            line-height: 1;
            color: var(--brand-dark);
            font-weight: 650;
            display: block;
            margin-bottom: 10px;
        }
        .dashboard-spotlight p {
            font-size: 0.98rem;
            max-width: none;
        }
        .dashboard-primary-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .dashboard-primary-actions > * {
            flex: 0 0 auto;
        }
        .dashboard-action-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 20px;
        }
        .dashboard-action-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            text-decoration: none;
            color: var(--text);
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(191, 219, 254, 0.8);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }
        .dashboard-action-card-primary {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: #fff;
            border-color: transparent;
        }
        .dashboard-action-card strong,
        .dashboard-action-card small {
            display: block;
        }
        .dashboard-action-card strong {
            font-size: 0.98rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .dashboard-action-card small {
            font-size: 0.84rem;
            opacity: 0.88;
        }
        .action-icon,
        .shortcut-icon,
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .action-icon svg,
        .shortcut-icon svg,
        .stat-icon svg,
        .spotlight-meta svg,
        .dashboard-mini-action svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .dashboard-action-card .action-icon {
            background: rgba(37, 99, 235, 0.08);
            color: var(--brand-dark);
        }
        .dashboard-action-card-primary .action-icon {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }
        .dashboard-secondary-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 18px;
        }
        .dashboard-mini-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 46px;
            padding: 12px 14px;
            border-radius: 14px;
            text-decoration: none;
            border: 1px solid rgba(191, 219, 254, 0.9);
            background: rgba(255, 255, 255, 0.85);
            color: var(--text);
        }
        .worker-dashboard-hero {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }
        .worker-dashboard-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            gap: 20px;
            align-items: stretch;
        }
        .worker-dashboard-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }
        .worker-month-card {
            border: 1px solid #dbe7ff;
            background: linear-gradient(180deg, #eef4ff 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 20px;
        }
        .worker-month-card-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }
        .worker-month-label {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            background: #ffffff;
            color: var(--brand-dark);
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .worker-month-card strong {
            display: block;
            font-size: 2.8rem;
            line-height: 1;
            color: var(--brand-dark);
            font-weight: 650;
            margin-bottom: 8px;
        }
        .worker-month-card p {
            margin: 0;
            color: var(--muted);
        }
        .worker-month-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        .worker-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .worker-stat-card {
            padding: 18px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: #fff;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        }
        .worker-stat-top {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 12px;
        }
        .worker-stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: var(--brand-dark);
            box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.08);
        }
        .worker-stat-icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .worker-stat-card strong {
            display: block;
            font-size: 1.95rem;
            color: var(--brand-dark);
            font-weight: 650;
            margin-bottom: 4px;
        }
        .worker-stat-label {
            color: var(--muted);
            line-height: 1.45;
            display: block;
            font-size: 0.92rem;
        }
        .worker-dashboard-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(320px, 0.75fr);
            gap: 20px;
            align-items: start;
        }
        .worker-side-column {
            display: grid;
            gap: 20px;
        }
        .worker-summary-card,
        .worker-quick-card,
        .worker-recent-card {
            border-radius: 20px;
        }
        .worker-summary-list {
            display: grid;
            gap: 10px;
        }
        .worker-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            padding: 12px 14px;
            border-radius: 14px;
            background: #f8fbff;
            border: 1px solid #e8eef8;
        }
        .worker-summary-row span {
            color: var(--muted);
            line-height: 1.45;
        }
        .worker-summary-row strong {
            color: var(--brand-dark);
            font-weight: 650;
            font-size: 1rem;
        }
        .worker-recent-list {
            display: grid;
            gap: 12px;
        }
        .worker-recent-item {
            padding: 14px;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            background: #fbfcff;
        }
        .worker-recent-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .worker-recent-top strong {
            display: block;
            font-size: 0.98rem;
            margin-bottom: 4px;
        }
        .worker-recent-top span {
            color: var(--muted);
            font-size: 0.82rem;
        }
        .worker-recent-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 999px;
            background: #eff6ff;
            color: var(--brand-dark);
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .worker-recent-item p {
            margin: 0 0 14px;
            color: var(--muted);
            line-height: 1.5;
        }
        .worker-recent-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        .spotlight-meta {
            display: grid;
            gap: 8px;
            margin-top: 18px;
        }
        .spotlight-meta span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #35507a;
            font-size: 0.92rem;
        }
        .dashboard-hero-copy {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .card { padding: 20px; }
        .card h3, .card h4 { margin-top: 0; font-weight: 550; }
        .stat { padding: 18px; border-radius: 16px; background: var(--surface-soft); border: 1px solid var(--line); }
        .stat strong { display: block; font-size: 2rem; margin-bottom: 6px; color: var(--brand-dark); font-weight: 650; }
        .stat span { color: var(--muted); line-height: 1.5; }
        .dashboard-shortcuts {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .shortcut-card {
            display: block;
            padding: 18px;
            border-radius: 18px;
            text-decoration: none;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }
        .dashboard-shortcuts-icons .shortcut-card {
            padding: 20px;
        }
        .shortcut-icon {
            margin-bottom: 14px;
            background: #eff6ff;
            color: var(--brand-dark);
        }
        .shortcut-card strong {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .shortcut-card span {
            color: var(--muted);
            line-height: 1.45;
            font-size: 0.92rem;
        }
        .dashboard-stat-grid .stat {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
        }
        .dashboard-icon-stats .stat {
            position: relative;
            overflow: hidden;
        }
        .dashboard-icon-stats .stat-icon {
            margin-bottom: 12px;
            background: #eff6ff;
            color: var(--brand-dark);
        }
        .stat-featured {
            border-color: #cfe0ff;
        }
        label { display: block; font-weight: 500; margin-bottom: 8px; }
        input, select, textarea {
            width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #d0d7e2;
            background: #fff; color: var(--text);
        }
        .input-error {
            border-color: #fda29b;
            background: #fff6f5;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #9bb8f5;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
        }
        .field-error {
            margin-top: 8px;
            color: var(--danger);
            font-size: 0.9rem;
            line-height: 1.4;
        }
        textarea { min-height: 110px; resize: vertical; }
        .form-grid { display: grid; gap: 16px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .metric-grid { display: grid; gap: 14px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .section-title { margin: 0 0 14px; font-size: 1rem; color: var(--brand-dark); font-weight: 550; }
        .alert { padding: 14px 16px; border-radius: 14px; margin-bottom: 18px; }
        .alert-success { background: #ecfdf3; color: #027a48; border: 1px solid #abefc6; }
        .alert-error { background: #fef3f2; color: var(--danger); border: 1px solid #fecdca; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        th { font-size: 0.82rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.04em; font-weight: 600; }
        .muted { color: var(--muted); }
        .inline-form { display: inline; }
        .spaced { display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap; }
        .action-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .action-row > * {
            flex: 0 0 auto;
        }
        .action-row-inline {
            display: flex;
            gap: 12px;
            flex-wrap: nowrap;
        }
        .action-row-inline > * {
            flex: 1 1 0;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 20px;
            align-items: start;
        }
        .form-layout {
            display: grid;
            grid-template-columns: 0.82fr 1.18fr;
            gap: 20px;
            align-items: start;
        }
        .form-layout-minimal {
            display: grid;
            gap: 18px;
        }
        .card-soft {
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }
        .page-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border-radius: 999px;
            border: 1px solid #dbe7ff;
            background: #f4f8ff;
            color: var(--brand-dark);
            font-size: 0.78rem;
            font-weight: 500;
            margin-bottom: 12px;
        }
        .mini-stats {
            display: grid;
            gap: 12px;
        }
        .mini-stats-inline {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .mini-stat {
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #ebeff5;
            background: #fff;
        }
        .mini-stat strong {
            display: block;
            font-size: 1.1rem;
            color: var(--brand-dark);
            margin-bottom: 4px;
            font-weight: 550;
        }
        .mini-stat span {
            color: var(--muted);
            font-size: 0.86rem;
            line-height: 1.45;
        }
        .helper-list {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .helper-list li {
            padding: 12px 14px;
            border-radius: 14px;
            background: var(--surface-soft);
            border: 1px solid var(--line);
            color: var(--muted);
            line-height: 1.45;
        }
        .subtle-panel {
            padding: 14px 16px;
            border: 1px solid #ebeff5;
            border-radius: 16px;
            background: #fcfdff;
        }
        .input-with-icon {
            position: relative;
            width: 100%;
        }
        .input-with-icon input {
            padding-left: 38px;
        }
        .form-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }
        .form-toolbar p {
            margin: 4px 0 0;
            color: var(--muted);
        }
        .list-table td:first-child,
        .list-table th:first-child {
            width: 150px;
        }
        .touch-card {
            border-radius: 18px;
        }
        .form-section {
            padding: 16px;
            border: 1px solid #ebeff5;
            border-radius: 14px;
            background: #fcfdff;
        }
        .form-section + .form-section {
            margin-top: 18px;
        }
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .section-head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }
        .auth-shell {
            min-height: calc(100vh - 48px);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            padding: 32px;
        }
        .auth-title {
            margin: 0 0 8px;
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
        }
        .auth-subtitle {
            margin: 0 0 24px;
            text-align: center;
            color: var(--muted);
            line-height: 1.5;
        }
        .stack {
            display: grid;
            gap: 16px;
        }
        .button-block {
            width: 100%;
        }
        .text-center {
            text-align: center;
        }
        .password-field {
            position: relative;
        }
        .password-input {
            padding-right: 44px;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--muted);
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .password-toggle svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .password-toggle:hover {
            color: var(--brand-dark);
            background: rgba(79, 70, 229, 0.06);
        }
        .mobile-only {
            display: none;
        }
        .desktop-only {
            display: block;
        }
        .table-stack {
            display: none;
        }
        .compact-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brand-dark);
        }
        .metric-card {
            border: 1px solid #ebeff5;
            border-radius: 12px;
            background: #fff;
            padding: 12px;
        }
        .metric-card label {
            font-size: 0.88rem;
            line-height: 1.4;
            min-height: 38px;
        }
        .dashboard-submission-stack {
            gap: 14px;
        }
        .dashboard-panel {
            border-radius: 22px;
        }
        .quick-form-panel {
            background: linear-gradient(180deg, #f8fbff 0%, #fefeff 100%);
        }
        .daily-form-hero {
            padding: 20px 22px;
        }
        .daily-form-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
        }
        .daily-form-toolbar-copy {
            display: grid;
            gap: 4px;
        }
        .daily-form-toolbar-title {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            font-weight: 550;
            color: var(--text);
        }
        .daily-form-toolbar-title svg,
        .field-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .daily-date-switch-form {
            position: relative;
            min-width: 210px;
        }
        .field-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }
        .daily-date-switch-form input {
            padding-left: 38px;
        }
        .daily-form-top-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .daily-form-toolbar-panel {
            display: grid;
            gap: 14px;
            background: #fcfdff;
        }
        .daily-form-meta .mini-stat {
            background: #fff;
        }
        .daily-form-secondary-actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }
        .daily-form-card {
            border-radius: 18px;
        }
        .daily-form-section {
            background: #fcfdff;
            border-color: #ebeff5;
        }
        .daily-form-section-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
        }
        .daily-form-section-count {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 999px;
            background: #f6f8fb;
            color: #5f6b7a;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }
        .daily-metric-card {
            padding: 10px;
            border-radius: 10px;
            background: #fff;
        }
        .daily-form-submit-row {
            margin-top: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .photo-proof-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 14px;
        }
        .photo-proof-card {
            display: block;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #ebeff5;
            background: #fff;
            aspect-ratio: 1 / 1;
        }
        .photo-proof-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .sticky-action-bar {
            position: sticky;
            bottom: 18px;
            display: flex;
            gap: 12px;
            padding: 14px;
            margin-top: 24px;
            border: 1px solid #ebeff5;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-lg);
            z-index: 20;
        }
        .sticky-action-bar .button {
            flex: 1;
            justify-content: center;
        }
        .submission-card {
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
        }
        .submission-card-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .submission-card-head h4 {
            margin: 0;
        }
        .submission-subtitle {
            display: inline-block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.84rem;
        }
        .submissions-hero {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.1), transparent 26%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 251, 255, 0.98) 100%);
        }
        .submissions-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
        }
        .submissions-hero-copy {
            display: grid;
            gap: 2px;
        }
        .submissions-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }
        .submissions-month-form {
            position: relative;
            min-width: 190px;
        }
        .submissions-month-form input {
            padding-left: 38px;
        }
        .submissions-stats {
            margin-bottom: 20px;
        }
        .submissions-panel {
            border-radius: 20px;
        }
        .submissions-table {
            table-layout: fixed;
        }
        .submissions-table th:nth-child(1) {
            width: 210px;
        }
        .submissions-table th:nth-child(3) {
            width: 130px;
        }
        .submissions-table th:nth-child(4) {
            width: 360px;
        }
        .submissions-date-cell {
            vertical-align: middle;
        }
        .submissions-date-chip {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 16px;
            background: #f8fbff;
            border: 1px solid #e3ebf8;
        }
        .submissions-date-day {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: var(--brand-dark);
            font-size: 1rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        .submissions-date-meta {
            display: grid;
            gap: 2px;
        }
        .submissions-date-meta strong {
            font-size: 0.92rem;
            font-weight: 550;
        }
        .submissions-date-meta small {
            color: var(--muted);
            font-size: 0.8rem;
        }
        .submissions-remarks-cell strong,
        .submissions-remarks-cell span {
            display: block;
        }
        .submissions-remarks-cell strong {
            font-size: 0.9rem;
            font-weight: 550;
            margin-bottom: 4px;
        }
        .submissions-remarks-cell span {
            color: var(--muted);
            line-height: 1.5;
        }
        .submissions-actions-cell {
            vertical-align: middle;
        }
        .submissions-actions-inline {
            display: grid;
            grid-template-columns: repeat(3, minmax(96px, 1fr));
            gap: 10px;
        }
        .submissions-actions-inline .button,
        .submission-actions .button {
            min-width: 0;
            padding: 10px 12px;
            font-size: 0.84rem;
            justify-content: center;
            white-space: nowrap;
        }
        .submissions-actions-inline .button-icon span,
        .submission-actions .button-icon span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .submissions-stack {
            gap: 14px;
        }
        .submissions-card {
            border-radius: 16px;
        }
        .submissions-footer {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .submissions-pagination {
            justify-content: flex-end;
        }
        .worker-submissions-hero {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }
        .worker-submissions-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
        }
        .worker-submissions-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }
        .worker-submissions-filter {
            position: relative;
            min-width: 190px;
        }
        .worker-submissions-filter input {
            padding-left: 38px;
        }
        .worker-submissions-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .worker-submission-stat {
            padding: 18px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: #fff;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.04);
        }
        .worker-submission-stat strong {
            display: block;
            font-size: 1.4rem;
            color: var(--brand-dark);
            font-weight: 650;
            margin-bottom: 4px;
        }
        .worker-submission-stat span {
            color: var(--muted);
            line-height: 1.45;
        }
        .worker-final-remark-card,
        .worker-submissions-table-card {
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .worker-final-remark-head {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 16px;
        }
        .worker-final-remark-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: var(--brand-dark);
            flex-shrink: 0;
        }
        .worker-final-remark-icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .worker-final-remark-head h3 {
            margin: 0 0 4px;
        }
        .worker-final-remark-head p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }
        .submission-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 999px;
            background: #eff6ff;
            color: var(--brand-dark);
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .submission-remark {
            margin: 0 0 14px;
            color: var(--muted);
            line-height: 1.55;
        }
        .submission-actions {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        @media (max-width: 900px) {
            .grid-2, .grid-3, .metric-grid, .form-grid, .dashboard-grid, .form-layout, .mini-stats-inline, .dashboard-hero-grid, .submission-actions, .dashboard-shortcuts, .dashboard-secondary-actions, .dashboard-action-grid, .daily-form-secondary-actions, .daily-form-hero-grid, .submissions-hero-grid, .photo-proof-grid, .worker-dashboard-hero-grid, .worker-month-actions, .worker-stat-grid, .worker-dashboard-layout, .worker-recent-actions, .worker-submissions-hero-grid, .worker-submissions-stats { grid-template-columns: 1fr; }
            .shell { padding: 16px; }
            .hero { padding: 24px; }
            .topbar { align-items: stretch; flex-direction: column; }
            .topbar-left { align-items: stretch; }
            .topbar-right { justify-content: flex-end; }
            .auth-shell { min-height: auto; }
            .auth-card { padding: 24px; }
        }
        @media (max-width: 640px) {
            .shell {
                padding: 12px;
            }
            .topbar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 10px;
                border-radius: 16px;
                margin-bottom: 14px;
            }
            .brand-link {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
            .brand {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .topbar-mobile-bar {
                display: flex;
                width: auto;
                flex-shrink: 0;
            }
            .topbar-left {
                width: auto;
                min-width: 0;
                flex: 1 1 auto;
            }
            .topbar-right {
                display: none;
            }
            .desktop-nav {
                display: none;
            }
            .brand-mark {
                width: 30px;
                height: 30px;
                border-radius: 9px;
                font-size: 0.72rem;
                flex-shrink: 0;
            }
            .brand h1 {
                font-size: 0.88rem;
                line-height: 1.15;
            }
            .brand p {
                display: none;
            }
            .nav-links {
                padding: 4px;
                gap: 4px;
                overflow-x: auto;
                scrollbar-width: none;
            }
            .nav-links::-webkit-scrollbar {
                display: none;
            }
            .nav-link {
                padding: 8px 11px;
                font-size: 0.84rem;
            }
            .user-chip {
                width: auto;
                justify-content: flex-start;
                min-width: 0;
            }
            .profile-dropdown {
                right: auto;
                left: 0;
                width: min(260px, calc(100vw - 32px));
            }
            .mobile-menu {
                left: 10px;
                right: 10px;
                width: auto;
                max-width: none;
            }
            .mobile-menu .nav-links {
                overflow-x: visible;
                overflow-y: visible;
                padding: 0;
            }
            .button, button {
                min-height: 44px;
                width: 100%;
            }
            .action-row {
                display: grid;
                grid-template-columns: 1fr;
            }
            .action-row-inline {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
            .action-row-inline .button,
            .action-row-inline button {
                width: 100%;
                min-width: 0;
                padding-left: 10px;
                padding-right: 10px;
                font-size: 0.9rem;
            }
            .hero {
                padding: 18px;
                margin-bottom: 16px;
            }
            .hero h2 {
                font-size: 1.45rem;
            }
            .dashboard-spotlight {
                padding: 16px;
                border-radius: 18px;
            }
            .dashboard-spotlight strong {
                font-size: 2.4rem;
            }
            .dashboard-primary-actions {
                display: grid;
                grid-template-columns: 1fr;
            }
            .dashboard-action-card {
                padding: 14px 16px;
            }
            .daily-form-top-actions {
                justify-content: stretch;
            }
            .submissions-hero-actions {
                justify-content: stretch;
            }
            .worker-submissions-actions {
                justify-content: stretch;
            }
            .card {
                padding: 14px;
                border-radius: 18px;
            }
            .stat {
                padding: 16px;
            }
            .stat strong {
                font-size: 1.7rem;
            }
            .section-head {
                flex-direction: column;
                align-items: stretch;
            }
            .form-section {
                padding: 12px;
            }
            .metric-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .metric-card label {
                min-height: 0;
            }
            .daily-form-section-head {
                align-items: flex-start;
                flex-direction: column;
            }
            .submissions-footer {
                align-items: stretch;
            }
            .submissions-pagination {
                justify-content: stretch;
            }
            .submissions-actions-inline {
                grid-template-columns: 1fr;
            }
            .sticky-action-bar {
                flex-direction: column;
            }
            .desktop-only {
                display: none;
            }
            .table-stack {
                display: grid;
                gap: 12px;
            }
            .table-stack-card {
                border: 1px solid var(--line);
                border-radius: 14px;
                background: #fff;
                padding: 14px;
            }
            .table-stack-card h4 {
                margin: 0 0 10px;
                font-size: 0.96rem;
            }
            .table-stack-row {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                padding: 8px 0;
                border-top: 1px solid #eef2f7;
                font-size: 0.92rem;
            }
            .table-stack-row:first-of-type {
                border-top: 0;
                padding-top: 0;
            }
            .table-stack-row span:first-child {
                color: var(--muted);
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        @php($isAuthPage = request()->routeIs('login') || request()->routeIs('register'))

        @unless($isAuthPage)
            <div class="nav-shell">
                <div class="topbar">
                    <div class="topbar-left">
                        <a href="{{ route('dashboard') }}" class="brand-link">
                            <span class="brand-mark">SHG</span>
                        </a>
                    </div>
                    <div class="topbar-mobile-bar">
                        <button class="menu-toggle" type="button" data-mobile-menu-toggle aria-label="Open navigation menu" aria-expanded="false">
                            <span class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                    <div class="topbar-right">
                        @auth
                            <div class="desktop-nav">
                                @include('layouts.partials.nav-links')
                                @include('layouts.partials.profile-dropdown', ['showMeta' => true])
                            </div>
                        @else
                            @include('layouts.partials.guest-actions')
                        @endauth
                    </div>
                </div>
                @auth
                    <div class="mobile-menu" data-mobile-menu>
                        <div class="mobile-menu-panel">
                            @php($mobileRoleLabel = auth()->user()->role === 'worker' ? 'Community Mobilizer' : ucfirst(auth()->user()->role))
                            <div class="mobile-menu-profile">
                                <strong>{{ auth()->user()->name }}</strong>
                                <span>{{ $mobileRoleLabel }}</span>
                            </div>
                            @include('layouts.partials.nav-links')
                            <div class="mobile-menu-actions">
                                <a href="{{ route('profile.edit') }}">Edit Profile</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        @endunless

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any() && ! $isAuthPage)
            <div class="alert alert-error">
                <strong>Please fix the highlighted fields.</strong>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        @yield('content')
    </div>
    <script>
        (function () {
            var mobileMenus = Array.from(document.querySelectorAll('[data-mobile-menu]'));
            var mobileToggles = Array.from(document.querySelectorAll('[data-mobile-menu-toggle]'));
            var profileMenus = Array.from(document.querySelectorAll('.profile-menu'));

            function closeMobileMenus() {
                mobileMenus.forEach(function (menu) {
                    menu.classList.remove('open');
                });

                mobileToggles.forEach(function (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                });
            }

            function closeProfileMenus(exceptMenu) {
                profileMenus.forEach(function (menu) {
                    if (menu === exceptMenu) {
                        return;
                    }

                    var dropdown = menu.querySelector('[data-profile-dropdown]');
                    var toggle = menu.querySelector('[data-profile-toggle]');

                    if (dropdown) {
                        dropdown.classList.remove('open');
                    }

                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            mobileToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();

                    var menu = document.querySelector('[data-mobile-menu]');

                    if (!menu) {
                        return;
                    }

                    closeProfileMenus();

                    var isOpen = menu.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });

            mobileMenus.forEach(function (menu) {
                menu.addEventListener('click', function (event) {
                    event.stopPropagation();
                });

                menu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', closeMobileMenus);
                });
            });

            profileMenus.forEach(function (menu) {
                var toggle = menu.querySelector('[data-profile-toggle]');
                var dropdown = menu.querySelector('[data-profile-dropdown]');

                if (!toggle || !dropdown) {
                    return;
                }

                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();
                    closeMobileMenus();

                    var isOpen = !dropdown.classList.contains('open');
                    closeProfileMenus(menu);
                    dropdown.classList.toggle('open', isOpen);
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                dropdown.addEventListener('click', function (event) {
                    event.stopPropagation();
                });
            });

            document.addEventListener('click', function () {
                closeMobileMenus();
                closeProfileMenus();
            });
        })();

        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = document.getElementById(button.getAttribute('data-password-toggle'));

                if (!input) {
                    return;
                }

                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');

                var openIcon = button.querySelector('[data-eye-open]');
                var closedIcon = button.querySelector('[data-eye-closed]');

                if (openIcon && closedIcon) {
                    openIcon.style.display = isPassword ? 'none' : '';
                    closedIcon.style.display = isPassword ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>
