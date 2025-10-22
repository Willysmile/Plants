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
};

window.openQuickFertilizingModalFromModal = function(checkbox) {
  QuickModalsManager.fertilizing.open.call(QuickModalsManager.fertilizing, checkbox);
};

window.closeQuickFertilizingModalFromModal = function() {
  QuickModalsManager.fertilizing.close();
};

window.openQuickRepottingModalFromModal = function(checkbox) {
  QuickModalsManager.repotting.open.call(QuickModalsManager.repotting, checkbox);
};

window.closeQuickRepottingModalFromModal = function() {
  QuickModalsManager.repotting.close();
};

// Exporter pour utilisation globale
window.QuickModalsManager = QuickModalsManager;
