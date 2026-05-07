@php
    $currentLang = adminLang();
    $flagClass = $currentLang === 'ar' ? 'fi-sa' : 'fi-us';
@endphp
<li class="nav-item dropdown-language dropdown">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
       data-bs-toggle="dropdown"
       aria-label="{{ __('admin/main.language') }}">
        <i class="ti ti-language" aria-hidden="true"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item {{ $currentLang === 'en' ? 'active' : '' }}"
               href="{{ route('admin.lang.change', ['lang' => 'en']) }}">
                <span class="align-middle">
                    <i class="fi fi-us me-2"></i>English
                    @if($currentLang === 'en')
                        <i class="ti ti-check ms-auto" aria-hidden="true" style="float: inline-end"></i>
                    @endif
                </span>
            </a>
        </li>
        <li>
            <a class="dropdown-item {{ $currentLang === 'ar' ? 'active' : '' }}"
               href="{{ route('admin.lang.change', ['lang' => 'ar']) }}">
                <span class="align-middle">
                    <i class="fi fi-sa me-2"></i>العربية
                    @if($currentLang === 'ar')
                        <i class="ti ti-check ms-auto" aria-hidden="true" style="float: inline-end"></i>
                    @endif
                </span>
            </a>
        </li>
    </ul>
</li>
