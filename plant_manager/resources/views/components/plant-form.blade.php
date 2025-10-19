@props(['plant' => null, 'categories' => [], 'tags' => []])

@php
  $isEdit = $plant !== null;
  $action = $isEdit ? route('plants.update', $plant) : route('plants.store');
  $method = $isEdit ? 'PATCH' : 'POST';
  $submitText = $isEdit ? 'Modifier' : 'Créer';
@endphp

<form id="plant-form" action="{{ $action }}" method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
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

    <!-- Famille -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Famille</label>
      <input name="family" value="{{ old('family', $plant->family ?? '') }}" placeholder="Ex: Orchidaceae" class="mt-1 block w-full border rounded p-2 @error('family') border-red-500 @enderror">
      @error('family') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Sous-famille -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Sous-famille</label>
      <input name="subfamily" value="{{ old('subfamily', $plant->subfamily ?? '') }}" placeholder="Ex: Epidendroideae" class="mt-1 block w-full border rounded p-2 @error('subfamily') border-red-500 @enderror">
      @error('subfamily') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Genre (Genus) -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Genre</label>
      <input name="genus" value="{{ old('genus', $plant->genus ?? '') }}" placeholder="Ex: Phalaenopsis" class="mt-1 block w-full border rounded p-2 @error('genus') border-red-500 @enderror">
      @error('genus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Espèce -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Espèce</label>
      <input name="species" value="{{ old('species', $plant->species ?? '') }}" placeholder="Ex: amabilis" class="mt-1 block w-full border rounded p-2 @error('species') border-red-500 @enderror">
      @error('species') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Sous-espèce -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Sous-espèce</label>
      <input name="subspecies" value="{{ old('subspecies', $plant->subspecies ?? '') }}" placeholder="Facultatif" class="mt-1 block w-full border rounded p-2 @error('subspecies') border-red-500 @enderror">
      @error('subspecies') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Variété -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Variété</label>
      <input name="variety" value="{{ old('variety', $plant->variety ?? '') }}" placeholder="Facultatif" class="mt-1 block w-full border rounded p-2 @error('variety') border-red-500 @enderror">
      @error('variety') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Cultivar -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Cultivar</label>
      <input name="cultivar" value="{{ old('cultivar', $plant->cultivar ?? '') }}" placeholder="Ex: White Dream" class="mt-1 block w-full border rounded p-2 @error('cultivar') border-red-500 @enderror">
      @error('cultivar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Nom scientifique (auto-généré, lecture seule pour info) -->
    @if($plant)
      <div class="md:col-span-2 bg-gray-50 p-3 rounded border border-gray-200">
        <p class="text-sm text-gray-600">
          <strong>Nom complet généré:</strong> 
          <span class="italic">{{ $plant->full_name ?? '—' }}</span>
        </p>
      </div>
    @endif

    <!-- Référence (éditable manuellement) -->
    <div class="md:col-span-2">
      <div class="flex justify-between items-center mb-1">
        <label class="block text-sm font-medium text-gray-700">Référence</label>
        @if($plant && $plant->reference)
          <span class="text-xs text-gray-500">Actuelle: <code class="bg-gray-100 px-2 py-1 rounded">{{ $plant->reference }}</code></span>
        @endif
      </div>
      <div class="flex gap-2">
        <input type="text" 
               name="reference" 
               value="{{ old('reference', $plant->reference ?? '') }}" 
               placeholder="Ex: FAM-001"
               class="flex-1 border rounded p-2 @error('reference') border-red-500 @enderror">
        @if($plant)
          <button type="button" 
                  onclick="regenerateReference()" 
                  class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded transition">
            🔄 Régénérer
          </button>
        @endif
      </div>
      <p class="text-xs text-gray-500 mt-1">Format: FAMILLE-NUM (ex: Orchidaceae-001). Laissez vide pour auto-génération.</p>
      @error('reference') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Description -->
    <div class="md:col-span-2">
      <div class="flex justify-between items-center mb-1">
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <span id="char-count" class="text-sm text-gray-500">0/200</span>
      </div>
      <textarea 
        id="description-input"
        name="description" 
        rows="4" 
        maxlength="200"
        class="mt-1 block w-full border rounded p-2 @error('description') border-red-500 @enderror"
      >{{ old('description', $plant->description ?? '') }}</textarea>
      @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Tags -->
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700">Tags (Ctrl/Cmd pour multi)</label>
      <select name="tags[]" multiple class="mt-1 block w-full border rounded p-2 @error('tags') border-red-500 @enderror" style="padding: 8px 6px;">
        @php
          $tagsByCategory = $tags->groupBy('category');
        @endphp
        @foreach($tagsByCategory as $category => $categoryTags)
          <optgroup label="{{ $category ?? 'Sans catégorie' }}" style="font-weight: bold; background-color: #f3f4f6; color: #374151;">
            @foreach($categoryTags as $tag)
              <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $plant?->tags?->pluck('id')->toArray() ?? []))) style="padding-left: 20px; background-color: white; color: #1f2937;">{{ $tag->name }}</option>
            @endforeach
          </optgroup>
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

    <!-- Lieu d'achat -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Lieu d'achat</label>
      <input type="text" 
             name="purchase_place" 
             value="{{ old('purchase_place', $plant?->purchase_place ?? '') }}" 
             placeholder="Ex: Pépinière, Jardinerie, Marché..."
             class="mt-1 block w-full border rounded p-2 @error('purchase_place') border-red-500 @enderror">
      @error('purchase_place') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Emplacement actuel -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Emplacement actuel</label>
      <input type="text" 
             name="location" 
             value="{{ old('location', $plant?->location ?? '') }}" 
             placeholder="Ex: Fenêtre salon, Salle de bain..."
             class="mt-1 block w-full border rounded p-2 @error('location') border-red-500 @enderror">
      @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const textarea = document.getElementById('description-input');
  const charCount = document.getElementById('char-count');
  
  // Initialiser le compteur avec le contenu existant
  function updateCharCount() {
    const count = textarea.value.length;
    charCount.textContent = count + '/200';
    
    // Changer la couleur en fonction du nombre de caractères
    if (count > 180) {
      charCount.classList.remove('text-gray-500');
      charCount.classList.add('text-red-500', 'font-medium');
    } else if (count > 150) {
      charCount.classList.remove('text-gray-500', 'text-red-500');
      charCount.classList.add('text-orange-500');
    } else {
      charCount.classList.remove('text-orange-500', 'text-red-500', 'font-medium');
      charCount.classList.add('text-gray-500');
    }
  }
  
  // Mettre à jour le compteur au chargement
  updateCharCount();
  
  // Mettre à jour le compteur à chaque changement
  textarea.addEventListener('input', updateCharCount);
  textarea.addEventListener('keyup', updateCharCount);
});

