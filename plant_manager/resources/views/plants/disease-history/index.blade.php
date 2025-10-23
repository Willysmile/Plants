@extends('layouts.app')

@section('title', $plant->name . ' - Historique des maladies')

@section('content')
  <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $plant->name }}</h1>
        <p class="text-gray-600">🦠 Historique des maladies</p>
      </div>
      <div class="space-x-3">
        <a href="{{ route('plants.show', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
          Retour
        </a>
        <button onclick="openQuickDiseaseModalFromModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          + Ajouter une maladie
        </button>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
      </div>
    @endif

    <!-- Liste des maladies -->
    <div class="bg-white rounded-lg shadow-md">
      @if($diseaseHistories->count())
        <div class="divide-y">
          @foreach($diseaseHistories as $disease)
            <div class="p-6 hover:bg-gray-50 transition">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $disease->disease->name }}</h3>
                    @php
                      $statusLabels = [
                        'detected' => 'Détecté',
                        'treated' => 'Traité',
                        'cured' => 'Guéri',
                        'recurring' => 'Récurrent'
                      ];
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $disease->status_color }}">
                      {{ $statusLabels[$disease->status] ?? ucfirst($disease->status) }}
                    </span>
                  </div>
                  
                  <div class="grid grid-cols-2 gap-4 mb-3 text-sm">
                    <div>
                      <p class="text-gray-500">Détecté le</p>
                      <p class="text-gray-900 font-medium">{{ $disease->detected_at->format('d/m/Y') }}</p>
                    </div>
                    @if($disease->treated_at)
                      <div>
                        <p class="text-gray-500">Traité le</p>
                        <p class="text-gray-900 font-medium">{{ $disease->treated_at->format('d/m/Y') }}</p>
                      </div>
                    @endif
                  </div>

                  @if($disease->description)
                    <div class="mb-3">
                      <p class="text-gray-500 text-sm font-medium mb-1">Symptômes</p>
                      <p class="text-gray-700 whitespace-pre-wrap">{{ $disease->description }}</p>
                    </div>
                  @endif

                  @if($disease->treatment)
                    <div>
                      <p class="text-gray-500 text-sm font-medium mb-1">Traitement appliqué</p>
                      <p class="text-gray-700 whitespace-pre-wrap">{{ $disease->treatment }}</p>
                    </div>
                  @endif
                </div>

                <div class="ml-4 flex gap-2">
                  <button 
                    onclick="editDiseaseHistory({{ $disease->id }}, '{{ $disease->disease->name }}', '{{ $disease->description }}', '{{ $disease->treatment }}', '{{ $disease->detected_at->format('Y-m-d') }}', '{{ optional($disease->treated_at)->format('Y-m-d') }}', '{{ $disease->status }}')"
                    class="text-blue-500 hover:text-blue-700 font-semibold text-sm flex items-center gap-1"
                  >
                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                    Éditer
                  </button>
                  <form action="{{ route('plants.disease-history.destroy', [$plant, $disease]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm flex items-center gap-1">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                      Supprimer
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="p-12 text-center">
          <p class="text-gray-500 text-lg">Aucune maladie enregistrée pour cette plante.</p>
          <button onclick="openQuickDiseaseModalFromModal()" class="mt-4 text-blue-500 hover:text-blue-700 font-semibold">
            + Ajouter une maladie
          </button>
        </div>
      @endif
    </div>
  </div>

  <!-- Include le quick disease modal -->
  @include('components.quick-disease-modal')
@endsection

@push('scripts')
  <script src="{{ asset('js/quick-modals-manager.js') }}"></script>
  <script>
    // Stocker la plante ID pour l'utiliser dans les modals
    window.currentPlantId = {{ $plant->id }};

    // Wrapper functions pour disease modal
    window.openQuickDiseaseModalFromModal = function() {
      const modal = document.getElementById('quick-disease-modal-{{ $plant->id }}');
      if (modal) {
        modal.style.display = 'flex';
      }
    };

    window.closeQuickDiseaseModalFromModal = function() {
      const modal = document.getElementById('quick-disease-modal-{{ $plant->id }}');
      if (modal) {
        modal.style.display = 'none';
      }
      // Réinitialiser le formulaire
      const form = document.getElementById('quick-disease-form-{{ $plant->id }}');
      if (form) {
        form.reset();
      }
    };

    // Fonction pour rafraîchir la page après ajout
    window.refreshModal = function() {
      location.reload();
    };

    // Initialiser le modal au chargement
    document.addEventListener('DOMContentLoaded', function() {
      setupQuickDiseaseModal({{ $plant->id }});
      // Initialiser les icônes Lucide
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });
  </script>
@endpush
