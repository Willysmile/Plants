@extends('layouts.app')

@section('title', 'Plantes')

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Plantes</h1>
      <div class="flex items-center gap-3">
        <a href="{{ route('settings.index') }}" class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded text-sm transition">⚙️ Paramètres</a>
        <a href="{{ route('plants.create') }}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition">Ajouter</a>
      </div>
    </header>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      @foreach($plants as $plant)
        <x-plant-card :plant="$plant" />
      @endforeach
    </div>

    <div class="mt-6">
      {{ $plants->links() }}
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
  
  <!-- Quick Modal Validation Scripts (must be loaded at page init, before AJAX modals load) -->
  <script>
    console.log('[INDEX] Defining global form handlers for quick modals');
    
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