@props(['type' => 'info', 'message' => '', 'dismissible' => true])

@php
    $colors = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'text' => 'text-green-800',
            'icon-color' => 'text-green-600',
            'button' => 'text-green-600 hover:text-green-800',
            'icon' => 'check-circle'
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-800',
            'icon-color' => 'text-red-600',
            'button' => 'text-red-600 hover:text-red-800',
            'icon' => 'alert-circle'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-200',
            'text' => 'text-yellow-800',
            'icon-color' => 'text-yellow-600',
            'button' => 'text-yellow-600 hover:text-yellow-800',
            'icon' => 'alert-triangle'
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-800',
            'icon-color' => 'text-blue-600',
            'button' => 'text-blue-600 hover:text-blue-800',
            'icon' => 'info'
        ]
    ];
    
    $config = $colors[$type] ?? $colors['info'];
@endphp

<div x-data="{ open: true }" 
     x-show="open" 
     x-transition
     class="mb-4 p-4 rounded-lg border {{ $config['bg'] }} {{ $config['border'] }}">
    <div class="flex items-start">
        <!-- Icone -->
        <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 {{ $config['icon-color'] }} mt-0.5 flex-shrink-0"></i>
        
        <!-- Message -->
        <div class="ml-3 flex-1">
            <p class="{{ $config['text'] }} text-sm font-medium">
                {{ $message ?: $slot }}
            </p>
        </div>
        
        <!-- Bouton de fermeture -->
        @if($dismissible)
            <button @click="open = false" 
                    class="ml-3 {{ $config['button'] }} transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
