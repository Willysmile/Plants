@extends('layouts.app')

@section('title', 'Éditer Tag')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Éditer le Tag</h1>
        <p class="text-gray-600 mt-2">Modifiez les informations du tag</p>
    </header>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <form method="POST" action="{{ route('tags.update', $tag) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Nom du Tag <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       value="{{ old('name', $tag->name) }}"
                       required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Field -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">
                    Catégorie <span class="text-red-600">*</span>
                </label>
                <select id="category" 
                        name="category" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                        required>
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $tag->category) === $cat)>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">
                    <strong>Créé :</strong> {{ $tag->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="text-sm text-gray-600">
                    <strong>Dernière modification :</strong> {{ $tag->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    ✅ Mettre à jour
                </button>
                <a href="{{ route('tags.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                    ❌ Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
