<ul class="menu-inner">
    @foreach($routes as $key => $route)
        @if(empty($route['children']))
            @include('admin.layouts.sidebar.simple-route', ['route' => $route])
        @elseif($route['route'] != null)
            @include('admin.layouts.sidebar.drop-down-route', ['route' => $route])
        @else
            @include('admin.layouts.sidebar.group-route', ['routes' => $route])
        @endif
    @endforeach
</ul>
