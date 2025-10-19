@extends('layouts.simple')

@section('title', 'Gestion des types d\'engrais')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('settings.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
        ← Paramètres
      </a>
      <h1 class="text-3xl font-bold text-gray-900">Types d'engrais</h1>
    </div>
    <a href="{{ route('fertilizer-types.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold">
      + Ajouter
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
      {{ session('error') }}
    </div>
  @endif

  @if($fertilizerTypes->count())
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2 text-left font-semibold">Nom</th>
            <th class="border px-4 py-2 text-left font-semibold">Unité</th>
            <th class="border px-4 py-2 text-left font-semibold">Description</th>
            <th class="border px-4 py-2 text-center font-semibold w-24">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($fertilizerTypes as $type)
            <tr class="hover:bg-gray-50">
              <td class="border px-4 py-3 font-medium">{{ $type->name }}</td>
              <td class="border px-4 py-3">
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                  @if($type->unit === 'ml') bg-blue-100 text-blue-800
                  @elseif($type->unit === 'g') bg-amber-100 text-amber-800
                  @else bg-purple-100 text-purple-800
                  @endif
                ">
                  {{ $type->unit }}
                </span>
              </td>
              <td class="border px-4 py-3 text-gray-600 text-sm">
                {{ $type->description ?? '—' }}
              </td>
              <td class="border px-4 py-3 text-center">
                <div class="flex gap-2 justify-center">
                  <a href="{{ route('fertilizer-types.edit', $type) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">
                    Éditer
                  </a>
                  <form action="{{ route('fertilizer-types.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded">
                      Supprimer
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="text-center py-12">
      <p class="text-gray-500 text-lg mb-4">Aucun type d'engrais défini</p>
      <a href="{{ route('fertilizer-types.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold">
        Créer le premier
      </a>
    </div>
  @endif
</div>
@endsection
