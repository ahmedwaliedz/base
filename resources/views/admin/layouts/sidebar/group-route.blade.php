<li class="menu-item {{isset($route['is_active']) && $route['is_active'] ? 'open' : ''}}" style="">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="ti ti-users-group me-2"></i>
        <div>{{  __("admin/routes.{$route['title']}.index") }}</div>
    </a>
    <ul class="menu-sub">
        @foreach($route['children'] as $key => $sub)
            @if(empty($sub['children']))
                @if($route['route'] !== null)
                    @include('admin.layouts.sidebar.simple-route', ['route' => $sub , 'parent' => $route])
                @else
                    @include('admin.layouts.sidebar.simple-route', ['route' => $sub])
                @endif
            @else
                @include('admin.layouts.sidebar.drop-down-route', ['route' => $sub])
            @endif
        @endforeach
    </ul>
</li>
