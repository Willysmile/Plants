@props(['plant' => null, 'categories' => [], 'tags' => [], 'locations' => [], 'purchasePlaces' => []])

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
                  onclick="regenerateReference(this)" 
                  class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded transition">
            🔄 Régénérer
          </button>
        @endif
      </div>
      <p class="text-xs text-gray-500 mt-1">Format: FAMILLE-NUM (ex: Orchidaceae-001). Laissez vide pour auto-génération.</p>
      @error('reference') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Tags -->
    <div class="md:col-span-2">
      @php
        $selectedTagIds = old('tags', $plant?->tags?->pluck('id')->toArray() ?? []);
        $tagCount = count($selectedTagIds);
      @endphp
      <div class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Tags:</label>
        <div id="tags-display" class="flex flex-wrap gap-2">
          <!-- Les tags s'affichent ici -->
        </div>
        <button type="button"
                onclick="document.getElementById('tags-modal').style.display = 'flex'"
                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs font-medium">
          @if($tagCount > 0) Modifier @else + Ajouter @endif
        </button>
      </div>
    </div>
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
      <select name="purchase_place_id" class="mt-1 block w-full border rounded p-2 @error('purchase_place_id') border-red-500 @enderror">
        <option value="">— Sélectionner un lieu —</option>
        @foreach($purchasePlaces as $pp)
          <option value="{{ $pp->id }}" 
                  @selected((int) old('purchase_place_id', $plant?->purchase_place_id ?? null) === (int)$pp->id)>
            {{ $pp->name }}
          </option>
        @endforeach
      </select>
      @error('purchase_place_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Emplacement actuel -->
    <div>
      <label class="block text-sm font-medium text-gray-700">Emplacement actuel</label>
      <select name="location_id" class="mt-1 block w-full border rounded p-2 @error('location_id') border-red-500 @enderror">
        <option value="">— Sélectionner un emplacement —</option>
        @foreach($locations as $loc)
          <option value="{{ $loc->id }}" 
                  @selected((int) old('location_id', $plant?->location_id ?? null) === (int)$loc->id)>
            {{ $loc->name }}
          </option>
        @endforeach
      </select>
      @error('location_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Notes -->
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700">Notes</label>
      <textarea name="notes" rows="3" class="mt-1 block w-full border rounded p-2 @error('notes') border-red-500 @enderror">{{ old('notes', $plant->notes ?? '') }}</textarea>
      @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Photo principale -->
    <div class="md:col-span-1">
      <h3 class="text-lg font-medium mb-2">Photo principale</h3>
      @if($plant?->main_photo)
        <div class="mb-2 max-w-xs">
          <img src="{{ Storage::url($plant->main_photo) }}" alt="" class="w-full h-auto max-h-48 object-cover rounded shadow">
        </div>
      @endif
      <input type="file" name="main_photo" accept="image/*" id="mainPhotoInput" class="block @error('main_photo') border-red-500 @enderror">
      <div id="mainPhotoPreview" class="mt-2 max-w-xs"></div>
      <p class="text-xs text-gray-500 mt-1">{{ $isEdit ? 'Remplacer la photo principale (optionnel).' : 'Sélectionner une photo.' }}</p>
      @error('main_photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Galerie -->
    <div class="md:col-span-1">
      <h3 class="text-lg font-medium mb-2">Ajouter des photos (galerie)</h3>
      <input type="file" name="photos[]" accept="image/*" multiple id="galleryPhotosInput" class="block @error('photos') border-red-500 @enderror">
      <div id="galleryPhotosPreview" class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-2"></div>
      <p class="text-xs text-gray-500 mt-1">Les nouvelles images seront ajoutées à la galerie.</p>
      @error('photos') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Boutons -->
    <div class="md:col-span-2 flex items-center justify-end gap-2 mt-4">
      <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">{{ $submitText }}</button>
      <a href="{{ $isEdit ? route('plants.show', $plant) : route('plants.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Annuler</a>
    </div>
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
window.regenerateReference = function(btn) {
  const familyInput = document.querySelector('input[name="family"]');
  const referenceInput = document.querySelector('input[name="reference"]');
  
  if (!btn) {
    console.error('Button element not found');
    return;
  }
  
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

// Données de tous les tags disponibles avec catégories
window.allTagsData = {!! json_encode($tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'category' => $t->category])->values()->toArray(), JSON_UNESCAPED_UNICODE) !!};

// Couleurs par catégorie
window.categoryColors = {
  'Climat': { bg: 'bg-amber-100', text: 'text-amber-800' },
  'Feuillage': { bg: 'bg-green-100', text: 'text-green-800' },
  'Type': { bg: 'bg-blue-100', text: 'text-blue-800' },
  'Forme': { bg: 'bg-purple-100', text: 'text-purple-800' },
  'Floraison': { bg: 'bg-pink-100', text: 'text-pink-800' },
  'Taille': { bg: 'bg-yellow-100', text: 'text-yellow-800' },
  'Croissance': { bg: 'bg-orange-100', text: 'text-orange-800' },
  'Caractéristiques': { bg: 'bg-cyan-100', text: 'text-cyan-800' },
  'Système racinaire': { bg: 'bg-indigo-100', text: 'text-indigo-800' }
};

// Mettre à jour l'affichage des tags
window.updateTagsDisplay = function() {
  const checkboxes = document.querySelectorAll('input[name="tags[]"]:checked');
  const selectedIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
  
  // Mettre à jour le bouton
  const btnEl = document.querySelector('button[onclick*="tags-modal"]');
  if (btnEl) btnEl.textContent = selectedIds.length > 0 ? 'Modifier' : '+ Ajouter';
  
  // Afficher les noms des tags avec couleurs
  const display = document.getElementById('tags-display');
  if (display) {
    const selectedTags = window.allTagsData.filter(t => selectedIds.includes(t.id));
    let html = '';
    if (selectedTags.length > 0) {
      html = selectedTags.map(tag => {
        const colors = window.categoryColors[tag.category] || { bg: 'bg-gray-100', text: 'text-gray-800' };
        return `<span class="inline-flex items-center px-3 py-1 ${colors.bg} ${colors.text} rounded-full text-xs font-medium">${tag.name}</span>`;
      }).join('');
    }
    display.innerHTML = html;
  }
};

// Appeler au démarrage pour afficher les tags déjà sélectionnés
document.addEventListener('DOMContentLoaded', updateTagsDisplay);

// Aussi appeler après un petit délai pour être sûr
setTimeout(updateTagsDisplay, 100);

// Écouter les changements de checkboxes
document.addEventListener('change', function(e) {
  if (e.target.name === 'tags[]') {
    updateTagsDisplay();
  }
});
</script>
@endpush
