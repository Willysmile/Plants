/**
 * Gallery Manager
 * Gère les interactions avec les galeries (thumbnails, photo principale)
 */

const GalleryManager = {
  // 🔧 FIX: Stocker l'état des échanges par modal pour restauration
  swapStates: {},  // { plantId: currentMainPhotoIndex, ... }

  /**
   * Initialise le gestionnaire de galerie
   * @param {HTMLElement} scope - Optionnel, élément racine à cibler
   */
  init(scope) {
    // Si on reçoit un scope (ex: élément modal ou container injecté),
    // essayer d'initialiser les images lightbox à partir du JSON inclus dans cette scope/modal
    try {
      let modalEl = null;
      if (scope instanceof HTMLElement) {
        modalEl = scope.closest('[data-modal-plant-id]') || scope.querySelector('[data-modal-plant-id]') || scope;
      }

      if (modalEl) {
        const dataScript = modalEl.querySelector('script[data-lightbox-images]');
        if (dataScript) {
          window.globalLightboxImagesOriginal = JSON.parse(dataScript.textContent);
          // working copy
          window.globalLightboxImages = JSON.parse(JSON.stringify(window.globalLightboxImagesOriginal));
          // lightbox images loaded from modal
        }
        // Restore any previous swap state for this modal
        try {
          this.restoreSwapState(modalEl);
        } catch (err) {
          // restore failed silently
        }
      }

    } catch (err) {
      console.warn('[GALLERY] Failed to init lightbox images from scope', err);
    }

    this.setupThumbnailHandlers(scope);
    this.setupMainPhotoHandlers(scope);
  },

  /**
   * Configure les handlers pour les miniatures
   * @param {HTMLElement} scope
   */
  setupThumbnailHandlers(scope) {
    const root = scope || document;
    root.addEventListener('click', (event) => {
      if (!event.target.closest('[data-type="thumbnail"]')) return;

      const thumbnailBtn = event.target.closest('[data-type="thumbnail"]');
      const modal = thumbnailBtn.closest('[data-modal-plant-id]');

      // thumbnail clicked

      if (!modal) return;

      const thumbnailImg = thumbnailBtn.querySelector('img');
      if (!thumbnailImg) {
        // no img inside thumbnail button
        return;
      }

      // Récupérer l'index de la miniature cliquée
  const thumbIndexAttr = thumbnailBtn.getAttribute('data-lightbox-index') || thumbnailBtn.getAttribute('data-index');
  const thumbIndex = parseInt(thumbIndexAttr || 0);

      // Si la modal n'a pas d'élément main-photo (structure différente du show),
      // ouvrir directement le lightbox avec l'index correspondant.
      const mainPhoto = modal.querySelector('#main-photo-display');
      if (!mainPhoto) {
        // Charger les images lightbox depuis le script JSON présent dans la modal
        const dataScript = modal.querySelector('script[data-lightbox-images]');
          if (dataScript) {
            try {
              window.globalLightboxImages = JSON.parse(dataScript.textContent);
            } catch (err) {
              // failed to parse lightbox JSON in modal
            }
        }

        if (typeof window.openLightboxGlobal === 'function') {
          window.openLightboxGlobal(thumbIndex);
        } else {
          console.warn('[GALLERY] openLightboxGlobal not available to open image in modal');
        }

        return;
      }

      const plantId = modal.getAttribute('data-modal-plant-id');
      let currentSwap = this.swapStates[plantId] || null;

      // Si on clique sur la même miniature que celle déjà swappée => déswap (restauration)
      if (currentSwap && currentSwap.thumbIndex === thumbIndex) {
        this._restoreSnapshot(modal, plantId);
        return;
      }

      // Si un swap précédent existe et on clique sur une miniature différente, restaurer d'abord
      if (currentSwap && currentSwap.thumbIndex !== thumbIndex) {
        // restore previous swap before new swap
        this._restoreSnapshot(modal, plantId);
        currentSwap = null;
      }

      // 🔧 FIX: Sauvegarder les sources AVANT le swap pour la restauration ultérieure
      const mainOriginalSrc = mainPhoto.getAttribute('data-original-src') || mainPhoto.src;
      const thumbOriginalSrc = thumbnailBtn.getAttribute('data-original-src') || thumbnailImg.src;
      const lightboxOriginal = window.globalLightboxImages ? JSON.parse(JSON.stringify(window.globalLightboxImages)) : (window.globalLightboxImagesOriginal ? JSON.parse(JSON.stringify(window.globalLightboxImagesOriginal)) : []);

      // 🔧 FIX: Aussi sauvegarder l'image src AVANT le swap pour updateGalleryThumbnails
      const oldMainPhotoSrc = mainPhoto.src;

      // Échanger les images (loggage pour debug)
      this.swapImages(mainPhoto, thumbnailImg);

      // Mettre à jour l'array lightbox global pour que le lightbox ouvre la bonne image
      this.updateLightboxArray(modal, thumbIndex);

      // 🔧 NOUVEAU FIX: Mettre à jour les miniatures de la galerie après le swap
      // Passer l'ancienne source en paramètre pour que updateGalleryThumbnails sache quoi afficher
      this.updateGalleryThumbnails(modal, thumbnailBtn, mainPhoto, oldMainPhotoSrc);

      // Sauvegarder snapshot pour restauration ultérieure
      this.swapStates[plantId] = {
        thumbIndex,
        mainOriginalSrc,
        thumbOriginalSrc,
        lightboxOriginal
      };

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

    // Force repaint: remplacer les éléments par des clones pour éviter les problèmes de rendu/caching
    try {
      const mainClone = mainPhoto.cloneNode(true);
      mainClone.src = mainPhoto.src; // s'assurer que le clone a la nouvelle source
      mainPhoto.parentNode.replaceChild(mainClone, mainPhoto);

      const thumbParent = thumbnailImg.parentElement;
      const thumbCloneImg = thumbnailImg.cloneNode(true);
      thumbCloneImg.src = thumbnailImg.src;
      // remplacer l'image dans le parent miniature
      thumbParent.replaceChild(thumbCloneImg, thumbnailImg);

  // DOM nodes replaced to force repaint
    } catch (err) {
      console.warn('[GALLERY_SWAP] Failed to force-replace nodes', err);
    }
  },

  /**
   * Met à jour les miniatures de la galerie après un swap
   * Remplace la miniature swappée par une nouvelle avec l'ancienne photo principale
   * @param {HTMLElement} modal - Élément modal
   * @param {HTMLElement} swappedThumb - Miniature qui a été swappée
   * @param {HTMLElement} mainPhoto - Photo principale (après swap, contient la nouvelle image)
   * @param {string} oldMainPhotoSrc - Source de l'ancienne photo principale (avant swap)
   */
  updateGalleryThumbnails(modal, swappedThumb, mainPhoto, oldMainPhotoSrc) {
    try {
      // On doit reconstruire la miniature : remplacer la source de la miniature cliquée
      // par l'ancienne source de la photo principale
      
      // 1. Cloner la miniature swappée pour créer la "nouvelle" miniature
      const newThumbBtn = swappedThumb.cloneNode(true);
      
      // 2. Mettre à jour les sources pour afficher l'ancienne photo principale
      newThumbBtn.setAttribute('data-original-src', oldMainPhotoSrc);
      
      const newImg = newThumbBtn.querySelector('img');
      if (newImg) {
        // Utiliser la source de l'ancienne photo principale
        newImg.src = oldMainPhotoSrc;
        newImg.setAttribute('alt', mainPhoto.getAttribute('alt') || 'Ancienne photo principale');
      }

      // 3. Remplacer la miniature cliquée par cette nouvelle miniature
      // Cela va faire apparaître l'ancienne photo principale à la place de la miniature
      swappedThumb.replaceWith(newThumbBtn);

      // 4. Réinitialiser les event listeners sur la nouvelle miniature
      this.setupThumbnailHandlers(modal);

      // 5. DEBUG: Ajouter un badge pour voir que le swap a été effectué
      // (optionnel, à retirer après tests)
      newThumbBtn.style.opacity = '0.8';
      newThumbBtn.title = 'Ancienne photo principale (clique pour restaurer)';

    } catch (err) {
      console.warn('[GALLERY] Failed to update gallery thumbnails after swap', err);
    }
  },

  /**
   * Met à jour l'array lightbox global après échange de photos
   * Réorganise les images pour que la photo principale soit toujours en index 0
   * @param {HTMLElement} modal - Élément modal
   * @param {number} thumbIndex - Index de la miniature qui est devenue principale
   */
  updateLightboxArray(modal, thumbIndex) {
    // Priorité: reconstruire l'array depuis le JSON inclus dans la modal si présent
    let arr = [];
    try {
      const dataScript = modal ? modal.querySelector('script[data-lightbox-images]') : null;
      if (dataScript) {
        arr = JSON.parse(dataScript.textContent) || [];
  // Rebuilt lightbox array from modal JSON
      } else {
        arr = window.globalLightboxImages || [];
      }
    } catch (err) {
      console.warn('[GALLERY] Failed to parse modal lightbox JSON, falling back to global array', err);
      arr = window.globalLightboxImages || [];
    }

    if (!arr.length) return;

    // Si c'est déjà la photo principale (index 0), pas besoin de réorganiser
    if (thumbIndex === 0) return;

    // Sécurité: clamp thumbIndex dans les limites de l'array
    const idx = Math.max(0, Math.min(thumbIndex, arr.length - 1));

    const reordered = [arr[idx], ...arr.slice(0, idx), ...arr.slice(idx + 1)];
    window.globalLightboxImages = reordered;
  // Updated globalLightboxImages
  },

  /**
   * 🔧 FIX: Restaure l'état des échanges quand le modal se réouvre
   * @param {HTMLElement} modal - Élément modal
   */
  restoreSwapState(modal) {
    const plantId = modal.getAttribute('data-modal-plant-id');
    const savedThumbIndex = this.swapStates[plantId];

  // restoreSwapState called

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
,

  /**
   * Restaurer l'état sauvegardé dans swapStates[plantId]
   * @param {HTMLElement} modal
   * @param {string} plantId
   */
  _restoreSnapshot(modal, plantId) {
    const snapshot = this.swapStates[plantId];
    if (!snapshot) return;

    try {
      const mainPhoto = modal.querySelector('#main-photo-display');
      const currentThumbBtn = modal.querySelector(`[data-type="thumbnail"][data-index="${snapshot.thumbIndex}"]`);

      // 1. Restaurer la photo principale
      if (mainPhoto && snapshot.mainOriginalSrc) {
        mainPhoto.src = snapshot.mainOriginalSrc;
        mainPhoto.setAttribute('data-original-src', snapshot.mainOriginalSrc);
      }

      // 2. Restaurer la miniature cliquée (elle a été remplacée après le swap)
      // Pour cela, créer une nouvelle miniature avec les sources originales
      if (currentThumbBtn) {
        const restoredThumbBtn = currentThumbBtn.cloneNode(true);
        restoredThumbBtn.setAttribute('data-original-src', snapshot.thumbOriginalSrc);
        
        const restoredImg = restoredThumbBtn.querySelector('img');
        if (restoredImg) {
          restoredImg.src = snapshot.thumbOriginalSrc;
        }
        
        // Restaurer l'opacité (en cas de style appliqué après swap)
        restoredThumbBtn.style.opacity = '1';
        restoredThumbBtn.title = '';
        
        // Remplacer la miniature swappée par la miniature restaurée
        currentThumbBtn.replaceWith(restoredThumbBtn);
        
        // Réinitialiser les event listeners
        this.setupThumbnailHandlers(modal);
      }

      // 3. Restaurer l'array lightbox
      if (snapshot.lightboxOriginal) {
        window.globalLightboxImages = JSON.parse(JSON.stringify(snapshot.lightboxOriginal));
      }

      // 4. Nettoyer snapshot
      delete this.swapStates[plantId];
      modal.removeAttribute('data-active-thumb');
    } catch (err) {
      console.warn('[GALLERY] Failed to restore snapshot', err);
    }
  }
};

// Exporter pour utilisation globale
window.GalleryManager = GalleryManager;