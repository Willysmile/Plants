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
  },

  /**
   * Gère la modale maladie rapide
   */
  disease: {
    modalId: 'quickDiseaseModalFromModal',
    dateFieldId: 'quickDiseaseDetectedAtFromModal',
    checkboxId: 'quickDiseaseCheckbox',

    open(checkbox) {
      // Si checkbox existe et n'est pas coché, fermer
      if (checkbox && !checkbox.checked) {
        this.close();
        return;
      }

      const now = new Date();
      const dateStr = now.toISOString().slice(0, 10);
      
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
  // Reset form
  const form = document.getElementById('quickWateringFormFromModal');
  if (form) form.reset();
  // Refresh modal to see updated histories
  if (typeof refreshModal === 'function') {
    setTimeout(refreshModal, 300);
  }
};

window.openQuickFertilizingModalFromModal = function(checkbox) {
  QuickModalsManager.fertilizing.open.call(QuickModalsManager.fertilizing, checkbox);
};

window.closeQuickFertilizingModalFromModal = function() {
  QuickModalsManager.fertilizing.close();
  // Reset form
  const form = document.getElementById('quickFertilizingFormFromModal');
  if (form) form.reset();
  // Refresh modal to see updated histories
  if (typeof refreshModal === 'function') {
    setTimeout(refreshModal, 300);
  }
};

window.openQuickRepottingModalFromModal = function(checkbox) {
  QuickModalsManager.repotting.open.call(QuickModalsManager.repotting, checkbox);
};

window.closeQuickRepottingModalFromModal = function() {
  QuickModalsManager.repotting.close();
  // Reset form
  const form = document.getElementById('quickRepottingFormFromModal');
  if (form) form.reset();
  // Refresh modal to see updated histories
  if (typeof refreshModal === 'function') {
    setTimeout(refreshModal, 300);
  }
};

window.openQuickDiseaseModalFromModal = function(checkbox) {
  QuickModalsManager.disease.open.call(QuickModalsManager.disease, checkbox);
};

window.closeQuickDiseaseModalFromModal = function() {
  QuickModalsManager.disease.close();
  // Reset form
  const form = document.getElementById('quickDiseaseFormFromModal');
  if (form) form.reset();
  // Refresh modal to see updated histories
  if (typeof refreshModal === 'function') {
    setTimeout(refreshModal, 300);
  }
};

// Exporter pour utilisation globale
window.QuickModalsManager = QuickModalsManager;

/**
 * Setup functions for quick modals - called when modal opens
 */
window.setupQuickWateringModal = function() {
  const dateInput = document.getElementById('quickWateringDateFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
  }
};

window.setupQuickFertilizingModal = function() {
  const dateInput = document.getElementById('quickFertilizingDateFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
  }
};

window.setupQuickRepottingModal = function() {
  const dateInput = document.getElementById('quickRepottingDateFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
  }
};

window.setupQuickDiseaseModal = function() {
  const dateInput = document.getElementById('quickDiseaseDetectedAtFromModal');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.max = today;
  }
  // Handle disease select change
  const select = document.getElementById('quickDiseaseTypeFromModal');
  const newDiseaseDiv = document.getElementById('quickNewDiseaseDiv');
  if (select) {
    select.addEventListener('change', function() {
      if (newDiseaseDiv) {
        newDiseaseDiv.style.display = this.value === 'new' ? 'block' : 'none';
      }
    });
  }
};

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

/**
 * Submit handlers for quick modals - validates date and submits AJAX form
 */

/**
 * Handle submission of quick watering modal
 */
window.handleQuickWateringSubmit = function(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const form = document.getElementById('quickWateringFormFromModal');
  const dateInput = document.getElementById('quickWateringDateFromModal');
  const dateError = document.getElementById('quickWateringDateError');
  
  if (!form || !dateInput || !dateError) {
    console.error('[QuickWatering] Elements not found');
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
        if (typeof alertSuccess === 'function') {
          alertSuccess('Arrosage enregistré', 0);
        }
        // Refresh modal to see updated histories
        if (typeof refreshModal === 'function') {
          setTimeout(refreshModal, 500);
        }
        // Close modal
        closeQuickWateringModalFromModal();
      } else {
        return response.text().then(text => {
          throw new Error(text);
        });
      }
    })
    .catch(error => {
      console.error('[QuickWatering] Error:', error);
      dateError.textContent = 'Erreur lors de l\'enregistrement';
      dateError.classList.remove('hidden');
    });
  
  return false;
};

