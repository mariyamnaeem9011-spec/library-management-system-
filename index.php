<?php
require_once __DIR__ . '/backend.php';

$appState = fetchDashboardState($conn);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library Management System Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --bg: #0F172A;
      --sidebar: #111827;
      --sidebar-width: 260px;
      --card: rgba(255,255,255,0.08);
      --purple: #8B5CF6;
      --blue: #3B82F6;
      --text: #F8FAFC;
      --muted: #94A3B8;
      --border: rgba(255,255,255,0.1);
      --green: #22C55E;
      --pink: #EC4899;
      --cyan: #06B6D4;
      --amber: #F59E0B;
      --shadow: 0 24px 70px rgba(0,0,0,0.38);
    }

    * { box-sizing: border-box; }

    body {
      min-height: 100vh;
      margin: 0;
      color: var(--text);
      font-family: "Poppins", sans-serif;
      background:
        radial-gradient(circle at 18% 8%, rgba(139,92,246,0.22), transparent 30%),
        radial-gradient(circle at 88% 18%, rgba(59,130,246,0.20), transparent 32%),
        linear-gradient(145deg, #0F172A 0%, #111827 48%, #0B1120 100%);
      overflow-x: hidden;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      pointer-events: none;
      background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
      background-size: 34px 34px;
      mask-image: linear-gradient(to bottom, rgba(0,0,0,0.85), transparent 92%);
      z-index: -1;
    }

    .app-shell { display: flex; min-height: 100vh; overflow-x: hidden; }

    .sidebar {
      position: fixed;
      inset: 0 auto 0 0;
      width: var(--sidebar-width);
      padding: 24px 18px;
      background: rgba(17,24,39,0.86);
      border-right: 1px solid var(--border);
      box-shadow: var(--shadow);
      backdrop-filter: blur(24px);
      z-index: 40;
      transition: transform .35s ease;
      display: flex;
      flex-direction: column;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 10px 22px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 18px;
    }

    .brand-icon {
      width: 48px;
      height: 48px;
      display: grid;
      place-items: center;
      border-radius: 18px;
      background: linear-gradient(135deg, var(--purple), var(--blue));
      box-shadow: 0 0 28px rgba(139,92,246,0.55);
      font-size: 21px;
    }

    .brand h1 { font-size: 18px; line-height: 1.2; margin: 0; font-weight: 800; }
    .brand span { color: var(--muted); font-size: 12px; }

    .menu {
      display: grid;
      gap: 8px;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .menu a {
      display: flex;
      align-items: center;
      gap: 13px;
      min-height: 48px;
      padding: 12px 14px;
      color: #CBD5E1;
      text-decoration: none;
      border: 1px solid transparent;
      border-radius: 16px;
      transition: all .22s ease;
    }

    .menu a i { width: 21px; text-align: center; color: #A78BFA; }
    .menu a:hover, .menu a.active {
      color: #fff;
      background: linear-gradient(135deg, rgba(139,92,246,0.28), rgba(59,130,246,0.18));
      border-color: rgba(255,255,255,0.14);
      box-shadow: 0 0 26px rgba(139,92,246,0.22);
      transform: translateX(4px);
    }

    .book-widget {
      margin-top: auto;
      padding: 18px;
      border-radius: 20px;
      background: linear-gradient(145deg, rgba(255,255,255,0.1), rgba(255,255,255,0.04));
      border: 1px solid var(--border);
      box-shadow: 0 18px 44px rgba(0,0,0,0.25);
      overflow: hidden;
    }

    .book-stack {
      height: 110px;
      position: relative;
      margin-bottom: 10px;
    }

    .book {
      position: absolute;
      left: 28px;
      right: 24px;
      height: 20px;
      border-radius: 6px;
      border: 1px solid rgba(255,255,255,0.18);
      box-shadow: 0 12px 22px rgba(0,0,0,0.28);
    }

    .book:nth-child(1) { bottom: 5px; background: linear-gradient(90deg, var(--purple), #C084FC); transform: rotate(-2deg); }
    .book:nth-child(2) { bottom: 28px; background: linear-gradient(90deg, var(--blue), #67E8F9); transform: rotate(3deg); }
    .book:nth-child(3) { bottom: 51px; background: linear-gradient(90deg, var(--pink), #F9A8D4); transform: rotate(-4deg); }
    .book:nth-child(4) { bottom: 74px; background: linear-gradient(90deg, var(--amber), #FDE68A); transform: rotate(2deg); }
    .book::after {
      content: "";
      position: absolute;
      top: 4px;
      right: 18px;
      width: 42px;
      height: 4px;
      border-radius: 99px;
      background: rgba(255,255,255,0.45);
    }

    .book-widget p { margin: 0; color: #E2E8F0; font-size: 13px; line-height: 1.55; }

    .content {
      width: 100%;
      min-width: 0;
      max-width: calc(100vw - var(--sidebar-width));
      margin-left: var(--sidebar-width);
      padding: 22px 26px 32px;
      transition: margin .35s ease, max-width .35s ease;
    }

    .topbar {
      position: sticky;
      top: 14px;
      z-index: 20;
      display: grid;
      grid-template-columns: auto minmax(0, 1fr) auto auto auto;
      gap: 12px;
      align-items: center;
      padding: 12px;
      margin-bottom: 24px;
      border: 1px solid var(--border);
      border-radius: 20px;
      background: rgba(15,23,42,0.70);
      backdrop-filter: blur(22px);
      box-shadow: 0 20px 60px rgba(0,0,0,0.30);
    }

    .icon-btn, .arrow-btn {
      border: 1px solid var(--border);
      color: #fff;
      background: rgba(255,255,255,0.08);
      width: 46px;
      height: 46px;
      border-radius: 16px;
      display: grid;
      place-items: center;
      transition: all .22s ease;
    }

    .icon-btn:hover, .arrow-btn:hover {
      transform: translateY(-2px);
      border-color: rgba(139,92,246,0.55);
      box-shadow: 0 0 24px rgba(139,92,246,0.35);
      background: rgba(139,92,246,0.2);
    }

    .search-wrap {
      position: relative;
      min-width: 0;
    }

    .search-wrap i {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #94A3B8;
    }

    .search-input {
      width: 100%;
      height: 48px;
      padding: 0 18px 0 48px;
      border-radius: 16px;
      border: 1px solid var(--border);
      outline: none;
      color: #fff;
      background: rgba(255,255,255,0.07);
      transition: all .22s ease;
    }

    .search-input:focus {
      border-color: rgba(59,130,246,0.62);
      box-shadow: 0 0 0 4px rgba(59,130,246,0.11), 0 0 24px rgba(59,130,246,0.22);
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
    }

    .avatar {
      width: 46px;
      height: 46px;
      display: grid;
      place-items: center;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--blue), var(--purple));
      box-shadow: 0 0 26px rgba(59,130,246,0.38);
      font-weight: 800;
    }

    .profile strong { display: block; font-size: 14px; }
    .profile span { display: block; font-size: 12px; color: var(--muted); }

    .date-pill {
      min-width: 0;
      padding: 12px 14px;
      white-space: nowrap;
      border-radius: 16px;
      color: #E0F2FE;
      border: 1px solid rgba(59,130,246,0.24);
      background: rgba(59,130,246,0.12);
      font-size: 13px;
      font-weight: 600;
    }

    .hero {
      position: relative;
      padding: 34px;
      border-radius: 20px;
      border: 1px solid var(--border);
      background:
        linear-gradient(135deg, rgba(139,92,246,0.23), rgba(59,130,246,0.13)),
        rgba(255,255,255,0.06);
      backdrop-filter: blur(20px);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .hero::after {
      content: "";
      position: absolute;
      width: 280px;
      height: 280px;
      right: -90px;
      top: -110px;
      background: radial-gradient(circle, rgba(59,130,246,0.44), transparent 66%);
      filter: blur(4px);
    }

    .hero h2 { font-size: clamp(28px, 4vw, 48px); line-height: 1.05; font-weight: 800; margin: 0 0 12px; }
    .hero p { color: #D8E4F8; margin: 0; max-width: 660px; font-size: 16px; }

    .section-space { margin-top: 22px; }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(min(100%, 190px), 1fr));
      gap: 16px;
    }

    .glass-card, .panel {
      border: 1px solid var(--border);
      background: var(--card);
      backdrop-filter: blur(22px);
      box-shadow: 0 18px 50px rgba(0,0,0,0.28);
      border-radius: 20px;
    }

    .stat-card {
      padding: 18px;
      transition: all .25s ease;
      overflow: hidden;
      position: relative;
    }

    .stat-card::before {
      content: "";
      position: absolute;
      inset: 0;
      opacity: 0;
      background: linear-gradient(135deg, rgba(139,92,246,0.22), rgba(59,130,246,0.10));
      transition: opacity .25s ease;
    }

    .stat-card:hover, .action-card:hover {
      transform: translateY(-8px);
      border-color: rgba(139,92,246,0.38);
      box-shadow: 0 24px 72px rgba(0,0,0,0.36), 0 0 32px rgba(139,92,246,0.18);
    }

    .stat-card:hover::before { opacity: 1; }
    .stat-card > * { position: relative; z-index: 1; }

    .stat-head {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      align-items: start;
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      display: grid;
      place-items: center;
      border-radius: 16px;
      background: rgba(139,92,246,0.18);
      color: #DDD6FE;
      font-size: 21px;
      box-shadow: inset 0 0 18px rgba(139,92,246,0.24);
    }

    .growth {
      color: #86EFAC;
      background: rgba(34,197,94,0.12);
      border: 1px solid rgba(34,197,94,0.20);
      border-radius: 99px;
      padding: 5px 9px;
      font-size: 12px;
      font-weight: 700;
    }

    .stat-card h3 { margin: 16px 0 3px; font-size: 30px; font-weight: 800; }
    .stat-card p { margin: 0 0 12px; color: var(--muted); font-size: 13px; }
    .mini-chart { width: 100%; height: 54px; }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(min(100%, 340px), 1fr));
      gap: 18px;
    }

    .panel { padding: 20px; min-width: 0; }

    .panel-title {
      display: flex;
      justify-content: space-between;
      gap: 14px;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }

    .panel-title h3 { margin: 0; font-size: 18px; font-weight: 800; }
    .panel-title span { color: var(--muted); font-size: 12px; }

    .tab-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      max-width: 100%;
      padding: 5px;
      border: 1px solid var(--border);
      border-radius: 15px;
      background: rgba(255,255,255,0.05);
    }

    .tab-pills button {
      border: 0;
      color: #CBD5E1;
      background: transparent;
      border-radius: 12px;
      padding: 9px 12px;
      font-size: 13px;
      font-weight: 700;
      transition: all .22s ease;
    }

    .tab-pills button.active {
      color: #fff;
      background: linear-gradient(135deg, var(--purple), var(--blue));
      box-shadow: 0 0 22px rgba(139,92,246,0.34);
    }

    .activity-list { display: grid; gap: 12px; }

    .activity-row {
      display: grid;
      grid-template-columns: 52px 1fr auto;
      align-items: center;
      gap: 13px;
      padding: 12px;
      border-radius: 18px;
      background: rgba(255,255,255,0.055);
      border: 1px solid rgba(255,255,255,0.08);
      transition: all .2s ease;
    }

    .activity-row:hover {
      background: rgba(255,255,255,0.09);
      transform: translateX(4px);
    }

    .cover {
      width: 52px;
      height: 68px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      color: #fff;
      box-shadow: 0 12px 24px rgba(0,0,0,0.25);
      background: linear-gradient(145deg, var(--purple), var(--blue));
      position: relative;
      overflow: hidden;
    }

    .cover::before {
      content: "";
      position: absolute;
      left: 8px;
      top: 0;
      bottom: 0;
      width: 4px;
      background: rgba(255,255,255,0.22);
    }

    .activity-row h4 { margin: 0 0 5px; font-size: 14px; font-weight: 800; }
    .activity-row p { margin: 0; color: var(--muted); font-size: 12px; }
    .date-chip { color: #BFDBFE; font-size: 12px; white-space: nowrap; }

    .chart-toolbar {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .filter-select {
      color: #E2E8F0;
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 9px 34px 9px 12px;
      background-color: rgba(255,255,255,0.07);
      font-size: 13px;
      outline: none;
    }

    .legend {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      color: var(--muted);
      font-size: 12px;
      margin-bottom: 12px;
    }

    .legend span::before {
      content: "";
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 7px;
      background: var(--blue);
      box-shadow: 0 0 12px var(--blue);
    }

    .legend span:last-child::before { background: var(--purple); box-shadow: 0 0 12px var(--purple); }

    .main-chart { width: 100%; height: 315px; cursor: crosshair; }

    .quick-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
      gap: 16px;
    }

    .action-card {
      min-height: 184px;
      padding: 22px;
      border-radius: 20px;
      border: 1px solid rgba(255,255,255,0.16);
      overflow: hidden;
      position: relative;
      transition: all .25s ease;
      background: linear-gradient(135deg, rgba(139,92,246,0.33), rgba(59,130,246,0.16));
      box-shadow: 0 18px 50px rgba(0,0,0,0.28);
    }

    .action-card:nth-child(2) { background: linear-gradient(135deg, rgba(59,130,246,0.34), rgba(6,182,212,0.15)); }
    .action-card:nth-child(3) { background: linear-gradient(135deg, rgba(236,72,153,0.27), rgba(139,92,246,0.20)); }
    .action-card:nth-child(4) { background: linear-gradient(135deg, rgba(34,197,94,0.22), rgba(59,130,246,0.16)); }

    .action-card::after {
      content: "";
      position: absolute;
      right: -44px;
      bottom: -54px;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: rgba(255,255,255,0.13);
    }

    .action-card i.big {
      width: 58px;
      height: 58px;
      display: grid;
      place-items: center;
      border-radius: 18px;
      background: rgba(255,255,255,0.13);
      font-size: 24px;
      margin-bottom: 22px;
    }

    .action-card h3 { font-size: 18px; font-weight: 800; margin: 0 0 8px; }
    .action-card p { color: #DCE8FF; font-size: 13px; margin: 0; max-width: 220px; }
    .action-card .arrow-btn { position: absolute; right: 18px; bottom: 18px; z-index: 1; }

    .toast-box {
      position: fixed;
      right: 22px;
      bottom: 22px;
      z-index: 80;
      display: grid;
      gap: 10px;
    }

    .app-toast {
      padding: 13px 16px;
      border: 1px solid rgba(139,92,246,0.34);
      border-radius: 16px;
      color: #fff;
      background: rgba(15,23,42,0.88);
      backdrop-filter: blur(18px);
      box-shadow: 0 18px 50px rgba(0,0,0,0.36);
      animation: slideIn .25s ease both;
    }

    .modal-content {
      color: var(--text);
      border: 1px solid rgba(255,255,255,0.16);
      border-radius: 20px;
      background: rgba(15,23,42,0.94);
      backdrop-filter: blur(22px);
      box-shadow: var(--shadow);
    }

    .modal-header, .modal-footer { border-color: var(--border); }
    .modal-title { font-weight: 800; }

    .form-label {
      color: #CBD5E1;
      font-size: 13px;
      font-weight: 700;
    }

    .form-control, .form-select {
      color: #fff;
      border: 1px solid var(--border);
      border-radius: 14px;
      background-color: rgba(255,255,255,0.07);
    }

    .form-control:focus, .form-select:focus {
      color: #fff;
      border-color: rgba(139,92,246,0.62);
      background-color: rgba(255,255,255,0.09);
      box-shadow: 0 0 0 4px rgba(139,92,246,0.13);
    }

    .form-select option { color: #0F172A; }

    .btn-neon {
      color: #fff;
      border: 0;
      border-radius: 14px;
      padding: 11px 18px;
      font-weight: 800;
      background: linear-gradient(135deg, var(--purple), var(--blue));
      box-shadow: 0 0 24px rgba(139,92,246,0.34);
    }

    .btn-neon:hover {
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 0 34px rgba(59,130,246,0.42);
    }

    .empty-state {
      padding: 18px;
      border: 1px dashed rgba(255,255,255,0.18);
      border-radius: 18px;
      color: var(--muted);
      text-align: center;
      background: rgba(255,255,255,0.04);
    }

    .records-panel {
      margin-top: 22px;
      padding: 20px;
      border: 1px solid var(--border);
      border-radius: 20px;
      background: var(--card);
      backdrop-filter: blur(22px);
      box-shadow: 0 18px 50px rgba(0,0,0,0.28);
      scroll-margin-top: 90px;
    }

    .records-toolbar {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
      margin-bottom: 16px;
    }

    .records-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .records-tabs button {
      border: 1px solid var(--border);
      color: #CBD5E1;
      background: rgba(255,255,255,0.06);
      border-radius: 14px;
      padding: 9px 13px;
      font-size: 13px;
      font-weight: 800;
      transition: all .22s ease;
    }

    .records-tabs button.active, .records-tabs button:hover {
      color: #fff;
      border-color: rgba(139,92,246,0.48);
      background: linear-gradient(135deg, rgba(139,92,246,0.55), rgba(59,130,246,0.34));
      box-shadow: 0 0 22px rgba(139,92,246,0.24);
    }

    .table-glass {
      overflow-x: auto;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 18px;
    }

    .table-glass table {
      width: 100%;
      min-width: 760px;
      margin: 0;
      color: #E2E8F0;
      border-collapse: collapse;
    }

    .table-glass th {
      color: #F8FAFC;
      padding: 14px 16px;
      font-size: 12px;
      text-transform: uppercase;
      background: rgba(255,255,255,0.08);
      border-bottom: 1px solid var(--border);
    }

    .table-glass td {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.07);
      font-size: 13px;
      vertical-align: middle;
    }

    .table-glass tr:last-child td { border-bottom: 0; }
    .table-glass tr:hover td { background: rgba(255,255,255,0.045); }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 800;
      white-space: nowrap;
    }

    .status-issued {
      color: #BFDBFE;
      background: rgba(59,130,246,0.14);
      border: 1px solid rgba(59,130,246,0.28);
    }

    .status-returned {
      color: #BBF7D0;
      background: rgba(34,197,94,0.13);
      border: 1px solid rgba(34,197,94,0.24);
    }

    .status-low {
      color: #FDE68A;
      background: rgba(245,158,11,0.13);
      border: 1px solid rgba(245,158,11,0.24);
    }

    @keyframes slideIn {
      from { opacity: 0; transform: translateY(14px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .sidebar-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.48);
      z-index: 35;
      display: none;
    }

    .sidebar-backdrop.show { display: block; }

    @media (min-width: 1081px) {
      #menuToggle { display: none; }
    }

    @media (max-width: 1500px) {
      .hero { padding: 28px; }
      .stat-card h3 { font-size: 26px; }
      .action-card { min-height: 168px; padding: 18px; }
    }

    @media (max-width: 1280px) {
      :root { --sidebar-width: 240px; }
      .brand h1 { font-size: 16px; }
      .menu a { padding: 10px 12px; font-size: 14px; }
      .content { padding: 18px 20px 28px; }
      .date-pill { font-size: 12px; padding: 10px 12px; }
    }

    @media (max-width: 1080px) {
      .sidebar { transform: translateX(-105%); }
      .sidebar.show { transform: translateX(0); }
      .content {
        margin-left: 0;
        max-width: 100vw;
        padding: 18px;
      }
    }

    @media (max-width: 900px) {
      .topbar {
        grid-template-columns: auto minmax(0, 1fr) auto;
        grid-template-areas:
          "menu search notify"
          "profile profile date";
      }
      .topbar > .icon-btn:first-child { grid-area: menu; }
      .topbar > .search-wrap { grid-area: search; }
      .topbar > #notifyBtn { grid-area: notify; }
      .topbar > .profile { grid-area: profile; justify-self: start; }
      .topbar > .date-pill { grid-area: date; justify-self: end; }
    }

    @media (max-width: 760px) {
      .date-pill, .profile span { display: none; }
      .topbar {
        grid-template-columns: auto minmax(0, 1fr) auto;
        grid-template-areas: "menu search notify";
      }
      .topbar > .profile,
      .topbar > .date-pill { display: none; }
      .hero { padding: 24px; }
      .stats-grid, .quick-grid { grid-template-columns: 1fr; }
      .panel-title { align-items: flex-start; flex-direction: column; }
      .activity-row { grid-template-columns: 48px 1fr; }
      .date-chip { grid-column: 2; }
      .main-chart { height: 255px; }
    }

    @media (max-width: 460px) {
      .content { padding: 12px; }
      .topbar { top: 8px; gap: 8px; padding: 9px; }
      .profile strong { display: none; }
      .icon-btn { width: 42px; height: 42px; border-radius: 14px; }
      .search-input { height: 42px; border-radius: 14px; }
      .hero h2 { font-size: 27px; }
      .tab-pills { width: 100%; }
      .tab-pills button { flex: 1; padding-inline: 8px; }
    }
  </style>
</head>
<body>
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-icon"><i class="fa-solid fa-book-open-reader"></i></div>
      <div>
        <h1>Library Management System</h1>
        <span>University SaaS Dashboard</span>
      </div>
    </div>

    <ul class="menu">
      <li><a class="active" href="#dashboard"><i class="fa-solid fa-chart-pie"></i>Dashboard</a></li>
      <li><a href="#members"><i class="fa-solid fa-users"></i>Members</a></li>
      <li><a href="#books"><i class="fa-solid fa-book"></i>Books</a></li>
      <li><a href="#librarians"><i class="fa-solid fa-user-tie"></i>Librarians</a></li>
      <li><a href="#issue"><i class="fa-solid fa-arrow-up-right-from-square"></i>Issue Book</a></li>
      <li><a href="#return"><i class="fa-solid fa-rotate-left"></i>Return Book</a></li>
      <li><a href="#reports"><i class="fa-solid fa-file-lines"></i>Reports</a></li>
      <li><a href="#settings"><i class="fa-solid fa-gear"></i>Settings</a></li>
    </ul>

    <div class="book-widget">
      <div class="book-stack" aria-hidden="true">
        <div class="book"></div>
        <div class="book"></div>
        <div class="book"></div>
        <div class="book"></div>
      </div>
      <p>"A room without books is like a body without a soul."</p>
    </div>
  </aside>

  <main class="content" id="mainContent">
    <nav class="topbar">
      <button class="icon-btn" id="menuToggle" aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input class="search-input" id="globalSearch" type="search" placeholder="Search books, members, transactions...">
      </div>
      <button class="icon-btn" id="notifyBtn" aria-label="Notifications"><i class="fa-regular fa-bell"></i></button>
      <div class="profile">
        <div class="avatar">A</div>
        <div>
          <strong>Admin User</strong>
          <span>Head Librarian</span>
        </div>
      </div>
      <div class="date-pill" id="dateWidget">Today</div>
    </nav>

    <section class="hero">
      <h2>Welcome to Library Management System</h2>
      <p>Manage books, members and library transactions efficiently.</p>
    </section>

    <section class="stats-grid section-space" id="statsGrid"></section>

    <section class="dashboard-grid section-space">
      <div class="panel">
        <div class="panel-title">
          <div>
            <h3>Recent Activity</h3>
            <span>Live circulation movements</span>
          </div>
          <div class="tab-pills" role="tablist">
            <button class="active" data-tab="issued" type="button">Recently Issued</button>
            <button data-tab="returned" type="button">Recently Returned</button>
          </div>
        </div>
        <div class="activity-list" id="activityList"></div>
      </div>

      <div class="panel">
        <div class="panel-title">
          <div>
            <h3>Library Statistics</h3>
            <span>Books issued vs returned</span>
          </div>
          <div class="chart-toolbar">
            <select class="filter-select" id="monthFilter" aria-label="Monthly filter">
              <option value="6">Last 6 Months</option>
              <option value="9">Last 9 Months</option>
              <option value="12" selected>Last 12 Months</option>
            </select>
          </div>
        </div>
        <div class="legend"><span>Books Issued</span><span>Books Returned</span></div>
        <canvas class="main-chart" id="mainChart"></canvas>
      </div>
    </section>

    <section class="section-space">
      <div class="panel-title">
        <div>
          <h3>Quick Actions</h3>
          <span>Common library workflows</span>
        </div>
      </div>
      <div class="quick-grid">
        <article class="action-card" data-action="Add Member">
          <i class="fa-solid fa-user-plus big"></i>
          <h3>Add Member</h3>
          <p>Create student, faculty or guest member records.</p>
          <button class="arrow-btn" aria-label="Add Member"><i class="fa-solid fa-arrow-right"></i></button>
        </article>
        <article class="action-card" data-action="Add Book">
          <i class="fa-solid fa-book-medical big"></i>
          <h3>Add Book</h3>
          <p>Register new books with category and shelf details.</p>
          <button class="arrow-btn" aria-label="Add Book"><i class="fa-solid fa-arrow-right"></i></button>
        </article>
        <article class="action-card" data-action="Issue Book">
          <i class="fa-solid fa-share-from-square big"></i>
          <h3>Issue Book</h3>
          <p>Assign books to members with instant tracking.</p>
          <button class="arrow-btn" aria-label="Issue Book"><i class="fa-solid fa-arrow-right"></i></button>
        </article>
        <article class="action-card" data-action="Return Book">
          <i class="fa-solid fa-right-left big"></i>
          <h3>Return Book</h3>
          <p>Process returns, fines and availability updates.</p>
          <button class="arrow-btn" aria-label="Return Book"><i class="fa-solid fa-arrow-right"></i></button>
        </article>
      </div>
    </section>

    <section class="records-panel" id="recordsPanel">
      <div class="records-toolbar">
        <div>
          <div class="panel-title mb-0">
            <div>
              <h3 id="recordsTitle">Members Directory</h3>
              <span id="recordsSubtitle">All registered library members</span>
            </div>
          </div>
        </div>
        <div class="records-tabs" id="recordsTabs">
          <button class="active" data-view="members" type="button">Members</button>
          <button data-view="books" type="button">Books</button>
          <button data-view="librarians" type="button">Librarians</button>
          <button data-view="issued" type="button">Issued Books</button>
          <button data-view="returned" type="button">Returned Books</button>
          <button data-view="reports" type="button">Reports</button>
        </div>
      </div>
      <div class="table-glass" id="recordsTable"></div>
    </section>
  </main>

  <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" id="memberForm">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-user-plus me-2 text-info"></i>Add Member</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="Name">Member Name</label>
              <input class="form-control" id="Name" required placeholder="Enter full name">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="Address">Address</label>
              <input class="form-control" id="Address" required placeholder="Enter address">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="phone_No.">Phone No.</label>
              <input class="form-control" id="phone_No." required placeholder="Enter phone number">
            </div>
            <div class="col-12">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" id="email" type="email" required placeholder="name@example.com">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light rounded-4" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-neon" type="submit">Save Member</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" id="bookForm">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-book-medical me-2 text-info"></i>Add Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="title">Book Title</label>
              <input class="form-control" id="title" required placeholder="Enter book title">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="author">Author</label>
              <input class="form-control" id="author" required placeholder="Author name">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="publisher">Publisher</label>
              <input class="form-control" id="publisher" required placeholder="Publisher name">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="year">Year</label>
              <input class="form-control" id="year" type="number" required placeholder="2024">
            </div>
            <div class="col-md-6">
              <label class="form-label" for="price">Price</label>
              <input class="form-control" id="price" type="number" required placeholder="1000">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light rounded-4" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-neon" type="submit">Save Book</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="issueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" id="issueForm">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-share-from-square me-2 text-info"></i>Issue Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="Book_ID">Available Book</label>
              <select class="form-select" id="Book_ID" required></select>
            </div>
            <div class="col-12">
              <label class="form-label" for="Member_ID">Member</label>
              <select class="form-select" id="Member_ID" required></select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="Issue_Date">Issue Date</label>
              <input class="form-control" id="Issue_Date" type="date" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="Return_Date">Return Date (Due)</label>
              <input class="form-control" id="Return_Date" type="date" required>
            </div>
            <div class="col-12">
              <label class="form-label" for="Librarian_ID">Librarian</label>
              <select class="form-select" id="Librarian_ID" required></select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light rounded-4" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-neon" type="submit">Issue Book</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" id="returnForm">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-right-left me-2 text-info"></i>Return Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" for="Issue_ID">Issued Transaction</label>
              <select class="form-select" id="Issue_ID" required></select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="Return_Date_Actual">Return Date</label>
              <input class="form-control" id="Return_Date_Actual" type="date" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="Fine_Amount">Fine Amount</label>
              <input class="form-control" id="Fine_Amount" type="number" min="0" value="0">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light rounded-4" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-neon" type="submit">Return Book</button>
        </div>
      </form>
    </div>
  </div>

  <div class="toast-box" id="toastBox"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const todayISO = new Date().toISOString().slice(0, 10);
    let appState = <?= json_encode($appState, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    async function postAction(action, payload) {
      const body = new FormData();
      body.append("action", action);
      Object.entries(payload).forEach(([key, value]) => body.append(key, value));

      const response = await fetch("backend.php", {
        method: "POST",
        body
      });

      const result = await response.json();
      if (!response.ok || !result.success) {
        throw new Error(result.message || "Request failed");
      }

      appState = result.data;
      return result;
    }

    const recordsTable = document.getElementById("recordsTable");
    const recordsTitle = document.getElementById("recordsTitle");
    const recordsSubtitle = document.getElementById("recordsSubtitle");
    let activeRecordsView = "members";

    const modals = {
      member: new bootstrap.Modal(document.getElementById("memberModal")),
      book: new bootstrap.Modal(document.getElementById("bookModal")),
      issue: new bootstrap.Modal(document.getElementById("issueModal")),
      return: new bootstrap.Modal(document.getElementById("returnModal"))
    };

    function findBook(id) { return appState.books.find(b => b.book_id == id); }
    function findMember(id) { return appState.members.find(m => m.Member_id == id); }
    function findLibrarian(id) { return appState.librarians.find(l => l.Librarian_ID == id); }

    function formatDate(dateStr) {
      if (!dateStr) return "-";
      const d = new Date(dateStr);
      return d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
    }

    function refreshDropdowns() {
      const issueBook = document.getElementById("Book_ID");
      const issueMember = document.getElementById("Member_ID");
      const issueLibrarian = document.getElementById("Librarian_ID");
      const returnTransaction = document.getElementById("Issue_ID");

      const availableBooks = appState.books; // Simplified for this logic
      const issuedTransactions = appState.transactions.filter(item => item.status === "Issued");

      issueBook.innerHTML = availableBooks.length
        ? availableBooks.map(book => `<option value="${book.book_id}">${book.title}</option>`).join("")
        : `<option value="">No books found</option>`;

      issueMember.innerHTML = appState.members.length
        ? appState.members.map(member => `<option value="${member.Member_id}">${member.Name}</option>`).join("")
        : `<option value="">No members found</option>`;

      issueLibrarian.innerHTML = appState.librarians.length
        ? appState.librarians.map(lib => `<option value="${lib.Librarian_ID}">${lib.Name}</option>`).join("")
        : `<option value="">No librarians found</option>`;

      returnTransaction.innerHTML = issuedTransactions.length
        ? issuedTransactions.map(item => {
          const book = findBook(item.Book_ID) || { title: "Unknown Book" };
          const member = findMember(item.Member_ID) || { Name: "Unknown Member" };
          return `<option value="${item.Issue_ID}">${book.title} issued to ${member.Name}</option>`;
        }).join("")
        : `<option value="">No issued books to return</option>`;
    }

    function renderRecords(view = activeRecordsView) {
      activeRecordsView = view;
      document.querySelectorAll("#recordsTabs button").forEach(button => {
        button.classList.toggle("active", button.dataset.view === view);
      });

      const titles = {
        members: ["Members Directory", "All registered library members"],
        books: ["Books Catalog", "All books, copies and availability"],
        librarians: ["Librarians", "Library staff overview"],
        issued: ["Issued Books", "Books currently issued to members"],
        returned: ["Returned Books", "Completed return transactions"],
        reports: ["Reports", "Live operational summary"]
      };
      recordsTitle.textContent = titles[view][0];
      recordsSubtitle.textContent = titles[view][1];

      if (view === "members") renderMembersTable();
      else if (view === "books") renderBooksTable();
      else if (view === "librarians") renderLibrariansTable();
      else if (view === "issued") renderTransactionsTable("Issued");
      else if (view === "returned") renderTransactionsTable("Returned");
      else renderReportsTable();
    }

    function renderMembersTable() {
      if (!appState.members.length) return recordsTable.innerHTML = "<div class='empty-state'>No members</div>";
      recordsTable.innerHTML = `
        <table>
          <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th></tr></thead>
          <tbody>
            ${appState.members.map(member => `
              <tr>
                <td>${member.Member_id}</td>
                <td><strong>${member.Name}</strong></td>
                <td>${member["phone No."]}</td>
                <td>${member.email}</td>
                <td>${member.Address}</td>
              </tr>`).join("")}
          </tbody>
        </table>`;
    }

    function renderBooksTable() {
      if (!appState.books.length) return recordsTable.innerHTML = "<div class='empty-state'>No books</div>";
      recordsTable.innerHTML = `
        <table>
          <thead><tr><th>ID</th><th>Title</th><th>Author</th><th>Publisher</th><th>Year</th><th>Price</th></tr></thead>
          <tbody>
            ${appState.books.map(book => `
              <tr>
                <td>${book.book_id}</td>
                <td><strong>${book.title}</strong></td>
                <td>${book.author}</td>
                <td>${book.publisher}</td>
                <td>${book.year}</td>
                <td>${book.price}</td>
              </tr>`).join("")}
          </tbody>
        </table>`;
    }

    function renderLibrariansTable() {
      if (!appState.librarians.length) return recordsTable.innerHTML = "<div class='empty-state'>No librarians</div>";
      recordsTable.innerHTML = `
        <table>
          <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Salary</th></tr></thead>
          <tbody>
            ${appState.librarians.map(lib => `
              <tr>
                <td>${lib.Librarian_ID}</td>
                <td><strong>${lib.Name}</strong></td>
                <td>${lib.Phone_No}</td>
                <td>${lib.Email}</td>
                <td>${lib.Salary}</td>
              </tr>`).join("")}
          </tbody>
        </table>`;
    }

    function renderTransactionsTable(status) {
      const rows = appState.transactions.filter(item => item.status === status);
      if (!rows.length) return recordsTable.innerHTML = "<div class='empty-state'>No records</div>";
      recordsTable.innerHTML = `
        <table>
          <thead><tr><th>ID</th><th>Book</th><th>Member</th><th>Issue Date</th><th>Due Date</th><th>${status === "Returned" ? "Return Date" : "Status"}</th><th>Fine</th></tr></thead>
          <tbody>
            ${rows.map(item => {
              const book = findBook(item.Book_ID) || { title: "Unknown" };
              const member = findMember(item.Member_ID) || { Name: "Unknown" };
              return `<tr>
                <td>${item.Issue_ID}</td>
                <td>${book.title}</td>
                <td>${member.Name}</td>
                <td>${formatDate(item.Issue_Date)}</td>
                <td>${formatDate(item.Return_Date)}</td>
                <td>${status === "Returned" ? formatDate(item.Return_Date_Actual) : "Issued"}</td>
                <td>${item.Fine_Amount || 0}</td>
              </tr>`;
            }).join("")}
          </tbody>
        </table>`;
    }

    function renderReportsTable() {
      recordsTable.innerHTML = "<div class='empty-state'>Reports view simplified for attribute matching.</div>";
    }

    function refreshDashboard() {
      refreshDropdowns();
      renderRecords();
    }

    document.getElementById("memberForm").addEventListener("submit", async event => {
      event.preventDefault();

      try {
        await postAction("add_member", {
          Name: document.getElementById("Name").value.trim(),
          Address: document.getElementById("Address").value.trim(),
          phone: document.getElementById("phone_No.").value.trim(),
          email: document.getElementById("email").value.trim()
        });
        event.currentTarget.reset();
        modals.member.hide();
        refreshDashboard();
        alert("Add successfully");
      } catch (error) {
        alert("Error");
      }
    });

    document.getElementById("bookForm").addEventListener("submit", async event => {
      event.preventDefault();

      try {
        await postAction("add_book", {
          title: document.getElementById("title").value.trim(),
          author: document.getElementById("author").value.trim(),
          publisher: document.getElementById("publisher").value.trim(),
          year: document.getElementById("year").value,
          price: document.getElementById("price").value
        });
        event.currentTarget.reset();
        modals.book.hide();
        refreshDashboard();
        alert("Add successfully");
      } catch (error) {
        alert("Error");
      }
    });

    document.getElementById("issueForm").addEventListener("submit", async event => {
      event.preventDefault();

      try {
        await postAction("issue_book", {
          Book_ID: document.getElementById("Book_ID").value,
          Member_ID: document.getElementById("Member_ID").value,
          Issue_Date: document.getElementById("Issue_Date").value,
          Return_Date: document.getElementById("Return_Date").value,
          Librarian_ID: document.getElementById("Librarian_ID").value
        });
        modals.issue.hide();
        refreshDashboard();
        alert("Add successfully");
      } catch (error) {
        alert("Error");
      }
    });

    document.getElementById("returnForm").addEventListener("submit", async event => {
      event.preventDefault();

      try {
        await postAction("return_book", {
          Issue_ID: document.getElementById("Issue_ID").value,
          Return_Date_Actual: document.getElementById("Return_Date_Actual").value,
          Fine_Amount: document.getElementById("Fine_Amount").value
        });
        modals.return.hide();
        refreshDashboard();
        alert("Add successfully");
      } catch (error) {
        alert("Error");
      }
    });

    function openWorkflow(action) {
      if (action === "Add Member") modals.member.show();
      else if (action === "Add Book") modals.book.show();
      else if (action === "Issue Book") modals.issue.show();
      else if (action === "Return Book") modals.return.show();
    }

    const sidebarRoutes = {
      dashboard: { scroll: "top" },
      members: { view: "members", scroll: "recordsPanel" },
      books: { view: "books", scroll: "recordsPanel" },
      librarians: { view: "librarians", scroll: "recordsPanel" },
      issue: { view: "issued", scroll: "recordsPanel" },
      return: { view: "returned", scroll: "recordsPanel" },
      reports: { view: "reports", scroll: "recordsPanel" },
      settings: { scroll: "top" }
    };

    const viewToHash = {
      members: "members",
      books: "books",
      librarians: "librarians",
      issued: "issue",
      returned: "return",
      reports: "reports"
    };

    function setActiveMenuItem(route) {
      document.querySelectorAll(".menu a").forEach(link => {
        link.classList.toggle("active", link.getAttribute("href") === `#${route}`);
      });
    }

    function scrollToSection(target) {
      if (target === "top") {
        window.scrollTo({ top: 0, behavior: "smooth" });
        return;
      }

      const element = document.getElementById(target);
      if (element) {
        element.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    }

    function closeMobileSidebar() {
      document.getElementById("sidebar").classList.remove("show");
      document.getElementById("sidebarBackdrop").classList.remove("show");
    }

    function navigateTo(route) {
      const config = sidebarRoutes[route];
      if (!config) return;

      setActiveMenuItem(route);

      if (config.view) {
        renderRecords(config.view);
      }

      scrollToSection(config.scroll);
      closeMobileSidebar();
    }

    function handleHashNavigation() {
      const route = (location.hash || "#dashboard").replace("#", "");
      navigateTo(route in sidebarRoutes ? route : "dashboard");
    }

    document.querySelectorAll(".action-card").forEach(card => {
      card.addEventListener("click", () => openWorkflow(card.dataset.action));
    });

    document.getElementById("menuToggle").addEventListener("click", () => {
      document.getElementById("sidebar").classList.toggle("show");
      document.getElementById("sidebarBackdrop").classList.toggle("show");
    });

    document.getElementById("sidebarBackdrop").addEventListener("click", closeMobileSidebar);

    function resetSidebarOnResize() {
      if (window.innerWidth > 1080) {
        closeMobileSidebar();
      }
    }

    window.addEventListener("resize", resetSidebarOnResize);

    document.querySelectorAll(".menu a").forEach(link => {
      link.addEventListener("click", event => {
        event.preventDefault();
        const route = link.getAttribute("href").replace("#", "");
        history.pushState(null, "", `#${route}`);
        navigateTo(route);
      });
    });

    window.addEventListener("hashchange", handleHashNavigation);

    document.querySelectorAll("#recordsTabs button").forEach(button => {
      button.addEventListener("click", () => {
        const view = button.dataset.view;
        renderRecords(view);
        const route = viewToHash[view] || "dashboard";
        history.replaceState(null, "", `#${route}`);
        setActiveMenuItem(route);
      });
    });

    document.getElementById("dateWidget").textContent = new Date().toLocaleDateString("en-US", {
      weekday: "short",
      month: "short",
      day: "numeric",
      year: "numeric"
    });

    refreshDashboard();
    handleHashNavigation();
  </script>
</body>
</html>
