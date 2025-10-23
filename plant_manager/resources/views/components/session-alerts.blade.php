@php
    $flashNotifications = collect([
        ['type' => 'success', 'message' => session('success')],
        ['type' => 'error', 'message' => session('error')],
        ['type' => 'warning', 'message' => session('warning')],
        ['type' => 'info', 'message' => session('info')],
    ])->filter(fn ($alert) => filled($alert['message']));
@endphp

@if($flashNotifications->isNotEmpty())
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            @foreach($flashNotifications as $alert)
                if (typeof showNotification === 'function') {
                    showNotification(@json($alert['message']), @json($alert['type']), 0);
                }
            @endforeach
        });
    </script>
@endif
