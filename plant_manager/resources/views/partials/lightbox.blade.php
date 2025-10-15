<div id="global-lb" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-6" role="dialog" aria-modal="true" style="display:none;">
  <div class="relative" style="max-width:100%;max-height:100%;display:flex;align-items:center;justify-content:center;gap:20px;">
    <!-- Flèche gauche SVG -->
    <button id="global-lb-prev" aria-label="Image précédente" style="background:transparent;border:0;cursor:pointer;flex-shrink:0;padding:0;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"></polyline>
      </svg>
    </button>

    <div style="display:flex;flex-direction:column;align-items:center;max-width:calc(100vw - 200px);position:relative;">
      <img id="global-lb-img" src="" alt="" style="max-width:100%; max-height:calc(100vh - 120px); width:auto; height:auto; object-fit:contain; border-radius:6px; box-shadow:0 10px 30px rgba(0,0,0,.6);">

      <!-- Croix de fermeture sur la photo avec effet hover plus clair -->
      <button id="global-lb-close"
        aria-label="Fermer"
        style="position:absolute;top:10px;right:10px;z-index:61;background:transparent;border:0;cursor:pointer;font-size:24px;color:#15803d;font-weight:bold;transition:color 0.2s;"
        onmouseover="this.style.color='#22c55e'"
        onmouseout="this.style.color='#15803d'">✕</button>
      
      <!-- Ajout de la légende manquante -->
      <div id="global-lb-caption" style="color:#fff;margin-top:12px;text-align:center;max-width:100%;word-break:break-word"></div>

      <!-- Compteur avec marge pour éviter la coupure -->
      <div id="global-lb-counter" style="color:#fff;margin-top:8px;margin-bottom:10px;font-size:14px;"></div>
    </div>

    <!-- Flèche droite SVG -->
    <button id="global-lb-next" aria-label="Image suivante" style="background:transparent;border:0;cursor:pointer;flex-shrink:0;padding:0;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6"></polyline>
      </svg>
    </button>
  </div>
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