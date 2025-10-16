<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Plantes</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-7xl mx-auto p-6">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Plantes</h1>
      <a href="{{ route('plants.create') }}" class="px-3 py-1 bg-blue-600 text-white rounded">Ajouter</a>
    </header>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
      @foreach($plants as $plant)
        <article class="bg-white rounded-lg shadow overflow-hidden">
          <div class="w-full h-48 bg-gray-100 overflow-hidden">
            <button
              type="button"
              class="w-full h-full block focus:outline-none"
              data-modal-url="{{ route('plants.modal', $plant) }}"
              aria-label="Ouvrir {{ $plant->name }}"
            >
              @if($plant->main_photo)
                <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">Pas d'image</div>
              @endif
            </button>
          </div>

          <div class="p-3">
            <h3 class="text-sm font-medium text-gray-800 truncate" title="{{ $plant->name }}">{{ $plant->name }}</h3>
            <p class="text-xs text-gray-500 mt-1 truncate">{{ $plant->category->name ?? '—' }}</p>
            <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
              <span>Arrosage: {{ \App\Models\Plant::$wateringLabels[$plant->watering_frequency] ?? $plant->watering_frequency }}</span>
              <a href="{{ route('plants.show', $plant) }}" class="text-blue-600 hover:underline">Détails</a>
            </div>
          </div>
        </article>
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

  <script>
    (function(){
      const modalRoot = document.getElementById('plant-modal-root');
      const modalContent = document.getElementById('plant-modal-content');
      
      function showModalHtml(html){
        modalContent.innerHTML = html;
        modalRoot.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // charger les images depuis le script JSON embarqué dans la modal
        const dataScript = modalContent.querySelector('script[data-lightbox-images]');
        if (dataScript) {
          try {
            window.globalLightboxImages = JSON.parse(dataScript.textContent);
            console.log('Images modal chargées:', window.globalLightboxImages.length);
          } catch(e) {
            console.error('Erreur parsing images modal:', e);
          }
        }
        
        // Réinitialiser les icônes Lucide après le chargement du contenu
        if (typeof lucide !== 'undefined') {
          console.log('Lucide trouvé, réinitialisation des icônes');
          setTimeout(() => {
            lucide.createIcons();
            console.log('Icônes Lucide réinitialisées');
          }, 50);
        } else {
          console.log('Lucide non trouvé');
        }
      }
      
      window.closeModal = function(){
        modalRoot.style.display = 'none';
        modalContent.innerHTML = '';
        document.body.style.overflow = '';
        window.globalLightboxImages = []; // nettoyer
      };
      
      document.addEventListener('click', function(e){
        const btn = e.target.closest('button[data-modal-url]');
        if(!btn) return;
        e.preventDefault();
        const url = btn.getAttribute('data-modal-url');
        if(!url) return;
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
          .then(r => {
            if(!r.ok) throw new Error('Erreur de chargement');
            return r.text();
          })
          .then(html => showModalHtml(html))
          .catch(err => {
            console.error(err);
            alert('Impossible de charger la fiche. Voir console.');
          });
      });

      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') window.closeModal();
      });
    })();
  </script>
  <!-- Juste avant la fermeture de </body>, ajoutez ce code -->
<script>
// Gestionnaire global pour les miniatures et la photo principale
document.addEventListener('click', function(event) {
  // Vérifiez si l'élément cliqué est une miniature
  if (event.target.closest('[data-type="thumbnail"]')) {
    const thumbnailBtn = event.target.closest('[data-type="thumbnail"]');
    const modal = thumbnailBtn.closest('[data-modal-plant-id]');
    
    if (!modal) return;
    
    const mainPhoto = modal.querySelector('#main-photo-display');
    if (!mainPhoto) return;
    
    const thumbnailImg = thumbnailBtn.querySelector('img');
    if (!thumbnailImg) return;
    
    // Échanger les images
    const mainSrc = mainPhoto.src;
    const thumbSrc = thumbnailImg.src;
    
    mainPhoto.src = thumbSrc;
    thumbnailImg.src = mainSrc;
    
    // Marquer cette miniature comme active
    modal.setAttribute('data-active-thumb', thumbnailBtn.getAttribute('data-index'));
    console.log('Thumbnail clicked, swapped images');
  }
  
  // Vérifiez si l'élément cliqué est la photo principale
  if (event.target.matches('[data-type="main-photo"]')) {
    const mainPhoto = event.target;
    const modal = mainPhoto.closest('[data-modal-plant-id]');
    
    if (!modal) return;
    
    const activeThumbIndex = modal.getAttribute('data-active-thumb');
    if (!activeThumbIndex) return;
    
    const thumbnailBtn = modal.querySelector(`[data-type="thumbnail"][data-index="${activeThumbIndex}"]`);
    if (!thumbnailBtn) return;
    
    const thumbnailImg = thumbnailBtn.querySelector('img');
    if (!thumbnailImg) return;
    
    // Échanger les images
    const mainSrc = mainPhoto.src;
    const thumbSrc = thumbnailImg.src;
    
    mainPhoto.src = thumbSrc;
    thumbnailImg.src = mainSrc;
    
    console.log('Main photo clicked, swapped with active thumbnail');
  }
  
  // Vérifiez si l'élément cliqué est le bouton de fermeture
  if (event.target.closest('.modal-close')) {
    const closeBtn = event.target.closest('.modal-close');
    const modal = closeBtn.closest('[data-modal-plant-id]');
    
    if (!modal) return;
    
    // Réinitialiser les images
    const mainPhoto = modal.querySelector('#main-photo-display');
    if (mainPhoto) {
      const originalSrc = mainPhoto.getAttribute('data-original-src');
      if (originalSrc) mainPhoto.src = originalSrc;
    }
    
    // Réinitialiser les miniatures
    modal.querySelectorAll('[data-type="thumbnail"]').forEach(thumb => {
      const originalSrc = thumb.getAttribute('data-original-src');
      if (originalSrc) {
        const img = thumb.querySelector('img');
        if (img) img.src = originalSrc;
      }
    });
    
    // Réinitialiser l'état
    modal.removeAttribute('data-active-thumb');
    
    // Fermer la modal
    if (window.closeModal) {
      window.closeModal();
    }
    
    console.log('Modal closed and reset');
  }
});

console.log('Global gallery handler initialized');
  </script>


  @include('partials.lightbox')
</body>
</html>