@extends('layouts.app')

@section('title', 'Plantes')

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-semibold">Plantes</h1>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200">{{ $plants->count() }}</span>
        <a href="{{ route('plants.archived') }}" class="ml-4 px-3 py-1 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded hover:bg-gray-50 transition">
          📦 Archivées
        </a>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('settings.index') }}" class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm transition">⚙️ Paramètres</a>
        <a href="{{ route('plants.create') }}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition">Ajouter</a>
      </div>
    </header>

    <!-- Grille de plantes -->
    <div id="plants-grid" class="grid grid-cols-5 gap-4">
      @foreach($plants as $plant)
        <x-plant-card :plant="$plant" />
      @endforeach
    </div>

    <!-- Pagination Navigation (bas de page, discret) -->
    <div class="flex items-center justify-center gap-2 mt-8 py-4">
      <button id="prev-btn" class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition">← Précédent</button>
      <span id="page-info" class="text-xs text-gray-400 min-w-12 text-center">Page 1</span>
      <button id="next-btn" class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-30 disabled:cursor-not-allowed transition">Suivant →</button>
    </div>
  </div>

  <!-- Modal container -->
  <div id="plant-modal-root" x-data x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="plant-modal-backdrop" class="absolute inset-0 bg-black/60" @click="closeModal()"></div>
    <div id="plant-modal-content" class="relative max-w-4xl w-full z-10"></div>
  </div>

  @include('partials.lightbox')
@endsection

@section('extra-scripts')
  <!-- External JS Modules -->
  <script src="{{ asset('js/modal-manager.js') }}"></script>
  <script src="{{ asset('js/gallery-manager.js') }}"></script>
  <script src="{{ asset('js/quick-modals-manager.js') }}"></script>
  <script src="{{ asset('js/form-validation.js') }}"></script>
  <script src="{{ asset('js/file-preview.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  
  <script>
    // Pagination for plants grid
    const PLANTS_PER_PAGE = 15; // 5 columns × 3 rows
    let currentPage = 0;
    
    const gridContainer = document.getElementById('plants-grid');
    const allCards = Array.from(gridContainer.querySelectorAll('article'));
    const totalPages = Math.ceil(allCards.length / PLANTS_PER_PAGE);
    
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const pageInfo = document.getElementById('page-info');
    
    function showPage(page) {
      currentPage = Math.max(0, Math.min(page, totalPages - 1));
      
      // Hide all cards
      allCards.forEach(card => card.style.display = 'none');
      
      // Show cards for current page
      const start = currentPage * PLANTS_PER_PAGE;
      const end = start + PLANTS_PER_PAGE;
      allCards.slice(start, end).forEach(card => card.style.display = 'block');
      
      // Update buttons
      prevBtn.disabled = currentPage === 0;
      nextBtn.disabled = currentPage === totalPages - 1;
      
      // Update page info
      pageInfo.textContent = `Page ${currentPage + 1} / ${totalPages}`;
    }
    
    prevBtn.addEventListener('click', () => showPage(currentPage - 1));
    nextBtn.addEventListener('click', () => showPage(currentPage + 1));
    
    // Initial display
    showPage(0);
  </script>
@endsection

