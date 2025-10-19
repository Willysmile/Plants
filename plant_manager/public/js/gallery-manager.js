/**
 * Gallery Manager
 * Gère les interactions avec les galeries (thumbnails, photo principale)
 */

const GalleryManager = {
  // 🔧 FIX: Stocker l'état des échanges par modal pour restauration
  swapStates: {},  // { plantId: currentMainPhotoIndex, ... }

  /**
   * Initialise le gestionnaire de galerie
   */
  init() {
    this.setupThumbnailHandlers();
    this.setupMainPhotoHandlers();
  },

  /**
   * Configure les handlers pour les miniatures
   */
  setupThumbnailHandlers() {
    document.addEventListener('click', (event) => {
      if (!event.target.closest('[data-type="thumbnail"]')) return;

      const thumbnailBtn = event.target.closest('[data-type="thumbnail"]');
      const modal = thumbnailBtn.closest('[data-modal-plant-id]');

      if (!modal) return;

      const mainPhoto = modal.querySelector('#main-photo-display');
      if (!mainPhoto) return;

      const thumbnailImg = thumbnailBtn.querySelector('img');
      if (!thumbnailImg) return;

      // Récupérer l'index de la miniature cliquée
      const thumbIndex = parseInt(thumbnailBtn.getAttribute('data-index') || 0);

      // 🔧 FIX: Si on clique sur la miniature qui est déjà la photo principale,
      // on la "déswap" - on revient à l'état précédent
      const plantId = modal.getAttribute('data-modal-plant-id');
      const currentSwapState = this.swapStates[plantId];

      if (currentSwapState === thumbIndex) {
        // Déjà swappée avec cette miniature - annuler le swap
        delete this.swapStates[plantId];
        
        // 🔧 IMPORTANT: Réinitialiser l'array lightbox à l'ordre original
        // Charger le script JSON embarqué pour remettre l'ordre original
        const dataScript = modal.querySelector('script[data-lightbox-images]');
        if (dataScript) {
          try {
            window.globalLightboxImages = JSON.parse(dataScript.textContent);
          } catch (e) {
            console.error('Erreur réinitialisation lightbox:', e);
          }
        }
        
        return;
      }

      // Échanger les images
      this.swapImages(mainPhoto, thumbnailImg);

      // Mettre à jour l'array lightbox global pour que le lightbox ouvre la bonne image
      this.updateLightboxArray(modal, thumbIndex);

      // 🔧 FIX: Sauvegarder l'état de l'échange pour cette plante
      this.swapStates[plantId] = thumbIndex;

      // Marquer cette miniature comme active
      modal.setAttribute('data-active-thumb', thumbIndex);
    });
  },

  /**
   * Configure les handlers pour la photo principale
   */
  setupMainPhotoHandlers() {
    document.addEventListener('click', (event) => {
      if (!event.target.matches('[data-type="main-photo"]')) return;

      const mainPhoto = event.target;
      const modal = mainPhoto.closest('[data-modal-plant-id]');

      if (!modal) return;

      // Ouvrir le lightbox avec l'index 0 (photo principale est toujours la 1ère)
      if (typeof window.openLightboxGlobal === 'function') {
        window.openLightboxGlobal(0);
      }
    });
  },

  /**
   * Échange deux images
   * @param {HTMLElement} mainPhoto - Élément photo principale
   * @param {HTMLElement} thumbnailImg - Élément miniature
   */
  swapImages(mainPhoto, thumbnailImg) {
    const mainSrc = mainPhoto.src;
    const thumbSrc = thumbnailImg.src;

    // Échanger les sources visuelles
    mainPhoto.src = thumbSrc;
    thumbnailImg.src = mainSrc;

    // 🔧 FIX: Échanger aussi les data-original-src pour que le lightbox utilise la bonne photo
    const mainDataSrc = mainPhoto.getAttribute('data-original-src');
    const thumbDataSrc = thumbnailImg.parentElement.getAttribute('data-original-src');

    if (mainDataSrc && thumbDataSrc) {
      mainPhoto.setAttribute('data-original-src', thumbDataSrc);
      thumbnailImg.parentElement.setAttribute('data-original-src', mainDataSrc);
    }
  },

  /**
   * Met à jour l'array lightbox global après échange de photos
   * Réorganise les images pour que la photo principale soit toujours en index 0
   * @param {HTMLElement} modal - Élément modal
   * @param {number} thumbIndex - Index de la miniature qui est devenue principale
   */
  updateLightboxArray(modal, thumbIndex) {
    const arr = window.globalLightboxImages || [];
    if (!arr.length) return;

    // Si c'est déjà la photo principale (index 0), pas besoin de réorganiser
    if (thumbIndex === 0) return;

    // Créer un nouveau tableau avec la photo à thumbIndex en premier
    const reordered = [arr[thumbIndex], ...arr.slice(0, thumbIndex), ...arr.slice(thumbIndex + 1)];

    // Mettre à jour l'array global
    window.globalLightboxImages = reordered;
  },

  /**
   * 🔧 FIX: Restaure l'état des échanges quand le modal se réouvre
   * @param {HTMLElement} modal - Élément modal
   */
  restoreSwapState(modal) {
    const plantId = modal.getAttribute('data-modal-plant-id');
    const savedThumbIndex = this.swapStates[plantId];

    if (!savedThumbIndex || savedThumbIndex === 0) return;

    // Récupérer les éléments
    const mainPhoto = modal.querySelector('#main-photo-display');
    const thumbnailBtn = modal.querySelector(`[data-type="thumbnail"][data-index="${savedThumbIndex}"]`);

    if (!mainPhoto || !thumbnailBtn) return;

    const thumbnailImg = thumbnailBtn.querySelector('img');
    if (!thumbnailImg) return;

    // Reappliquer l'échange visuel
    const mainSrc = mainPhoto.src;
    const thumbSrc = thumbnailImg.src;
    mainPhoto.src = thumbSrc;
    thumbnailImg.src = mainSrc;

    // Reappliquer l'échange data-*
    const mainDataSrc = mainPhoto.getAttribute('data-original-src');
    const thumbDataSrc = thumbnailBtn.getAttribute('data-original-src');
    if (mainDataSrc && thumbDataSrc) {
      mainPhoto.setAttribute('data-original-src', thumbDataSrc);
      thumbnailBtn.setAttribute('data-original-src', mainDataSrc);
    }

    // Réorganiser l'array lightbox
    this.updateLightboxArray(modal, savedThumbIndex);

    // Marquer comme actif
    modal.setAttribute('data-active-thumb', savedThumbIndex);
  },

  /**
   * Réinitialise les images après fermeture modal
   * @param {HTMLElement} modal - Élément modal
   */
  resetImages(modal) {
    const mainPhoto = modal.querySelector('#main-photo-display');
    if (mainPhoto) {
      const originalSrc = mainPhoto.getAttribute('data-original-src');
      if (originalSrc) mainPhoto.src = originalSrc;
    }

    modal.querySelectorAll('[data-type="thumbnail"]').forEach((thumb) => {
      const originalSrc = thumb.getAttribute('data-original-src');
      if (originalSrc) {
        const img = thumb.querySelector('img');
        if (img) img.src = originalSrc;
      }
    });

    modal.removeAttribute('data-active-thumb');
  }
};

// Exporter pour utilisation globale
window.GalleryManager = GalleryManager;