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

      // Échanger les images
      this.swapImages(mainPhoto, thumbnailImg);

      // Marquer cette miniature comme active
      modal.setAttribute('data-active-thumb', thumbnailBtn.getAttribute('data-index'));
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

      const activeThumbIndex = modal.getAttribute('data-active-thumb');
      if (!activeThumbIndex) return;

      const thumbnailBtn = modal.querySelector(
        `[data-type="thumbnail"][data-index="${activeThumbIndex}"]`
      );
      if (!thumbnailBtn) return;

      const thumbnailImg = thumbnailBtn.querySelector('img');
      if (!thumbnailImg) return;

      // Échanger les images
      this.swapImages(mainPhoto, thumbnailImg);
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

    mainPhoto.src = thumbSrc;
    thumbnailImg.src = mainSrc;
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
