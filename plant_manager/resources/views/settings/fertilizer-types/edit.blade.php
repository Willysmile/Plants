@extends('layouts.simple')

@section('title', 'Éditer - ' . $fertilizerType->name)

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
  <div class="flex items-center mb-6">
    <a href="{{ route('fertilizer-types.index') }}" class="text-blue-500 hover:text-blue-700">
      ← Retour
    </a>
    <h1 class="text-3xl font-bold text-gray-900 ml-4">Éditer {{ $fertilizerType->name }}</h1>
  </div>

  <form action="{{ route('fertilizer-types.update', $fertilizerType) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label for="name" class="block text-gray-700 font-bold mb-2">
        Nom <span class="text-red-500">*</span>
      </label>
      <input type="text" 
        id="name" 
        name="name" 
        value="{{ old('name', $fertilizerType->name) }}" 
        placeholder="Ex: NPK, Organique, Minéral..."
        required
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror">
      @error('name')
        <span class="text-red-500 text-sm">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label for="unit" class="block text-gray-700 font-bold mb-2">
        Unité <span class="text-red-500">*</span>
      </label>
      <select id="unit" 
        name="unit" 
        required
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('unit') border-red-500 @enderror">
        <option value="">-- Sélectionner --</option>
        <option value="ml" @selected(old('unit', $fertilizerType->unit) === 'ml')>ml (millilitres)</option>
        <option value="g" @selected(old('unit', $fertilizerType->unit) === 'g')>g (grammes)</option>
        <option value="nombre" @selected(old('unit', $fertilizerType->unit) === 'nombre')>Nombre (cuillères, gouttes, etc.)</option>
      </select>
      @error('unit')
        <span class="text-red-500 text-sm">{{ $message }}</span>
      @enderror
    </div>

    <div>
      <label for="description" class="block text-gray-700 font-bold mb-2">
        Description
      </label>
      <textarea id="description" 
        name="description" 
        placeholder="Description détaillée du type d'engrais..."
        rows="4"
        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $fertilizerType->description) }}</textarea>
      @error('description')
        <span class="text-red-500 text-sm">{{ $message }}</span>
      @enderror
    </div>

    <div class="flex gap-2 pt-4">
      <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
        Mettre à jour
      </button>
      <a href="{{ route('fertilizer-types.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded">
        Annuler
      </a>
    </div>
  </form>
</div>
@endsection
