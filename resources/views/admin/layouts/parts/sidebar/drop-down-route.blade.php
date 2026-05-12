<li class="menu-item {{ !empty($route['is_active']) ? 'open active' : '' }}" style="">
    <a href="javascript:void(0);" class="menu-link menu-toggle {{ !empty($route['is_active']) ? 'active' : '' }}">
        {!!  $route['icon']!!}
        <div>{{  __("admin/routes.admin.{$route['title']}.index") }} </div>
    </a>
    <ul class="menu-sub">
        @foreach($route['children'] as $sub)
            @if(empty($sub['children']))
                @if($route['route'] !== null)
                    @include('admin.layouts.parts.sidebar.simple-route', ['route' => $sub , 'parent' => $route])
                @else
                    @include('admin.layouts.parts.sidebar.simple-route', ['route' => $sub])
                @endif
            @else
                @include('admin.layouts.parts.sidebar.drop-down-route', ['route' => $sub])
            @endif
        @endforeach
    </ul>
</li>
