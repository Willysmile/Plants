@extends('layouts.simple')

@section('title', 'Nouvel arrosage - ' . $plant->name)

@section('content')
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center mb-6">
      <a href="{{ route('plants.watering-history.index', $plant) }}" class="text-blue-500 hover:text-blue-700">
        ← Retour
      </a>
      <h1 class="text-3xl font-bold text-gray-900 ml-4">Nouvel arrosage</h1>
    </div>

    <x-history-form :plant="$plant" type="watering" />
  </div>
@endsection
