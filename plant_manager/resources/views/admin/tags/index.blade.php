@extends('layouts.app')

@section('title', 'Gestion des Tags')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="{ openTagModal: false, openCategoryModal: false }">
    <!-- Header -->
    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Tags</h1>
            <p class="text-gray-600 mt-2">Gérez les tags pour organiser vos plantes</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->is_admin)
                <button @click="openCategoryModal = true" class="bg-amber-500  text-white px-4 py-2 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    Gérer les catégories
                </button>
            @endif
            <button @click="openTagModal = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition font-semibold shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Nouveau Tag
            </button>
        </div>
    </header>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Modal Backdrop -->
    <div x-show="openTagModal" class="fixed inset-0 bg-black/50 z-40" @click="openTagModal = false"></div>
    <div x-show="openCategoryModal" class="fixed inset-0 bg-black/50 z-40" @click="openCategoryModal = false"></div>

    <!-- Modal: Create New Tag -->
    <div x-show="openTagModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full" @click.stop>
            <!-- Modal Header -->
            <div class="bg-green-600 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Créer un nouveau Tag</h2>
                <button @click="openTagModal = false" class="text-white hover:text-gray-100 transition">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form method="POST" action="{{ route('tags.store') }}" class="p-6 space-y-4">
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
                           placeholder="Ex: Facile d'entretien"
                           required
                           autofocus>
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
                            name="tag_category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tag_category_id') border-red-500 @enderror"
                            required>
                        <option value="">-- Sélectionner une catégorie --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('tag_category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        ✅ Créer
                    </button>
                    <button type="button" @click="openTagModal = false" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">
                        ❌ Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

        <!-- Modal: Manage Categories (Admin Only) -->
    <div x-show="openCategoryModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl h-[70vh] overflow-hidden flex flex-col" @click.stop>
            <!-- Modal Header -->
            <div class="bg-amber-500 text-white px-6 py-3 flex justify-between items-center flex-shrink-0">
                <h2 class="text-lg font-bold">Gestion des Catégories</h2>
                <button @click="openCategoryModal = false" class="text-white hover:text-gray-100 transition">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex-grow overflow-y-auto flex gap-4 p-4">
                <!-- Left: New Category Form -->
                <div class="w-1/3 flex-shrink-0 border-r pr-4">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Créer une catégorie</h3>
                    <form method="POST" action="{{ route('tags.store-category') }}" class="space-y-3">
                        @csrf
                        
                        <div>
                            <label for="cat-name" class="block text-xs font-medium text-gray-700 mb-1">
                                Nom <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="cat-name" 
                                   name="name" 
                                   class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Ex: Floraison"
                                   required
                                   autofocus>
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cat-desc" class="block text-xs font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea id="cat-desc" 
                                      name="description" 
                                      class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Description optionnelle"
                                      rows="2"></textarea>
                            @error('description')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-amber-500 text-white px-3 py-1.5 rounded-lg hover:bg-amber-600 transition font-semibold text-xs">
                            ✅ Créer
                        </button>
                    </form>
                </div>

                <!-- Right: Categories List -->
                <div class="flex-grow overflow-y-auto">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Catégories existantes</h3>
                    <div class="space-y-1.5">
                        @forelse($categories as $category)
                            @php
                                $count = $category->tags()->count();
                            @endphp
                            <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded border border-gray-200 hover:bg-gray-100 transition text-xs">
                                <div class="flex-grow">
                                    <p class="font-semibold text-gray-900">{{ $category->name }}</p>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    @if($count === 0)
                                        <span class="text-orange-600 font-semibold text-xs">Aucun tag</span>
                                        <form method="POST" action="{{ route('tags.destroy-category', $category) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-1.5 py-0.5 rounded hover:bg-red-600 transition text-xs font-semibold" onclick="return confirm('Supprimer cette catégorie vide ?')">
                                                🗑️
                                            </button>
                                        </form>
                                    @else
                                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">{{ $count }} tag{{ $count > 1 ? 's' : '' }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm">Aucune catégorie trouvée</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-2 border-t flex justify-end flex-shrink-0">
                <button @click="openCategoryModal = false" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 transition font-semibold text-xs">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- Tags by Category - Accordion -->
    <div class="space-y-3">
        @forelse($tags as $category => $categoryTags)
            <div x-data="{ isExpanded: false }" class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                <!-- Category Header Button -->
                <button 
                    @click="isExpanded = !isExpanded" 
                    class="w-full px-6 py-4 bg-blue-500 hover:bg-blue-600 text-white font-bold flex items-center justify-between transition"
                >
                    <span class="text-lg">{{ $category }}</span>
                    <div class="flex items-center gap-3">
                        <span class="bg-blue-700 px-2 py-1 rounded text-xs font-semibold">{{ count($categoryTags) }}</span>
                        <svg :class="isExpanded ? 'rotate-180' : ''" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                </button>

                <!-- Tags Content - Collapsible -->
                <template x-if="isExpanded">
                    <div class="divide-y bg-gray-50 max-h-96 overflow-y-auto">
                        @foreach($categoryTags as $tag)
                            <div class="p-4 flex justify-between items-center hover:bg-white transition">
                                <p class="font-medium text-gray-900">{{ $tag->name }}</p>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('tags.edit', $tag) }}" 
                                       class="inline-flex items-center gap-1 bg-blue-500 text-white px-2 py-1 text-sm rounded hover:bg-blue-600 transition">
                                        ✏️
                                    </a>
                                    
                                    <form method="POST" action="{{ route('tags.destroy', $tag) }}" class="inline" onsubmit="return confirm('Supprimer ce tag ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 bg-red-500 text-white px-2 py-1 text-sm rounded hover:bg-red-600 transition">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>
            </div>
        @empty
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-12 text-center">
                <p class="text-gray-500 text-lg">Aucun tag trouvé</p>
                <a href="{{ route('tags.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold mt-2 inline-block">
                    Créer le premier tag →
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
