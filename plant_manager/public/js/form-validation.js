/**
 * Gestionnaire de validation client pour les formulaires
 */
const FormValidator = {
  /**
   * Initialise les validations sur tous les formulaires
   */
  init() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
      this.setupForm(form);
    });
  },

  /**
   * Configure une forme pour la validation
   */
  setupForm(form) {
    // Valider lors de la soumission
    form.addEventListener('submit', (e) => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        this.displayErrors(form);
      }
      form.classList.add('was-validated');
    });

    // Valider en temps réel sur les champs
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
      input.addEventListener('blur', () => {
        this.validateField(input);
      });

      input.addEventListener('change', () => {
        this.validateField(input);
      });
    });
  },

  /**
   * Valide un champ individuel
   */
  validateField(field) {
    // Vider les messages d'erreur précédents
    const existingError = field.nextElementSibling;
    if (existingError && existingError.classList.contains('error-message')) {
      existingError.remove();
    }

    // Vérifier la validité
    if (!field.checkValidity()) {
      this.showFieldError(field);
      field.classList.add('is-invalid');
    } else {
      field.classList.remove('is-invalid');
    }
  },

  /**
   * Affiche une erreur pour un champ
   */
  showFieldError(field) {
    const errorMsg = this.getErrorMessage(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message text-red-500 text-sm mt-1';
    errorDiv.textContent = errorMsg;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
  },

  /**
   * Retourne le message d'erreur approprié
   */
  getErrorMessage(field) {
    if (!field.validity) {
      return 'Champ invalide';
    }

    if (field.validity.valueMissing) {
      return `${field.name || 'Ce champ'} est obligatoire`;
    }

    if (field.validity.typeMismatch) {
      return `Format invalide pour ${field.name || 'ce champ'}`;
    }

    if (field.validity.rangeUnderflow) {
      return `La valeur doit être au minimum ${field.min}`;
    }

    if (field.validity.rangeOverflow) {
      return `La valeur doit être au maximum ${field.max}`;
    }

    if (field.validity.patternMismatch) {
      return `Format incorrect pour ${field.name || 'ce champ'}`;
    }

    return 'Erreur de validation';
  },

  /**
   * Affiche tous les messages d'erreur d'une forme
   */
  displayErrors(form) {
    const inputs = form.querySelectorAll('input, textarea, select');
    let hasError = false;

    inputs.forEach(input => {
      if (!input.checkValidity()) {
        this.showFieldError(input);
        input.classList.add('is-invalid');
        hasError = true;
      }
    });

    // Scroll vers le premier champ avec erreur
    if (hasError) {
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth' });
        firstInvalid.focus();
      }
    }
  }
};

// Initialiser au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
  FormValidator.init();
});
