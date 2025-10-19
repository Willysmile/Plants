<!-- Quick Repotting Modal -->
<div id="quickRepottingModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96">
    <h3 class="text-lg font-semibold mb-4 text-amber-900">Rempotage rapide</h3>
    <form id="quickRepottingFormFromModal" action="{{ route('plants.repotting-history.store', $plant) }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="quickRepottingDateFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input type="date" id="quickRepottingDateFromModal" name="repotting_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-amber-500">
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div>
          <label for="quickRepottingOldSizeFromModal" class="block text-sm font-medium text-gray-700 mb-1">Ancien pot (cm)</label>
          <input type="text" id="quickRepottingOldSizeFromModal" name="old_pot_size" placeholder="10 cm" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-amber-500">
        </div>
        <div>
          <label for="quickRepottingNewSizeFromModal" class="block text-sm font-medium text-gray-700 mb-1">Nouveau pot (cm)</label>
          <input type="text" id="quickRepottingNewSizeFromModal" name="new_pot_size" placeholder="12 cm" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-amber-500">
        </div>
      </div>
      <div class="mb-3">
        <label for="quickRepottingSoilFromModal" class="block text-sm font-medium text-gray-700 mb-1">Type de terreau</label>
        <input type="text" id="quickRepottingSoilFromModal" name="soil_type" placeholder="Ex: Terreau spécialisé..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-amber-500">
      </div>
      <div class="mb-4">
        <label for="quickRepottingNotesFromModal" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea id="quickRepottingNotesFromModal" name="notes" placeholder="Remarques..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-amber-500" rows="2"></textarea>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="flex-1 bg-amber-600 text-white font-medium py-2 rounded hover:bg-amber-700">Enregistrer</button>
        <button type="button" class="flex-1 bg-gray-300 text-gray-700 font-medium py-2 rounded hover:bg-gray-400" onclick="closeQuickRepottingModalFromModal()">Annuler</button>
      </div>
    </form>
  </div>
</div>
