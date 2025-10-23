/**
 * Quick Entry Modals Manager
 * Gère l'ouverture et fermeture des modales rapides pour arrosage, fertilisation, rempotage
 */

const QuickModalsManager = {
  /**
   * Gère la modale arrosage rapide
   */
  watering: {
    modalId: 'quickWateringModalFromModal',
    dateFieldId: 'quickWateringDateFromModal',
    checkboxId: 'quickWateringCheckbox',

    open(checkbox) {
      // Si checkbox existe et n'est pas coché, fermer
      if (checkbox && !checkbox.checked) {
        this.close();
        return;
      }

      const now = new Date();
      const dateStr = now.toISOString().slice(0, 16);
      
      const dateField = document.getElementById(this.dateFieldId);
      if (dateField) dateField.value = dateStr;

      const modal = document.getElementById(this.modalId);
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    },

    close() {
      const checkbox = document.getElementById(this.checkboxId);
      if (checkbox) checkbox.checked = false;

      const modal = document.getElementById(this.modalId);
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
  },

  /**
   * Gère la modale fertilisation rapide
   */
  fertilizing: {
    modalId: 'quickFertilizingModalFromModal',
    dateFieldId: 'quickFertilizingDateFromModal',
    checkboxId: 'quickFertilizingCheckbox',

    open(checkbox) {
      // Si checkbox existe et n'est pas coché, fermer
      if (checkbox && !checkbox.checked) {
        this.close();
        return;
      }

      const now = new Date();
      const dateStr = now.toISOString().slice(0, 16);
      
      const dateField = document.getElementById(this.dateFieldId);
      if (dateField) dateField.value = dateStr;

      const modal = document.getElementById(this.modalId);
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    },

    close() {
      const checkbox = document.getElementById(this.checkboxId);
      if (checkbox) checkbox.checked = false;

      const modal = document.getElementById(this.modalId);
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
  },

  /**
   * Gère la modale rempotage rapide
   */
  repotting: {
    modalId: 'quickRepottingModalFromModal',
    dateFieldId: 'quickRepottingDateFromModal',
    checkboxId: 'quickRepottingCheckbox',

    open(checkbox) {
      // Si checkbox existe et n'est pas coché, fermer
      if (checkbox && !checkbox.checked) {
        this.close();
        return;
      }

      const now = new Date();
      const dateStr = now.toISOString().slice(0, 16);
      
      const dateField = document.getElementById(this.dateFieldId);
      if (dateField) dateField.value = dateStr;

      const modal = document.getElementById(this.modalId);
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    },

    close() {
      const checkbox = document.getElementById(this.checkboxId);
      if (checkbox) checkbox.checked = false;

      const modal = document.getElementById(this.modalId);
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
  }
};

/**
 * Functions globales pour les onclick handlers dans les templates
 */
window.openQuickWateringModalFromModal = function(checkbox) {
  QuickModalsManager.watering.open.call(QuickModalsManager.watering, checkbox);
};

window.closeQuickWateringModalFromModal = function() {
  QuickModalsManager.watering.close();
  if (typeof reloadHistoriesInModal === 'function') {
    reloadHistoriesInModal();
  }
};

window.openQuickFertilizingModalFromModal = function(checkbox) {
  QuickModalsManager.fertilizing.open.call(QuickModalsManager.fertilizing, checkbox);
};

window.closeQuickFertilizingModalFromModal = function() {
  QuickModalsManager.fertilizing.close();
  if (typeof reloadHistoriesInModal === 'function') {
    reloadHistoriesInModal();
  }
};

window.openQuickRepottingModalFromModal = function(checkbox) {
  QuickModalsManager.repotting.open.call(QuickModalsManager.repotting, checkbox);
};

window.closeQuickRepottingModalFromModal = function() {
  QuickModalsManager.repotting.close();
  if (typeof reloadHistoriesInModal === 'function') {
    reloadHistoriesInModal();
  }
};

// Exporter pour utilisation globale
window.QuickModalsManager = QuickModalsManager;

/**
 * Helpers génériques pour paramétrer les modales rapides
 */
function setDateMax(dateInputId) {
  const dateInput = document.getElementById(dateInputId);
  if (!dateInput) {
    return;
  }

  const today = new Date().toISOString().split('T')[0];
  dateInput.max = today;
}

window.createQuickModalSetupHandler = function(dateInputId) {
  return function () {
    setDateMax(dateInputId);
  };
};

window.createQuickModalSubmitHandler = function(options) {
  const {
    formId,
    dateInputId,
    dateErrorId,
    successMessage,
    onSuccess,
    onError,
  } = options;

  return function(event) {
    event.preventDefault();
    event.stopPropagation();

    const form = document.getElementById(formId);
    const dateInput = document.getElementById(dateInputId);
    const dateError = document.getElementById(dateErrorId);

    if (!form || !dateInput || !dateError) {
      console.error(`[QuickModal] Elements not found for form ${formId}`);
      return false;
    }

    const enteredDate = dateInput.value;
    const today = new Date().toISOString().split('T')[0];

    if (!enteredDate) {
      dateError.textContent = 'La date est requise';
      dateError.classList.remove('hidden');
      return false;
    }

    if (enteredDate > today) {
      dateError.textContent = 'La date ne peut pas être dans le futur';
      dateError.classList.remove('hidden');
      return false;
    }

    dateError.classList.add('hidden');

    const formData = new FormData(form);
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : null;

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      body: formData,
    })
      .then(response => {
        if (response.ok) {
          if (typeof onSuccess === 'function') {
            onSuccess({ form, dateInput, successMessage });
          } else if (typeof alertSuccess === 'function') {
            alertSuccess(successMessage, 0);
          }
        } else {
          return response.text().then(text => {
            throw new Error(text);
          });
        }
      })
      .catch(error => {
        console.error('[QuickModal] Error submitting form', error);
        dateError.textContent = 'Erreur lors de l\'enregistrement';
        dateError.classList.remove('hidden');
        if (typeof onError === 'function') {
          onError(error);
        }
      });

    return false;
  };
};
