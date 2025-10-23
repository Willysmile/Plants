@extends('layouts.app')

@section('title', 'Éditer Info Diverse')

@section('content')
  <div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
      <a href="{{ url()->previous() }}" class="text-blue-500 hover:text-blue-700">
        ← Retour
      </a>
      <h1 class="text-3xl font-bold text-gray-900 ml-4">Éditer Info Diverse</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <form action="{{ route('plants.histories.update', $plantHistory) }}" method="POST">
        @csrf
        @method('PUT')
        
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
          >{{ old('body', $plantHistory->body) }}</textarea>
          <p class="text-sm text-gray-500 mt-1">Max 144 caractères</p>
          @error('body')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex space-x-3">
          <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
            Enregistrer
          </button>
          <a href="{{ url()->previous() }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
            Annuler
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
