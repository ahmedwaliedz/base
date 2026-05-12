<div class="user-danger">
    @if (! $isDeleted)
        <div class="user-danger__row">
            <div>
                <h6 class="user-danger__title">{{ __('admin/main.block_account') }}</h6>
                <p class="user-danger__sub">{{ __('admin/main.block_account_hint') }}</p>
            </div>
            <button type="button" class="btn btn-label-warning switch-block" data-id="{{ $user->id }}"
                data-route="{{ route('admin.users.switchBlock', ['id' => $user->id]) }}">
                <i class="ti ti-{{ $user->is_blocked ? 'lock-open' : 'lock' }} me-1"></i>
                {{ $user->is_blocked ? __('admin/main.unblock') : __('admin/main.block') }}
            </button>
        </div>

        <div class="user-danger__row">
            <div>
                <h6 class="user-danger__title">{{ __('admin/main.delete_account') }}</h6>
                <p class="user-danger__sub">{{ __('admin/main.delete_account_hint') }}</p>
            </div>
            <a href="#" class="btn btn-danger delete-record" data-id="{{ $user->id }}"
                data-route="{{ route('admin.users.destroy', ['user' => $user]) }}">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        </div>
    @else
        <div class="user-danger__row">
            <div>
                <h6 class="user-danger__title">{{ __('admin/main.restore_account') }}</h6>
                <p class="user-danger__sub">{{ __('admin/main.restore_account_hint') }}</p>
            </div>
            <a href="#" class="btn btn-success restore-row" data-id="{{ $user->id }}"
                data-route="{{ route('admin.users.restore', ['id' => $user->id]) }}">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        </div>
    @endif
</div>
