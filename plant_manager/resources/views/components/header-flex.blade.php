@props([
    'title' => '',
    'titleClass' => 'text-sm font-semibold text-gray-900',
    'showCheckbox' => false,
    'checkboxId' => '',
    'checkboxClass' => 'text-blue-600',
    'checkboxOnclick' => ''
])

<div class="flex items-center justify-between mb-2">
  <div class="flex-1">
    {{ $title }}
  </div>
  @if($showCheckbox)
    <label class="flex items-center cursor-pointer ml-2">
      <input 
        type="checkbox" 
        id="{{ $checkboxId }}" 
        class="w-4 h-4 {{ $checkboxClass }} rounded focus:ring-2"
        @if($checkboxOnclick) onclick="{{ $checkboxOnclick }}" @endif
      >
    </label>
  @endif
</div>
