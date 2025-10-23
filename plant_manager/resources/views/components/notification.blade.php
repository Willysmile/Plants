@props(['type' => 'info', 'message' => '', 'dismissible' => true])

@php
    $config = [
        'success' => [
            'bg' => 'bg-green-100',
            'border' => 'border-green-400',
            'text' => 'text-green-900',
            'icon' => 'check-circle',
            'iconColor' => 'text-green-700'
        ],
        'error' => [
            'bg' => 'bg-red-100',
            'border' => 'border-red-400',
            'text' => 'text-red-900',
            'icon' => 'alert-circle',
            'iconColor' => 'text-red-700'
        ],
        'warning' => [
            'bg' => 'bg-yellow-100',
            'border' => 'border-yellow-400',
            'text' => 'text-yellow-900',
            'icon' => 'alert-triangle',
            'iconColor' => 'text-yellow-700'
        ],
        'info' => [
            'bg' => 'bg-blue-100',
            'border' => 'border-blue-400',
            'text' => 'text-blue-900',
            'icon' => 'info',
            'iconColor' => 'text-blue-700'
        ]
    ];
    $styles = $config[$type] ?? $config['info'];
@endphp

<div class="p-8 rounded-2xl border-2 {{ $styles['bg'] }} {{ $styles['border'] }} shadow-2xl max-w-2xl w-11/12 pointer-events-auto cursor-pointer transform transition-all duration-300 opacity-100 scale-100 notification-alert" data-type="{{ $type }}">
    <div class="flex items-center gap-4">
        <i data-lucide="{{ $styles['icon'] }}" class="w-10 h-10 {{ $styles['iconColor'] }} flex-shrink-0"></i>
        <div class="flex-1">
            <p class="{{ $styles['text'] }} text-lg font-medium">{{ $message }}</p>
        </div>
        @if($dismissible)
            <button class="ml-4 text-gray-500 hover:text-gray-700 flex-shrink-0 transition notification-close" type="button">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        @endif
    </div>
</div>

