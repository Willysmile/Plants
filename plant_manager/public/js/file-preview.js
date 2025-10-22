/**
 * Gestionnaire d'aperçu des fichiers photo
 * Affiche les images sélectionnées avant soumission du formulaire
 * Empêche la perte des photos si une erreur de validation survient
 */
const FilePreviewManager = {
  /**
   * Initialise les gestionnaires d'aperçu pour les inputs file
   */
  init() {
    const mainPhotoInput = document.getElementById('mainPhotoInput');
    const galleryPhotosInput = document.getElementById('galleryPhotosInput');

    if (mainPhotoInput) {
      mainPhotoInput.addEventListener('change', (e) => {
        this.previewMainPhoto(e.target);
      });
    }

    if (galleryPhotosInput) {
      galleryPhotosInput.addEventListener('change', (e) => {
        this.previewGalleryPhotos(e.target);
      });
    }
  },

  /**
   * Affiche l'aperçu de la photo principale
   */
  previewMainPhoto(input) {
    const preview = document.getElementById('mainPhotoPreview');
    preview.innerHTML = ''; // Vider les aperçus précédents

    if (input.files && input.files[0]) {
      const file = input.files[0];

      // Vérifier que c'est une image
      if (!this.isValidImageFile(file)) {
        preview.innerHTML = '<p class="text-red-500 text-sm">Format invalide. Utilisez une image (JPG, PNG, GIF, etc.)</p>';
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'w-48 h-48 object-cover rounded shadow border-2 border-blue-400';
        img.alt = 'Aperçu photo principale';

        const container = document.createElement('div');
        container.className = 'mb-2';
        container.appendChild(img);

        const label = document.createElement('p');
        label.className = 'text-xs text-blue-600 mt-1';
        label.textContent = '✓ ' + file.name + ' (' + this.formatFileSize(file.size) + ')';

        preview.appendChild(container);
        preview.appendChild(label);
      };

      reader.readAsDataURL(file);
    }
  },

  /**
   * Affiche les aperçus des photos de galerie
   */
  previewGalleryPhotos(input) {
    const preview = document.getElementById('galleryPhotosPreview');
    preview.innerHTML = ''; // Vider les aperçus précédents

    if (input.files && input.files.length > 0) {
      const validFiles = [];

      // Valider tous les fichiers d'abord
      for (let file of input.files) {
        if (this.isValidImageFile(file)) {
          validFiles.push(file);
        }
      }

      if (validFiles.length === 0) {
        const error = document.createElement('p');
        error.className = 'text-red-500 text-sm col-span-full';
        error.textContent = 'Aucun fichier image valide. Utilisez JPG, PNG, GIF, etc.';
        preview.appendChild(error);
        return;
      }

      // Afficher les aperçus
      let loadedCount = 0;
      validFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const card = document.createElement('div');
          card.className = 'relative rounded border-2 border-green-400 overflow-hidden';

          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'w-full h-32 object-cover';
          img.alt = 'Aperçu ' + file.name;

          const label = document.createElement('div');
          label.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-1 text-xs truncate';
          label.textContent = file.name + ' (' + this.formatFileSize(file.size) + ')';

          card.appendChild(img);
          card.appendChild(label);
          preview.appendChild(card);

          loadedCount++;

          // Ajouter le message de confirmation quand tous les aperçus sont chargés
          if (loadedCount === validFiles.length) {
            const summary = document.createElement('p');
            summary.className = 'text-xs text-green-600 col-span-full mt-2';
            summary.textContent = '✓ ' + validFiles.length + ' image(s) sélectionnée(s)';
            preview.appendChild(summary);
          }
        };

        reader.readAsDataURL(file);
      });
    }
  },

  /**
   * Vérifie que c'est un fichier image valide
   */
  isValidImageFile(file) {
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml'];
    return validTypes.includes(file.type);
  },

  /**
   * Formate la taille du fichier en format lisible
   */
  formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
  }
};

// Initialiser au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
  FilePreviewManager.init();
});
