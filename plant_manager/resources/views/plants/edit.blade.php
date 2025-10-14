<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Modifier : {{ $plant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Modifier la plante</h1>
      <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-200 rounded">Retour fiche</a>
    </header>

    <form action="{{ route('plants.update', $plant) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
      @csrf
      @method('PATCH')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nom</label>
          <input name="name" value="{{ old('name', $plant->name) }}" class="mt-1 block w-full border rounded p-2" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Nom scientifique</label>
          <input name="scientific_name" value="{{ old('scientific_name', $plant->scientific_name) }}" class="mt-1 block w-full border rounded p-2">
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea name="description" rows="4" class="mt-1 block w-full border rounded p-2">{{ old('description', $plant->description) }}</textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Catégorie</label>
          <select name="category_id" class="mt-1 block w-full border rounded p-2">
            <option value="">— Aucun —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" @selected(old('category_id', $plant->category_id)==$cat->id)>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Tags (Ctrl/Cmd pour multi)</label>
          <select name="tags[]" multiple class="mt-1 block w-full border rounded p-2">
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $plant->tags->pluck('id')->toArray())))>{{ $tag->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Arrosage</label>
          <select name="watering_frequency" class="mt-1 block w-full border rounded p-2">
            <option value="">— Sélectionner —</option>
            @foreach(\App\Models\Plant::$wateringLabels as $key => $label)
              <option value="{{ $key }}" @selected((string) old('watering_frequency', $plant->watering_frequency) === (string)$key)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Lumière</label>
          <select name="light_requirement" class="mt-1 block w-full border rounded p-2">
            <option value="">— Sélectionner —</option>
            @foreach(\App\Models\Plant::$lightLabels as $key => $label)
              <option value="{{ $key }}" @selected((string) old('light_requirement', $plant->light_requirement) === (string)$key)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div>
        <h3 class="text-lg font-medium mb-2">Photo principale</h3>
        @if($plant->main_photo)
          <div class="mb-2">
            <img src="{{ Storage::url($plant->main_photo) }}" alt="" class="w-48 h-48 object-cover rounded shadow">
          </div>
        @endif
        <input type="file" name="main_photo" accept="image/*" class="block">
        <p class="text-xs text-gray-500 mt-1">Remplacer la photo principale (optionnel).</p>
      </div>

      <div>
        <h3 class="text-lg font-medium mb-2">Ajouter des photos (galerie)</h3>
        <input type="file" name="photos[]" accept="image/*" multiple class="block">
        <p class="text-xs text-gray-500 mt-1">Les nouvelles images seront ajoutées à la galerie.</p>
      </div>

      <div>
        <h3 class="text-lg font-medium">Galerie & légendes</h3>
        <p class="text-xs text-gray-500 mb-3">Modifie les légendes ci‑dessous. Pour supprimer une photo, coche "Supprimer".</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          @foreach($plant->photos as $photo)
            <div class="bg-gray-50 p-3 rounded shadow-sm">
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