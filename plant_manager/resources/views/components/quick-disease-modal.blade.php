<!-- Quick Disease Modal -->
<div id="quickDiseaseModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="event.stopPropagation(); setupQuickDiseaseModal();">
  <div class="relative bg-white rounded-lg shadow-lg p-6 w-96" onclick="event.stopPropagation();">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-red-900">Maladie</h3>
      <button type="button" onclick="closeQuickDiseaseModalFromModal()" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <form id="quickDiseaseFormFromModal" action="{{ route('plants.disease-history.store', $plant) }}" method="POST" onsubmit="return handleQuickDiseaseSubmit(event)">
      @csrf
      <input type="hidden" name="_ajax" value="1">
      
      <div class="mb-3">
        <label for="quickDiseaseDetectedAtFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date de détection</label>
        <input type="date" id="quickDiseaseDetectedAtFromModal" name="detected_at" required max="" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500">
        <p id="quickDiseaseDateError" class="text-xs text-red-600 mt-1 hidden">La date ne peut pas être dans le futur</p>
      </div>
      
      <div class="mb-3">
        <label for="quickDiseaseTypeFromModal" class="block text-sm font-medium text-gray-700 mb-1">Maladie</label>
        <select id="quickDiseaseTypeFromModal" name="disease_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500">
          <option value="">-- Sélectionner une maladie --</option>
          @foreach(\App\Models\Disease::orderBy('name')->get() as $disease)
            <option value="{{ $disease->id }}">{{ $disease->name }}</option>
          @endforeach
          <option value="new">➕ Ajouter une nouvelle...</option>
        </select>
      </div>

      <!-- Nouvelle maladie (caché par défaut) -->
      <div id="quickNewDiseaseDiv" style="display:none;" class="mb-3">
        <label for="quickNewDiseaseName" class="block text-sm font-medium text-gray-700 mb-1">Nom de la nouvelle maladie</label>
        <input type="text" id="quickNewDiseaseName" name="new_disease_name" placeholder="Ex: Cochenilles, Oïdium..."
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500">
      </div>

      <div class="mb-3">
        <label for="quickDiseaseStatusFromModal" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
        <select id="quickDiseaseStatusFromModal" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500">
          <option value="">-- Sélectionner un statut --</option>
          <option value="detected">🔴 Détectée</option>
          <option value="treated">🟡 Traitée</option>
          <option value="cured">🟢 Guérie</option>
          <option value="recurring">🔄 Récurrente</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="quickDiseaseDescriptionFromModal" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea id="quickDiseaseDescriptionFromModal" name="description" placeholder="Symptômes observés..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500" rows="2"></textarea>
      </div>

      <div class="mb-3">
        <label for="quickDiseaseTreatedAtFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date du traitement</label>
        <input type="date" id="quickDiseaseTreatedAtFromModal" name="treated_at" max="" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500">
      </div>

      <div class="mb-4">
        <label for="quickDiseaseTreatmentFromModal" class="block text-sm font-medium text-gray-700 mb-1">Traitement appliqué</label>
        <textarea id="quickDiseaseTreatmentFromModal" name="treatment" placeholder="Traitement appliqué..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500" rows="2"></textarea>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="flex-1 bg-red-600 text-white font-medium py-2 rounded hover:bg-red-700">Enregistrer</button>
        <button type="button" class="flex-1 bg-gray-300 text-gray-700 font-medium py-2 rounded hover:bg-gray-400" onclick="closeQuickDiseaseModalFromModal()">Annuler</button>
      </div>
    </form>
  </div>
</div>
