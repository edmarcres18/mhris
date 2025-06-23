<div class="notification-item mb-3">
    <div>
        <i class="{{ $notification['icon'] ?? 'fas fa-bell' }}"></i>
        {{ $notification['text'] ?? 'Notification' }}
    </div>
    <small class="text-muted">{{ $notification['time'] ?? '' }}</small>
</div>