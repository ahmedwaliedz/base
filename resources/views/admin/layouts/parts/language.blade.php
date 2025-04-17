<!-- Language -->
<li class="nav-item dropdown-language dropdown me-2 me-xl-0">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class="ti ti-language rounded-circle ti-md"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{route('admin.lang.change' , ['lang' => 'en'])}}">
                <span class="align-middle "><i class=" fi fi-us me-2"></i>English</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{route('admin.lang.change' , ['lang' => 'ar'])}}">
                <span class="align-middle "><i class=" fi fi-sa me-2"></i>العربية</span>
            </a>
        </li>

    </ul>
</li>
<!--/ Language -->
