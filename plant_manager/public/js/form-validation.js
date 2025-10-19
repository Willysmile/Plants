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
      // Vérifier validité HTML5 ET règles personnalisées
      let isValid = form.checkValidity();
      
      // Vérifier aussi les règles personnalisées
      const inputs = form.querySelectorAll('input, textarea, select');
      inputs.forEach(input => {
        if (!this.validateCustomRules(input)) {
          isValid = false;
        }
      });

      if (!isValid) {
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
    if (!field.checkValidity() || !this.validateCustomRules(field)) {
      this.showFieldError(field);
      field.classList.add('is-invalid');
    } else {
      field.classList.remove('is-invalid');
    }
  },

  /**
   * Valide les règles personnalisées
   */
  validateCustomRules(field) {
    // Validation 1: Date d'achat ne doit pas être future
    if (field.name === 'purchase_date' && field.value) {
      // Convertir la date saisie (format ISO: YYYY-MM-DD) en objets Date comparables
      const parts = field.value.split('-');
      const purchaseDate = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
      
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if (purchaseDate > today) {
        field.dataset.customError = 'La date d\'achat ne peut pas être future';
        return false;
      }
    }

    // Validation 2: Humidité ne doit pas dépasser 100%
    if (field.name === 'humidity_level' && field.value) {
      const humidity = parseFloat(field.value);
      if (humidity > 100) {
        field.dataset.customError = 'L\'humidité ne peut pas dépasser 100%';
        return false;
      }
      if (humidity < 0) {
        field.dataset.customError = 'L\'humidité ne peut pas être négative';
        return false;
      }
    }

    // Validation 3: Température min/max - min ne doit pas être > max
    if (field.name === 'temperature_min' && field.value) {
      const form = field.closest('form');
      const tempMaxField = form.querySelector('input[name="temperature_max"]');
      if (tempMaxField && tempMaxField.value) {
        const tempMin = parseFloat(field.value);
        const tempMax = parseFloat(tempMaxField.value);
        if (tempMin > tempMax) {
          field.dataset.customError = 'La température min ne peut pas dépasser la température max';
          return false;
        }
      }
    }

    // Validation 3b: Température max ne doit pas être < min
    if (field.name === 'temperature_max' && field.value) {
      const form = field.closest('form');
      const tempMinField = form.querySelector('input[name="temperature_min"]');
      if (tempMinField && tempMinField.value) {
        const tempMin = parseFloat(tempMinField.value);
        const tempMax = parseFloat(field.value);
        if (tempMax < tempMin) {
          field.dataset.customError = 'La température max ne peut pas être inférieure à la température min';
          return false;
        }
      }
    }

    delete field.dataset.customError;
    return true;
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
    // Vérifier les erreurs personnalisées d'abord
    if (field.dataset.customError) {
      return field.dataset.customError;
    }

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

    // D'abord, nettoyer tous les messages d'erreur précédents
    form.querySelectorAll('.error-message').forEach(msg => msg.remove());
    form.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));

    // Ensuite, valider et afficher les erreurs
    inputs.forEach(input => {
      if (!input.checkValidity() || !this.validateCustomRules(input)) {
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
