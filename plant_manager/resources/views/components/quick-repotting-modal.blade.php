<!-- Quick Repotting Modal -->
<div id="quickRepottingModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="event.stopPropagation();">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96" onclick="event.stopPropagation();">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-amber-900">Rempotage</h3>
      <button type="button" onclick="closeQuickRepottingModalFromModal()" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <form id="quickRepottingFormFromModal" action="{{ route('plants.repotting-history.store', $plant) }}" method="POST" onsubmit="return handleQuickRepottingSubmit(event)">
      @csrf
      <div class="mb-3">
        <label for="quickRepottingDateFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input type="date" id="quickRepottingDateFromModal" name="repotting_date" required max="" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-amber-500">
        <p id="quickRepottingDateError" class="text-xs text-red-600 mt-1 hidden">La date ne peut pas être dans le futur</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Set max date to today
  const dateInput = document.getElementById('quickRepottingDateFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
  }
});

function handleQuickRepottingSubmit(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const dateInput = document.getElementById('quickRepottingDateFromModal');
  const dateError = document.getElementById('quickRepottingDateError');
  const today = new Date().toISOString().split('T')[0];
  
  // Validate date is not in the future (client-side)
  if (dateInput.value > today) {
    dateError.textContent = 'La date ne peut pas être dans le futur';
    dateError.classList.remove('hidden');
    return false;
  }
  
  dateError.classList.add('hidden');
  
  // Submit form via AJAX
  const form = document.getElementById('quickRepottingFormFromModal');
  const formData = new FormData(form);
  
  fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.text();
  })
  .then(data => {
    alert('Rempotage effectué !!');
    form.reset();
    dateError.classList.add('hidden');
    closeQuickRepottingModalFromModal();
    // Reload the page to refresh history
    location.reload();
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Erreur lors de l\'enregistrement');
  });
  
  return false;
}
</script>
