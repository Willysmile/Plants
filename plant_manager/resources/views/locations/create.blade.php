@extends('layouts.app')

@section('title', 'Créer Emplacement')

@section('content')
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">📍 Créer un Emplacement</h1>

    <form action="{{ route('locations.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
      @csrf

      <!-- Nom -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
        <input type="text" name="name" required value="{{ old('name') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Pièce -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Pièce</label>
        <input type="text" name="room" placeholder="Ex: Salon, Cuisine, Chambre..." value="{{ old('room') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('room') border-red-500 @enderror">
        @error('room') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Niveau de lumière -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Niveau de Lumière</label>
        <select name="light_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('light_level') border-red-500 @enderror">
          <option value="">-- Sélectionner --</option>
          <option value="Plein soleil" @selected(old('light_level') == 'Plein soleil')>☀️ Plein soleil</option>
          <option value="Lumière vive" @selected(old('light_level') == 'Lumière vive')>🌞 Lumière vive</option>
          <option value="Lumière indirecte" @selected(old('light_level') == 'Lumière indirecte')>🌤️ Lumière indirecte</option>
          <option value="Modérée" @selected(old('light_level') == 'Modérée')>⛅ Modérée</option>
          <option value="Faible" @selected(old('light_level') == 'Faible')>🌑 Faible</option>
          <option value="Très faible" @selected(old('light_level') == 'Très faible')>🌙 Très faible</option>
          <option value="Artificielle" @selected(old('light_level') == 'Artificielle')>💡 Artificielle</option>
        </select>
        @error('light_level') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Humidité -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Humidité (%) <span class="text-gray-500 text-xs">(0-100)</span></label>
        <input type="number" name="humidity_level" min="0" max="100" value="{{ old('humidity_level') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('humidity_level') border-red-500 @enderror">
        @error('humidity_level') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Température -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Température (°C) <span class="text-gray-500 text-xs">(-50 à +50)</span></label>
        <input type="number" name="temperature" step="0.1" min="-50" max="50" value="{{ old('temperature') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('temperature') border-red-500 @enderror">
        @error('temperature') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Boutons -->
      <div class="flex gap-3 pt-4 border-t">
        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
          ✅ Créer
        </button>
        <a href="{{ route('locations.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
          Annuler
        </a>
      </div>
    </form>
  </div>
@endsection
