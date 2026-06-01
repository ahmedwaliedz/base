@foreach($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6 data-rows">
        <div class="role-card h-100">

            <div class="role-card__header">
                <div class="d-flex align-items-start gap-3 min-w-0 flex-grow-1">
                    <div class="role-card__icon-wrap flex-shrink-0">
                        <i class="ti ti-shield-check"></i>
                    </div>
                    <div class="min-w-0">
                        <h6 class="role-card__name" title="{{ $role->name }}">{{ $role->name }}</h6>
                        <span class="role-card__meta">
                            <i class="ti ti-users" style="font-size: 0.7rem;"></i>
                            {{ __('admin/main.total_admin_um', ['num' => $role->admins_count]) }}
                        </span>
                    </div>
                </div>
                <div class="role-card__actions">
                    <a href="{{ route('admin.roles.show', $role->id) }}"
                       class="role-card__action role-card__action--view"
                       data-bs-toggle="tooltip" title="{{ __('admin/main.show') }}">
                        <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                       class="role-card__action role-card__action--edit"
                       data-bs-toggle="tooltip" title="{{ __('admin/main.edit') }}">
                        <i class="ti ti-pencil"></i>
                    </a>
                    <a href="javascript:void(0);"
                       class="role-card__action role-card__action--delete delete-row"
                       data-route="{{ route('admin.roles.destroy', $role->id) }}"
                       data-bs-toggle="tooltip" title="{{ __('admin/main.delete') }}">
                        <i class="ti ti-trash"></i>
                    </a>
                </div>
            </div>

            <div class="role-card__divider"></div>

            <div class="role-card__footer">
                @if($role->admins->isNotEmpty())
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        @foreach($role->admins->take(5) as $admin)
                            <li class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $admin->name }}">
                                <img class="rounded-circle" src="{{ $admin->image }}" alt="{{ $admin->name }}">
                            </li>
                        @endforeach
                    </ul>
                    @if($role->admins_count > 5)
                        <span class="role-card__avatar-overflow">+{{ $role->admins_count - 5 }}</span>
                    @endif
                @else
                    <span class="role-card__meta-soft">
                        <i class="ti ti-user-off" style="font-size: 0.75rem;"></i>
                        {{ __('admin/main.no_admins_assigned') }}
                    </span>
                @endif
            </div>

        </div>
    </div>
@endforeach

@if($roles->count() === 0)
    <div class="col-12 data-rows">
        <div class="roles-empty">
            <div class="roles-empty__icon">
                <i class="ti ti-shield-off"></i>
            </div>
            <h5 class="roles-empty__title">{{ __('admin/main.no_data_found') }}</h5>
            <p class="roles-empty__desc">{{ __('admin/main.no_data_description') }}</p>
            <a href="{{ route('admin.roles.create') }}" class="btn-role-action btn-role-action--primary">
                <i class="ti ti-plus"></i>{{ __('admin/main.add') }}
            </a>
        </div>
    </div>
@endif

<div class="data-rows">
    {{ $roles->links('admin.layouts.pagination') }}
</div>
