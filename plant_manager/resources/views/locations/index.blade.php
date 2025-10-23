@extends('layouts.app')

@section('title', 'Emplacements')

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-semibold">📍 Emplacements</h1>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 border border-blue-200">{{ $locations->total() }}</span>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('locations.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">+ Ajouter Emplacement</a>
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

    <!-- Tableau des emplacements -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nom</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pièce</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Lumière</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Plantes</th>
            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($locations as $location)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">
                <a href="{{ route('locations.show', $location) }}" class="text-blue-600 hover:underline font-medium">
                  {{ $location->name }}
                </a>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $location->room ?? '—' }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $location->light_level ?? '—' }}</td>
              <td class="px-6 py-4 text-sm">
                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                  {{ $location->plants_count }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('locations.edit', $location) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium mr-3">
                  ✏️ Éditer
                </a>
                <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
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
                Aucun emplacement créé. <a href="{{ route('locations.create') }}" class="text-blue-600 hover:underline">Créer le premier</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $locations->links() }}
    </div>
  </div>
@endsection
