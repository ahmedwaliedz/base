@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/home.css') }}">
@endpush

@section('content')
<div class="dash-home-shell">

    @include('admin.home.parts.welcome')
    <br>

    @include('admin.home.parts.user-stats')
    <br>

    @include('admin.home.parts.content-stats')
    <br>

    @include('admin.home.parts.charts-row-1')
    <br>

    @include('admin.home.parts.charts-row-2')
    <br>

    @include('admin.home.parts.tables-users-complaints')
    <br>

    @include('admin.home.parts.tables-contacts')

</div>{{-- .dash-home-shell --}}

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js" defer></script>
<script>
    window.HOME_STATS = {
        monthly_labels:        @json($stats['monthly_labels']),
        users_monthly_counts:  @json($stats['monthly_counts']),
        admins_monthly_counts: @json($stats['admin_monthly_counts']),

        activity_labels:     @json($stats['activity_labels']),
        activity_complaints: @json($stats['activity_complaints']),
        activity_contacts:   @json($stats['activity_contacts']),

        dist_series: @json($stats['dist_series']),
        dist_labels: [
            @json(__('admin/main.home_stat_total_users')),
            @json(__('admin/main.home_stat_total_complaints')),
            @json(__('admin/main.home_stat_total_contacts')),
            @json(__('admin/main.home_stat_total_categories')),
            @json(__('admin/main.home_stat_total_faqs')),
            @json(__('admin/main.home_stat_posts')),
            @json(__('admin/main.home_stat_sliders')),
        ],

        polar_series: @json($stats['polar_series']),
        polar_labels: [
            @json(__('admin/main.home_stat_active_users')),
            @json(__('admin/main.home_stat_total_categories')),
            @json(__('admin/main.home_stat_sliders')),
            @json(__('admin/main.home_chart_resolved')),
        ],

        labels: {
            users:      @json(__('admin/main.home_stat_total_users')),
            admins:     @json(__('admin/main.home_stat_total_admins')),
            complaints: @json(__('admin/main.home_stat_total_complaints')),
            contacts:   @json(__('admin/main.home_stat_total_contacts')),
            total:      @json(__('admin/main.home_chart_total')),
        }
    };
</script>
<script src="{{ asset('style/admin/js/home.js') }}" defer></script>
@endpush
