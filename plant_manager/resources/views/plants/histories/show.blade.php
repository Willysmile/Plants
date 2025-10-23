@extends('layouts.app')

@section('title', 'Info Diverse')

@section('content')
  <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Info Diverse</h1>
      <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Retour
      </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Description</label>
        <p class="text-gray-800">{{ $plantHistory->body }}</p>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Date</label>
        <p class="text-gray-600">{{ $plantHistory->created_at->format('d/m/Y à H:i') }}</p>
      </div>

      <div class="flex space-x-3">
        <a href="{{ route('plants.histories.edit', $plantHistory) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
          Éditer
        </a>
        <form action="{{ route('plants.histories.destroy', $plantHistory) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Supprimer
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