@section('extra-scripts')
  <!-- Quick Modal Validation Scripts (must be loaded at page init, before AJAX modals load) -->
  <script>
    console.log('[INDEX] Defining global form handlers for quick modals');
    
    // Refresh entire modal with loading animation
    window.refreshModal = function() {
      const modal = document.getElementById('plant-modal-content');
      const button = event.currentTarget;
      const icon = button.querySelector('[data-lucide="refresh-cw"]');
      
      if (!modal) return;
      
      const plantModalEl = modal.querySelector('[data-modal-plant-id]');
      if (!plantModalEl) {
        console.warn('[REFRESH] No plant modal found');
        return;
      }
      
      const plantId = plantModalEl.getAttribute('data-modal-plant-id');
      if (!plantId) {
        console.warn('[REFRESH] No plant ID found');
        return;
      }
      
      // Add spinning animation
      if (icon) {
        icon.style.animation = 'spin 1s linear infinite';
      }
      
      // Fetch the new modal HTML
      fetch(`/plants/${plantId}/modal`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.text())
      .then(html => {
        modal.innerHTML = html;
        console.log('[REFRESH] Modal refreshed successfully');
        
        // Reinitialize Lucide icons
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
      })
      .catch(error => console.error('[REFRESH] Error:', error))
      .finally(() => {
        if (icon) {
          icon.style.animation = 'none';
        }
      });
    };
    
    // Reload histories in modal via AJAX
    window.reloadHistoriesInModal = function() {
      const modal = document.getElementById('plant-modal-content');
      if (!modal) return;
      
      const plantModalEl = modal.querySelector('[data-modal-plant-id]');
      if (!plantModalEl) {
        console.warn('[RELOAD] No plant modal found');
        return;
      }
      
      const plantId = plantModalEl.getAttribute('data-modal-plant-id');
      if (!plantId) {
        console.warn('[RELOAD] No plant ID found');
        return;
      }
      
      const container = modal.querySelector(`#modal-histories-container-${plantId}`);
      if (!container) {
        console.warn('[RELOAD] No histories container found');
        return;
      }
      
      // Fetch the new histories HTML
      fetch(`/plants/${plantId}/histories`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.text())
      .then(html => {
        container.innerHTML = html;
        console.log('[RELOAD] Histories reloaded successfully');
        
        // Reinitialize Lucide icons
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
      })
      .catch(error => console.error('[RELOAD] Error reloading histories:', error));
    };
    
    // FREE HISTORIES MODAL
    window.openModalFreeHistories = function(plantId) {
      const modal = document.getElementById('plant-modal-content');
      if (!modal) return;
      
      const freeHistoriesModal = modal.querySelector(`#free-histories-modal-${plantId}`);
      if (freeHistoriesModal) {
        freeHistoriesModal.style.display = 'flex';
        console.log('[FREE_HISTORIES] Modal opened for plant:', plantId);
      } else {
        console.warn('[FREE_HISTORIES] Modal not found for plant:', plantId);
      }
    };
    
    window.closeModalFreeHistories = function(plantId) {
      const modal = document.getElementById('plant-modal-content');
      if (!modal) return;
      
      const freeHistoriesModal = modal.querySelector(`#free-histories-modal-${plantId}`);
      if (freeHistoriesModal) {
        freeHistoriesModal.style.display = 'none';
        console.log('[FREE_HISTORIES] Modal closed for plant:', plantId);
      }
    };
    
    
    // WATERING VALIDATION
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
      
      if (!enteredDate) {
        dateError.textContent = 'La date est requise';
        dateError.classList.remove('hidden');
        return false;
      }
      
      if (enteredDate > today) {
        dateError.textContent = 'La date ne peut pas être dans le futur';
        dateError.classList.remove('hidden');
        console.log('[WATERING] Error: Future date blocked');
        return false;
      }
      
      dateError.classList.add('hidden');
      console.log('[WATERING] Date valid, submitting...');
      
      const formData = new FormData(form);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      
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
          alert('Arrosage enregistré !');
          closeQuickWateringModalFromModal();
          reloadHistoriesInModal();
          const plantsModal = document.getElementById('quickPlantsModalFromModal');
          if (plantsModal) {
            plantsModal.classList.remove('hidden');
          }
        } else {
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
    
    // FERTILIZING VALIDATION
    window.handleQuickFertilizingSubmit = function(event) {
      event.preventDefault();
      event.stopPropagation();
      
      const dateInput = document.getElementById('quickFertilizingDateFromModal');
      const dateError = document.getElementById('quickFertilizingDateError');
      const form = document.getElementById('quickFertilizingFormFromModal');
      
      if (!dateInput || !dateError || !form) {
        console.error('[FERTILIZING] Elements not found!');
        return false;
      }
      
      const enteredDate = dateInput.value;
      const today = new Date().toISOString().split('T')[0];
      
      console.log('[FERTILIZING] Date entered:', enteredDate);
      console.log('[FERTILIZING] Today:', today);
      console.log('[FERTILIZING] Is future?', enteredDate > today);
      
      if (!enteredDate) {
        dateError.textContent = 'La date est requise';
        dateError.classList.remove('hidden');
        return false;
      }
      
      if (enteredDate > today) {
        dateError.textContent = 'La date ne peut pas être dans le futur';
        dateError.classList.remove('hidden');
        console.log('[FERTILIZING] Error: Future date blocked');
        return false;
      }
      
      dateError.classList.add('hidden');
      console.log('[FERTILIZING] Date valid, submitting...');
      
      const formData = new FormData(form);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      
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
          alert('Fertilisation enregistrée !');
          closeQuickFertilizingModalFromModal();
          reloadHistoriesInModal();
          const plantsModal = document.getElementById('quickPlantsModalFromModal');
          if (plantsModal) {
            plantsModal.classList.remove('hidden');
          }
        } else {
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
    
    // REPOTTING VALIDATION
    window.handleQuickRepottingSubmit = function(event) {
      event.preventDefault();
      event.stopPropagation();
      
      const dateInput = document.getElementById('quickRepottingDateFromModal');
      const dateError = document.getElementById('quickRepottingDateError');
      const form = document.getElementById('quickRepottingFormFromModal');
      
      if (!dateInput || !dateError || !form) {
        console.error('[REPOTTING] Elements not found!');
        return false;
      }
      
      const enteredDate = dateInput.value;
      const today = new Date().toISOString().split('T')[0];
      
      console.log('[REPOTTING] Date entered:', enteredDate);
      console.log('[REPOTTING] Today:', today);
      console.log('[REPOTTING] Is future?', enteredDate > today);
      
      if (!enteredDate) {
        dateError.textContent = 'La date est requise';
        dateError.classList.remove('hidden');
        return false;
      }
      
      if (enteredDate > today) {
        dateError.textContent = 'La date ne peut pas être dans le futur';
        dateError.classList.remove('hidden');
        console.log('[REPOTTING] Error: Future date blocked');
        return false;
      }
      
      dateError.classList.add('hidden');
      console.log('[REPOTTING] Date valid, submitting...');
      
      const formData = new FormData(form);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      
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
          alert('Rempotage enregistré !');
          closeQuickRepottingModalFromModal();
          reloadHistoriesInModal();
          const plantsModal = document.getElementById('quickPlantsModalFromModal');
          if (plantsModal) {
            plantsModal.classList.remove('hidden');
          }
        } else {
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
    
    // Setup functions for max date
    window.setupQuickWateringModal = function() {
      const dateInput = document.getElementById('quickWateringDateFromModal');
      if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.max = today;
        console.log('[WATERING] Max date set to:', today);
      }
    };
    
    window.setupQuickFertilizingModal = function() {
      const dateInput = document.getElementById('quickFertilizingDateFromModal');
      if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.max = today;
        console.log('[FERTILIZING] Max date set to:', today);
      }
    };
    
    window.setupQuickRepottingModal = function() {
      const dateInput = document.getElementById('quickRepottingDateFromModal');
      if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.max = today;
        console.log('[REPOTTING] Max date set to:', today);
      }
    };
    
    console.log('[INDEX] All quick modal handlers defined');
  </script>
@endsection