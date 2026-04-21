@extends('mahasiswa.layouts.app')

@section('title', 'Virtual Lab Koding')

@push('css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            corePlugins: { preflight: false }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/hint/show-hint.min.css">
    <style>
        :root {
            --vl-bg: #f0f2f7;
            --vl-panel: #ffffff;
            --vl-border: #e2e8f0;
            --vl-accent: #4f46e5;
            --vl-accent-light: #eef2ff;
            --vl-green: #059669;
            --vl-terminal: #0d1117;
        }

        .vl-root { font-family: 'Inter', sans-serif; background: var(--vl-bg); min-height: 100vh; }

        /* ─── PAGE HEADER ─── */
        .vl-topbar {
            background: #fff;
            border-bottom: 1px solid var(--vl-border);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .vl-topbar-left { display: flex; align-items: center; gap: 12px; }
        .vl-back-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1.5px solid var(--vl-border);
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            transition: all .2s;
            text-decoration: none;
        }
        .vl-back-btn:hover { border-color: var(--vl-accent); color: var(--vl-accent); background: var(--vl-accent-light); }
        .vl-task-title { font-size: 16px; font-weight: 700; color: #1e293b; line-height: 1.2; }
        .vl-task-sub   { font-size: 12px; color: #94a3b8; margin-top: 2px; }

        .badge-difficulty {
            padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;
            letter-spacing: .4px; text-transform: uppercase;
        }
        .badge-beginner     { background: #dcfce7; color: #16a34a; }
        .badge-intermediate { background: #fef9c3; color: #b45309; }
        .badge-advanced     { background: #fee2e2; color: #dc2626; }

        /* TBUT metrics bar */
        .tbut-bar {
            display: flex; align-items: center; gap: 0;
            background: #f8fafc; border: 1.5px solid var(--vl-border);
            border-radius: 12px; overflow: hidden;
        }
        .tbut-item {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 14px; font-size: 13px;
        }
        .tbut-item + .tbut-item { border-left: 1.5px solid var(--vl-border); }
        .tbut-label { color: #94a3b8; font-size: 11px; font-weight: 600; }
        .tbut-val   { font-weight: 700; color: #1e293b; font-variant-numeric: tabular-nums; }
        .tbut-bar-completed { background: #f0fdf4; border-color: #bbf7d0; }
        .tbut-bar-completed .tbut-val { color: #15803d; }

        .btn-save {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;
            border: 1.5px solid #c7d2fe; background: #fff; color: var(--vl-accent);
            cursor: pointer; transition: all .18s;
        }
        .btn-save:hover { background: var(--vl-accent-light); border-color: var(--vl-accent); }

        .btn-submit {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 18px; border-radius: 10px; font-size: 13px; font-weight: 700;
            background: linear-gradient(135deg, #059669, #047857); color: #fff;
            border: none; cursor: pointer; transition: all .18s;
            box-shadow: 0 2px 8px rgba(5,150,105,.25);
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(5,150,105,.35); }

        /* ─── MAIN LAYOUT ─── */
        .vl-layout {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 0;
            height: calc(100vh - 64px);
            max-width: 1600px;
            margin: 0 auto;
        }

        /* ─── LEFT PANEL ─── */
        .vl-left {
            background: var(--vl-panel);
            border-right: 1px solid var(--vl-border);
            display: flex; flex-direction: column;
            overflow: hidden;
        }

        /* Tab bar */
        .vl-tabs {
            display: flex;
            border-bottom: 1px solid var(--vl-border);
            background: #fafafa;
            flex-shrink: 0;
        }
        .vl-tab {
            flex: 1; padding: 12px 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: none; background: transparent;
            color: #94a3b8; display: flex; align-items: center; justify-content: center;
            gap: 7px; border-bottom: 2px solid transparent;
            transition: all .18s;
        }
        .vl-tab.active { color: var(--vl-accent); border-bottom-color: var(--vl-accent); background: #fff; }
        .vl-tab:hover:not(.active) { color: #475569; }

        .vl-panel-body { flex: 1; overflow-y: auto; }
        .vl-panel-body::-webkit-scrollbar { width: 5px; }
        .vl-panel-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }

        /* Instruction content */
        .vl-instruksi-content { padding: 20px; }
        .vl-instruksi-content .prose p { color: #475569; font-size: 14px; line-height: 1.7; }
        .vl-instruksi-content .prose h1,
        .vl-instruksi-content .prose h2,
        .vl-instruksi-content .prose h3 { color: #1e293b; font-weight: 700; }
        .vl-instruksi-content .prose code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #7c3aed; }
        .vl-instruksi-content .prose pre  { background: #1e293b; color: #e2e8f0; border-radius: 8px; padding: 12px 16px; font-family: 'JetBrains Mono', monospace; font-size: 13px; }

        /* Expected Result panel content */
        .vl-expected-header {
            padding: 16px 20px 12px;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border-bottom: 1px solid #a7f3d0;
        }
        .vl-expected-header h3 { font-size: 14px; font-weight: 700; color: #065f46; margin: 0; }
        .vl-expected-header p  { font-size: 12px; color: #059669; margin: 2px 0 0; }

        .vl-expected-body { padding: 16px 20px; }

        .terminal-mock {
            background: #0d1117;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
        }
        .terminal-mock-bar {
            background: #161b22;
            padding: 8px 12px;
            display: flex; align-items: center; gap: 6px;
        }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-r { background: #ff5f57; }
        .dot-y { background: #febc2e; }
        .dot-g { background: #28c840; }
        .terminal-mock-label { font-size: 11px; color: #6e7681; margin-left: 6px; font-family: 'JetBrains Mono', monospace; }
        .terminal-mock pre {
            padding: 12px 16px 14px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #3fb950;
            margin: 0;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow-x: auto;
        }

        .vl-section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: #94a3b8;
            display: flex; align-items: center; gap: 6px;
            margin-bottom: 8px;
        }
        .vl-section-label::after { content:''; flex:1; height:1px; background:#f1f5f9; }

        .img-preview-card {
            border: 1.5px solid var(--vl-border);
            border-radius: 10px; overflow: hidden;
            background: #fafafa;
        }
        .img-preview-card-header {
            padding: 8px 12px;
            background: #f1f5f9;
            border-bottom: 1px solid var(--vl-border);
            font-size: 12px; color: #64748b; font-weight: 600;
            display: flex; align-items: center; gap: 6px;
        }
        .img-preview-card img {
            display: block; width: 100%;
            max-height: 280px; object-fit: contain;
            cursor: zoom-in;
            transition: opacity .2s;
        }
        .img-preview-card img:hover { opacity: .9; }

        /* Sandbox empty state */
        .vl-sandbox {
            padding: 32px 20px; text-align: center;
        }
        .vl-sandbox-icon {
            width: 64px; height: 64px; border-radius: 16px;
            background: var(--vl-accent-light);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 24px; color: var(--vl-accent);
        }
        .vl-sandbox h3 { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .vl-sandbox p  { font-size: 13px; color: #64748b; line-height: 1.6; }
        .vl-sandbox-rules {
            background: var(--vl-accent-light); border-radius: 10px;
            padding: 14px 16px; text-align: left; margin-top: 16px;
        }
        .vl-sandbox-rules h4 { font-size: 12px; font-weight: 700; color: var(--vl-accent); margin-bottom: 8px; }
        .vl-sandbox-rules li { font-size: 13px; color: #3730a3; margin-bottom: 5px; display: flex; gap: 8px; }

        /* ─── RIGHT PANEL ─── */
        .vl-right {
            display: flex; flex-direction: column;
            overflow: hidden;
            background: #1e1e2e;
        }

        /* Editor toolbar */
        .vl-editor-bar {
            background: #16213e;
            border-bottom: 1px solid #2d3561;
            padding: 0 12px 0 4px;
            display: flex; align-items: stretch; justify-content: space-between;
            flex-shrink: 0;
        }
        .vl-file-tab {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 20px;
            font-size: 13px; font-weight: 600;
            background: #1e2a4a; color: #7dd3fc;
            border-bottom: 2px solid #38bdf8;
            font-family: 'JetBrains Mono', monospace;
        }
        .vl-file-tab i { font-size: 14px; }

        .btn-run {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 7px 20px; border-radius: 8px; font-size: 13px; font-weight: 700;
            background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff;
            border: none; cursor: pointer; margin: 6px 0;
            transition: all .18s;
            box-shadow: 0 2px 8px rgba(99,102,241,.4);
        }
        .btn-run:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,.5); }
        .btn-run:active { transform: translateY(0); }

        /* Editor Area */
        .vl-editor-area {
            flex: 1; min-height: 0; position: relative;
        }
        .CodeMirror {
            height: 100% !important;
            font-family: 'JetBrains Mono', 'Courier New', monospace !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
        }
        .CodeMirror-hints { z-index: 9999 !important; }

        /* Terminal Area */
        .vl-terminal {
            background: var(--vl-terminal);
            border-top: 1px solid #1f2937;
            flex-shrink: 0;
            display: flex; flex-direction: column;
            height: 35%;
            min-height: 200px;
            max-height: 320px;
        }

        .vl-stdin-bar {
            background: #161b22;
            border-bottom: 1px solid #21262d;
            padding: 7px 14px;
            display: flex; align-items: center; gap: 8px;
            flex-shrink: 0;
        }
        .vl-stdin-label { font-size: 11px; font-family: 'JetBrains Mono', monospace; color: #e3b341; font-weight: 600; letter-spacing: .5px; }
        .vl-stdin-hint  { font-size: 11px; color: #6e7681; }

        .vl-stdin-input {
            width: 100%; background: #0d1117; color: #f0e68c;
            font-family: 'JetBrains Mono', monospace; font-size: 13px;
            padding: 6px 14px; resize: none;
            border: none; border-bottom: 1px solid #21262d;
            outline: none;
        }

        .vl-output-bar {
            background: #161b22;
            border-bottom: 1px solid #21262d;
            padding: 6px 14px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .vl-output-label { font-size: 11px; color: #6e7681; font-family: 'JetBrains Mono', monospace; letter-spacing: .5px; text-transform: uppercase; }

        .vl-output-pre {
            flex: 1; padding: 10px 16px;
            font-family: 'JetBrains Mono', monospace; font-size: 13px;
            color: #c9d1d9; line-height: 1.6;
            overflow-y: auto; white-space: pre-wrap;
        }
        .vl-output-pre::-webkit-scrollbar { width: 4px; }
        .vl-output-pre::-webkit-scrollbar-thumb { background: #21262d; border-radius: 99px; }

        .loading-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #e3b341; animation: pulse-dot 1s ease-in-out infinite;
        }
        @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ─── STATUS BADGES ─── */
        .badge-build-ok  { background: rgba(56,189,248,.12); color: #38bdf8; padding: 2px 8px; border-radius: 5px; font-size: 11px; font-weight: 700; font-family: 'JetBrains Mono', monospace; }
        .badge-build-err { background: rgba(239,68,68,.12);  color: #f87171; padding: 2px 8px; border-radius: 5px; font-size: 11px; font-weight: 700; font-family: 'JetBrains Mono', monospace; }

        /* ─── IMAGE LIGHTBOX ─── */
        #imageModal {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.88); z-index: 99999;
            align-items: center; justify-content: center;
            padding: 24px; cursor: zoom-out;
        }
        #imageModal.open { display: flex; }
        #imageModal img { max-width: 100%; max-height: 90vh; border-radius: 12px; object-fit: contain; box-shadow: 0 0 60px rgba(0,0,0,.6); }
        #imageModal-close {
            position: absolute; top: 20px; right: 28px;
            color: rgba(255,255,255,.7); font-size: 14px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.1); border: none;
            padding: 6px 14px; border-radius: 8px;
            transition: background .2s;
        }
        #imageModal-close:hover { background: rgba(255,255,255,.2); color: #fff; }

        /* ─── SUBMIT MODAL ─── */
        #submitTaskModal {
            position: fixed; inset: 0;
            background: rgba(15,23,42,.7); backdrop-filter: blur(6px);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        #submitTaskModal.open { opacity: 1; pointer-events: all; }
        #submitTaskModalDialog {
            background: #fff; border-radius: 20px;
            width: 90%; max-width: 420px;
            overflow: hidden;
            transform: scale(.95);
            transition: transform .25s;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
        }
        #submitTaskModal.open #submitTaskModalDialog { transform: scale(1); }

        .modal-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            padding: 36px 28px 28px; text-align: center; position: relative; overflow: hidden;
        }
        .modal-hero::before {
            content: ''; position: absolute; top: -30%; right: -10%;
            width: 120px; height: 120px; border-radius: 50%;
            background: rgba(255,255,255,.08);
        }
        .modal-hero-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
            border: 2px solid rgba(255,255,255,.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: #fff; margin: 0 auto 16px;
        }
        .modal-hero h3 { font-size: 20px; font-weight: 800; color: #fff; margin: 0 0 8px; }
        .modal-hero p  { font-size: 13px; color: rgba(255,255,255,.8); line-height: 1.6; margin: 0; }

        .modal-metrics {
            padding: 20px 24px;
            background: #f8fafc;
            border-bottom: 1px solid var(--vl-border);
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        }
        .metric-card {
            background: #fff; border: 1.5px solid var(--vl-border);
            border-radius: 12px; padding: 14px;
            text-align: center;
        }
        .metric-card .metric-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; margin-bottom: 4px; }
        .metric-card .metric-val   { font-size: 22px; font-weight: 800; font-variant-numeric: tabular-nums; }
        .metric-timer { color: var(--vl-accent); }
        .metric-steps { color: #f59e0b; }

        .modal-actions {
            padding: 20px 24px;
            display: flex; gap: 10px;
        }
        .btn-modal-cancel {
            flex: 1; padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
            background: #f1f5f9; color: #64748b; border: none; cursor: pointer; transition: background .18s;
        }
        .btn-modal-cancel:hover { background: #e2e8f0; }
        .btn-modal-confirm {
            flex: 1.5; padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 700;
            background: linear-gradient(135deg, #059669, #047857); color: #fff; border: none; cursor: pointer;
            box-shadow: 0 4px 14px rgba(5,150,105,.3); transition: all .18s;
        }
        .btn-modal-confirm:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(5,150,105,.4); }

        /* ─── READONLY BANNER ─── */
        .readonly-banner {
            position: fixed; bottom: 24px; right: 24px;
            background: #fff; border: 1.5px solid #bbf7d0;
            border-radius: 14px; padding: 12px 18px;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,.1); z-index: 9999;
        }
        .readonly-banner span { font-size: 13px; font-weight: 600; color: #15803d; }
        .btn-back-lab {
            font-size: 12px; font-weight: 600; color: #475569;
            background: #f1f5f9; border: none; border-radius: 8px;
            padding: 6px 12px; cursor: pointer; transition: background .18s;
            text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-back-lab:hover { background: #e2e8f0; }

        @media (max-width: 900px) {
            .vl-layout { grid-template-columns: 1fr; height: auto; }
            .vl-left  { height: 420px; border-right: none; border-bottom: 1px solid var(--vl-border); }
            .vl-right { height: 600px; }
            .vl-topbar { position: static; }
        }
    </style>
@endpush

@section('content')
<div class="vl-root">

    {{-- ═══ TOPBAR ═══ --}}
    <div class="vl-topbar">
        <div class="vl-topbar-left">
            <a href="{{ route('virtual-lab.index') }}" class="vl-back-btn">
                <i class="fas fa-arrow-left" style="font-size:13px;"></i>
            </a>
            <div>
                <div class="vl-task-title">{{ $activeTask ? $activeTask->title : 'Sandbox Mode' }}</div>
                <div class="vl-task-sub">{{ $activeTask ? $activeTask->material->title : 'Eksperimen Bebas' }}</div>
            </div>
            @if($activeTask)
            <span class="badge-difficulty badge-{{ $activeTask->difficulty }}">{{ ucfirst($activeTask->difficulty) }}</span>
            @endif
        </div>

        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            @if(isset($tbutSession) && $tbutSession && $activeTask)

            @if($tbutSession->is_completed)
            {{-- Completed --}}
            <div class="tbut-bar tbut-bar-completed">
                <div class="tbut-item">
                    <i class="fas fa-stopwatch" style="color:#16a34a;font-size:12px;"></i>
                    <span class="tbut-label">Waktu</span>
                    <span class="tbut-val" style="color:#16a34a;">{{ $tbutSession->formattedDuration() }}</span>
                </div>
                <div class="tbut-item">
                    <i class="fas fa-mouse-pointer" style="color:#16a34a;font-size:12px;"></i>
                    <span class="tbut-label">Aksi</span>
                    <span class="tbut-val" style="color:#16a34a;">{{ $tbutSession->run_count }}x</span>
                </div>
                <div class="tbut-item">
                    <i class="fas fa-check-circle" style="color:#16a34a;font-size:12px;"></i>
                    <span class="tbut-val" style="color:#16a34a;">Selesai</span>
                </div>
            </div>

            @else
            {{-- Active TBUT --}}
            <div class="tbut-bar">
                <div class="tbut-item">
                    <i class="fas fa-stopwatch" style="color:#6366f1;font-size:12px;"></i>
                    <span class="tbut-label">Waktu</span>
                    <span class="tbut-val" id="tbut-timer">00:00</span>
                </div>
                <div class="tbut-item">
                    <i class="fas fa-mouse-pointer" style="color:#f59e0b;font-size:12px;"></i>
                    <span class="tbut-label">Aksi</span>
                    <span class="tbut-val"><span id="tbut-run-count">{{ $tbutSession->run_count }}</span>x</span>
                </div>
                <div class="tbut-item">
                    @if($tbutSession->is_success)
                        <i class="fas fa-check-circle" style="color:#16a34a;font-size:12px;"></i>
                        <span class="tbut-val" style="color:#16a34a;">Tepat!</span>
                    @else
                        <i class="fas fa-circle-notch fa-spin" style="color:#6366f1;font-size:12px;"></i>
                        <span class="tbut-val" style="color:#94a3b8;font-weight:600;font-size:12px;">Belum Tepat</span>
                    @endif
                </div>
            </div>

            <button type="button" id="btn-save-code" class="btn-save">
                <i class="fas fa-save" style="font-size:12px;"></i> Simpan
            </button>
            <button type="button" id="btn-submit-task" class="btn-submit">
                <i class="fas fa-check-circle" style="font-size:12px;"></i> Submit &amp; Selesai
            </button>
            {{-- ── Deadline countdown badge ── --}}
            @if($activeTask->deadline_minutes)
            <div id="deadline-badge" class="tbut-bar" style="border-color:#fecaca;background:#fff7f7;min-width:120px;">
                <div class="tbut-item">
                    <i class="fas fa-hourglass-half" id="deadline-icon" style="color:#dc2626;font-size:12px;"></i>
                    <span class="tbut-label" style="color:#dc2626;">Sisa</span>
                    <span class="tbut-val" id="deadline-display" style="color:#dc2626;font-variant-numeric:tabular-nums;">--:--:--</span>
                </div>
            </div>
            @endif
            @endif
            @endif
        </div>
    </div>

    {{-- ═══ MAIN LAYOUT ═══ --}}
    <div class="vl-layout">

        {{-- ─── LEFT PANEL ─── --}}
        <div class="vl-left">
            {{-- Tab Header --}}
            @if($activeTask && ($activeTask->expected_output || $activeTask->expected_result_image))
            <div class="vl-tabs">
                <button class="vl-tab active" id="tab-instruksi" onclick="switchTab('instruksi')">
                    <i class="fas fa-book-open" style="font-size:12px;"></i> Instruksi
                </button>
                <button class="vl-tab" id="tab-expected" onclick="switchTab('expected')">
                    <i class="fas fa-bullseye" style="font-size:12px;"></i> Expected Result
                </button>
            </div>
            @else
            <div class="vl-tabs" style="pointer-events:none;">
                <div class="vl-tab active" style="cursor:default;">
                    <i class="fas fa-book-open" style="font-size:12px;"></i> Instruksi
                </div>
            </div>
            @endif

            {{-- Instruksi Panel --}}
            <div class="vl-panel-body" id="panel-instruksi">
                @if($activeTask)

                {{-- Deadline info banner --}}
                @if($activeTask->deadline_minutes && isset($tbutSession) && $tbutSession && !$tbutSession->is_completed)
                @php
                    $dlH = intdiv($activeTask->deadline_minutes, 60);
                    $dlM = $activeTask->deadline_minutes % 60;
                @endphp
                <div style="
                    margin: 12px 12px 0;
                    background: linear-gradient(135deg, #dc2626, #b91c1c);
                    border-radius: 12px; padding: 12px 16px;
                    display: flex; align-items: center; gap: 12px;
                ">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">⏰</div>
                    <div style="flex:1;">
                        <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.75);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Deadline Tugas</div>
                        <div style="font-size:16px;font-weight:800;color:#fff;line-height:1;">
                            {{ $dlH > 0 ? $dlH.' jam' : '' }}{{ $dlH > 0 && $dlM > 0 ? ' ' : '' }}{{ $dlM > 0 ? $dlM.' menit' : '' }}
                        </div>
                        <div style="font-size:11px;color:rgba(255,255,255,.7);margin-top:2px;">Sisa waktu ditampilkan di bagian atas halaman</div>
                    </div>
                    <div style="text-align:center;background:rgba(255,255,255,.18);border-radius:10px;padding:8px 12px;">
                        <div style="font-size:20px;font-weight:900;color:#fff;font-variant-numeric:tabular-nums;" id="dl-banner-display">--:--</div>
                        <div style="font-size:10px;color:rgba(255,255,255,.7);font-weight:600;">SISA</div>
                    </div>
                </div>
                @endif

                <div class="vl-instruksi-content">
                    <div class="prose prose-sm max-w-none">
                        {!! $activeTask->description !!}
                    </div>
                </div>
                @else
                <div class="vl-sandbox">
                    <div class="vl-sandbox-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Sandbox Mode</h3>
                    <p>Area bebas untuk eksperimen kode Java. Tidak ada tugas yang dievaluasi.</p>
                    <div class="vl-sandbox-rules">
                        <h4>⚠️ Aturan:</h4>
                        <ul style="list-style:none;padding:0;margin:0;">
                            <li><i class="fas fa-check-circle" style="color:#4f46e5;margin-top:2px;flex-shrink:0;"></i>Class utama harus bernama <code style="background:#e0e7ff;padding:1px 5px;border-radius:4px;font-size:12px;color:#4f46e5;">Main</code></li>
                            <li><i class="fas fa-check-circle" style="color:#4f46e5;margin-top:2px;flex-shrink:0;"></i>Wajib ada method <code style="background:#e0e7ff;padding:1px 5px;border-radius:4px;font-size:12px;color:#4f46e5;">public static void main</code></li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            {{-- Expected Result Panel --}}
            @if($activeTask && ($activeTask->expected_output || $activeTask->expected_result_image))
            <div class="vl-panel-body" id="panel-expected" style="display:none;">
                <div class="vl-expected-header">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;background:#059669;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-bullseye" style="color:#fff;font-size:13px;"></i>
                        </div>
                        <div>
                            <h3>Expected Result</h3>
                            <p>Hasil yang harus dicapai kode Anda</p>
                        </div>
                    </div>
                </div>
                <div class="vl-expected-body">

                    {{-- Text Output --}}
                    @if($activeTask->expected_output)
                    <div style="margin-bottom:20px;">
                        <div class="vl-section-label">
                            <i class="fas fa-terminal" style="font-size:10px;"></i> Output Terminal
                        </div>
                        <div class="terminal-mock">
                            <div class="terminal-mock-bar">
                                <span class="dot dot-r"></span>
                                <span class="dot dot-y"></span>
                                <span class="dot dot-g"></span>
                                <span class="terminal-mock-label">expected-output.txt</span>
                            </div>
                            <pre>{{ $activeTask->expected_output }}</pre>
                        </div>
                        <p style="font-size:11px;color:#94a3b8;margin-top:8px;display:flex;align-items:center;gap:5px;">
                            <i class="fas fa-info-circle"></i>
                            Sistem mencocokkan output secara otomatis (tidak case-sensitive, spasi ekstra diabaikan).
                        </p>
                    </div>
                    @endif

                    {{-- Image Result --}}
                    @if($activeTask->expected_result_image)
                    <div>
                        <div class="vl-section-label">
                            <i class="fas fa-image" style="font-size:10px;"></i> Contoh Tampilan Hasil
                        </div>
                        <div class="img-preview-card">
                            <div class="img-preview-card-header">
                                <i class="fas fa-eye"></i> Preview Hasil — klik untuk zoom
                            </div>
                            <div style="padding:10px;">
                                <img
                                    src="{{ asset('storage/' . $activeTask->expected_result_image) }}"
                                    alt="Expected Result"
                                    onclick="openImageModal(this.src)"
                                >
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif
        </div>

        {{-- ─── RIGHT PANEL (Code Editor + Terminal) ─── --}}
        <form method="POST" action="{{ route('virtual-lab.execute') }}" id="codeForm" class="vl-right">
            @csrf
            @if($activeTask)
                <input type="hidden" name="task_id" value="{{ $activeTask->id }}">
                <input type="hidden" name="elapsed" id="execute-elapsed">
            @endif

            {{-- Editor Toolbar --}}
            <div class="vl-editor-bar">
                @php
                    $mainFile = (isset($files) && count($files) > 0)
                        ? $files[0]
                        : ['filename' => 'Main.java', 'content' => "public class Main {\n    public static void main(String[] args) {\n        // Tulis kode di sini\n    }\n}"];
                    $currentId = 'file-0';
                @endphp
                <div class="vl-file-tab">
                    <i class="fab fa-java"></i>
                    <span>{{ $mainFile['filename'] }}</span>
                    <input type="hidden" name="files[{{ $currentId }}][filename]" value="{{ $mainFile['filename'] }}">
                </div>
                <button type="submit" name="action" value="run" class="btn-run">
                    <i class="fas fa-play" style="font-size:11px;"></i> Run Code
                </button>
            </div>

            {{-- Editor Area --}}
            <div class="vl-editor-area" id="editors-container">
                <div id="{{ $currentId }}" style="position:absolute;inset:0;width:100%;height:100%;">
                    <textarea id="code-editor-textarea"
                        name="files[{{ $currentId }}][content]"
                        style="width:100%;height:100%;display:none;"
                        spellcheck="false"
                        @if(isset($tbutSession) && $tbutSession && $tbutSession->is_completed) readonly @endif
                    >{{ $mainFile['content'] }}</textarea>
                </div>
            </div>

            {{-- Terminal --}}
            <div class="vl-terminal">
                {{-- STDIN --}}
                <div class="vl-stdin-bar">
                    <i class="fas fa-keyboard" style="color:#e3b341;font-size:11px;"></i>
                    <span class="vl-stdin-label">STDIN</span>
                    <span class="vl-stdin-hint">— isi jika kode memakai Scanner</span>
                </div>
                <textarea id="stdin-input" name="stdin"
                    rows="2"
                    class="vl-stdin-input"
                    placeholder="Contoh: 5&#10;3"
                    spellcheck="false">{{ $stdin ?? '' }}</textarea>

                {{-- Output header --}}
                <div class="vl-output-bar">
                    <span class="vl-output-label">Terminal Output</span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div id="loading-indicator" style="display:none;align-items:center;gap:6px;">
                            <div class="loading-dot"></div>
                            <span style="font-size:11px;color:#e3b341;font-family:'JetBrains Mono',monospace;">Compiling...</span>
                        </div>
                        @if(isset($error))
                        <span class="{{ $error ? 'badge-build-err' : 'badge-build-ok' }}">
                            {{ $error ? 'Build Failed' : 'Build OK' }}
                        </span>
                        @endif
                    </div>
                </div>

                <pre class="vl-output-pre">{{ $output ?? '// Hasil eksekusi akan muncul di sini...' }}</pre>
            </div>
        </form>

    </div>{{-- end vl-layout --}}
</div>{{-- end vl-root --}}

{{-- ═══ HIDDEN FORMS (TBUT Active Mode) ═══ --}}
@if(isset($tbutSession) && $tbutSession && $activeTask)

@if($tbutSession->is_completed)
{{-- Read-only banner --}}
<div class="readonly-banner">
    <i class="fas fa-lock" style="color:#16a34a;"></i>
    <span>Mode Review — Kode tidak dapat diubah</span>
    <a href="{{ route('virtual-lab.index') }}" class="btn-back-lab">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@else
{{-- Active mode forms --}}
<form id="form-save-code" style="display:none;">
    @csrf
    <input type="hidden" name="task_id" value="{{ $activeTask->id }}">
    <input type="hidden" name="code" id="save-code-content">
    <input type="hidden" name="elapsed" id="save-elapsed">
</form>

<form id="form-submit-task" method="POST" action="{{ route('virtual-lab.submit-task') }}" style="display:none;">
    @csrf
    <input type="hidden" name="task_id" value="{{ $activeTask->id }}">
    <input type="hidden" name="code" id="submit-code-content">
    <input type="hidden" name="elapsed" id="submit-elapsed">
</form>

{{-- Submit Confirmation Modal --}}
<div id="submitTaskModal">
    <div id="submitTaskModalDialog">
        <div class="modal-hero">
            <div class="modal-hero-icon">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <h3>Kumpulkan Tugas?</h3>
            <p>Langkah Anda akan direkam secara permanen. Pastikan kode sudah sesuai sebelum dikumpulkan.</p>
        </div>

        <div class="modal-metrics">
            <div class="metric-card">
                <div class="metric-label"><i class="fas fa-stopwatch" style="margin-right:4px;"></i>Waktu Selesai</div>
                <div class="metric-val metric-timer" id="modal-display-timer">00:00</div>
            </div>
            <div class="metric-card">
                <div class="metric-label"><i class="fas fa-mouse-pointer" style="margin-right:4px;"></i>Total Aksi</div>
                <div class="metric-val metric-steps"><span id="modal-display-run">0</span>x</div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" id="btn-cancel-submit" class="btn-modal-cancel">Kembali</button>
            <button type="button" id="btn-confirm-submit" class="btn-modal-confirm">
                <i class="fas fa-paper-plane" style="margin-right:7px;"></i>Ya, Kumpulkan!
            </button>
        </div>
    </div>
</div>

{{-- ════ DEADLINE EXPIRED MODAL ════ --}}
@if($activeTask->deadline_minutes)
<div id="deadlineModal" style="
    position:fixed;inset:0;background:rgba(15,23,42,.85);backdrop-filter:blur(8px);
    z-index:99999;display:none;align-items:center;justify-content:center;
">
    <div style="background:#fff;border-radius:20px;width:90%;max-width:400px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.3);">
        <div style="background:linear-gradient(135deg,#dc2626,#b91c1c);padding:32px 24px 24px;text-align:center;">
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;margin:0 auto 16px;">⏰</div>
            <h3 style="font-size:20px;font-weight:800;color:#fff;margin:0 0 8px;">Waktu Habis!</h3>
            <p style="font-size:13px;color:rgba(255,255,255,.85);line-height:1.6;margin:0;">Deadline pengerjaan tugas sudah berakhir. Kode Anda akan dikumpulkan secara otomatis.</p>
        </div>
        <div style="padding:20px 24px;">
            <button id="btn-deadline-submit" style="width:100%;padding:14px;border-radius:12px;font-size:15px;font-weight:700;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(220,38,38,.35);">
                <i class="fas fa-paper-plane" style="margin-right:8px;"></i>Kumpulkan Sekarang
            </button>
        </div>
    </div>
</div>
@endif

<script>
(function() {
    'use strict';
    // ── Elapsed timer (counts UP for TBUT recording) ──────────────────────
    let elapsed = {{ $tbutSession->duration_seconds ?? 0 }};
    const timerEl = document.getElementById('tbut-timer');

    function formatTime(s) {
        const m   = Math.floor(s / 60).toString().padStart(2, '0');
        const sec = (s % 60).toString().padStart(2, '0');
        return m + ':' + sec;
    }
    function formatTimeHMS(s) {
        const h   = Math.floor(s / 3600).toString().padStart(2, '0');
        const m   = Math.floor((s % 3600) / 60).toString().padStart(2, '0');
        const sec = (s % 60).toString().padStart(2, '0');
        return h + ':' + m + ':' + sec;
    }

    if (timerEl) {
        timerEl.textContent = formatTime(elapsed);
    }

    // ── Deadline countdown (counts DOWN, auto-submit when 0) ───────────────
    @if($activeTask->deadline_minutes)
    @php
        $deadlineSecs = $activeTask->deadline_minutes * 60;
        if (isset($tbutSession) && $tbutSession && $tbutSession->created_at) {
            $targetTime = $tbutSession->created_at->timestamp + ($activeTask->deadline_minutes * 60);
            $currentTime = now()->timestamp;
            $deadlineSecs = max(0, $targetTime - $currentTime);
        }
    @endphp
    let deadlineSecs = {{ $deadlineSecs }};
    const deadlineDisplay = document.getElementById('deadline-display');
    const deadlineBadge   = document.getElementById('deadline-badge');
    const deadlineIcon    = document.getElementById('deadline-icon');
    let deadlineTriggered = false;

    function updateDeadlineUI() {
        if (!deadlineDisplay) return;
        if (deadlineSecs <= 0) {
            deadlineDisplay.textContent = '00:00:00';
            const banner = document.getElementById('dl-banner-display');
            if (banner) banner.textContent = '00:00';
            return;
        }
        deadlineDisplay.textContent = formatTimeHMS(deadlineSecs);

        // Sync left-panel banner mini-display (MM:SS only)
        const banner = document.getElementById('dl-banner-display');
        if (banner) {
            const mm = Math.floor(deadlineSecs / 60).toString().padStart(2,'0');
            const ss = (deadlineSecs % 60).toString().padStart(2,'0');
            banner.textContent = mm + ':' + ss;
        }

        // Visual warnings
        if (deadlineSecs <= 60) {
            // < 1 min — pulse red
            deadlineBadge.style.borderColor = '#dc2626';
            deadlineBadge.style.background  = '#fef2f2';
            deadlineDisplay.style.color     = '#dc2626';
            deadlineIcon.className          = 'fas fa-hourglass-end';
            deadlineIcon.style.animation    = 'pulse-dot 0.7s ease-in-out infinite';
            if (banner) banner.style.color  = '#ff8080';
        } else if (deadlineSecs <= 300) {
            // < 5 min — orange warning
            deadlineBadge.style.borderColor = '#f97316';
            deadlineBadge.style.background  = '#fff7ed';
            deadlineDisplay.style.color     = '#ea580c';
            deadlineIcon.className          = 'fas fa-hourglass-half';
        }
    }
    updateDeadlineUI();
    @endif

    // ── Main tick (every second) ───────────────────────────────────────────
    setInterval(() => {
        elapsed++;
        if (timerEl) timerEl.textContent = formatTime(elapsed);

        @if($activeTask->deadline_minutes)
        if (!deadlineTriggered) {
            deadlineSecs--;
            updateDeadlineUI();

            if (deadlineSecs <= 0) {
                deadlineTriggered = true;
                triggerDeadlineSubmit();
            }
        }
        @endif
    }, 1000);

    @if($activeTask->deadline_minutes)
    function triggerDeadlineSubmit() {
        // Show deadline expired modal
        const dlModal = document.getElementById('deadlineModal');
        if (dlModal) {
            dlModal.style.display = 'flex';
            // Auto-submit after 5s countdown on button
            let countdown = 5;
            const dlBtn = document.getElementById('btn-deadline-submit');
            if (dlBtn) {
                dlBtn.textContent = `⏰ Mengumpulkan otomatis dalam ${countdown}s...`;
                const cntInt = setInterval(() => {
                    countdown--;
                    dlBtn.textContent = `⏰ Mengumpulkan otomatis dalam ${countdown}s...`;
                    if (countdown <= 0) {
                        clearInterval(cntInt);
                        forceSubmit();
                    }
                }, 1000);
                dlBtn.addEventListener('click', () => { clearInterval(cntInt); forceSubmit(); });
            } else {
                setTimeout(forceSubmit, 5000);
            }
        } else {
            forceSubmit();
        }
    }

    function forceSubmit() {
        const code = getCurrentCode();
        document.getElementById('submit-code-content').value = code;
        document.getElementById('submit-elapsed').value = elapsed;
        document.getElementById('form-submit-task').submit();
    }
    @endif

    // Count runs
    const codeForm   = document.getElementById('codeForm');
    const runCountEl = document.getElementById('tbut-run-count');
    if (codeForm && runCountEl) {
        codeForm.addEventListener('submit', function() {
            runCountEl.textContent = parseInt(runCountEl.textContent || '0') + 1;
            const elInput = document.getElementById('execute-elapsed');
            if (elInput) elInput.value = elapsed;
        });
    }

    function getCurrentCode() {
        if (window.editorInstance) return window.editorInstance.getValue();
        const ta = document.querySelector('#editors-container textarea');
        return ta ? ta.value : '';
    }

    // Save button
    const btnSave = document.getElementById('btn-save-code');
    if (btnSave) {
        btnSave.addEventListener('click', function() {
            const code = getCurrentCode();
            const csrf = document.querySelector('#form-save-code [name=_token]').value;
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:12px;"></i> Menyimpan...';
            fetch('{{ route('virtual-lab.save-code') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ task_id: {{ $activeTask->id }}, code: code, elapsed: elapsed })
            })
            .then(r => r.json())
            .then(data => {
                btnSave.disabled = false;
                if (data.success) {
                    btnSave.innerHTML = '<i class="fas fa-check" style="font-size:12px;"></i> Tersimpan!';
                    setTimeout(() => { btnSave.innerHTML = '<i class="fas fa-save" style="font-size:12px;"></i> Simpan'; }, 2000);
                } else {
                    btnSave.innerHTML = '<i class="fas fa-save" style="font-size:12px;"></i> Simpan';
                }
            })
            .catch(() => { btnSave.disabled = false; btnSave.innerHTML = '<i class="fas fa-save" style="font-size:12px;"></i> Simpan'; });
        });
    }

    // Submit modal
    const btnSubmit      = document.getElementById('btn-submit-task');
    const modal          = document.getElementById('submitTaskModal');
    const btnCancel      = document.getElementById('btn-cancel-submit');
    const btnConfirm     = document.getElementById('btn-confirm-submit');
    const modalTimerDisp = document.getElementById('modal-display-timer');
    const modalRunDisp   = document.getElementById('modal-display-run');

    const openModal = () => {
        if (modalTimerDisp && timerEl) modalTimerDisp.textContent = timerEl.textContent;
        if (modalRunDisp && runCountEl) modalRunDisp.textContent = runCountEl.textContent;
        modal.classList.add('open');
    };
    const closeModal = () => modal.classList.remove('open');

    if (btnSubmit) btnSubmit.addEventListener('click', openModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);
    if (modal) modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

    if (btnConfirm) {
        btnConfirm.addEventListener('click', function() {
            const code = getCurrentCode();
            document.getElementById('submit-code-content').value = code;
            document.getElementById('submit-elapsed').value = elapsed;
            btnConfirm.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:7px;"></i>Mengirim...';
            btnConfirm.disabled = true;
            document.getElementById('form-submit-task').submit();
        });
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // Auto-save every 60s
    setInterval(() => { if (btnSave && !btnSave.disabled) btnSave.click(); }, 60000);
})();
</script>
@endif
@endif

{{-- ═══ IMAGE LIGHTBOX ═══ --}}
<div id="imageModal">
    <button id="imageModal-close" onclick="closeImageModal()">
        <i class="fas fa-times"></i> Tutup
    </button>
    <img id="modalImage" src="" alt="Expected Result">
</div>

{{-- ═══ SCRIPTS ═══ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/hint/anyword-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/edit/closebrackets.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('code-editor-textarea');
    if (textarea) {
        textarea.style.display = '';
        window.editorInstance = CodeMirror.fromTextArea(textarea, {
            mode: 'text/x-java',
            theme: 'dracula',
            lineNumbers: true,
            autoCloseBrackets: true,
            matchBrackets: true,
            indentUnit: 4,
            tabSize: 4,
            indentWithTabs: false,
            readOnly: textarea.hasAttribute('readonly'),
            extraKeys: { 'Ctrl-Space': 'autocomplete', 'Tab': 'indentMore' }
        });
        window.editorInstance.setSize('100%', '100%');

        const form = document.getElementById('codeForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                window.editorInstance.save();
                const ta = document.getElementById('code-editor-textarea');
                if (ta && ta.value.trim() === '') {
                    e.preventDefault();
                    alert('Editor kosong! Tulis kode Java terlebih dahulu sebelum Run Code.');
                    return;
                }
                const li = document.getElementById('loading-indicator');
                if (li) li.style.display = 'flex';
            }, true);
        }
    }
});

// ── Tab Switching ──────────────────────────────────────────────────────────
function switchTab(tab) {
    const panels = {
        instruksi: document.getElementById('panel-instruksi'),
        expected:  document.getElementById('panel-expected')
    };
    const tabs = {
        instruksi: document.getElementById('tab-instruksi'),
        expected:  document.getElementById('tab-expected')
    };

    Object.keys(panels).forEach(key => {
        if (!panels[key] || !tabs[key]) return;
        if (key === tab) {
            panels[key].style.display = '';
            tabs[key].classList.add('active');
        } else {
            panels[key].style.display = 'none';
            tabs[key].classList.remove('active');
        }
    });
}

// ── Image Lightbox ─────────────────────────────────────────────────────────
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const img   = document.getElementById('modalImage');
    if (!modal || !img) return;
    img.src = src;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) modal.classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeImageModal(); });
</script>
@endsection
