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

    // Réinitialiser les icônes Lucide
    if (typeof lucide !== 'undefined') {
      setTimeout(() => {
        lucide.createIcons();
      }, 50);
    }
  },

  /**
   * Charge les images lightbox depuis le script JSON embarqué
   */
  loadLightboxImages() {
    const dataScript = this.modalContent.querySelector('script[data-lightbox-images]');
    if (dataScript) {
      try {
        window.globalLightboxImages = JSON.parse(dataScript.textContent);
        console.log('Images modal chargées:', window.globalLightboxImages.length);
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
