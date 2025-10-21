/**
 * Modal Manager
 * Gère le chargement et l'affichage des modales AJAX pour les détails plantes
 */

const ModalManager = {
  modalRoot: null,
  modalContent: null,

  /**
   * Initialise le gestionnaire modal
   */
  init() {
    this.modalRoot = document.getElementById('plant-modal-root');
    this.modalContent = document.getElementById('plant-modal-content');
    
    if (!this.modalRoot || !this.modalContent) {
      console.error('Modal elements not found');
      return;
    }

    this.setupEventListeners();
    this.setupKeyboardHandler();
  },

  /**
   * Configure les listeners pour les boutons modaux
   */
  setupEventListeners() {
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-modal-url]');
      if (!btn) return;
      
      e.preventDefault();
      const url = btn.getAttribute('data-modal-url');
      if (!url) return;
      
      this.load(url);
    });
  },

  /**
   * Configure le clavier (Escape pour fermer)
   */
  setupKeyboardHandler() {
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') this.close();
    });
  },

  /**
   * Charge le contenu modal via AJAX
   * @param {string} url - URL du contenu modal
   */
  async load(url) {
    try {
      const response = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      if (!response.ok) {
        throw new Error('Erreur de chargement');
      }

      const html = await response.text();
      this.display(html);
    } catch (err) {
      console.error('Modal loading error:', err);
      alert('Impossible de charger la fiche. Voir console.');
    }
  },

  /**
   * Affiche le contenu modal
   * @param {string} html - Contenu HTML de la modal
   */
  display(html) {
    this.modalContent.innerHTML = html;
    this.modalRoot.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Charger les images lightbox
    this.loadLightboxImages();

    // 🔧 FIX: Restaurer l'état des échanges si une photo avait été changée
    if (typeof GalleryManager !== 'undefined') {
      const modal = this.modalContent.querySelector('[data-modal-plant-id]');
      if (modal) {
        GalleryManager.restoreSwapState(modal);
      }
    }

    // Réinitialiser les icônes Lucide
    // Ensure lucide is loaded, then call createIcons() (with retries)
    const ensureLucideLoaded = () => {
      return new Promise((resolve, reject) => {
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
          return resolve(lucide);
        }

        // Avoid inserting multiple script tags
        if (document.querySelector('script[data-lucide-loader]')) {
          // wait for it to load
          const existing = document.querySelector('script[data-lucide-loader]');
          existing.addEventListener('load', () => {
            if (typeof lucide !== 'undefined') resolve(lucide);
            else reject(new Error('lucide did not initialize'));
          });
          existing.addEventListener('error', () => reject(new Error('Failed to load lucide')));
          return;
        }

        const s = document.createElement('script');
        s.setAttribute('src', 'https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.min.js');
        s.setAttribute('data-lucide-loader', '1');
        s.async = true;
        s.onload = () => {
          if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            resolve(lucide);
          } else {
            reject(new Error('lucide loaded but not available'));
          }
        };
        s.onerror = () => reject(new Error('Failed to load lucide script'));
        document.head.appendChild(s);
      });
    };

    const runCreateIconsWithRetry = (attemptsLeft = 2) => {
      try {
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
          lucide.createIcons();
          return Promise.resolve();
        }
      } catch (e) {
        // fallthrough to retry
      }

      if (attemptsLeft <= 0) return Promise.reject(new Error('lucide.createIcons() failed'));

      return new Promise((resolve) => {
        setTimeout(() => {
          runCreateIconsWithRetry(attemptsLeft - 1).then(resolve).catch(() => resolve());
        }, 50);
      });
    };

    ensureLucideLoaded()
      .then(() => runCreateIconsWithRetry(2))
      .catch((err) => {
        // If lucide cannot be loaded, we silently ignore — icons will stay as fallback text
        // Optionally, you can log the error if DEBUG is enabled
        if (window.DEBUG) console.error('Lucide init error:', err);
      });
  },

  /**
   * Charge les images lightbox depuis le script JSON embarqué
   */
  loadLightboxImages() {
    const dataScript = this.modalContent.querySelector('script[data-lightbox-images]');
    if (dataScript) {
      try {
        window.globalLightboxImages = JSON.parse(dataScript.textContent);
      } catch (e) {
        console.error('Erreur parsing images modal:', e);
      }
    }
  },

  /**
   * Ferme la modal
   */
  close() {
    this.modalRoot.style.display = 'none';
    this.modalContent.innerHTML = '';
    document.body.style.overflow = '';
    window.globalLightboxImages = [];
  }
};

// Exporter pour utilisation globale
window.ModalManager = ModalManager;
