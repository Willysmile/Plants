@extends('layouts.app')

@section('title', $plant->name . ' - Historique de rempotage')

@section('content')
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">{{ $plant->name }}</h1>
      <p class="text-gray-600">Historique de rempotage</p>
    </div>
    <div class="space-x-3">
      <a href="{{ route('plants.show', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Retour
      </a>
      <a href="{{ route('plants.repotting-history.create', $plant) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        + Nouveau rempotage
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  <x-history-list :plant="$plant" :histories="$histories" type="repotting" />
@endsection
