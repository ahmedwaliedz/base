{{-- Content & activity stats --}}
<div class="dash-section-label">{{ __('admin/main.home_section_content') }}</div>
<div class="row g-3 mb-4">

    {{-- Complaints --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--complaints h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-alert-circle"></i></div>
                @php $chCompl = $stats['change_complaints']; @endphp
                {{-- For "complaints": up = bad (red), down = good (green) --}}
                <span class="dsc__change {{ $chCompl['up'] ? 'dsc__change--down' : 'dsc__change--up' }}">
                    <i class="ti {{ $chCompl['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chCompl['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_complaints') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_complaints'] }}">0</div>
                <div class="dsc__sub">{{ $stats['pending_complaints'] }} {{ __('admin/main.home_stat_pending') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_complaints'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Messages --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--contacts h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-mail"></i></div>
                @php $chCont = $stats['change_contacts']; @endphp
                <span class="dsc__change {{ $chCont['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chCont['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chCont['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_contacts') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_contacts'] }}">0</div>
                <div class="dsc__sub">+{{ $stats['new_contacts_month'] }} {{ __('admin/main.home_stat_this_month') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill"
                         data-width="{{ $stats['total_contacts'] > 0
                                        ? min((int) round($stats['new_contacts_month'] / max($stats['total_contacts'], 1) * 100 * 10), 100)
                                        : 0 }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--categories h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-tag"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_categories'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_categories') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_categories'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_categories'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_categories'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- FAQs --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--faqs h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-help-circle"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_faqs'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_faqs') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_faqs'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_faqs_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_faqs'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Posts --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--posts h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-article"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_posts'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_posts') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_posts'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_posts'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_posts'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sliders --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--sliders h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-slideshow"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_sliders'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_sliders') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_sliders'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_sliders'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_sliders'] }}"></div>
                </div>
            </div>
        </div>
    </div>

</div>
