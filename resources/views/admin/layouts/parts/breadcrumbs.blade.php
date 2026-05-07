@php
    $lastCrumb   = collect($crumbs)->last();
    $pageTitle   = $lastCrumb ? __('admin/routes.admin.' . $lastCrumb['title']) : '';
    $pageIcon    = $lastCrumb['icon'] ?? '';
@endphp

<div class="navbar-page-info">

    {{-- Page title --}}
    <div class="navbar-page-title">
        @if($pageIcon)
            {!! $pageIcon !!}
        @endif
        <span>{{ $pageTitle }}</span>
    </div>

    {{-- Breadcrumb trail --}}
    <div class="navbar-breadcrumb-trail">
        @foreach($crumbs as $crumb)
            @if($crumb['active'])
                <span class="crumb-active">{{ __('admin/routes.admin.' . $crumb['title']) }}</span>
            @else
                <a href="{{ $crumb['url'] }}" class="crumb-link">{{ __('admin/routes.admin.' . $crumb['title']) }}</a>
            @endif

            @unless($loop->last)
                <span class="crumb-sep">/</span>
            @endunless
        @endforeach
    </div>

</div>
