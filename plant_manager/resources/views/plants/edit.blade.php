@extends('layouts.app')

@section('title', 'Modifier : ' . $plant->name)

@section('content')
  <div class="max-w-4xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Modifier la plante</h1>
      <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-200 rounded">Retour fiche</a>
    </header>

    <x-plant-form :plant="$plant" :categories="$categories" :tags="$tags" />
  </div>
@endsection
              <img src="{{ Storage::url($photo->filename) }}" alt="" class="w-full h-32 object-cover rounded mb-2">
              <label class="block text-xs text-gray-600 mb-1">Légende</label>
              <textarea name="photo_descriptions[{{ $photo->id }}]" rows="2" maxlength="1000" class="w-full border p-2 rounded text-sm">{{ old('photo_descriptions.'.$photo->id, $photo->description) }}</textarea>
              <label class="inline-flex items-center text-xs text-red-600 mt-2">
                <input type="checkbox" name="photo_delete[{{ $photo->id }}]" value="1" class="mr-2"> Supprimer
              </label>
            </div>
          @endforeach
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500">
          <em>Les légendes sont limitées à 1000 caractères.</em>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('plants.show', $plant) }}" class="px-3 py-2 bg-gray-200 rounded">Annuler</a>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Enregistrer</button>
        </div>
      </div>
    </form>
  </div>
</body>
</html>