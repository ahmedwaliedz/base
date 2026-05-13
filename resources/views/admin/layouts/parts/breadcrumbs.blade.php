<div class="admin-breadcrumbs-glass mb-4">
    @foreach($crumbs as $crumb)
        {{-- render the crumb --}}
        @if($crumb['active'])
            <span class="breadcrumb-current active-breadcrumb" aria-current="page">
                {!! $crumb['icon'] !!} {{ adminRouteLabel($crumb['title']) }}
            </span>
        @else
            <a href="{{ $crumb['url'] }}">
                {!! $crumb['icon'] !!} {{ adminRouteLabel($crumb['title']) }}
            </a>
        @endif

        {{-- separator after, except on the last item --}}
        @unless($loop->last)
            <span class="breadcrumb-separator mx-1">
                <i class="fa fa-angle-left"></i>
            </span>
        @endunless
    @endforeach
</div>
