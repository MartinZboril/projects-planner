<li class="list-group-item notification-{{ $notification->id }}-item">
    <div class="text-muted">
        <i class="fas fa-clock"></i>
        {{ $notification->created_at->diffForHumans() }}
        <span class="float-right">
            @if ($notification->data['link'] ?? false)
                <a href="#" onclick="viewNotificationLink('{{ route('notifications.seen', $notification) }}', '{{ $notification->data['link'] }}')" title="Detail">
                    <i class="fas fa-eye mr-1"></i>
                </a>
            @endif
            <a href="#" onclick="seenNotification('{{ route('notifications.seen', $notification) }}', '{{ $notification->id }}')" class="text-success" title="Mark as read">
                <i class="fas fa-book-open mr-1"></i>
            </a>
        </span>
    </div>
    {{ $notification->data['content'] }}
</li>
