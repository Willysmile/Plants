<!-- Quick Fertilizing Modal -->
<div id="quickFertilizingModalFromModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96">
    <h3 class="text-lg font-semibold mb-4 text-green-900">Fertilisation</h3>
    <form id="quickFertilizingFormFromModal" action="{{ route('plants.fertilizing-history.store', $plant) }}" method="POST" onsubmit="handleQuickFertilizingSubmit(event)">
      @csrf
      <div class="mb-3">
        <label for="quickFertilizingDateFromModal" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input type="date" id="quickFertilizingDateFromModal" name="fertilizing_date" required max="" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500">
        <p id="quickFertilizingDateError" class="text-xs text-red-600 mt-1 hidden">La date ne peut pas être dans le futur</p>
      </div>
      <div class="mb-3">
        <label for="quickFertilizingTypeFromModal" class="block text-sm font-medium text-gray-700 mb-1">Type d'engrais</label>
        <input type="text" id="quickFertilizingTypeFromModal" name="fertilizer_type" placeholder="Ex: Engrais liquide..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500">
      </div>
      <div class="mb-3">
        <label for="quickFertilizingAmountFromModal" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
        <input type="text" id="quickFertilizingAmountFromModal" name="amount" placeholder="50ml..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500">
      </div>
      <div class="mb-4">
        <label for="quickFertilizingNotesFromModal" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea id="quickFertilizingNotesFromModal" name="notes" placeholder="Remarques..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500" rows="2"></textarea>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="flex-1 bg-green-600 text-white font-medium py-2 rounded hover:bg-green-700">Enregistrer</button>
        <button type="button" class="flex-1 bg-gray-300 text-gray-700 font-medium py-2 rounded hover:bg-gray-400" onclick="closeQuickFertilizingModalFromModal()">Annuler</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Set max date to today
  const dateInput = document.getElementById('quickFertilizingDateFromModal');
  const today = new Date().toISOString().split('T')[0];
  dateInput.max = today;
});

function handleQuickFertilizingSubmit(event) {
  event.preventDefault();
  
  const dateInput = document.getElementById('quickFertilizingDateFromModal');
  const dateError = document.getElementById('quickFertilizingDateError');
  const today = new Date().toISOString().split('T')[0];
  
  // Validate date is not in the future (client-side)
  if (dateInput.value > today) {
    dateError.classList.remove('hidden');
    return false;
  }
  
  dateError.classList.add('hidden');
  
  // Submit form via AJAX
  const form = document.getElementById('quickFertilizingFormFromModal');
  const formData = new FormData(form);
  
  fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    }
  })
  .then(response => {
    // Check if response is JSON
    return response.json().then(data => ({ status: response.status, data }));
  })
  .then(({ status, data }) => {
    if (status === 200 || status === 201) {
      alert('Fertilisation effectuée !!');
      form.reset();
      dateError.classList.add('hidden');
      closeQuickFertilizingModalFromModal();
    } else if (status === 422) {
      // Validation error from Laravel
      const errors = data.errors || {};
      if (errors.fertilizing_date) {
        dateError.textContent = errors.fertilizing_date[0];
        dateError.classList.remove('hidden');
      } else {
        alert('Erreur de validation: ' + JSON.stringify(errors));
      }
    } else {
      alert('Erreur lors de l\'enregistrement');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Erreur lors de l\'enregistrement');
  });
  
  return false;
}
</script>
