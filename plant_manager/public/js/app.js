/**
 * Application Initializer
 * Point d'entrée pour initialiser tous les gestionnaires
 */

// ============================================================
// DEFINE GLOBAL FUNCTIONS FIRST (before DOMContentLoaded)
// ============================================================

/**
 * Fonction globale pour rafraîchir la modale
 */
window.refreshModal = function() {
  // refreshModal called
  
  const modal = document.getElementById('plant-modal-content');
  
  if (!modal) {
    console.error('[REFRESH] No plant-modal-content found');
    return;
  }
  
  const plantModalEl = modal.querySelector('[data-modal-plant-id]');
  
  if (!plantModalEl) {
    console.warn('[REFRESH] No plant modal found');
    return;
  }
  
  const plantId = plantModalEl.getAttribute('data-modal-plant-id');
  
  if (!plantId) {
    console.warn('[REFRESH] No plant ID found');
    return;
  }
  
  // Find the refresh button and its icon
  const refreshButton = modal.querySelector('button[onclick*="refreshModal"]');
  const icon = refreshButton ? refreshButton.querySelector('[data-lucide="refresh-cw"]') : null;
  // refresh button/icon presence logged during development
  
  // Add spinning animation
  if (icon) {
    icon.style.animation = 'spin 1s linear infinite';
  }
  
  // Fetching updated modal HTML
  
  // Fetch the new modal HTML
  fetch(`/plants/${plantId}/modal`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(response => response.text())
  .then(html => {
    modal.innerHTML = html;
  // Modal refreshed successfully
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  })
  .catch(error => console.error('Refresh error:', error))
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
  // open free histories modal
  
  const modal = document.getElementById('plant-modal-content');
  if (!modal) {
    console.warn('[FREE_HISTORIES] Modal container not found');
    return;
  }
  
  const freeHistoriesModal = modal.querySelector(`#free-histories-modal-${plantId}`);
  if (freeHistoriesModal) {
    freeHistoriesModal.style.display = 'flex';
  // modal opened
  } else {
  // modal not found
  }
};

/**
 * Ferme la modale des Infos Diverses dans la modale plants
 */
window.closeModalFreeHistories = function(plantId) {
  // close free histories modal
  
  const modal = document.getElementById('plant-modal-content');
  if (!modal) {
  // modal container not found
    return;
  }
  
  const freeHistoriesModal = modal.querySelector(`#free-histories-modal-${plantId}`);
  if (freeHistoriesModal) {
    freeHistoriesModal.style.display = 'none';
  // modal closed
  }
};

/**
 * Ouvre la modale des Infos Diverses en show.blade.php
 */
window.openFreeHistoriesModal = function(plantId) {
  // open free histories modal (show)
  
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'flex';
  // modal opened
  } else {
  // modal not found
  }
};

/**
 * Ferme la modale des Infos Diverses en show.blade.php
 */
window.closeFreeHistoriesModal = function(plantId) {
  // close free histories modal (show)
  
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'none';
  // modal closed
  }
};

/**
 * Ouvre la modale et réinitialise la galerie à chaque ouverture
 */
window.openModal = function() {
  const modalRoot = document.getElementById('plant-modal-root');
  if (modalRoot && modalRoot.__x) {
    modalRoot.__x.$data.open = true;
    // Réinitialise la galerie à chaque ouverture
    if (typeof GalleryManager !== 'undefined') {
      GalleryManager.init();
    }
  }
};

// ============================================================
// APP INITIALIZATION
// ============================================================

