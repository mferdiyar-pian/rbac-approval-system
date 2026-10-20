<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'RBAC Approval') }} — Sistem Persetujuan Transaksi</title>
        <meta name="description" content="Sistem manajemen transaksi berbasis Role-Based Access Control dengan alur kerja Maker-Checker-Approver yang terstruktur.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: 'Inter', sans-serif; }

            .hero-bg {
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #3730a3 50%, #4338ca 75%, #1e1b4b 100%);
                background-size: 400% 400%;
                animation: gradientShift 14s ease infinite;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.12; pointer-events: none; }
            .orb-1 { width: 600px; height: 600px; background: #818cf8; top: -200px; left: -200px; }
            .orb-2 { width: 400px; height: 400px; background: #a78bfa; bottom: -100px; right: -100px; }

            .grid-pattern {
                background-image: radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px);
                background-size: 40px 40px;
                opacity: 0.04;
            }

            /* Navbar */
            .navbar {
                background: rgba(255,255,255,0.05);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(255,255,255,0.08);
                padding: 16px 40px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .btn-outline {
                padding: 8px 20px;
                border: 1px solid rgba(255,255,255,0.25);
                border-radius: 10px;
                color: rgba(255,255,255,0.9);
                font-size: 14px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.2s;
                backdrop-filter: blur(8px);
                background: rgba(255,255,255,0.05);
            }
            .btn-outline:hover {
                background: rgba(255,255,255,0.12);
                border-color: rgba(255,255,255,0.4);
                transform: translateY(-1px);
            }
            .btn-primary {
                padding: 8px 20px;
                border: 1px solid transparent;
                border-radius: 10px;
                background: white;
                color: #4338ca;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s;
                box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            }
            .btn-primary:hover {
                background: #f0f0ff;
                transform: translateY(-1px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.25);
            }

            /* Hero */
            .hero {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 80px 24px 60px;
                position: relative;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                border-radius: 100px;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.15);
                color: rgba(255,255,255,0.85);
                font-size: 12px;
                font-weight: 500;
                margin-bottom: 24px;
            }

            .hero-title {
                font-size: clamp(40px, 6vw, 80px);
                font-weight: 900;
                color: white;
                line-height: 1.05;
                letter-spacing: -2px;
                margin-bottom: 20px;
            }

            .hero-title .highlight {
                background: linear-gradient(135deg, #a5b4fc, #c4b5fd, #7dd3fc);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .hero-desc {
                max-width: 560px;
                color: rgba(199, 210, 254, 0.85);
                font-size: 18px;
                line-height: 1.7;
                margin-bottom: 40px;
            }

            .hero-cta {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .cta-main {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 14px 32px;
                background: white;
                color: #4338ca;
                border-radius: 14px;
                font-weight: 700;
                font-size: 15px;
                text-decoration: none;
                box-shadow: 0 8px 32px rgba(0,0,0,0.3);
                transition: all 0.2s;
            }
            .cta-main:hover {
                background: #eef2ff;
                transform: translateY(-2px);
                box-shadow: 0 12px 40px rgba(0,0,0,0.35);
            }

            .cta-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 14px 32px;
                border: 1.5px solid rgba(255,255,255,0.25);
                color: white;
                border-radius: 14px;
                font-weight: 600;
                font-size: 15px;
                text-decoration: none;
                backdrop-filter: blur(8px);
                background: rgba(255,255,255,0.06);
                transition: all 0.2s;
            }
            .cta-secondary:hover {
                background: rgba(255,255,255,0.12);
                border-color: rgba(255,255,255,0.4);
                transform: translateY(-2px);
            }

            /* Flow Section */
            .flow-section {
                padding: 0 24px 80px;
                max-width: 900px;
                margin: 0 auto;
                width: 100%;
            }

            .flow-title {
                text-align: center;
                color: rgba(199, 210, 254, 0.7);
                font-size: 13px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 2px;
                margin-bottom: 24px;
            }

            .flow-cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 16px;
            }

            .flow-card {
                background: rgba(255,255,255,0.06);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 16px;
                padding: 24px;
                color: white;
                transition: all 0.2s;
            }
            .flow-card:hover {
                background: rgba(255,255,255,0.09);
                transform: translateY(-3px);
                border-color: rgba(255,255,255,0.18);
            }

            .flow-num {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px; height: 40px;
                border-radius: 12px;
                font-size: 16px;
                font-weight: 800;
                margin-bottom: 14px;
            }

            .flow-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
            .flow-card p { font-size: 13px; color: rgba(199, 210, 254, 0.7); line-height: 1.6; }

            .connector {
                display: flex;
                align-items: center;
                justify-content: center;
                color: rgba(255,255,255,0.25);
                font-size: 24px;
            }

            /* Stats bar */
            .stats-bar {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 40px;
                padding: 0 24px 60px;
                flex-wrap: wrap;
            }

            .stat-item { text-align: center; }
            .stat-num {
                font-size: 32px;
                font-weight: 800;
                color: white;
                line-height: 1;
            }
            .stat-label {
                font-size: 13px;
                color: rgba(199,210,254,0.65);
                margin-top: 4px;
                font-weight: 500;
            }

            .stat-divider {
                width: 1px;
                height: 40px;
                background: rgba(255,255,255,0.15);
            }

            @media (max-width: 768px) {
                .navbar { padding: 12px 20px; }
                .hero { padding: 60px 20px 40px; }
                .flow-section { padding: 0 20px 60px; }
                .stats-bar { gap: 24px; }
                .stat-divider { display: none; }
            }
        </style>
    </head>

    <body>
        <div class="hero-bg relative overflow-hidden">

            <!-- Orbs -->
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>

            <!-- Grid overlay -->
            <div class="grid-pattern absolute inset-0"></div>

            <!-- Navbar -->
            <nav class="navbar relative z-10">
                <div class="flex items-center gap-3">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="white" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <span style="color:white;font-weight:700;font-size:15px;display:block;line-height:1.2;">RBAC Approval</span>
                        <span style="color:rgba(199,210,254,0.7);font-size:11px;font-weight:500;">Sistem Persetujuan</span>
                    </div>
                </div>

                @if (Route::has('login'))
                    <div style="display:flex;align-items:center;gap:8px;">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>

            <!-- Hero -->
            <main class="hero relative z-10">
                <div class="badge">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    Role-Based Access Control System
                </div>

                <h1 class="hero-title">
                    Sistem Persetujuan<br>
                    <span class="highlight">Transaksi Terpadu</span>
                </h1>

                <p class="hero-desc">
                    Alur kerja <strong style="color:white;">Maker → Checker → Approver</strong> yang terstruktur
                    dengan kontrol akses berbasis peran penuh untuk keamanan dan transparansi proses bisnis.
                </p>

                <div class="hero-cta">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="cta-main">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="cta-main">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk ke Sistem
                        </a>
                        <a href="#alur" class="cta-secondary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Pelajari Alurnya
                        </a>
                    @endauth
                </div>
            </main>

            <!-- Stats -->
            <div class="stats-bar relative z-10">
                <div class="stat-item">
                    <div class="stat-num">3</div>
                    <div class="stat-label">Tahap Persetujuan</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-num">5+</div>
                    <div class="stat-label">Peran Pengguna</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Audit Trail</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-num">RBAC</div>
                    <div class="stat-label">Keamanan Akses</div>
                </div>
            </div>

            <!-- Flow Cards -->
            <div class="flow-section relative z-10" id="alur">
                <p class="flow-title">Alur Kerja Sistem</p>
                <div class="flow-cards">
                    <!-- Inputer -->
                    <div class="flow-card">
                        <div class="flow-num" style="background:rgba(99,102,241,0.25);color:#a5b4fc;">1</div>
                        <h3>Inputer (Maker)</h3>
                        <p>Membuat transaksi baru sebagai draft, mengisi detail, lalu mengajukannya ke tahap pemeriksaan.</p>
                        <div style="margin-top:14px;padding:10px 12px;background:rgba(99,102,241,0.12);border-radius:10px;border:1px solid rgba(99,102,241,0.2);">
                            <p style="font-size:11px;color:rgba(165,180,252,0.8);font-family:monospace;">inputer@example.com</p>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="connector" style="display:flex;align-items:center;justify-content:center;">
                        <svg width="24" height="24" fill="none" stroke="rgba(255,255,255,0.3)" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <!-- Checker -->
                    <div class="flow-card">
                        <div class="flow-num" style="background:rgba(245,158,11,0.2);color:#fcd34d;">2</div>
                        <h3>Checker (Pemeriksa)</h3>
                        <p>Memeriksa kelayakan dan kebenaran data transaksi. Jika lolos, diteruskan ke Approver.</p>
                        <div style="margin-top:14px;padding:10px 12px;background:rgba(245,158,11,0.1);border-radius:10px;border:1px solid rgba(245,158,11,0.2);">
                            <p style="font-size:11px;color:rgba(252,211,77,0.8);font-family:monospace;">checker@example.com</p>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="connector">
                        <svg width="24" height="24" fill="none" stroke="rgba(255,255,255,0.3)" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>

                    <!-- Approver -->
                    <div class="flow-card">
                        <div class="flow-num" style="background:rgba(16,185,129,0.2);color:#6ee7b7;">3</div>
                        <h3>Approver (Penyetuju)</h3>
                        <p>Memberikan keputusan akhir — menyetujui transaksi atau menolaknya dengan catatan alasan.</p>
                        <div style="margin-top:14px;padding:10px 12px;background:rgba(16,185,129,0.1);border-radius:10px;border:1px solid rgba(16,185,129,0.2);">
                            <p style="font-size:11px;color:rgba(110,231,183,0.8);font-family:monospace;">approver@example.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div style="text-align:center;padding:20px;color:rgba(165,180,252,0.4);font-size:12px;position:relative;z-index:10;border-top:1px solid rgba(255,255,255,0.06);">
                &copy; {{ date('Y') }} RBAC Approval System &mdash; Dibangun dengan Laravel
            </div>

        </div>
    </body>
</html>
