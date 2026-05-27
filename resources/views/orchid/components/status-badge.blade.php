{{-- resources/views/orchid/components/status-badge.blade.php --}}
@php
    $badgeClass = match($type) {
        'warning' => 'badge-warning',
        'info' => 'badge-info',
        'primary' => 'badge-primary',
        'success' => 'badge-success',
        'danger' => 'badge-danger',
        default => 'badge-secondary'
    };
@endphp

<span class="badge {{ $badgeClass }}">{{ $label }}</span>