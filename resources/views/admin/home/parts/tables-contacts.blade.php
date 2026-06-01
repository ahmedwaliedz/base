{{-- Latest contact messages --}}
<div class="row g-3 mb-4">

    <div class="col-12">
        <div class="dqt">
            <div class="dqt__head">
                <div class="dqt__head-left">
                    <div class="dqt__icon"
                         style="background:rgba(var(--home-contact-rgb),.14);color:rgb(var(--home-contact-rgb))">
                        <i class="ti ti-mail" aria-hidden="true"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_latest_contacts') }}</span>
                </div>
                <a href="{{ route('admin.contact-messages.index') }}" class="dqt__view-all">
                    {{ __('admin/main.home_table_view_all') }} <span class="dqt__arrow">{{ $arrow }}</span>
                </a>
                </div>
                <div class="dqt__table-scroll">
                <table class="dqt__table">
                <thead>
                    <tr>
                        <th>{{ __('admin/main.home_table_col_name') }}</th>
                        <th>{{ __('admin/main.home_table_col_email') }}</th>
                        <th>{{ __('admin/main.home_table_col_phone') }}</th>
                        <th>{{ __('admin/main.home_table_col_subject') }}</th>
                        <th>{{ __('admin/main.home_table_col_date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['latest_contacts'] as $m)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="dqt__avatar-fallback dqt__avatar-fallback--contact"
                                      aria-hidden="true">
                                    {{ mb_substr($m->name, 0, 1) }}
                                </span>
                                <span class="dqt__name">{{ $m->name }}</span>
                            </div>
                        </td>
                        <td><span class="dqt__sub">{{ $m->email ?? '—' }}</span></td>
                        <td><span class="dqt__sub">{{ $m->phone ?? '—' }}</span></td>
                        <td><span class="dqt__cell-clip">{{ $m->subject }}</span></td>
                        <td><span class="dqt__date">{{ \Carbon\Carbon::parse($m->created_at)->diffForHumans() }}</span></td>
                        <td>
                            <a href="{{ route('admin.contact-messages.show', $m->id) }}" class="dqt__action"
                               aria-label="{{ __('admin/main.home_action_view') }}">
                                <i class="ti ti-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="dqt__empty">
                                <i class="ti ti-mail" aria-hidden="true"></i>
                                {{ __('admin/main.home_table_empty') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>{{-- .dqt__table-scroll --}}
        </div>
    </div>

</div>
