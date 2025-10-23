@extends('layouts.app')

@section('title', $plant->name . ' - Infos Diverses')

@section('content')
  <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $plant->name }}</h1>
        <p class="text-gray-600">Infos Diverses</p>
      </div>
      <div class="space-x-3">
        <a href="{{ route('plants.show', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
          Retour
        </a>
        <button onclick="document.getElementById('addHistoryForm').classList.toggle('hidden')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          + Ajouter une info
        </button>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <!-- Form to add history -->
    <div id="addHistoryForm" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">Ajouter une nouvelle info</h2>
      <form action="{{ route('plants.histories.store', $plant) }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="body" class="block text-gray-700 font-bold mb-2">Description</label>
          <textarea 
            name="body" 
            id="body"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Entrez une info..."
            required
            maxlength="144"
          ></textarea>
          <p class="text-sm text-gray-500 mt-1">Max 144 caractères</p>
          @error('body')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="flex space-x-3">
          <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
            Enregistrer
          </button>
          <button type="button" onclick="document.getElementById('addHistoryForm').classList.toggle('hidden')" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
            Annuler
          </button>
        </div>
      </form>
    </div>

    @if($histories->count() > 0)
      <div class="space-y-4">
        @foreach($histories as $history)
          <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <p class="text-gray-800">{{ $history->body }}</p>
                <p class="text-sm text-gray-500 mt-2">
                  {{ $history->created_at->format('d/m/Y à H:i') }}
                </p>
              </div>
              <div class="flex space-x-2">
                <a href="{{ route('plants.histories.edit', [$plant, $history]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-3 rounded text-sm">
                  Éditer
                </a>
                <form action="{{ route('plants.histories.destroy', [$plant, $history]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm">
                    Supprimer
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-6">
        {{ $histories->links() }}
      </div>
    @else
      <div class="bg-gray-100 rounded-lg p-6 text-center">
        <p class="text-gray-600">Aucune info pour le moment</p>
      </div>
    @endif
  </div>
@endsection
