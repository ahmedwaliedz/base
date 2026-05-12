@if ($sessions->isEmpty())
    <div class="user-profile__empty">
        <i class="ti ti-device-desktop-off"></i>
        <p>{{ __('admin/main.no_sessions_yet') }}</p>
    </div>
@else
    <table class="user-sessions-table">
        <thead>
            <tr>
                <th>{{ __('admin/main.ip_address') }}</th>
                <th>{{ __('admin/main.device') }}</th>
                <th>{{ __('admin/main.last_activity') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $s)
                <tr>
                    <td><code>{{ $s->ip_address ?? '—' }}</code></td>
                    <td class="text-truncate" style="max-width: 360px;">{{ $s->user_agent ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp((int) $s->last_activity)->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
