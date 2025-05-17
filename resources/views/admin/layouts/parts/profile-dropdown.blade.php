<li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="{{auth('admin')->user()->image_url}}" alt class="h-auto rounded-circle"/>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{route('admin.profile')}}">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar avatar-online">
                            <img src="{{auth('admin')->user()->image_url}}" alt class="h-auto rounded-circle"/>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <span class="fw-medium d-block">{{auth('admin')->user()->name}}</span>
                        <small class="text-muted">{{auth('admin')->user()->role_name}}</small>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('admin.profile')}}">
                <i class="ti ti-user-check me-2 ti-sm"></i>
                <span class="align-middle">{{__('admin/main.profile')}}</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('admin.settings.index')}}">
                <i class="ti ti-settings me-2 ti-sm"></i>
                <span class="align-middle">{{__('admin/main.settings')}}</span>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('admin.logout')}}">
                <i class="ti ti-logout me-2 ti-sm"></i>
                <span class="align-middle">{{__('admin/auth.logout')}}</span>
            </a>
        </li>
    </ul>
</li>