// Fonction pour régénérer la référence
window.regenerateReference = function() {
  const familyInput = document.querySelector('input[name="family"]');
  const referenceInput = document.querySelector('input[name="reference"]');
  const btn = event.target;
  
  const family = familyInput.value;
  
  if (!family) {
    alert('Veuillez d\'abord remplir le champ "Famille"');
    return;
  }
  
  // Désactiver le bouton
  btn.disabled = true;
  btn.textContent = '⏳ Génération...';
  
  // Appeler l'API
  fetch('{{ route("plants.generate-reference") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ family: family })
  })
  .then(response => response.json())
  .then(data => {
    if (data.reference) {
      referenceInput.value = data.reference;
      
      // Afficher une notification de succès
      btn.textContent = '✓ Référence générée!';
      btn.classList.add('bg-green-500', 'hover:bg-green-600');
      btn.classList.remove('bg-gray-400', 'hover:bg-gray-500');
      
      setTimeout(() => {
        btn.textContent = '🔄 Régénérer';
        btn.classList.remove('bg-green-500', 'hover:bg-green-600');
        btn.classList.add('bg-gray-400', 'hover:bg-gray-500');
        btn.disabled = false;
      }, 2000);
    } else if (data.error) {
      alert('Erreur: ' + data.error);
      btn.textContent = '🔄 Régénérer';
      btn.disabled = false;
    }
  })
  .catch(error => {
    console.error('Erreur:', error);
    alert('Erreur lors de la génération');
    btn.textContent = '🔄 Régénérer';
    btn.disabled = false;
  });
};
</script>
@endpush
