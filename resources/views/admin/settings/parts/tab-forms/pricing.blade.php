<div class="tab-pane fade" id="navs-pills-justified-pricing" role="tabpanel">
    <div class="settings-card" data-card="pricing">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-receipt-2"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.pricing') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.pricing_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-percentage" aria-hidden="true"></i>
                {{ __('admin/main.percentage_values') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.settings.update') }}" method="POST" novalidate>
            @csrf
            @method('put')
            <div class="settings-card__body">
                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-calculator" aria-hidden="true"></i>
                        {{ __('admin/main.commission_taxes') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text :options="['name' => 'app_commission',   'value' => isset($settings['app_commission'])   ? $settings['app_commission']   : '', 'label' => 'app_commission',   'class' => 'col-md-6', 'isRequired' => true]" />
                        <x-form.text :options="['name' => 'vat_ratio',        'value' => isset($settings['vat_ratio'])        ? $settings['vat_ratio']        : '', 'label' => 'vat_ratio',        'class' => 'col-md-6', 'isRequired' => true]" />
                        <x-form.text :options="['name' => 'coupon_max_ratio', 'value' => isset($settings['coupon_max_ratio']) ? $settings['coupon_max_ratio'] : '', 'label' => 'coupon_max_ratio', 'class' => 'col-md-6', 'isRequired' => true]" />
                    </div>
                </div>
            </div>

            <footer class="settings-card__foot">
                <button type="submit" class="btn btn-primary waves-effect waves-light submit-button">
                    <i class="ti ti-device-floppy me-1" aria-hidden="true"></i>
                    {{ __('admin/main.save_changes') }}
                </button>
            </footer>
        </form>
    </div>
</div>