window.App = window.App || {
  /**
   * Initialise l'application
   */
  init() {
    this.setupCloseModalHandler();
    
    if (typeof ModalManager !== 'undefined') {
      ModalManager.init();
    } else {
      // ModalManager not loaded
    }
    
    if (typeof GalleryManager !== 'undefined') {
      GalleryManager.init();
    } else {
      // GalleryManager not loaded
    }
    
    if (typeof FormValidator !== 'undefined') {
      FormValidator.init();
    } else {
      // FormValidator not loaded
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
 * Ferme la modale Alpine dans index.blade.php
 */
window.closeModal = function() {
  console.log('[CLOSE] closeModal called');
  const modalRoot = document.getElementById('plant-modal-root');
  if (modalRoot) {
    modalRoot.style.display = 'none';
    document.body.style.overflow = 'auto';
    console.log('[CLOSE] Modal closed');
  }
};

/**
 * Rafraîchit la page show.blade.php avec animation
 */
window.refreshShowPage = function() {
  console.log('[REFRESH_SHOW] refreshShowPage called');
  console.log('[REFRESH_SHOW] typeof location:', typeof location);
  console.log('[REFRESH_SHOW] location.reload:', typeof location.reload);
  
  // Find the refresh button and animate its icon
  const buttons = document.querySelectorAll('button[onclick*="refreshShowPage"]');
  console.log('[REFRESH_SHOW] Found buttons with onclick:', buttons.length);
  
  buttons.forEach(button => {
    const icon = button.querySelector('[data-lucide="refresh-cw"]');
    if (icon) {
      icon.style.animation = 'spin 1s linear infinite';
    }
  });
  
  // Reload after brief delay to show animation
  setTimeout(() => {
    console.log('[REFRESH_SHOW] Reloading page...');
    location.reload();
  }, 300);
};

/**
 * Ouvre la modale d'ajout de maladie
 * Fonctionne dans le contexte show.blade.php OU modal.blade.php
 */
window.openAddDiseaseModal = function(plantId) {
  const modal = document.getElementById(`add-disease-modal-${plantId}`);
  if (modal) {
    modal.style.display = 'flex';
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  } else {
    console.warn(`[DISEASE] Modal add-disease-modal-${plantId} not found`);
  }
};

/**
 * Ferme la modale d'ajout de maladie (contexte modal.blade.php)
 */
window.closeAddDiseaseModalFromModal = function(plantId) {
  const modal = document.getElementById(`add-disease-modal-${plantId}`);
  if (modal) {
    modal.style.display = 'none';
  }
};

/**
 * Ferme la modale d'ajout de maladie (contexte show.blade.php)
 */
window.closeAddDiseaseModal = function(plantId) {
  const modal = document.getElementById(`add-disease-modal-${plantId}`);
  if (modal) {
    modal.style.display = 'none';
  }
};

/**
 * Bascule l'affichage du champ "nouvelle maladie"
 */
window.toggleNewDiseaseFromModal = function(plantId) {
  const select = document.getElementById(`diseaseSelect-modal-${plantId}`);
  const newDiseaseDiv = document.getElementById(`newDiseaseDiv-modal-${plantId}`);
  if (select && newDiseaseDiv) {
    if (select.value === 'new') {
      newDiseaseDiv.style.display = 'block';
    } else {
      newDiseaseDiv.style.display = 'none';
    }
  }
};

/**
 * Bascule l'affichage du champ "nouvelle maladie" (show.blade.php)
 */
window.toggleNewDisease = function(plantId) {
  const select = document.getElementById(`diseaseSelect-${plantId}`);
  const newDiseaseDiv = document.getElementById(`newDiseaseDiv-${plantId}`);
  if (select && newDiseaseDiv) {
    if (select.value === 'new') {
      newDiseaseDiv.style.display = 'block';
    } else {
      newDiseaseDiv.style.display = 'none';
    }
  }
};

/**
 * Ouvre la modale des maladies (show.blade.php)
 */
window.openDiseasesModal = function(plantId) {
  const modal = document.getElementById(`diseases-modal-${plantId}`);
  if (modal) {
    modal.style.display = 'flex';
  }
};

/**
 * Ouvre la modale des maladies (contexte modal.blade.php)
 */
window.openDiseasesModalFromModal = function(plantId) {
  const modal = document.getElementById(`free-diseases-modal-${plantId}`);
  if (modal) {
    modal.style.display = 'flex';
  }
};

/**
 * Ferme la modale des maladies (contexte modal.blade.php)
 */
window.closeDiseasesModalFromModal = function(plantId) {
  const modal = document.getElementById(`free-diseases-modal-${plantId}`);
  if (modal) {
    modal.style.display = 'none';
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
