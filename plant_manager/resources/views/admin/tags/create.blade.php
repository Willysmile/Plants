@extends('layouts.app')

@section('title', 'Créer un Tag')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Header -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Créer un nouveau Tag</h1>
        <p class="text-gray-600 mt-2">Ajoutez un nouveau tag à votre catalogue</p>
    </header>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <form method="POST" action="{{ route('tags.store') }}" class="space-y-6">
            @csrf

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Nom du Tag <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}"
                       placeholder="Ex: Facile d'entretien"
                       required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Field -->
            <div>
                <label for="tag_category_id" class="block text-sm font-semibold text-gray-900 mb-2">
                    Catégorie <span class="text-red-600">*</span>
                </label>
                <select id="tag_category_id" 
                        name="tag_category_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tag_category_id') border-red-500 @enderror"
                        required>
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('tag_category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('tag_category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                    ✅ Créer le Tag
                </button>
                <a href="{{ route('tags.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                    ❌ Annuler
                </a>
            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-sm font-semibold text-blue-900 mb-2">💡 Conseils</h3>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• Le nom du tag doit être unique et clair</li>
            <li>• Sélectionnez la catégorie la plus pertinente</li>
            <li>• Utilisez des noms courts et descriptifs</li>
        </ul>
    </div>
</div>
@endsection
