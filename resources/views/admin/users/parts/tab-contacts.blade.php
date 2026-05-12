@if ($contacts->isEmpty())
    <div class="user-profile__empty">
        <i class="ti ti-mail-off"></i>
        <p>{{ __('admin/main.no_contact_messages') }}</p>
    </div>
@else
    <div class="table-responsive">
        <table class="user-sessions-table">
            <thead>
                <tr>
                    <th>{{ __('admin/main.id') }}</th>
                    <th>{{ __('admin/main.subject') }}</th>
                    <th>{{ __('admin/main.message') }}</th>
                    <th>{{ __('admin/main.created_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>#{{ $contact->id }}</td>
                        <td class="text-truncate" style="max-width: 240px;">{{ Str::limit($contact->subject, 60) }}</td>
                        <td class="text-truncate" style="max-width: 360px;">{{ Str::limit($contact->message, 90) }}</td>
                        <td>
                            {{ $contact->created_at?->format('Y-m-d H:i') }}
                            @if ($contact->created_at)
                                <small class="text-muted d-block">{{ $contact->created_at->diffForHumans() }}</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
