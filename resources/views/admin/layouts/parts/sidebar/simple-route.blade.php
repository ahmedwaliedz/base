<li class="menu-item {{ isset($route['is_active']) && $route['is_active'] ? 'active' : '' }} ">
    <a href="{{route('admin.'. $route['route'])}}" class="menu-link ">
        {!!  $route['icon']!!}
        @if(isset($parent))
            <div>{{ __("admin/routes.admin.{$parent['title']}.{$route['title']}") }} </div>
        @else
            <div>{{ __("admin/routes.admin.{$route['title']}") }} </div>
        @endif
    </a>
</li>
