<!-- Quick Watering Modal -->
<div id="quickWateringModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96">
    <h3 class="text-lg font-semibold mb-4 text-blue-900">Arrosage rapide</h3>
    <form id="quickWateringFormFromModal" action="{{ route('plants.watering-history.store', $plant) }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="quickWateringDateFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input type="date" id="quickWateringDateFromModal" name="watering_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
      </div>
      <div class="mb-3">
        <label for="quickWateringAmountFromModal" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
        <input type="text" id="quickWateringAmountFromModal" name="amount" placeholder="500ml, 1L..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
      </div>
      <div class="mb-4">
        <label for="quickWateringNotesFromModal" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea id="quickWateringNotesFromModal" name="notes" placeholder="Remarques..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500" rows="2"></textarea>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="flex-1 bg-blue-600 text-white font-medium py-2 rounded hover:bg-blue-700">Enregistrer</button>
        <button type="button" class="flex-1 bg-gray-300 text-gray-700 font-medium py-2 rounded hover:bg-gray-400" onclick="closeQuickWateringModalFromModal()">Annuler</button>
      </div>
    </form>
  </div>
</div>
