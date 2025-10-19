/**
 * Application Initializer
 * Point d'entrée pour initialiser tous les gestionnaires
 */

const App = {
  /**
   * Initialise l'application
   */
  init() {
    this.setupCloseModalHandler();
    
    if (typeof ModalManager !== 'undefined') {
      ModalManager.init();
    } else {
      console.warn('ModalManager not loaded');
    }
    
    if (typeof GalleryManager !== 'undefined') {
      GalleryManager.init();
    } else {
      console.warn('GalleryManager not loaded');
    }
    
    if (typeof FormValidator !== 'undefined') {
      FormValidator.init();
    } else {
      console.warn('FormValidator not loaded');
    }
  },

  /**
   * Configure le gestionnaire de fermeture de modale
   */
  setupCloseModalHandler() {
    document.addEventListener('click', (event) => {
      const closeBtn = event.target.closest('.modal-close');
      if (!closeBtn) return;

      const modal = closeBtn.closest('[data-modal-plant-id]');
      if (!modal) return;

      // Réinitialiser les images
      GalleryManager.resetImages(modal);

      // Fermer la modal
      if (window.ModalManager) {
        window.ModalManager.close();
      }
    });
  }
};

/**
 * Initialise l'app au chargement du DOM
 */
document.addEventListener('DOMContentLoaded', () => {
  App.init();
});

// Exporter pour utilisation globale
window.App = App;
