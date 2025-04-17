<div class="d-none d-lg-flex col-lg-7 p-0">
    <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">

        <div class="position-absolute d-flex flex-row" style="top: 2%;left: 2%;">
            <div class="nav-item  dropdown me-2 me-xl-0 " >
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                    <i  class="ti ti-md ti-language-katakana"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-start dropdown-styles" style="">
                    <li class="change-theme" data-theme="light">
                        <a class="dropdown-item" href="{{route('admin.lang.change' , ['lang' => 'en'])}}">
                            <span class="align-middle "><i class=" fi fi-us me-2"></i>English</span>
                        </a>
                    </li>
                    <li class="change-theme" data-theme="dark">
                        <a class="dropdown-item" href="{{route('admin.lang.change' , ['lang' => 'ar'])}}">
                            <span class="align-middle"><i class="fi fi-sa me-2"></i>العربية</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0 " >
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                    <i id="theme-icon" class="ti ti-md ti-moon"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-start dropdown-styles" style="">
                    <li class="change-theme" data-theme="light">
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle "><i class="ti ti-sun me-2"></i>Light</span>
                        </a>
                    </li>
                    <li class="change-theme" data-theme="dark">
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <img
            src="{{asset('style/admin/img/illustrations/auth-login-illustration-light.png')}}"
            alt="auth-login-cover"
            class="img-fluid my-5 auth-illustration"
            data-app-light-img="illustrations/auth-login-illustration-light.png"
            data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

        <img
            src="{{asset('style/admin/img/illustrations/bg-shape-image-dark.png')}}"
            alt="auth-login-cover"
            class="platform-bg"
            data-app-light-img="illustrations/bg-shape-image-light.png"
            data-app-dark-img="illustrations/bg-shape-image-dark.png" />
    </div>
</div>
