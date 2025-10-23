@extends('layouts.app')

@section('title', 'Lieux d\'Achat')

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-semibold">🛒 Lieux d'Achat</h1>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 border border-purple-200">{{ $purchasePlaces->total() }}</span>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('purchase-places.create') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded transition">+ Ajouter Lieu d'Achat</a>
      </div>
    </header>

    <!-- Messages Flash -->
    @if($message = session('success'))
      <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded text-green-700 text-sm">
        ✅ {{ $message }}
      </div>
    @endif

    @if($message = session('error'))
      <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
        ❌ {{ $message }}
      </div>
    @endif

    <!-- Tableau des lieux d'achat -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nom</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contact</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Plantes</th>
            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($purchasePlaces as $place)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">
                <a href="{{ route('purchase-places.show', $place) }}" class="text-purple-600 hover:underline font-medium">
                  {{ $place->name }}
                </a>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">
                @if($place->type)
                  <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs">{{ $place->type }}</span>
                @else
                  —
                @endif
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">
                @if($place->phone)
                  📞 {{ $place->phone }}<br>
                @endif
                @if($place->website)
                  🌐 <a href="{{ $place->website }}" target="_blank" class="text-purple-600 hover:underline">Site web</a>
                @endif
              </td>
              <td class="px-6 py-4 text-sm">
                <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                  {{ $place->plants_count }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('purchase-places.edit', $place) }}" class="text-purple-600 hover:text-purple-900 text-sm font-medium mr-3">
                  ✏️ Éditer
                </a>
                <form action="{{ route('purchase-places.destroy', $place) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                    🗑️ Supprimer
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                Aucun lieu d'achat créé. <a href="{{ route('purchase-places.create') }}" class="text-purple-600 hover:underline">Créer le premier</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $purchasePlaces->links() }}
    </div>
  </div>
@endsection