/**
 * Handle submission of quick fertilizing modal
 */
window.handleQuickFertilizingSubmit = function(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const form = document.getElementById('quickFertilizingFormFromModal');
  const dateInput = document.getElementById('quickFertilizingDateFromModal');
  const dateError = document.getElementById('quickFertilizingDateError');
  
  if (!form || !dateInput || !dateError) {
    console.error('[QuickFertilizing] Elements not found');
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
        if (typeof alertSuccess === 'function') {
          alertSuccess('Fertilisation enregistrée', 0);
        }
        // Refresh modal to see updated histories
        if (typeof refreshModal === 'function') {
          setTimeout(refreshModal, 500);
        }
        // Close modal
        closeQuickFertilizingModalFromModal();
      } else {
        return response.text().then(text => {
          throw new Error(text);
        });
      }
    })
    .catch(error => {
      console.error('[QuickFertilizing] Error:', error);
      dateError.textContent = 'Erreur lors de l\'enregistrement';
      dateError.classList.remove('hidden');
    });
  
  return false;
};

/**
 * Handle submission of quick repotting modal
 */
window.handleQuickRepottingSubmit = function(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const form = document.getElementById('quickRepottingFormFromModal');
  const dateInput = document.getElementById('quickRepottingDateFromModal');
  const dateError = document.getElementById('quickRepottingDateError');
  
  if (!form || !dateInput || !dateError) {
    console.error('[QuickRepotting] Elements not found');
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
        if (typeof alertSuccess === 'function') {
          alertSuccess('Rempotage enregistré', 0);
        }
        // Refresh modal to see updated histories
        if (typeof refreshModal === 'function') {
          setTimeout(refreshModal, 500);
        }
        // Close modal
        closeQuickRepottingModalFromModal();
      } else {
        return response.text().then(text => {
          throw new Error(text);
        });
      }
    })
    .catch(error => {
      console.error('[QuickRepotting] Error:', error);
      dateError.textContent = 'Erreur lors de l\'enregistrement';
      dateError.classList.remove('hidden');
    });
  
  return false;
};

/**
 * Handle submission of quick disease modal
 */
window.handleQuickDiseaseSubmit = function(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const form = document.getElementById('quickDiseaseFormFromModal');
  const dateInput = document.getElementById('quickDiseaseDetectedAtFromModal');
  const dateError = document.getElementById('quickDiseaseDateError');
  
  if (!form || !dateInput || !dateError) {
    console.error('[QuickDisease] Elements not found');
    return false;
  }
  
  const enteredDate = dateInput.value;
  const today = new Date().toISOString().split('T')[0];
  
  if (!enteredDate) {
    dateError.textContent = 'La date de détection est requise';
    dateError.classList.remove('hidden');
    return false;
  }
  
  if (enteredDate > today) {
    dateError.textContent = 'La date de détection ne peut pas être dans le futur';
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
        if (typeof alertSuccess === 'function') {
          alertSuccess('Maladie enregistrée', 0);
        }
        // Refresh modal to see updated histories
        if (typeof refreshModal === 'function') {
          setTimeout(refreshModal, 500);
        }
        // Close modal
        closeQuickDiseaseModalFromModal();
      } else {
        return response.text().then(text => {
          throw new Error(text);
        });
      }
    })
    .catch(error => {
      console.error('[QuickDisease] Error:', error);
      dateError.textContent = 'Erreur lors de l\'enregistrement';
      dateError.classList.remove('hidden');
    });
  
  return false;
};

/**
 * Add a new fertilizer type on-the-fly in quick fertilizing modal
 */
window.addNewFertilizerTypeQuick = async function(event) {
  event.preventDefault();
  const input = document.getElementById('quickNewFertilizerTypeName');
  const name = input.value.trim();
  
  if (!name) {
    alert('Veuillez entrer un nom pour le type d\'engrais');
    return;
  }
  
  try {
    const response = await fetch('/fertilizer-types', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ name })
    });
    
    if (!response.ok) {
      throw new Error('Erreur lors de la création');
    }
    
    const data = await response.json();
    
    // Add option to select
    const select = document.getElementById('quickFertilizingTypeFromModal');
    const option = new Option(data.name, data.id);
    select.appendChild(option);
    select.value = data.id;
    
    // Clear input
    input.value = '';
    
    alert('Type d\'engrais créé avec succès !');
  } catch (error) {
    console.error('Error:', error);
    alert('Erreur lors de la création du type d\'engrais');
  }
};

