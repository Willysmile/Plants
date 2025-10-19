/**
 * Gallery Manager
 * Gère les interactions avec les galeries (thumbnails, photo principale)
 */

const GalleryManager = {
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

      // Échanger les images
      this.swapImages(mainPhoto, thumbnailImg);

      // Mettre à jour l'array lightbox global pour que le lightbox ouvre la bonne image
      this.updateLightboxArray(modal, thumbIndex);

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