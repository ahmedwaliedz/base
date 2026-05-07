<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['name']['ar'] ?? 'نمو' }} | المنصة المتكاملة لإدارة المزارع</title>
    <meta name="description" content="نمو — منصة متكاملة لإدارة الحظائر والمزارع والمواشي. تتبع، نقاط، تقارير.">
    <link rel="icon" type="image/x-icon" href="{{ $settings['fav_icon'] ?? '' }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- AOS Scroll Animations --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════
           BRAND DESIGN TOKENS — NAMO
           Primary  : Deep Forest Green #1a5c38
           Accent   : Warm Gold        #c9a96e
           ═══════════════════════════════════════ */
        :root {
            /* Greens */
            --g-900: #071e10;
            --g-800: #0d3320;
            --g-700: #1a5c38;
            --g-600: #2d7a50;
            --g-500: #4aaa72;
            --g-100: #d4edda;

            /* Golds */
            --a-600: #b8955a;
            --a-500: #c9a96e;
            --a-400: #e0c48a;
            --a-100: #f9f0e0;

            /* Neutrals */
            --n-cream:  #faf8f4;
            --n-white:  #ffffff;
            --n-900:    #1a1a1a;
            --n-600:    #4a4a4a;
            --n-400:    #8a8a8a;

            /* Radii & Shadows */
            --r-card:   16px;
            --r-btn:    50px;
            --sh-card:  0 4px 24px rgba(0,0,0,.08);
            --sh-lift:  0 16px 40px rgba(26,92,56,.14);

            --font: 'Tajawal', sans-serif;
            --font-en: 'Montserrat', sans-serif;
        }

        /* ─── Reset ─── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font);
            background: var(--n-white);
            color: var(--n-900);
            direction: rtl;
            overflow-x: hidden;
        }
        img { max-width: 100%; display: block; }
        a { text-decoration: none; }

        /* ═══════════════════════════════════════
           NAVBAR
           ═══════════════════════════════════════ */
        .navbar {
            position: fixed;
            inset: 0 0 auto;
            z-index: 1000;
            padding: 18px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: padding .3s, background .3s, box-shadow .3s;
        }
        .navbar.scrolled {
            background: rgba(7,30,16,.92);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 12px 6%;
            box-shadow: 0 4px 24px rgba(0,0,0,.35);
        }

        /* Logo */
        .nav-logo-img { height: 44px; object-fit: contain; }
        .nav-logo-text {
            font-family: var(--font-en);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--a-500);
            letter-spacing: 2px;
        }

        /* Links */
        .nav-links { display: flex; gap: 2.2rem; list-style: none; }
        .nav-links a {
            color: rgba(255,255,255,.8);
            font-size: .95rem;
            font-weight: 500;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--a-400); }

        /* Buttons */
        .nav-actions { display: flex; gap: 10px; align-items: center; }

        .btn-outline {
            padding: 8px 22px;
            border: 1.5px solid var(--a-500);
            border-radius: var(--r-btn);
            color: var(--a-500);
            font-size: .9rem;
            font-weight: 600;
            transition: all .25s;
        }
        .btn-outline:hover { background: var(--a-500); color: var(--g-800); }

        .btn-fill {
            padding: 9px 24px;
            background: var(--a-500);
            border: 1.5px solid var(--a-500);
            border-radius: var(--r-btn);
            color: var(--g-800);
            font-size: .9rem;
            font-weight: 800;
            transition: all .25s;
        }
        .btn-fill:hover {
            background: var(--a-400);
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(201,169,110,.4);
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }
        .hamburger span {
            display: block; width: 24px; height: 2px;
            background: var(--n-white);
            border-radius: 2px;
            transition: all .3s;
        }
        .hamburger.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Mobile nav */
        @media (max-width: 800px) {
            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%; right: 0; left: 0;
                background: rgba(7,30,16,.97);
                backdrop-filter: blur(14px);
                padding: 24px 6%;
                gap: 18px;
            }
            .nav-links.open { display: flex; }
            .hamburger { display: flex; }
            .btn-outline { display: none; }
        }

        /* ═══════════════════════════════════════
           HERO
           ═══════════════════════════════════════ */
        .hero {
            min-height: 100vh;
            background:
                radial-gradient(ellipse 90% 60% at 50% -5%, rgba(45,122,80,.45) 0%, transparent 65%),
                radial-gradient(ellipse 55% 35% at 85% 90%, rgba(201,169,110,.12) 0%, transparent 60%),
                linear-gradient(160deg, var(--g-900) 0%, var(--g-800) 45%, #092819 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 130px 6% 90px;
            position: relative;
            overflow: hidden;
        }

        /* subtle bokeh blobs */
        .hero::before, .hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .hero::before {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(74,170,114,.07) 0%, transparent 70%);
            top: -200px; left: -200px;
        }
        .hero::after {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(201,169,110,.07) 0%, transparent 70%);
            bottom: -150px; right: -150px;
        }

        /* Live badge */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,169,110,.12);
            border: 1px solid rgba(201,169,110,.28);
            border-radius: 50px;
            padding: 7px 20px;
            color: var(--a-400);
            font-size: .88rem;
            font-weight: 600;
            margin-bottom: 2.4rem;
            backdrop-filter: blur(6px);
            animation: fadeUp .7s ease both;
        }
        .hero-badge::before {
            content: '';
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--a-500);
            animation: livePulse 2s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%,100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(.7); opacity: .5; }
        }

        /* Logo */
        .hero-logo-wrap {
            margin-bottom: 2.8rem;
            animation: fadeUp .7s .1s ease both;
        }
        .hero-logo-img {
            height: 170px;
            width: auto;
            margin: 0 auto;
            filter: drop-shadow(0 10px 40px rgba(201,169,110,.3));
            animation: logoFloat 5s ease-in-out infinite;
        }
        @keyframes logoFloat {
            0%,100% { transform: translateY(0);    }
            50%      { transform: translateY(-12px); }
        }

        /* Headline */
        .hero-title {
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 900;
            color: var(--n-white);
            line-height: 1.22;
            margin-bottom: 1.4rem;
            text-shadow: 0 4px 24px rgba(0,0,0,.3);
            animation: fadeUp .7s .2s ease both;
        }
        .hero-title .accent { color: var(--a-500); }

        .hero-sub {
            font-size: clamp(.95rem, 2vw, 1.18rem);
            color: rgba(255,255,255,.72);
            max-width: 560px;
            line-height: 1.85;
            margin-bottom: 3rem;
            font-weight: 400;
            animation: fadeUp .7s .32s ease both;
        }

        /* CTAs */
        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp .7s .44s ease both;
        }

        .btn-hero-primary {
            padding: 15px 42px;
            background: var(--a-500);
            color: var(--g-800);
            border-radius: var(--r-btn);
            font-size: 1.05rem;
            font-weight: 800;
            transition: all .3s;
            box-shadow: 0 8px 28px rgba(201,169,110,.35);
        }
        .btn-hero-primary:hover {
            background: var(--a-400);
            transform: translateY(-3px);
            box-shadow: 0 14px 38px rgba(201,169,110,.5);
        }

        .btn-hero-secondary {
            padding: 15px 40px;
            border: 2px solid rgba(255,255,255,.35);
            color: var(--n-white);
            border-radius: var(--r-btn);
            font-size: 1.05rem;
            font-weight: 700;
            transition: all .3s;
            backdrop-filter: blur(6px);
        }
        .btn-hero-secondary:hover {
            border-color: rgba(255,255,255,.75);
            background: rgba(255,255,255,.09);
            transform: translateY(-3px);
        }

        /* Scroll hint */
        .hero-scroll-hint {
            position: absolute;
            bottom: 36px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,.35);
            font-size: .78rem;
        }
        .scroll-line {
            width: 1px;
            height: 44px;
            background: linear-gradient(to bottom, rgba(255,255,255,.4), transparent);
            animation: scrollAnim 2.2s ease-in-out infinite;
        }
        @keyframes scrollAnim {
            0%   { transform: scaleY(0); transform-origin: top;    opacity: 0; }
            40%  { transform: scaleY(1); transform-origin: top;    opacity: 1; }
            80%  { transform: scaleY(1); transform-origin: bottom; opacity: 1; }
            100% { transform: scaleY(0); transform-origin: bottom; opacity: 0; }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Trust strip */
        .trust-strip {
            background: var(--g-900);
            padding: 22px 6%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 36px;
            flex-wrap: wrap;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,.55);
            font-size: .9rem;
            font-weight: 500;
        }
        .trust-icon {
            width: 36px; height: 36px;
            background: rgba(201,169,110,.1);
            border: 1px solid rgba(201,169,110,.2);
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: 1rem;
        }

        /* ═══════════════════════════════════════
           SHARED SECTION STYLES
           ═══════════════════════════════════════ */
        .section { padding: 100px 6%; }

        .sec-head {
            text-align: center;
            margin-bottom: 64px;
        }
        .sec-eyebrow {
            display: inline-block;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3.5px;
            color: var(--a-500);
            margin-bottom: 14px;
        }
        .sec-title {
            font-size: clamp(1.8rem, 3.5vw, 2.9rem);
            font-weight: 900;
            line-height: 1.25;
            margin-bottom: 18px;
            color: var(--n-900);
        }
        .sec-title.on-dark { color: var(--n-white); }
        .sec-desc {
            font-size: 1.05rem;
            color: var(--n-600);
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.85;
        }
        .sec-desc.on-dark { color: rgba(255,255,255,.68); }

        /* ═══════════════════════════════════════
           FEATURES SECTION
           ═══════════════════════════════════════ */
        .features-section { background: var(--n-cream); }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
            gap: 26px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feat-card {
            background: var(--n-white);
            border-radius: var(--r-card);
            padding: 36px 30px;
            border: 1px solid rgba(26,92,56,.06);
            transition: transform .35s, box-shadow .35s;
            position: relative;
            overflow: hidden;
        }
        .feat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0; left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--g-700), var(--a-500));
            transform: scaleX(0);
            transform-origin: right;
            transition: transform .35s ease;
        }
        .feat-card:hover { transform: translateY(-7px); box-shadow: var(--sh-lift); }
        .feat-card:hover::after { transform: scaleX(1); }

        .feat-icon {
            width: 60px; height: 60px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--g-700), var(--g-600));
            display: grid;
            place-items: center;
            font-size: 1.7rem;
            margin-bottom: 22px;
        }
        .feat-title { font-size: 1.12rem; font-weight: 800; margin-bottom: 10px; }
        .feat-desc  { font-size: .94rem;  color: var(--n-600); line-height: 1.75; }

        /* ═══════════════════════════════════════
           STATS SECTION
           ═══════════════════════════════════════ */
        .stats-section {
            background:
                radial-gradient(ellipse 55% 70% at 20% 50%, rgba(74,170,114,.1) 0%, transparent 60%),
                radial-gradient(ellipse 45% 60% at 80% 50%, rgba(201,169,110,.07) 0%, transparent 60%),
                linear-gradient(150deg, var(--g-900) 0%, var(--g-800) 60%, #092819 100%);
            position: relative;
            overflow: hidden;
        }

        .stats-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            max-width: 960px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.07);
            background: rgba(255,255,255,.03);
            backdrop-filter: blur(8px);
        }

        .stat-cell {
            padding: 52px 28px;
            text-align: center;
            transition: background .25s;
            position: relative;
        }
        .stat-cell:not(:last-child)::before {
            content: '';
            position: absolute;
            top: 20%; bottom: 20%;
            left: 0;
            width: 1px;
            background: rgba(255,255,255,.09);
        }
        .stat-cell:hover { background: rgba(255,255,255,.04); }

        .stat-emoji { font-size: 2.2rem; margin-bottom: 14px; display: block; }

        .stat-num {
            font-family: var(--font-en);
            font-size: clamp(2.4rem, 4.5vw, 3.6rem);
            font-weight: 800;
            color: var(--a-500);
            line-height: 1;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        .stat-num .plus { font-size: .65em; opacity: .8; margin-right: 2px; }

        .stat-lbl {
            font-size: .92rem;
            color: rgba(255,255,255,.65);
            font-weight: 500;
            line-height: 1.45;
        }

        /* ═══════════════════════════════════════
           HOW IT WORKS
           ═══════════════════════════════════════ */
        .how-section { background: var(--n-white); }

        .steps-wrap {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
        }
        .steps-wrap::before {
            content: '';
            position: absolute;
            top: 52px;
            right: 13%; left: 13%;
            height: 2px;
            background: linear-gradient(90deg, var(--g-700), var(--a-500), var(--g-700));
            opacity: .35;
        }

        .step-box {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .step-num {
            width: 78px; height: 78px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--g-700), var(--g-600));
            color: var(--n-white);
            font-family: var(--font-en);
            font-size: 1.55rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 26px;
            box-shadow: 0 0 0 5px var(--n-white), 0 0 0 8px rgba(26,92,56,.15);
            transition: all .3s;
        }
        .step-box:hover .step-num {
            background: linear-gradient(135deg, var(--a-500), var(--a-400));
            color: var(--g-800);
            transform: scale(1.08);
        }
        .step-title { font-size: 1.15rem; font-weight: 800; margin-bottom: 12px; }
        .step-desc  { font-size: .94rem; color: var(--n-600); line-height: 1.75; }

        /* ═══════════════════════════════════════
           CTA SECTION
           ═══════════════════════════════════════ */
        .cta-section {
            background: linear-gradient(150deg, var(--g-700) 0%, var(--g-800) 100%);
            text-align: center;
            padding: 110px 6%;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: -40%; right: -8%;
            width: 550px; height: 550px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,169,110,.13) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -40%; left: -8%;
            width: 450px; height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(74,170,114,.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-title { font-size: clamp(1.8rem, 3.5vw, 2.8rem); font-weight: 900; color: var(--n-white); margin-bottom: 18px; position: relative; z-index: 1; }
        .cta-desc  {
            font-size: 1.08rem;
            color: rgba(255,255,255,.72);
            max-width: 520px;
            margin: 0 auto 44px;
            line-height: 1.85;
            position: relative;
            z-index: 1;
        }
        .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

        .btn-cta-p {
            padding: 17px 50px;
            background: var(--a-500);
            color: var(--g-800);
            border-radius: var(--r-btn);
            font-size: 1.08rem;
            font-weight: 800;
            transition: all .3s;
            box-shadow: 0 8px 30px rgba(201,169,110,.38);
        }
        .btn-cta-p:hover { background: var(--a-400); transform: translateY(-3px); box-shadow: 0 14px 42px rgba(201,169,110,.52); }

        .btn-cta-s {
            padding: 17px 46px;
            border: 2px solid rgba(255,255,255,.45);
            color: var(--n-white);
            border-radius: var(--r-btn);
            font-size: 1.08rem;
            font-weight: 700;
            transition: all .3s;
        }
        .btn-cta-s:hover { border-color: var(--n-white); background: rgba(255,255,255,.1); transform: translateY(-3px); }

        /* ═══════════════════════════════════════
           FOOTER
           ═══════════════════════════════════════ */
        .footer {
            background: var(--g-900);
            padding: 64px 6% 32px;
        }
        .footer-inner { max-width: 1200px; margin: 0 auto; }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            margin-bottom: 32px;
        }

        .footer-logo-img { height: 52px; object-fit: contain; margin-bottom: 18px; filter: brightness(1.1); }
        .footer-logo-text { font-family: var(--font-en); font-size: 1.6rem; font-weight: 800; color: var(--a-500); margin-bottom: 16px; display: inline-block; }

        .footer-tagline { font-size: .9rem; color: rgba(255,255,255,.45); line-height: 1.7; max-width: 270px; }

        .footer-col-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2.5px; color: var(--a-500); margin-bottom: 18px; }

        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 11px; }
        .footer-links a { font-size: .9rem; color: rgba(255,255,255,.5); transition: color .2s; }
        .footer-links a:hover { color: var(--a-400); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-copy { font-size: .83rem; color: rgba(255,255,255,.3); }
        .footer-copy .hi { color: var(--a-500); }

        /* ═══════════════════════════════════════
           RESPONSIVE
           ═══════════════════════════════════════ */
        @media (max-width: 900px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid > :first-child { grid-column: 1 / -1; }
            .steps-wrap::before { display: none; }
        }

        @media (max-width: 600px) {
            .section { padding: 80px 5%; }
            .footer-grid { grid-template-columns: 1fr; }
            .stats-panel { grid-template-columns: 1fr 1fr; }
            .stat-cell:not(:last-child)::before { display: none; }
            .hero-logo-img { height: 130px; }
            .trust-strip { gap: 20px; }
        }

        @media (max-width: 360px) {
            .stats-panel { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

{{-- ═══════════════ NAVBAR ═══════════════ --}}
<nav class="navbar" id="mainNav">
    <a href="#hero" aria-label="نمو - الرئيسية">
        @if(!empty($settings['logo']))
            <img class="nav-logo-img" src="{{ $settings['logo'] }}" alt="{{ $settings['name']['ar'] ?? 'نمو' }}">
        @else
            <span class="nav-logo-text">NAMO</span>
        @endif
    </a>

    <ul class="nav-links" id="navLinks" role="list">
        <li><a href="#features">المميزات</a></li>
        <li><a href="#stats">الإحصائيات</a></li>
        <li><a href="#how-it-works">كيف يعمل</a></li>
    </ul>

    <div class="nav-actions">
        <a href="{{ route('admin.loginPage') }}" class="btn-outline">تسجيل الدخول</a>
        <a href="{{ route('admin.loginPage') }}" class="btn-fill">ابدأ الآن</a>
    </div>

    <button class="hamburger" id="hamburger" aria-label="فتح القائمة" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero" id="hero" aria-label="القسم الرئيسي">

    <span class="hero-badge">منصة رائدة لإدارة المزارع والمواشي</span>

    <div class="hero-logo-wrap">
        @if(!empty($settings['logo']))
            <img class="hero-logo-img" src="{{ $settings['logo'] }}" alt="شعار نمو">
        @else
            {{-- Brand SVG fallback --}}
            <svg width="200" height="148" viewBox="0 0 200 148" fill="none" xmlns="http://www.w3.org/2000/svg"
                 style="filter:drop-shadow(0 10px 40px rgba(201,169,110,.35));margin:0 auto">
                <text x="50%" y="52%" dominant-baseline="middle" text-anchor="middle"
                      font-family="Tajawal,sans-serif" font-size="72" font-weight="900" fill="#c9a96e">نمو</text>
                <text x="50%" y="82%" dominant-baseline="middle" text-anchor="middle"
                      font-family="Montserrat,sans-serif" font-size="22" font-weight="700" fill="rgba(255,255,255,0.85)"
                      letter-spacing="8">NAMO</text>
            </svg>
        @endif
    </div>

    <h1 class="hero-title">
        المنصة المتكاملة<br>
        لإدارة <span class="accent">مزارعك ومواشيك</span>
    </h1>

    <p class="hero-sub">
        نمو — نظام ذكي يُمكّنك من إدارة الحظائر والمزارع، تتبّع المواشي والدواجن،
        ومكافأة مستخدميك بكل احترافية وسهولة.
    </p>

    <div class="hero-actions">
        <a href="{{ route('admin.loginPage') }}" class="btn-hero-primary">ابدأ مجاناً الآن</a>
        <a href="#features" class="btn-hero-secondary">اكتشف المزايا</a>
    </div>

    <div class="hero-scroll-hint" aria-hidden="true">
        <span>تمرير</span>
        <div class="scroll-line"></div>
    </div>
</section>

{{-- Trust strip --}}
<div class="trust-strip" data-aos="fade-up">
    <div class="trust-item">
        <div class="trust-icon">🔒</div>
        <span>آمن ومشفّر بالكامل</span>
    </div>
    <div class="trust-item">
        <div class="trust-icon">📱</div>
        <span>يعمل على جميع الأجهزة</span>
    </div>
    <div class="trust-item">
        <div class="trust-icon">⚡</div>
        <span>سريع وسهل الاستخدام</span>
    </div>
    <div class="trust-item">
        <div class="trust-icon">🌍</div>
        <span>عربي 100%</span>
    </div>
</div>

{{-- ═══════════════ FEATURES ═══════════════ --}}
<section class="section features-section" id="features">
    <div class="sec-head" data-aos="fade-up">
        <span class="sec-eyebrow">ما يميزنا</span>
        <h2 class="sec-title">كل ما تحتاجه في مكان واحد</h2>
        <p class="sec-desc">منصة نمو مصمَّمة خصيصاً لمتطلبات المزارعين وأصحاب الحظائر في المنطقة.</p>
    </div>

    <div class="features-grid">
        @php
        $features = [
            ['icon' => '🏠', 'title' => 'إدارة الحظائر والمزارع',    'desc' => 'أضف وأدر حظائرك ومزارعك بسهولة — تتبّع الطاقة الاستيعابية والحالة اللحظية لكل موقع.'],
            ['icon' => '🐄', 'title' => 'تتبّع المواشي والدواجن',    'desc' => 'سجلات مفصّلة لكل رأس ماشية أو طير مع تتبّع الصحة والإنتاج والتطعيمات.'],
            ['icon' => '🏆', 'title' => 'نظام النقاط والمكافآت',    'desc' => 'حفّز مستخدميك بنظام نقاط متكامل يعزز الولاء والمشاركة الفعّالة في المنصة.'],
            ['icon' => '📊', 'title' => 'تقارير وإحصائيات متقدمة', 'desc' => 'لوحة تحكم شاملة بتقارير تفصيلية تساعدك على اتخاذ قرارات مدروسة وتحسين الإنتاجية.'],
            ['icon' => '👥', 'title' => 'إدارة المستخدمين والأدوار',  'desc' => 'نظام متكامل للصلاحيات يضمن وصول كل مستخدم للمعلومات المناسبة له فقط.'],
            ['icon' => '📱', 'title' => 'تجربة متعددة الأجهزة',    'desc' => 'واجهة متجاوبة تعمل بسلاسة على الجوال والحاسوب — أدر مزرعتك من أي مكان في أي وقت.'],
        ];
        @endphp

        @foreach($features as $i => $feat)
            <div class="feat-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 80 }}">
                <div class="feat-icon">{{ $feat['icon'] }}</div>
                <h3 class="feat-title">{{ $feat['title'] }}</h3>
                <p class="feat-desc">{{ $feat['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════ STATS ═══════════════ --}}
<section class="section stats-section" id="stats">
    <div class="sec-head" data-aos="fade-up">
        <span class="sec-eyebrow">أرقامنا تتحدث</span>
        <h2 class="sec-title on-dark">إحصائيات المنصة</h2>
        <p class="sec-desc on-dark">أرقام حقيقية تعكس ثقة عملائنا وحجم العمل الذي تديره منصة نمو.</p>
    </div>

    <div class="stats-panel" id="statsPanel" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-cell">
            <span class="stat-emoji">👥</span>
            <div class="stat-num">
                <span class="counter" data-target="{{ $stats['active_users'] }}">0</span>
            </div>
            <div class="stat-lbl">مستخدم نشط</div>
        </div>

        <div class="stat-cell">
            <span class="stat-emoji">🏠</span>
            <div class="stat-num">
                <span class="counter" data-target="{{ $stats['total_branches'] }}">0</span>
            </div>
            <div class="stat-lbl">حظيرة ومزرعة</div>
        </div>

        <div class="stat-cell">
            <span class="stat-emoji">🏆</span>
            <div class="stat-num">
                <span class="counter" data-target="{{ $stats['total_points'] }}">0</span><span class="plus">+</span>
            </div>
            <div class="stat-lbl">نقطة مكافأة موزّعة</div>
        </div>

        <div class="stat-cell">
            <span class="stat-emoji">📂</span>
            <div class="stat-num">
                <span class="counter" data-target="{{ $stats['total_categories'] }}">0</span>
            </div>
            <div class="stat-lbl">تصنيف ومجموعة</div>
        </div>
    </div>
</section>

{{-- ═══════════════ HOW IT WORKS ═══════════════ --}}
<section class="section how-section" id="how-it-works">
    <div class="sec-head" data-aos="fade-up">
        <span class="sec-eyebrow">البداية سهلة</span>
        <h2 class="sec-title">كيف تبدأ مع نمو؟</h2>
        <p class="sec-desc">ثلاث خطوات بسيطة للبدء في إدارة مزارعك باحترافية كاملة.</p>
    </div>

    <div class="steps-wrap">
        @php
        $steps = [
            ['n' => '١', 'title' => 'أنشئ حسابك',          'desc' => 'سجّل بياناتك الأساسية وأنشئ حسابك في المنصة خلال دقائق معدودة بدون تعقيدات.'],
            ['n' => '٢', 'title' => 'أضف حظائرك ومزارعك', 'desc' => 'أدخل بيانات مواقعك الزراعية وحظائرك واحدة تلو الأخرى أو استورد البيانات دفعة واحدة.'],
            ['n' => '٣', 'title' => 'ابدأ الإدارة والتتبّع','desc' => 'استمتع بلوحة تحكم شاملة تمنحك رؤية 360° وتحكماً كاملاً في مزارعك ومواشيك.'],
        ];
        @endphp

        @foreach($steps as $i => $step)
            <div class="step-box" data-aos="fade-up" data-aos-delay="{{ $i * 140 }}">
                <div class="step-num">{{ $step['n'] }}</div>
                <h3 class="step-title">{{ $step['title'] }}</h3>
                <p class="step-desc">{{ $step['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════ CTA ═══════════════ --}}
<section class="cta-section" data-aos="fade-up">
    <h2 class="cta-title">هل أنت مستعد لتحويل مزرعتك؟</h2>
    <p class="cta-desc">
        انضم إلى المزارعين الذين يثقون في منصة نمو لإدارة أعمالهم الزراعية بشكل أكثر ذكاءً وكفاءة.
    </p>
    <div class="cta-actions">
        <a href="{{ route('admin.loginPage') }}" class="btn-cta-p">ابدأ الآن مجاناً</a>
        <a href="#features" class="btn-cta-s">تعرّف أكثر</a>
    </div>
</section>

{{-- ═══════════════ FOOTER ═══════════════ --}}
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                @if(!empty($settings['logo']))
                    <img class="footer-logo-img" src="{{ $settings['logo'] }}" alt="نمو">
                @else
                    <span class="footer-logo-text">NAMO نمو</span>
                @endif
                <p class="footer-tagline">
                    المنصة المتكاملة لإدارة المزارع والمواشي في المملكة العربية السعودية والمنطقة.
                </p>
            </div>

            <div>
                <p class="footer-col-title">الروابط</p>
                <ul class="footer-links">
                    <li><a href="#features">المميزات</a></li>
                    <li><a href="#stats">الإحصائيات</a></li>
                    <li><a href="#how-it-works">كيف يعمل</a></li>
                </ul>
            </div>

            <div>
                <p class="footer-col-title">الحساب</p>
                <ul class="footer-links">
                    <li><a href="{{ route('admin.loginPage') }}">تسجيل الدخول</a></li>
                    <li><a href="{{ route('admin.loginPage') }}">لوحة التحكم</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">
                &copy; {{ date('Y') }} جميع الحقوق محفوظة لـ <span class="hi">نمو</span>
            </p>
            <p class="footer-copy">صُنع بـ ❤️ في المملكة العربية السعودية</p>
        </div>
    </div>
</footer>

{{-- ═══════════════ SCRIPTS ═══════════════ --}}
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
(function () {
    'use strict';

    /* ── AOS ── */
    AOS.init({ duration: 680, easing: 'ease-out-cubic', once: true, offset: 55 });

    /* ── Navbar scroll class ── */
    const nav = document.getElementById('mainNav');
    const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 55);
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ── Hamburger ── */
    const btn     = document.getElementById('hamburger');
    const navList = document.getElementById('navLinks');
    btn?.addEventListener('click', () => {
        const open = navList.classList.toggle('open');
        btn.classList.toggle('active', open);
        btn.setAttribute('aria-expanded', open);
    });
    navList?.querySelectorAll('a').forEach(a =>
        a.addEventListener('click', () => {
            navList.classList.remove('open');
            btn?.classList.remove('active');
        })
    );

    /* ── Animated stat counters ── */
    function runCounter(el) {
        const target   = parseInt(el.dataset.target ?? '0', 10);
        if (target === 0) { el.textContent = '0'; return; }

        const duration = 1800;
        const start    = performance.now();

        function tick(now) {
            const elapsed  = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // ease-out cubic
            const eased    = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target).toLocaleString('ar-SA');
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    const panel = document.getElementById('statsPanel');
    if (panel) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    panel.querySelectorAll('.counter').forEach(runCounter);
                    io.unobserve(panel);
                }
            });
        }, { threshold: 0.25 });
        io.observe(panel);
    }
})();
</script>

</body>
</html>
