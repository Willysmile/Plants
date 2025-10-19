@extends('layouts.app')

@section('title', 'Plantes Archivées')

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-semibold text-gray-600">Plantes Archivées</h1>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300">{{ $plants->count() }}</span>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('plants.index') }}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition">← Retour aux plantes</a>
      </div>
    </header>

    <!-- Message si aucune plante archivée -->
    @if($plants->isEmpty())
      <div class="text-center py-12">
        <p class="text-gray-500 text-lg">Aucune plante archivée pour le moment.</p>
        <a href="{{ route('plants.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
          Voir toutes les plantes
        </a>
      </div>
    @else
      <!-- Grille de plantes archivées -->
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
    @endif
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
    const allCards = Array.from(gridContainer?.querySelectorAll('article') || []);
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
      pageInfo.textContent = `Page ${currentPage + 1} / ${totalPages}`;
    }
    
    prevBtn?.addEventListener('click', () => showPage(currentPage - 1));
    nextBtn?.addEventListener('click', () => showPage(currentPage + 1));
    
    // Initialize
    showPage(0);
  </script>
@endsection
