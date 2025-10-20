<!-- Infos Diverses - Formulaire + Titre + Compteur + Button -->
<div class="mt-4 space-y-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
  <!-- Formulaire de saisie -->
  <form id="free-history-form-{{ $plant->id }}" 
        action="{{ route('plants.histories.store', $plant) }}" 
        method="POST"
        class="space-y-2">
    @csrf
    <textarea name="body" 
              id="free-history-body-{{ $plant->id }}"
              placeholder="Ajouter une info..."
              maxlength="500"
              rows="2"
              class="w-full p-2 border rounded text-sm resize-none focus:ring-2 focus:ring-blue-500"
              style="font-size: 13px;"></textarea>
    <div class="flex items-center justify-between">
      <span id="free-history-count-{{ $plant->id }}" class="text-xs text-gray-500">0/500</span>
      <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-sm transition">
        Ajouter
      </button>
    </div>
  </form>

  <!-- Titre + Compteur + Button pour voir les entrées -->
  <div class="flex items-center justify-between gap-3 pt-2 border-t">
    <div class="flex items-center gap-2">
      <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Infos Diverses</h4>
      @if($plant->histories && $plant->histories->count() > 0)
        <span class="inline-block bg-gray-200 text-gray-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
          {{ $plant->histories->count() }}
        </span>
      @else
        <span class="inline-block bg-gray-100 text-gray-500 text-xs font-medium px-2.5 py-0.5 rounded-full">
          0
        </span>
      @endif
    </div>
    
    @if($plant->histories && $plant->histories->count() > 0)
      <button type="button" 
              onclick="openFreeHistoriesModal({{ $plant->id }})" 
              class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition flex items-center gap-1">
        <i data-lucide="eye" class="w-4 h-4"></i>
        Voir
      </button>
    @endif
  </div>
</div>

<script>
// Compteur de caractères
document.addEventListener('DOMContentLoaded', function() {
  const textarea = document.getElementById('free-history-body-{{ $plant->id }}');
  const counter = document.getElementById('free-history-count-{{ $plant->id }}');
  const form = document.getElementById('free-history-form-{{ $plant->id }}');
  
  if (textarea && counter) {
    function updateCounter() {
      counter.textContent = textarea.value.length + '/500';
    }
    
    textarea.addEventListener('input', updateCounter);
    textarea.addEventListener('keyup', updateCounter);
  }

  // Soumettre le formulaire
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(form);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      
      fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => {
        if (response.ok) {
          // Réinitialiser le formulaire
          textarea.value = '';
          updateCounter();
          
          // Recharger la page pour voir les nouvelles entrées
          location.reload();
        } else {
          alert('Erreur lors de l\'ajout');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout');
      });
    });
  }
});

// Fonctions globales pour gérer la modale des Infos Diverses en show.blade.php
window.openFreeHistoriesModal = function(plantId) {
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'flex';
  }
};

window.closeFreeHistoriesModal = function(plantId) {
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'none';
  }
};
</script>
