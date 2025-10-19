@props(['color' => 'blue', 'title', 'content' => null])

@php
  $bgColor = match($color) {
    'yellow' => 'bg-yellow-50 border-yellow-500',
    'green' => 'bg-green-50 border-green-500',
    'red' => 'bg-red-50 border-red-500',
    'cyan' => 'bg-cyan-50 border-cyan-500',
    'indigo' => 'bg-indigo-50 border-indigo-500',
    'teal' => 'bg-teal-50 border-teal-500',
    'purple' => 'bg-purple-50 border-purple-500',
    'amber' => 'bg-amber-50 border-amber-500',
    default => 'bg-blue-50 border-blue-500',
  };
  
  $textColor = match($color) {
    'yellow' => 'text-yellow-900',
    'green' => 'text-green-900',
    'red' => 'text-red-900',
    'cyan' => 'text-cyan-900',
    'indigo' => 'text-indigo-900',
    'teal' => 'text-teal-900',
    'purple' => 'text-purple-900',
    'amber' => 'text-amber-900',
    default => 'text-blue-900',
  };
@endphp

<div class="{{ $bgColor }} p-2 rounded-lg border-l-4">
  <div class="text-center">
    <h3 class="text-xs font-semibold {{ $textColor }} uppercase tracking-wide">{{ $title }}</h3>
  </div>
  <div class="mt-2 text-center text-xs text-gray-800">
    {{ $content ?? $slot }}
  </div>
</div>
