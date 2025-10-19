@props(['plant' => null, 'categories' => [], 'tags' => []])

@php
  $isEdit = $plant !== null;
  $action = $isEdit ? route('plants.update', $plant) : route('plants.store');
  $method = $isEdit ? 'PATCH' : 'POST';
  $submitText = $isEdit ? 'Modifier' : 'Créer';
@endphp

<form action="{{ $action }}" method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
  @csrf
  @if($isEdit) @method($method) @endif

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Nom -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Nom *</label>
      <input name="name" value="{{ old('name', $plant->name ?? '') }}" class="mt-1 block w-full border rounded p-2 @error('name') border-red-500 @enderror" required>
      @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Nom scientifique -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Nom scientifique</label>
      <input name="scientific_name" value="{{ old('scientific_name', $plant->scientific_name ?? '') }}" class="mt-1 block w-full border rounded p-2 @error('scientific_name') border-red-500 @enderror">
      @error('scientific_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Description -->
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700">Description</label>
      <textarea name="description" rows="4" class="mt-1 block w-full border rounded p-2 @error('description') border-red-500 @enderror">{{ old('description', $plant->description ?? '') }}</textarea>
      @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Catégorie -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Catégorie</label>
      <select name="category_id" class="mt-1 block w-full border rounded p-2 @error('category_id') border-red-500 @enderror">
        <option value="">— Sélectionner —</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" @selected(old('category_id', $plant->category_id ?? null)==$cat->id)>{{ $cat->name }}</option>
        @endforeach
      </select>
      @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Tags -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Tags (Ctrl/Cmd pour multi)</label>
      <select name="tags[]" multiple class="mt-1 block w-full border rounded p-2 @error('tags') border-red-500 @enderror">
        @foreach($tags as $tag)
          <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $plant?->tags?->pluck('id')->toArray() ?? [])))>{{ $tag->name }}</option>
        @endforeach
      </select>
      @error('tags') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Arrosage -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Arrosage</label>
      <select name="watering_frequency" class="mt-1 block w-full border rounded p-2 @error('watering_frequency') border-red-500 @enderror">
        <option value="">— Sélectionner —</option>
        @foreach(\App\Models\Plant::$wateringLabels as $key => $label)
          <option value="{{ $key }}" @selected((string) old('watering_frequency', $plant->watering_frequency ?? null) === (string)$key)>{{ $label }}</option>
        @endforeach
      </select>
      @error('watering_frequency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Lumière -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Lumière</label>
      <select name="light_requirement" class="mt-1 block w-full border rounded p-2 @error('light_requirement') border-red-500 @enderror">
        <option value="">— Sélectionner —</option>
        @foreach(\App\Models\Plant::$lightLabels as $key => $label)
          <option value="{{ $key }}" @selected((string) old('light_requirement', $plant->light_requirement ?? null) === (string)$key)>{{ $label }}</option>
        @endforeach
      </select>
      @error('light_requirement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Température min -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Température min (°C)</label>
      <input type="number" step="0.1" name="temperature_min" value="{{ old('temperature_min', $plant->temperature_min ?? '') }}" class="mt-1 block w-full border rounded p-2 @error('temperature_min') border-red-500 @enderror">
      @error('temperature_min') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Température max -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Température max (°C)</label>
      <input type="number" step="0.1" name="temperature_max" value="{{ old('temperature_max', $plant->temperature_max ?? '') }}" class="mt-1 block w-full border rounded p-2 @error('temperature_max') border-red-500 @enderror">
      @error('temperature_max') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Humidité -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Humidité (%) - Max 100%</label>
      <input type="number" step="1" min="0" max="100" name="humidity_level" value="{{ old('humidity_level', $plant->humidity_level ?? '') }}" class="mt-1 block w-full border rounded p-2 @error('humidity_level') border-red-500 @enderror">
      @error('humidity_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Date d'achat -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Date d'achat (pas future)</label>
      <input type="text" 
             name="purchase_date" 
             value="{{ old('purchase_date', $plant?->purchase_date ?? '') }}" 
             placeholder="jj/mm/aaaa ou mm/aaaa"
             class="mt-1 block w-full border rounded p-2 @error('purchase_date') border-red-500 @enderror"
             id="purchaseDateInput">
      <p class="text-xs text-gray-500 mt-1">Format: jj/mm/aaaa (ex: 15/07/2021) ou mm/aaaa (ex: 07/2021)</p>
      @error('purchase_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Notes -->
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700">Notes</label>
      <textarea name="notes" rows="3" class="mt-1 block w-full border rounded p-2 @error('notes') border-red-500 @enderror">{{ old('notes', $plant->notes ?? '') }}</textarea>
      @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
  </div>

  <!-- Photo principale -->
  <div>
    <h3 class="text-lg font-medium mb-2">Photo principale</h3>
    @if($plant?->main_photo)
      <div class="mb-2">
        <img src="{{ Storage::url($plant->main_photo) }}" alt="" class="w-48 h-48 object-cover rounded shadow">
      </div>
    @endif
    <input type="file" name="main_photo" accept="image/*" id="mainPhotoInput" class="block @error('main_photo') border-red-500 @enderror">
    <div id="mainPhotoPreview" class="mt-2"></div>
    <p class="text-xs text-gray-500 mt-1">{{ $isEdit ? 'Remplacer la photo principale (optionnel).' : 'Sélectionner une photo.' }}</p>
    @error('main_photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
  </div>

  <!-- Galerie -->
  <div>
    <h3 class="text-lg font-medium mb-2">Ajouter des photos (galerie)</h3>
    <input type="file" name="photos[]" accept="image/*" multiple id="galleryPhotosInput" class="block @error('photos') border-red-500 @enderror">
    <div id="galleryPhotosPreview" class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2"></div>
    <p class="text-xs text-gray-500 mt-1">Les nouvelles images seront ajoutées à la galerie.</p>
    @error('photos') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
  </div>

  <!-- Boutons -->
  <div class="flex gap-2 mt-4">
    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">{{ $submitText }}</button>
    <a href="{{ $isEdit ? route('plants.show', $plant) : route('plants.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Annuler</a>
  </div>
</form>
