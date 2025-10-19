@extends('layouts.simple')

@section('title', 'Ajouter une plante')

@section('content')
  <div class="bg-white p-6 rounded shadow" x-data="{ tagsModalOpen: false }">
    <h1 class="text-xl font-bold mb-6">Ajouter une plante</h1>

    <x-plant-form :tags="$tags" :tagsModalOpen="'tagsModalOpen'" />

    <!-- Modal Tags -->
    <div x-show="tagsModalOpen" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
          <h2 class="text-xl font-bold">Gestion des tags</h2>
          <button type="button" @click="tagsModalOpen = false" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            @php
              $selectedTagIds = old('tags', []);
              $tagsByCategory = $tags->groupBy('category') ?? collect();
            @endphp
            @if($tagsByCategory->count() > 0)
              @foreach($tagsByCategory as $index => $categoryTags)
                <div class="border rounded-lg p-3 bg-gray-50">
                  <h4 class="text-sm font-bold text-gray-800 mb-2">{{ $categoryTags->first()->category ?: 'Sans catégorie' }}</h4>
                  <div class="grid grid-cols-4 gap-2">
                    @foreach($categoryTags as $tag)
                      <label class="flex items-center gap-1.5 cursor-pointer hover:bg-white p-1 rounded">
                        <input type="checkbox" 
                               form="plant-form"
                               name="tags[]" 
                               value="{{ $tag->id }}"
                               @checked(in_array($tag->id, $selectedTagIds))
                               class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-xs text-gray-700">{{ $tag->name }}</span>
                      </label>
                    @endforeach
                  </div>
                </div>
              @endforeach
            @else
              <p class="text-sm text-gray-500">Aucun tag disponible</p>
            @endif
          </div>
        </div>
        <div class="sticky bottom-0 bg-gray-50 border-t px-6 py-4 flex justify-end gap-2">
          <button type="button" @click="tagsModalOpen = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Fermer</button>
          <button type="submit" form="plant-form" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium">Créer la plante</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('extra-scripts')
  <script src="{{ asset('js/form-validation.js') }}"></script>
  <script src="{{ asset('js/file-preview.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection