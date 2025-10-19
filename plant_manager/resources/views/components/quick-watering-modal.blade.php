<!-- Quick Watering Modal -->
<div id="quickWateringModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="event.stopPropagation();">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Set max date to today
  const dateInput = document.getElementById('quickWateringDateFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
  }
});

function handleQuickWateringSubmit(event) {
  event.preventDefault();
  event.stopPropagation();
  
  console.log('handleQuickWateringSubmit called');
  
  const dateInput = document.getElementById('quickWateringDateFromModal');
  const dateError = document.getElementById('quickWateringDateError');
  const today = new Date().toISOString().split('T')[0];
  
  console.log('Date input value:', dateInput.value);
  console.log('Today:', today);
  
  // Validate date is not in the future (client-side)
  if (dateInput.value > today) {
    dateError.textContent = 'La date ne peut pas être dans le futur';
    dateError.classList.remove('hidden');
    console.log('Date validation failed');
    return false;
  }
  
  dateError.classList.add('hidden');
  console.log('Date validation passed');
  
  // Submit form via AJAX
  const form = document.getElementById('quickWateringFormFromModal');
  const formData = new FormData(form);
  
  console.log('Submitting form...');
  
  fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(response => {
    console.log('Response status:', response.status);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.text();
  })
  .then(data => {
    console.log('Success response:', data);
    alert('Arrosage effectué !!');
    form.reset();
    dateError.classList.add('hidden');
    closeQuickWateringModalFromModal();
    // Note: History will be updated when modal is reopened
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Erreur lors de l\'enregistrement');
  });
  
  return false;
}
</script>
