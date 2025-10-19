@extends('layouts.app')

@section('title', 'Modifier : ' . $plant->name)

@section('content')
  <div class="max-w-4xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Modifier la plante</h1>
      <div class="flex gap-2">
        <button type="submit" form="plant-form" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium">Modifier</button>
        <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Retour fiche</a>
      </div>
    </header>

    <x-plant-form :plant="$plant" :categories="$categories" :tags="$tags" />
  </div>
@endsection

@section('extra-scripts')
  <script src="{{ asset('js/form-validation.js') }}"></script>
  <script src="{{ asset('js/file-preview.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
@endsection