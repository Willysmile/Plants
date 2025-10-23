<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width:1035px;height:863px;max-width:calc(100vw - 40px);" id="plant-modal-{{ $plant->id }}" data-modal-plant-id="{{ $plant->id }}">
  <div class="h-full flex flex-col">
    <!-- En-tête -->
    <div class="flex items-center justify-between p-3 border-b">
      <div class="flex items-center gap-3">
        <div>
          @if($plant->scientific_name)
            <h2 class="text-lg font-semibold italic text-green-700">{{ $plant->scientific_name }}</h2>
          @endif
          <div class="flex gap-2 mt-1 items-center">
            @if($plant->family)
              <span class="text-xs uppercase font-bold text-gray-400 tracking-wide">{{ $plant->family }}</span>
              @if($plant->subfamily)
                <span class="text-xs font-medium text-gray-500">{{ $plant->subfamily }}</span>
              @endif
            @endif
          </div>
          @if($plant->name)
            <p class="text-sm text-gray-700 mt-1">{{ $plant->name }}</p>
          @endif
        </div>
      </div>

      <!-- Référence badge réduit -->
      <div class="flex gap-2">
        @if($plant->reference)
          <div class="bg-purple-50 px-2 py-1 rounded border border-purple-200 text-xs">
            <p class="text-gray-600 font-medium">Référence</p>
            <p class="text-purple-700 font-mono font-semibold">{{ $plant->reference }}</p>
          </div>
        @endif
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200 transition">Voir</a>
        <a href="{{ route('plants.edit', $plant) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600 transition">Éditer</a>
        <button type="button" onclick="window.refreshModal()" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition flex items-center gap-1" title="Actualiser">
          <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
        <button class="px-2 py-1 bg-gray-200 rounded text-sm modal-close hover:bg-gray-300 transition" title="Fermer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 overflow-hidden flex p-3 gap-4">
      <!-- Colonne gauche (1/2) : Photo + Description + Galerie -->
      <div class="w-1/2 flex flex-col gap-3 overflow-y-auto pr-2">
        <!-- Photo principale -->
        <x-photo-section :plant="$plant" :aspectRatio="'4/3'" :clickable="true" height="280px" />

        <!-- Description -->
        @if($plant->description)
          <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-green-500 text-sm">
            <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide text-center">Description</h3>
            <p class="mt-2 text-gray-700 leading-relaxed text-xs">{{ $plant->description }}</p>
          </div>
        @endif

        <!-- Historiques (4 cartes) - Affichées horizontalement -->
        <div class="grid grid-cols-4 gap-2" id="modal-histories-container-{{ $plant->id }}">
          <x-history-card :plant="$plant" type="watering" context="modal" />
          <x-history-card :plant="$plant" type="fertilizing" context="modal" />
          <x-history-card :plant="$plant" type="repotting" context="modal" />
          <x-disease-card :plant="$plant" context="modal" />
        </div>

        <!-- Dernières Infos Diverses - Simple avec button Voir + Compteur -->
        @if($plant->histories->count())
          <div class="mt-2 p-2 bg-gray-50 rounded border border-gray-100">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Infos Diverses</h4>
                <span class="inline-block bg-gray-200 text-gray-700 text-xs font-bold px-2 py-0.5 rounded-full">
                  {{ $plant->histories->count() }}
                </span>
              </div>
              <button type="button" 
                      onclick="openModalFreeHistories({{ $plant->id }})" 
                      class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs transition flex items-center gap-1">
                <i data-lucide="eye" class="w-3 h-3"></i>
                Voir
              </button>
            </div>
          </div>
        @endif

        <!-- Modale pour les Infos Diverses dans la modale plants -->
        @if($plant->histories->count())
          <div id="free-histories-modal-{{ $plant->id }}" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto m-4">
              <div class="flex items-center justify-between p-3 border-b sticky top-0 bg-white">
                <h3 class="text-sm font-semibold text-gray-800">Infos Diverses</h3>
                <button type="button" 
                        onclick="closeModalFreeHistories({{ $plant->id }})" 
                        class="text-gray-500 hover:text-gray-700">
                  <i data-lucide="x" class="w-4 h-4"></i>
                </button>
              </div>
              <div class="p-3 space-y-2">
                @foreach($plant->histories->sortByDesc('created_at')->take(3) as $history)
                  <div class="bg-gray-50 p-2 rounded border border-gray-200 text-xs">
                    <div class="text-gray-500 font-medium">{{ $history->created_at->format('d/m/Y H:i') }}</div>
                    <div class="text-gray-800 mt-1 whitespace-pre-wrap break-words">{{ $history->body }}</div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Modal pour les Maladies dans la modale plants -->
        @if($plant->diseaseHistories->count())
          <div id="free-diseases-modal-{{ $plant->id }}" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto m-4">
              <div class="flex items-center justify-between p-3 border-b sticky top-0 bg-white">
                <h3 class="text-sm font-semibold text-gray-800">🦠 Maladies</h3>
                <button type="button" 
                        onclick="closeDiseasesModalFromModal({{ $plant->id }})" 
                        class="text-gray-500 hover:text-gray-700">
                  <i data-lucide="x" class="w-4 h-4"></i>
                </button>
              </div>
              <div class="p-3 space-y-2">
                @foreach($plant->diseaseHistories->sortByDesc('detected_at') as $disease)
                  <div class="{{ $disease->status_color }} p-2 rounded border text-xs">
                    <div class="font-medium">{{ $disease->disease->name }}</div>
                    <div class="text-gray-500 text-xs">{{ $disease->detected_at->format('d/m/Y') }}</div>
                    @if($disease->description)
                      <div class="mt-1 whitespace-pre-wrap break-words">{{ substr($disease->description, 0, 100) }}{{ strlen($disease->description) > 100 ? '...' : '' }}</div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Modal pour Ajouter une Maladie dans la modale plants -->
        <div id="add-disease-modal-{{ $plant->id }}" style="display:none" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full m-4">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b">
              <h3 class="text-lg font-semibold text-gray-800">🦠 Ajouter une Maladie</h3>
              <button type="button" 
                      onclick="closeAddDiseaseModalFromModal({{ $plant->id }})" 
                      class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
              </button>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('plants.disease-history.store', $plant) }}" method="POST" class="p-4">
              @csrf
              
              <div class="space-y-4">
                <!-- Maladie existante ou nouvelle -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Maladie *</label>
                  <select name="disease_id" id="diseaseSelect-modal-{{ $plant->id }}" onchange="toggleNewDiseaseFromModal({{ $plant->id }})" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Sélectionner une maladie existante --</option>
                    @foreach(\App\Models\Disease::orderBy('name')->get() as $disease)
                      <option value="{{ $disease->id }}">{{ $disease->name }}</option>
                    @endforeach
                    <option value="new">➕ Ajouter une nouvelle maladie...</option>
                  </select>
                </div>

                <!-- Nouvelle maladie (caché par défaut) -->
                <div id="newDiseaseDiv-modal-{{ $plant->id }}" style="display:none;">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la nouvelle maladie *</label>
                  <input type="text" name="new_disease_name" placeholder="Ex: Cochenilles, Oïdium, etc."
                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <!-- Date de détection -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Date de détection *</label>
                  <input type="datetime-local" name="detected_at" required
                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <!-- Description -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Description des symptômes</label>
                  <textarea name="description" rows="3" placeholder="Décrivez les symptômes observés..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <!-- Traitement -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Traitement appliqué</label>
                  <textarea name="treatment" rows="3" placeholder="Décrivez le traitement appliqué..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <!-- Date du traitement -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Date du traitement</label>
                  <input type="datetime-local" name="treated_at"
                         class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>

                <!-- Statut -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                  <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Sélectionner un statut --</option>
                    <option value="detected">🔴 Détectée (Nouveau problème)</option>
                    <option value="treated">🟡 Traitée (En cours de traitement)</option>
                    <option value="cured">🟢 Guérie (Problème résolu)</option>
                    <option value="recurring">🔄 Récurrente (Problème revient)</option>
                  </select>
                </div>
              </div>

              <!-- Boutons -->
              <div class="flex justify-end gap-2 mt-6 border-t pt-4">
                <button type="button" 
                        onclick="closeAddDiseaseModalFromModal({{ $plant->id }})"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                  Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                  Ajouter la Maladie
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Colonne droite (1/2) : Cartes en 2 colonnes + Galerie fixe en bas -->
      <div class="w-1/2 flex flex-col">
        <!-- Cartes scrollables -->
        <div class="overflow-y-auto pr-2 flex-1">
          <div class="grid grid-cols-2 gap-3">
            <!-- Besoins -->
            <div class="bg-yellow-50 p-2 rounded-lg border-l-4 border-yellow-500">
            <div class="text-center">
              <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Besoins</h3>
            </div>

            @php
              $wf = $plant->watering_frequency ?? 3;
              $lf = $plant->light_requirement ?? 3;
              $wIcon = \App\Models\Plant::$wateringIcons[$wf] ?? 'droplet';
              $lIcon = \App\Models\Plant::$lightIcons[$lf] ?? 'sun';
              $wColor = \App\Models\Plant::$wateringColors[$wf] ?? 'blue';
              $lColor = \App\Models\Plant::$lightColors[$lf] ?? 'yellow';
              $wLabel = \App\Models\Plant::$wateringLabels[$wf] ?? 'N/A';
              $lLabel = \App\Models\Plant::$lightLabels[$lf] ?? 'N/A';
            @endphp

            <div class="mt-2 flex items-center justify-around gap-3">
              <div class="flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Arrosage</span>
                <i data-lucide="{{ $wIcon }}" class="w-5 h-5 text-{{ $wColor }}"></i>
                <span class="text-xs text-gray-600">{{ $wLabel }}</span>
              </div>

              <div class="flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Lumière</span>
                <i data-lucide="{{ $lIcon }}" class="w-5 h-5 text-{{ $lColor }}"></i>
                <span class="text-xs text-gray-600">{{ $lLabel }}</span>
              </div>
            </div>
          </div>

          <!-- Tags -->
          <div class="bg-purple-50 p-2 rounded-lg border-l-4 border-purple-500">
            <div class="text-center">
              <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Tags</h3>
            </div>
            <div class="mt-2 text-center text-xs text-gray-800">{{ $plant->tags->pluck('name')->join(', ') ?: '—' }}</div>
          </div>

          <!-- Température & Humidité -->
          @if($plant->temperature_min || $plant->temperature_max || $plant->humidity_level)
            <div class="bg-red-50 p-2 rounded-lg border-l-4 border-red-500">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Température</h3>
              </div>
              <div class="mt-2 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Valeurs</span>
                <div class="text-gray-800 text-xs font-medium">
                  @if($plant->temperature_min || $plant->temperature_max)
                    @php
                      $minTemp = $plant->temperature_min ?? '?';
                      $maxTemp = $plant->temperature_max ?? '?';
                    @endphp
                    {{ $minTemp }}°C-{{ $maxTemp }}°C
                  @else
                    —
                  @endif
                </div>
              </div>
            </div>
          @endif

          <!-- Humidité -->
          @if($plant->humidity_level)
            <div class="bg-cyan-50 p-2 rounded-lg border-l-4 border-cyan-500">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Humidité</h3>
              </div>
              <div class="mt-2 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Taux</span>
                <div class="text-gray-800 text-xs font-medium">{{ $plant->humidity_level }}%</div>
              </div>
            </div>
          @endif

          <!-- Notes -->
          @if($plant->notes)
            <div class="bg-indigo-50 p-2 rounded-lg border-l-4 border-indigo-500 col-span-2">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Notes</h3>
              </div>
              <p class="mt-2 text-gray-700 leading-relaxed text-xs">{{ $plant->notes }}</p>
            </div>
          @endif
          </div>
        </div>

        <!-- Localisation (Emplacement, Date, Lieu d'achat) AU-DESSUS de la galerie -->
        <div class="grid gap-2 text-xs" style="grid-template-columns: repeat(auto-fit, minmax(0, 1fr));">
          @php
            $locationObj = $plant->location && is_object($plant->location) ? $plant->location : null;
            $purchasePlaceObj = $plant->purchasePlace && is_object($plant->purchasePlace) ? $plant->purchasePlace : null;
          @endphp
          
          @if($locationObj)
            <div class="bg-green-50 p-2 rounded border border-green-200">
              <p class="text-gray-600 font-medium text-xs">📍 Emplacement</p>
              <p class="text-green-700 font-semibold">{{ $locationObj->name }}</p>
              @if($locationObj->light_level)
                <p class="text-xs text-gray-600 mt-1">💡 {{ $locationObj->light_level }}</p>
              @endif
            </div>
          @endif
          
          @if($plant->purchase_date)
            <div class="bg-blue-50 p-2 rounded border border-blue-200">
              <p class="text-gray-600 font-medium text-xs">📅 Date d'achat</p>
              <p class="text-blue-700 font-semibold">{{ $plant->formatted_purchase_date ?? $plant->purchase_date }}</p>
            </div>
          @endif
          
          @if($purchasePlaceObj)
            <div class="bg-orange-50 p-2 rounded border border-orange-200">
              <p class="text-gray-600 font-medium text-xs">🛒 Lieu d'achat</p>
              <p class="text-orange-700 font-semibold">{{ $purchasePlaceObj->name }}</p>
              @if($purchasePlaceObj->phone)
                <p class="text-xs text-gray-600 mt-1">☎️ {{ $purchasePlaceObj->phone }}</p>
              @endif
            </div>
          @endif
        </div>

        <!-- Galerie fixe en bas -->
        @php
          $gallery = $plant->photos->filter(function($p) use ($plant){
            if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
            if (isset($p->is_main) && $p->is_main) return false;
            return true;
          })->values();
          $maxGallery = 2;
        @endphp

        <div class="border-t pt-2 mt-2">
          <h3 class="font-medium text-xs mb-2 text-center uppercase">Galerie</h3>
          @if($gallery->count())
            <div class="flex justify-center gap-2">
              @for($i = 0; $i < min($maxGallery, $gallery->count()); $i++)
      <button type="button" 
        class="gallery-thumbnail"
        data-type="thumbnail"
        data-index="{{ $i + 1 }}"
        data-lightbox-index="{{ $i + 1 }}"
        data-original-src="{{ Storage::url($gallery[$i]->filename) }}"
                       style="aspect-ratio:1/1; width:70px; height:70px; padding:0; border:0; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;" 
                       aria-label="Échanger avec la photo principale">
                  <img src="{{ Storage::url($gallery[$i]->filename) }}" 
                       alt="{{ $gallery[$i]->description ?? $plant->name }}" 
                       style="max-width:100%; max-height:100%; object-fit:cover; border-radius:4px;">
                </button>
              @endfor

              <!-- Points toujours affichés -->
              <a href="{{ route('plants.show', $plant) }}" 
                 style="width:70px; height:70px; padding:0; background:transparent; display:flex; align-items:center; justify-content:center; border-radius:4px; text-decoration:none; transition:all 0.2s;" 
                 class="more-photos" 
                 aria-label="Voir la galerie complète" 
                 onmouseover="this.style.border='1px solid #15803d'; this.querySelector('span').style.color='#15803d';" 
                 onmouseout="this.style.border='0'; this.querySelector('span').style.color='#999';">
                <span style="font-size:32px; color:#999; line-height:0.5; transition:color 0.2s;">⋯</span>
              </a>
            </div>
          @else
            <div class="flex items-center justify-center h-20 text-gray-400">
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-xs">Aucune photo</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Quick Entry Modals (extracted to separate components) -->
    <x-quick-watering-modal :plant="$plant" />
    <x-quick-fertilizing-modal :plant="$plant" :fertilizerTypes="$fertilizerTypes ?? \App\Models\FertilizerType::all()" />
    <x-quick-repotting-modal :plant="$plant" />
  </div>

  <script type="application/json" data-lightbox-images>
[
  @if($plant->main_photo)
    { "url": {!! json_encode(Storage::url($plant->main_photo)) !!}, "caption": {!! json_encode($plant->name) !!} }@if($gallery->count()),@endif
  @endif
  @foreach($gallery as $p)
    { "url": {!! json_encode(Storage::url($p->filename)) !!}, "caption": {!! json_encode($p->description ?? '') !!} }@if(!$loop->last),@endif
  @endforeach
]
  </script>
  
  <!-- Fonction de refresh local pour cette modale -->
    <script>
    window.refreshModal = function(event) {
      // Trouver le bouton refresh
      let button = event?.currentTarget;
      if (!button) {
        button = document.querySelector('button[onclick="refreshModal()"]');
      }
      if (!button) return;
      
      const icon = button.querySelector('[data-lucide="refresh-cw"]');
      const modal = document.getElementById('plant-modal-{{ $plant->id }}');
      
      if (!modal) return;
      
      // Add spinning animation
      if (icon) {
        icon.style.animation = 'spin 1s linear infinite';
      }
      
      const plantId = {{ $plant->id }};
      
      // Fetch the updated modal HTML
      fetch(`/plants/${plantId}/modal`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.text())
      .then(html => {
        // Parse and replace just the content
        const parser = new DOMParser();
        const newModal = parser.parseFromString(html, 'text/html').body.firstChild;
        modal.replaceWith(newModal);
        
        // Modal refreshed successfully
      })
      .catch(error => {
        console.error('Modal refresh error:', error);
      })
      .finally(() => {
        if (icon) {
          icon.style.animation = 'none';
        }
      });
    };

    // Fonctions pour ouvrir/fermer la modale des Infos Diverses dans la modale plants
    window.openModalFreeHistories = function(plantId) {
      const modal = document.getElementById('modal-free-histories-' + plantId);
      if (modal) {
        modal.style.display = 'flex';
      }
    };

    window.closeModalFreeHistories = function(plantId) {
      const modal = document.getElementById('modal-free-histories-' + plantId);
      if (modal) {
        modal.style.display = 'none';
      }
    };

    // Fonctions pour ouvrir/fermer la modale des Maladies dans la modale plants
    window.openDiseasesModalFromModal = function(plantId) {
      const modal = document.getElementById('free-diseases-modal-' + plantId);
      if (modal) {
        modal.style.display = 'flex';
      }
    };

    window.closeDiseasesModalFromModal = function(plantId) {
      const modal = document.getElementById('free-diseases-modal-' + plantId);
      if (modal) {
        modal.style.display = 'none';
      }
    };

    // Fonctions pour ouvrir/fermer la modale d'ajout de maladie dans la modale plants
    window.openAddDiseaseModal = function(plantId) {
      const modal = document.getElementById('add-disease-modal-' + plantId);
      if (modal) {
        modal.style.display = 'flex';
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
      }
    };

    window.closeAddDiseaseModalFromModal = function(plantId) {
      const modal = document.getElementById('add-disease-modal-' + plantId);
      if (modal) {
        modal.style.display = 'none';
      }
    };

    // Toggle new disease field when "new" is selected
    window.toggleNewDiseaseFromModal = function(plantId) {
      const select = document.getElementById('diseaseSelect-modal-' + plantId);
      const newDiseaseDiv = document.getElementById('newDiseaseDiv-modal-' + plantId);
      if (select && newDiseaseDiv) {
        if (select.value === 'new') {
          newDiseaseDiv.style.display = 'block';
        } else {
          newDiseaseDiv.style.display = 'none';
        }
      }
    };
  </script>

</div>
</div>