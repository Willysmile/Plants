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

/**
 * Fonction globale pour rafraîchir la modale
 */
window.refreshModal = function() {
  console.log('[REFRESH] refreshModal called');
  console.log('[REFRESH] window.refreshModal exists:', typeof window.refreshModal);
  
  const modal = document.getElementById('plant-modal-content');
  console.log('[REFRESH] modal found:', !!modal);
  
  if (!modal) {
    console.error('[REFRESH] No plant-modal-content found');
    return;
  }
  
  const plantModalEl = modal.querySelector('[data-modal-plant-id]');
  console.log('[REFRESH] plantModalEl found:', !!plantModalEl);
  
  if (!plantModalEl) {
    console.warn('[REFRESH] No plant modal found');
    return;
  }
  
  const plantId = plantModalEl.getAttribute('data-modal-plant-id');
  console.log('[REFRESH] plantId:', plantId);
  
  if (!plantId) {
    console.warn('[REFRESH] No plant ID found');
    return;
  }
  
  // Find the refresh button and its icon
  const refreshButton = modal.querySelector('button[onclick*="refreshModal"]');
  const icon = refreshButton ? refreshButton.querySelector('[data-lucide="refresh-cw"]') : null;
  console.log('[REFRESH] refreshButton found:', !!refreshButton);
  console.log('[REFRESH] icon found:', !!icon);
  
  // Add spinning animation
  if (icon) {
    icon.style.animation = 'spin 1s linear infinite';
  }
  
  console.log('[REFRESH] Fetching /plants/' + plantId + '/modal');
  
  // Fetch the new modal HTML
  fetch(`/plants/${plantId}/modal`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(response => response.text())
  .then(html => {
    modal.innerHTML = html;
    console.log('[REFRESH] Modal refreshed successfully');
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  })
  .catch(error => console.error('[REFRESH] Error:', error))
  .finally(() => {
    if (icon) {
      icon.style.animation = 'none';
    }
  });
};

/**
 * Ouvre la modale des Infos Diverses dans la modale plants
 */
window.openModalFreeHistories = function(plantId) {
  console.log('[FREE_HISTORIES] Opening modal for plant:', plantId);
  
  const modal = document.getElementById('plant-modal-content');
  if (!modal) {
    console.warn('[FREE_HISTORIES] Modal container not found');
    return;
  }
  
  const freeHistoriesModal = modal.querySelector(`#free-histories-modal-${plantId}`);
  if (freeHistoriesModal) {
    freeHistoriesModal.style.display = 'flex';
    console.log('[FREE_HISTORIES] Modal opened for plant:', plantId);
  } else {
    console.warn('[FREE_HISTORIES] Modal not found for plant:', plantId);
  }
};

/**
 * Ferme la modale des Infos Diverses dans la modale plants
 */
window.closeModalFreeHistories = function(plantId) {
  console.log('[FREE_HISTORIES] Closing modal for plant:', plantId);
  
  const modal = document.getElementById('plant-modal-content');
  if (!modal) {
    console.warn('[FREE_HISTORIES] Modal container not found');
    return;
  }
  
  const freeHistoriesModal = modal.querySelector(`#free-histories-modal-${plantId}`);
  if (freeHistoriesModal) {
    freeHistoriesModal.style.display = 'none';
    console.log('[FREE_HISTORIES] Modal closed for plant:', plantId);
  }
};

/**
 * Ouvre la modale des Infos Diverses en show.blade.php
 */
window.openFreeHistoriesModal = function(plantId) {
  console.log('[FREE_HISTORIES_SHOW] Opening modal for plant:', plantId);
  
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'flex';
    console.log('[FREE_HISTORIES_SHOW] Modal opened for plant:', plantId);
  } else {
    console.warn('[FREE_HISTORIES_SHOW] Modal not found for plant:', plantId);
  }
};

/**
 * Ferme la modale des Infos Diverses en show.blade.php
 */
window.closeFreeHistoriesModal = function(plantId) {
  console.log('[FREE_HISTORIES_SHOW] Closing modal for plant:', plantId);
  
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'none';
    console.log('[FREE_HISTORIES_SHOW] Modal closed for plant:', plantId);
  }
};
