{{-- Quick-action tables: latest users + pending complaints --}}
<div class="dash-section-label">{{ __('admin/main.home_section_tables') }}</div>
<div class="row g-3 mb-4">

    {{-- Latest Users --}}
    <div class="col-12 col-xl-6">
        <div class="dqt h-100">
            <div class="dqt__head">
                <div class="dqt__head-left">
                    <div class="dqt__icon"
                         style="background:rgba(var(--color-brand-primary-rgb),.14);color:var(--color-brand-primary)">
                        <i class="ti ti-users" aria-hidden="true"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_latest_users') }}</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="dqt__view-all">
                    {{ __('admin/main.home_table_view_all') }} <span class="dqt__arrow">{{ $arrow }}</span>
                </a>
            </div>
            <table class="dqt__table">
                <thead>
                    <tr>
                        <th>{{ __('admin/main.home_table_col_user') }}</th>
                        <th>{{ __('admin/main.home_table_col_phone') }}</th>
                        <th>{{ __('admin/main.home_table_col_status') }}</th>
                        <th>{{ __('admin/main.home_table_col_date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['latest_users'] as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($u->image)
                                    <img src="{{ asset('storage/'.$u->image) }}"
                                         class="dqt__avatar"
                                         alt="{{ $u->name }}">
                                @else
                                    <span class="dqt__avatar-fallback" aria-hidden="true">
                                        {{ mb_substr($u->name, 0, 1) }}
                                    </span>
                                @endif
                                <div class="dqt__name">{{ $u->name }}</div>
                            </div>
                        </td>
                        <td><span class="dqt__sub">{{ $u->phone ?? '—' }}</span></td>
                        <td>
                            @if($u->is_blocked)
                                <span class="dqt__badge dqt__badge--blocked">
                                    <i class="ti ti-lock" aria-hidden="true"></i>
                                    {{ __('admin/main.home_badge_blocked') }}
                                </span>
                            @else
                                <span class="dqt__badge dqt__badge--active">
                                    <i class="ti ti-circle-check" aria-hidden="true"></i>
                                    {{ __('admin/main.home_badge_active') }}
                                </span>
                            @endif
                        </td>
                        <td><span class="dqt__date">{{ $u->created_at->diffForHumans() }}</span></td>
                        <td>
                            <a href="{{ route('admin.users.index') }}" class="dqt__action"
                               aria-label="{{ __('admin/main.home_action_view') }}">
                                <i class="ti ti-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="dqt__empty">
                                <i class="ti ti-users" aria-hidden="true"></i>
                                {{ __('admin/main.home_table_empty') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Complaints --}}
    <div class="col-12 col-xl-6">
        <div class="dqt h-100">
            <div class="dqt__head">
                <div class="dqt__head-left">
                    <div class="dqt__icon"
                         style="background:rgba(var(--home-complaint-rgb),.14);color:rgb(var(--home-complaint-rgb))">
                        <i class="ti ti-alert-circle" aria-hidden="true"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_pending_complaints') }}</span>
                </div>
                <span class="dqt__view-all dqt__view-all--disabled">
                    {{ __('admin/main.home_table_view_all') }}
                </span>
            </div>
            <table class="dqt__table">
                <thead>
                    <tr>
                        <th>{{ __('admin/main.home_table_col_name') }}</th>
                        <th>{{ __('admin/main.home_table_col_subject') }}</th>
                        <th>{{ __('admin/main.home_table_col_status') }}</th>
                        <th>{{ __('admin/main.home_table_col_date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['pending_complaints_list'] as $c)
                    @php
                        $statusMap = [
                            'pending'    => ['class' => 'dqt__badge--pending', 'icon' => 'ti-clock',        'label' => __('admin/main.home_badge_pending')],
                            'processing' => ['class' => 'dqt__badge--process', 'icon' => 'ti-refresh',      'label' => __('admin/main.home_badge_processing')],
                            'completed'  => ['class' => 'dqt__badge--done',    'icon' => 'ti-circle-check', 'label' => __('admin/main.home_badge_completed')],
                            'rejected'   => ['class' => 'dqt__badge--reject',  'icon' => 'ti-x',            'label' => __('admin/main.home_badge_rejected')],
                            'unknown'    => ['class' => 'dqt__badge--blocked', 'icon' => 'ti-alert-triangle', 'label' => __('admin/main.home_badge_unknown')],
                        ];
                        $status = $c->status;
                        $statusValue = $status instanceof \App\Enums\ComplaintStatus ? $status->value : null;
                        $st = $statusMap[$statusValue] ?? $statusMap['unknown'];
                    @endphp
                    <tr>
                        <td>
                            <div class="dqt__name">{{ $c->name }}</div>
                            <div class="dqt__sub">{{ $c->phone ?? $c->email ?? '—' }}</div>
                        </td>
                        <td><span class="dqt__cell-clip">{{ $c->subject }}</span></td>
                        <td>
                            <span class="dqt__badge {{ $st['class'] }}">
                                <i class="ti {{ $st['icon'] }}" aria-hidden="true"></i>
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td><span class="dqt__date">{{ \Carbon\Carbon::parse($c->created_at)->diffForHumans() }}</span></td>
                        <td>
                            <span class="dqt__action dqt__action--disabled" aria-hidden="true">
                                <i class="ti ti-eye"></i>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="dqt__empty">
                                <i class="ti ti-circle-check" aria-hidden="true"></i>
                                {{ __('admin/main.home_table_no_pending') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
