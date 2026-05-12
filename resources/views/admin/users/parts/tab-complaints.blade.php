@php
    use App\Enums\ComplaintStatus;
@endphp

@if ($complaints->isEmpty())
    <div class="user-profile__empty">
        <i class="ti ti-message-off"></i>
        <p>{{ __('admin/main.no_complaints') }}</p>
    </div>
@else
    <div class="table-responsive">
        <table class="user-sessions-table">
            <thead>
                <tr>
                    <th>{{ __('admin/main.id') }}</th>
                    <th>{{ __('admin/main.subject') }}</th>
                    <th>{{ __('admin/main.type') }}</th>
                    <th>{{ __('admin/main.status') }}</th>
                    <th>{{ __('admin/main.created_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($complaints as $complaint)
                    @php
                        $statusValue = $complaint->status instanceof ComplaintStatus
                            ? $complaint->status->value
                            : (string) $complaint->status;

                        $statusClass = match ($statusValue) {
                            'pending'    => 'bg-label-warning',
                            'processing' => 'bg-label-info',
                            'completed'  => 'bg-label-success',
                            'rejected'   => 'bg-label-danger',
                            default      => 'bg-label-secondary',
                        };
                    @endphp
                    <tr>
                        <td>#{{ $complaint->id }}</td>
                        <td class="text-truncate" style="max-width: 280px;">{{ Str::limit($complaint->subject, 60) }}</td>
                        <td><span class="badge bg-label-secondary">{{ $complaint->type->value ?? '—' }}</span></td>
                        <td><span class="badge {{ $statusClass }}">{{ $statusValue }}</span></td>
                        <td>
                            {{ $complaint->created_at?->format('Y-m-d H:i') }}
                            @if ($complaint->created_at)
                                <small class="text-muted d-block">{{ $complaint->created_at->diffForHumans() }}</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
