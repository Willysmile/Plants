<!-- Quick Watering Modal -->
<script>
// Define validation function in global scope so it's available when form submits
window.handleQuickWateringSubmit = function(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const dateInput = document.getElementById('quickWateringDateFromModal');
  const dateError = document.getElementById('quickWateringDateError');
  const form = document.getElementById('quickWateringFormFromModal');
  
  if (!dateInput || !dateError || !form) {
    console.error('[WATERING] Elements not found!');
    return false;
  }
  
  const enteredDate = dateInput.value;
  const today = new Date().toISOString().split('T')[0];
  
  console.log('[WATERING] Date entered:', enteredDate);
  console.log('[WATERING] Today:', today);
  console.log('[WATERING] Is future?', enteredDate > today);
  
  // Validate date is not in the future (client-side)
  if (!enteredDate) {
    dateError.textContent = 'La date est requise';
    dateError.classList.remove('hidden');
    console.log('[WATERING] Error: No date');
    return false;
  }
  
  if (enteredDate > today) {
    dateError.textContent = 'La date ne peut pas être dans le futur';
    dateError.classList.remove('hidden');
    console.log('[WATERING] Error: Future date blocked');
    return false;
  }
  
  // Date is valid - hide error and submit via AJAX
  dateError.classList.add('hidden');
  console.log('[WATERING] Date valid, submitting...');
  
  // Collect form data
  const formData = new FormData(form);
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  
  // Submit via fetch (AJAX)
  fetch(form.action, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken,
    },
    body: formData
  })
  .then(response => {
    if (response.ok) {
      // Success - show message, then return to plants modal
      alert('Arrosage enregistré !');
      closeQuickWateringModalFromModal();
      // Show the plants modal (assuming it exists)
      const plantsModal = document.getElementById('quickPlantsModalFromModal');
      if (plantsModal) {
        plantsModal.classList.remove('hidden');
      }
    } else {
      // Server validation failed
      return response.text().then(text => {
        throw new Error(text);
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    dateError.textContent = 'Erreur lors de l\'enregistrement';
    dateError.classList.remove('hidden');
  });
  
  return false;
};

// Set max date when modal opens
window.setupQuickWateringModal = function() {
  const dateInput = document.getElementById('quickWateringDateFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
    console.log('[WATERING] Max date set to:', today);
  }
};
</script>

<div id="quickWateringModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="event.stopPropagation(); setupQuickWateringModal();">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96" onclick="event.stopPropagation();">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-blue-900">Arrosage</h3>
      <button type="button" onclick="closeQuickWateringModalFromModal()" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <form id="quickWateringFormFromModal" action="{{ route('plants.watering-history.store', $plant) }}" method="POST" onsubmit="return handleQuickWateringSubmit(event)">
      @csrf
      <input type="hidden" name="_ajax" value="1">
      <div class="mb-3">
        <label for="quickWateringDateFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input type="date" id="quickWateringDateFromModal" name="watering_date" required max="" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
        <p id="quickWateringDateError" class="text-xs text-red-600 mt-1 hidden">La date ne peut pas être dans le futur</p>
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
