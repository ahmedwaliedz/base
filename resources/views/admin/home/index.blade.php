@extends('admin.layouts.master')

@push('css')
    <style>
        .admin-welcome-banner {
            background: linear-gradient(125deg, #1a1a2e 0%, #252542 38%, #16213e 72%, #1f2937 100%);
            color: #fff;
            padding: 1.5rem 1.75rem;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.4);
            overflow: hidden;
        }

        .admin-welcome-banner__title {
            font-size: clamp(1.25rem, 2.5vw, 1.625rem);
            font-weight: 700;
            margin-bottom: 0.35rem;
            line-height: 1.3;
        }

        .admin-welcome-banner__subtitle {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.9375rem;
            margin-bottom: 0;
            max-width: 28rem;
            line-height: 1.5;
        }

        .admin-welcome-banner__stat-label {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.8125rem;
            margin-bottom: 0.25rem;
            line-height: 1.35;
            max-width: 11rem;
        }

        .admin-welcome-banner__stat-value {
            font-size: clamp(1.375rem, 3vw, 1.875rem);
            font-weight: 700;
            line-height: 1.2;
        }

        @keyframes admin-welcome-fade-up {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-welcome-banner__anim {
            opacity: 0;
            animation: admin-welcome-fade-up 0.65s ease-out forwards;
        }

        .admin-welcome-banner__anim--1 {
            animation-delay: 0ms;
        }

        .admin-welcome-banner__anim--2 {
            animation-delay: 80ms;
        }

        .admin-welcome-banner__anim--3 {
            animation-delay: 160ms;
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-welcome-banner__anim {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $admin = auth('admin')->user();
    @endphp
    <div class="admin-welcome-banner mb-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4">
            <div class="admin-welcome-banner__intro admin-welcome-banner__anim admin-welcome-banner__anim--1">
                <h1 class="admin-welcome-banner__title mb-0">
                    {{ __('admin/main.home_welcome_back', ['name' => $admin->name]) }}
                </h1>
                <p class="admin-welcome-banner__subtitle mt-2 mb-0">
                    {{ __('admin/main.home_welcome_subtitle') }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-4 gap-md-5 align-items-start">
                <div class="admin-welcome-banner__anim admin-welcome-banner__anim--2">
                    <div class="admin-welcome-banner__stat-label">
                        {{ __('admin/main.home_stat_users_this_year') }}
                    </div>
                    <div class="admin-welcome-banner__stat-value">
                        {{ number_format($stats['users_this_year']) }}
                    </div>
                </div>
                <div class="admin-welcome-banner__anim admin-welcome-banner__anim--3">
                    <div class="admin-welcome-banner__stat-label">
                        {{ __('admin/main.home_stat_top_package_purchases') }}
                    </div>
                    <div class="admin-welcome-banner__stat-value">
                        {{ number_format($stats['top_package_purchases']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
