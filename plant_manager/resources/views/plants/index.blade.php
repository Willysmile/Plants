@extends('layouts.app')

@section('title', 'Plantes')

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Plantes</h1>
      <div class="flex items-center gap-3">
        <a href="{{ route('settings.index') }}" class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm transition">⚙️ Paramètres</a>
        <a href="{{ route('plants.create') }}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition">Ajouter</a>
      </div>
    </header>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      @foreach($plants as $plant)
        <x-plant-card :plant="$plant" />
      @endforeach
    </div>

    <div class="mt-6">
      {{ $plants->links() }}
    </div>
  </div>

  <!-- Modal container -->
  <div id="plant-modal-root" x-data x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="plant-modal-backdrop" class="absolute inset-0 bg-black/60" @click="closeModal()"></div>
    <div id="plant-modal-content" class="relative max-w-4xl w-full z-10"></div>
  </div>

  @include('partials.lightbox')
@endsection

@section('extra-scripts')
  <!-- External JS Modules -->
  <script src="{{ asset('js/modal-manager.js') }}"></script>
  <script src="{{ asset('js/gallery-manager.js') }}"></script>
  <script src="{{ asset('js/quick-modals-manager.js') }}"></script>
  <script src="{{ asset('js/form-validation.js') }}"></script>
  <script src="{{ asset('js/file-preview.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection