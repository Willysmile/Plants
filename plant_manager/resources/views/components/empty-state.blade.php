@props([
    'message' => 'Aucun contenu',
    'height' => 'h-full',
    'icon' => null
])

<div class="w-full {{ $height }} flex flex-col items-center justify-center text-gray-400">
  @if($icon)
    <div class="text-4xl mb-2">{{ $icon }}</div>
  @endif
  <p>{{ $message }}</p>
</div>
