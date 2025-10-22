@extends('layouts.app')

@section('title', 'Gestion des Références')

@section('content')
<div class="max-w-6xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-semibold">Gestion des Références</h1>
    <a href="{{ route('settings.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md transition">Retour</a>
  </div>

  <!-- Statistiques -->
  <div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
      <p class="text-sm text-gray-600 font-medium">Références utilisées</p>
      <p class="text-3xl font-bold text-blue-700">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
      <p class="text-sm text-gray-600 font-medium">Familles</p>
      <p class="text-3xl font-bold text-purple-700">{{ $stats['families'] }}</p>
    </div>
    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
      <p class="text-sm text-gray-600 font-medium">Références orphelines</p>
      <p class="text-3xl font-bold text-red-700">{{ $stats['orphaned'] }}</p>
    </div>
  </div>

  <!-- Références par famille -->
  <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="p-4 border-b bg-gray-50">
      <h2 class="text-xl font-semibold text-gray-800">Références par Famille</h2>
    </div>
    
    <div class="divide-y">
      @forelse($referencesByFamily as $family => $plants)
        <div class="p-4">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-medium text-gray-700">{{ $family }}</h3>
            <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $plants->count() }} plantes</span>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            @foreach($plants as $plant)
              <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200">
                <div class="flex-1">
                  <p class="font-mono font-semibold text-gray-800">{{ $plant->reference }}</p>
                  <p class="text-sm text-gray-600">{{ $plant->name }}</p>
                </div>
                <div class="flex gap-2">
                  @if($plant->is_archived)
                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Archivée</span>
                  @endif
                  <a href="{{ route('plants.show', $plant) }}" class="text-blue-600 hover:underline text-sm">Voir</a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @empty
        <div class="p-4 text-center text-gray-500">
          Aucune référence trouvée
        </div>
      @endforelse
    </div>
  </div>

  <!-- Références orphelines -->
  @if(count($orphanedReferences) > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-4 border-b bg-red-50">
        <h2 class="text-xl font-semibold text-red-800">Références Orphelines (à réaffecter)</h2>
        <p class="text-sm text-red-700 mt-1">Ces références existent dans le système mais n'ont pas de plante associée</p>
      </div>
      
      <div class="p-4">
        <div class="grid grid-cols-4 gap-3">
          @foreach($orphanedReferences as $reference)
            <div class="p-3 bg-red-50 rounded border border-red-200 flex items-center justify-between">
              <span class="font-mono font-semibold text-red-700">{{ $reference }}</span>
              <button class="text-xs text-red-600 hover:text-red-800 font-medium" title="Copier">📋</button>
            </div>
          @endforeach
        </div>
        
        <div class="mt-4 p-3 bg-yellow-50 rounded border border-yellow-200">
          <p class="text-sm text-yellow-800">
            💡 <strong>Conseil :</strong> Vous pouvez réaffecter ces références à de nouvelles plantes lors de leur création.
          </p>
        </div>
      </div>
    </div>
  @else
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
      <p class="text-green-800 font-medium">✅ Aucune référence orpheline - système propre !</p>
    </div>
  @endif
</div>
@endsection
