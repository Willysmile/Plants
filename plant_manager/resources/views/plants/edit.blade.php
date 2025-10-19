@extends('layouts.app')

@section('title', 'Modifier : ' . $plant->name)

@section('content')
  <div class="max-w-4xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Modifier la plante</h1>
      <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-200 rounded">Retour fiche</a>
    </header>

    <x-plant-form :plant="$plant" :categories="$categories" :tags="$tags" />
  </div>
@endsection