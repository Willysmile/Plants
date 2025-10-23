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
  <div id="plant-modal-root" x-data="{ open: false }" x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="plant-modal-backdrop" class="absolute inset-0 bg-black/60" onclick="window.closeModal()"></div>
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
    const reopenPlantsModalIfNeeded = function() {
      const plantsModal = document.getElementById('quickPlantsModalFromModal');
      if (plantsModal) {
        plantsModal.classList.remove('hidden');
      }
    };

    window.setupQuickWateringModal = createQuickModalSetupHandler('quickWateringDateFromModal');
    window.setupQuickFertilizingModal = createQuickModalSetupHandler('quickFertilizingDateFromModal');
    window.setupQuickRepottingModal = createQuickModalSetupHandler('quickRepottingDateFromModal');

    window.handleQuickWateringSubmit = createQuickModalSubmitHandler({
      formId: 'quickWateringFormFromModal',
      dateInputId: 'quickWateringDateFromModal',
      dateErrorId: 'quickWateringDateError',
      successMessage: 'Arrosage enregistré !',
      onSuccess: ({ successMessage }) => {
        if (typeof alertSuccess === 'function') {
          alertSuccess(successMessage, 0);
        }
        closeQuickWateringModalFromModal();
        if (typeof reloadHistoriesInModal === 'function') {
          reloadHistoriesInModal();
        }
        reopenPlantsModalIfNeeded();
      },
    });

    window.handleQuickFertilizingSubmit = createQuickModalSubmitHandler({
      formId: 'quickFertilizingFormFromModal',
      dateInputId: 'quickFertilizingDateFromModal',
      dateErrorId: 'quickFertilizingDateError',
      successMessage: 'Fertilisation enregistrée !',
      onSuccess: ({ successMessage }) => {
        if (typeof alertSuccess === 'function') {
          alertSuccess(successMessage, 0);
        }
        closeQuickFertilizingModalFromModal();
        if (typeof reloadHistoriesInModal === 'function') {
          reloadHistoriesInModal();
        }
        reopenPlantsModalIfNeeded();
      },
    });

    window.handleQuickRepottingSubmit = createQuickModalSubmitHandler({
      formId: 'quickRepottingFormFromModal',
      dateInputId: 'quickRepottingDateFromModal',
      dateErrorId: 'quickRepottingDateError',
      successMessage: 'Rempotage enregistré !',
      onSuccess: ({ successMessage }) => {
        if (typeof alertSuccess === 'function') {
          alertSuccess(successMessage, 0);
        }
        closeQuickRepottingModalFromModal();
        if (typeof reloadHistoriesInModal === 'function') {
          reloadHistoriesInModal();
        }
        reopenPlantsModalIfNeeded();
      },
    });

    console.log('[INDEX] All quick modal handlers defined');
  </script>
@endsection