@extends('layouts.app')

@section('title', 'Créer Lieu d\'Achat')

@section('content')
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">🛒 Créer un Lieu d'Achat</h1>

    <form action="{{ route('purchase-places.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
      @csrf

      <!-- Nom -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
        <input type="text" name="name" required value="{{ old('name') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('name') border-red-500 @enderror">
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Type -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('type') border-red-500 @enderror">
          <option value="">-- Sélectionner --</option>
          <option value="Pépinière" @selected(old('type') == 'Pépinière')>🌱 Pépinière</option>
          <option value="Jardinerie" @selected(old('type') == 'Jardinerie')>🌿 Jardinerie</option>
          <option value="Marché" @selected(old('type') == 'Marché')>🛍️ Marché</option>
          <option value="Supermarché" @selected(old('type') == 'Supermarché')>🏬 Supermarché</option>
          <option value="En ligne" @selected(old('type') == 'En ligne')>💻 En ligne</option>
          <option value="Ami/Famille" @selected(old('type') == 'Ami/Famille')>👥 Ami/Famille</option>
          <option value="Autre" @selected(old('type') == 'Autre')>❓ Autre</option>
        </select>
        @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Adresse -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
        <input type="text" name="address" placeholder="Adresse complète" value="{{ old('address') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('address') border-red-500 @enderror">
        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Téléphone -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
        <input type="tel" name="phone" placeholder="01 23 45 67 89" value="{{ old('phone') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('phone') border-red-500 @enderror">
        @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Website -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Site Web</label>
        <input type="url" name="website" placeholder="https://exemple.com" value="{{ old('website') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('website') border-red-500 @enderror">
        @error('website') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Boutons -->
      <div class="flex gap-3 pt-4 border-t">
        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
          ✅ Créer
        </button>
        <a href="{{ route('purchase-places.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
          Annuler
        </a>
      </div>
    </form>
  </div>
@endsection
