@extends('layouts.app')

@section('title', 'Gestion des Tags')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Tags</h1>
            <p class="text-gray-600 mt-2">Gérez les tags pour organiser vos plantes</p>
        </div>
        <a href="{{ route('tags.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            ➕ Nouveau Tag
        </a>
    </header>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tags by Category -->
    <div class="space-y-6">
        @forelse($tags as $category => $categoryTags)
            <section class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Category Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center justify-between">
                        <span>{{ $category }}</span>
                        <span class="bg-white/30 px-3 py-1 rounded text-sm font-semibold">
                            {{ count($categoryTags) }}
                        </span>
                    </h2>
                </div>

                <!-- Tags List -->
                <div class="divide-y">
                    @foreach($categoryTags as $tag)
                        <div class="p-6 flex justify-between items-center hover:bg-gray-50 transition">
                            <div class="flex-1">
                                <p class="text-lg font-semibold text-gray-900">{{ $tag->name }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $category }}</p>
                            </div>
                            
                            <div class="flex gap-2 ml-4">
                                <a href="{{ route('tags.edit', $tag) }}" 
                                   class="inline-flex items-center gap-2 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition">
                                    ✏️ Éditer
                                </a>
                                
                                <form method="POST" action="{{ route('tags.destroy', $tag) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-2 bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?');">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
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
