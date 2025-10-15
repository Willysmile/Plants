<div id="global-lb" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-6" role="dialog" aria-modal="true" style="display:none;">
  <!-- Conteneur principal pour la photo et la légende, sans les flèches -->
  <div class="relative" style="max-width:100%;max-height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
    <!-- Image -->
    <div style="position:relative;">
      <img id="global-lb-img" src="" alt="" style="max-width:calc(100vw - 40px); max-height:calc(100vh - 120px); width:auto; height:auto; object-fit:contain; border-radius:6px; box-shadow:0 10px 30px rgba(0,0,0,.6);">
    </div>
    
    <!-- Légende -->
    <div id="global-lb-caption" style="color:#fff;margin-top:12px;text-align:center;max-width:100%;word-break:break-word"></div>

    <!-- Compteur avec marge pour éviter la coupure -->
    <div id="global-lb-counter" style="color:#fff;margin-top:8px;margin-bottom:10px;font-size:14px;"></div>
  </div>

  <!-- Croix de fermeture avec un SVG pour correspondre aux flèches -->
  <button id="global-lb-close"
    aria-label="Fermer"
    style="position:fixed;top:20px;right:20px;z-index:61;background:transparent;border:0;cursor:pointer;padding:15px;transition:all 0.2s ease;">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition:stroke 0.2s,transform 0.2s;">
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
  </button>

  <!-- Flèches positionnées de manière fixe sur les côtés -->
  <!-- Flèche gauche SVG -->
  <button id="global-lb-prev" 
    aria-label="Image précédente" 
    style="position:fixed;left:20px;top:50%;transform:translateY(-50%);background:transparent;border:0;cursor:pointer;padding:15px;z-index:60;transition:all 0.2s ease;">
    <svg width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition:stroke 0.2s,transform 0.2s;">
      <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
  </button>

  <!-- Flèche droite SVG -->
  <button id="global-lb-next" 
    aria-label="Image suivante" 
    style="position:fixed;right:20px;top:50%;transform:translateY(-50%);background:transparent;border:0;cursor:pointer;padding:15px;z-index:60;transition:all 0.2s ease;">
    <svg width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition:stroke 0.2s,transform 0.2s;">
      <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
  </button>
</div>

<script>
  (function() {
    const overlay = document.getElementById('global-lb');
    const img = document.getElementById('global-lb-img');
    const cap = document.getElementById('global-lb-caption');
    const counter = document.getElementById('global-lb-counter');
    const closeBtn = document.getElementById('global-lb-close');
    const prevBtn = document.getElementById('global-lb-prev');
    const nextBtn = document.getElementById('global-lb-next');
    
    // Ajouter les effets hover aux flèches et à la croix avec JavaScript pour plus de contrôle
    function addHoverEffect(button) {
      button.addEventListener('mouseover', function() {
        this.querySelector('svg').style.stroke = '#22c55e';
        this.querySelector('svg').style.transform = 'scale(1.1)';
      });
      
      button.addEventListener('mouseout', function() {
        this.querySelector('svg').style.stroke = '#15803d';
        this.querySelector('svg').style.transform = 'scale(1)';
      });
    }
    
    // Appliquer l'effet hover à tous les boutons
    addHoverEffect(prevBtn);
    addHoverEffect(nextBtn);
    addHoverEffect(closeBtn);

    let currentIndex = 0;

    function openByIndex(index) {
      const arr = window.globalLightboxImages || [];
      if (!arr.length) return;

      if (index < 0) index = arr.length - 1;
      if (index >= arr.length) index = 0;

      currentIndex = index;
      img.src = arr[index].url;
      cap.textContent = arr[index].caption || '';
      counter.textContent = `${index + 1} / ${arr.length}`;
      
      // Afficher/masquer les flèches selon le nombre d'images
      if (arr.length <= 1) {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
      } else {
        prevBtn.style.display = 'block';
        nextBtn.style.display = 'block';
      }
      
      show();
    }

    function show() {
      overlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeAll() {
      overlay.style.display = 'none';
      img.src = '';
      cap.textContent = '';
      counter.textContent = '';
      document.body.style.overflow = '';
    }

    function prev() {
      openByIndex(currentIndex - 1);
    }

    function next() {
      openByIndex(currentIndex + 1);
    }

    overlay.addEventListener('click', function(e) {
      if (e.target === overlay) closeAll();
    });
    closeBtn.addEventListener('click', closeAll);
    prevBtn.addEventListener('click', prev);
    nextBtn.addEventListener('click', next);

    document.addEventListener('keydown', function(e) {
      if (overlay.style.display === 'none') return;
      if (e.key === 'Escape') closeAll();
      if (e.key === 'ArrowLeft') prev();
      if (e.key === 'ArrowRight') next();
    });

    window.openLightboxGlobal = openByIndex;
    if (typeof window.openLightbox !== 'function') window.openLightbox = openByIndex;
    window.closeLightboxGlobal = closeAll;
  })();
</script>