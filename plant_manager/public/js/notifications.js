/**
 * Affiche une notification personnalisée avec Tailwind au lieu d'une alert()
 * @param {string} message - Le message à afficher
 * @param {string} type - Le type: 'success', 'error', 'warning', 'info'
 * @param {number} duration - Durée avant fermeture automatique (ms), 0 = pas de fermeture
 */
window.showNotification = function(message, type = 'info', duration = 4000) {
    // Configuration des styles
    const config = {
        success: {
            bg: 'bg-green-50',
            border: 'border-green-200',
            text: 'text-green-800',
            icon: 'check-circle',
            iconColor: 'text-green-600'
        },
        error: {
            bg: 'bg-red-50',
            border: 'border-red-200',
            text: 'text-red-800',
            icon: 'alert-circle',
            iconColor: 'text-red-600'
        },
        warning: {
            bg: 'bg-yellow-50',
            border: 'border-yellow-200',
            text: 'text-yellow-800',
            icon: 'alert-triangle',
            iconColor: 'text-yellow-600'
        },
        info: {
            bg: 'bg-blue-50',
            border: 'border-blue-200',
            text: 'text-blue-800',
            icon: 'info',
            iconColor: 'text-blue-600'
        }
    };

    const styles = config[type] || config.info;

    // Créer le conteneur principal s'il n'existe pas
    let container = document.getElementById('notifications-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notifications-container';
        container.className = 'fixed inset-0 flex items-center justify-center pointer-events-none z-50';
        document.body.appendChild(container);
    }

    // Créer l'alerte
    const alertDiv = document.createElement('div');
    alertDiv.className = `p-8 rounded-2xl border ${styles.bg} ${styles.border} shadow-2xl transform transition-all duration-300 ease-in-out scale-100 opacity-100 max-w-2xl w-11/12 pointer-events-auto`;
    alertDiv.innerHTML = `
        <div class="flex items-center gap-4">
            <i data-lucide="${styles.icon}" class="w-10 h-10 ${styles.iconColor} flex-shrink-0"></i>
            <div class="flex-1">
                <p class="${styles.text} text-lg font-medium">${message}</p>
            </div>
            <button class="ml-4 text-gray-400 hover:text-gray-600 flex-shrink-0" onclick="this.closest('[class*=rounded-2xl]').remove()">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
    `;

    container.appendChild(alertDiv);

    // Créer les icônes Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Fermeture automatique après duration
    if (duration > 0) {
        setTimeout(() => {
            alertDiv.classList.add('scale-95', 'opacity-0');
            setTimeout(() => alertDiv.remove(), 300);
        }, duration);
    }

    return alertDiv;
};

/**
 * Remplace alert() par showNotification()
 * Exemple: au lieu de alert('Message'), utiliser showNotification('Message', 'success')
 */
window.alertSuccess = function(message, duration = 4000) {
    return showNotification(message, 'success', duration);
};

window.alertError = function(message, duration = 4000) {
    return showNotification(message, 'error', duration);
};

window.alertWarning = function(message, duration = 4000) {
    return showNotification(message, 'warning', duration);
};

window.alertInfo = function(message, duration = 4000) {
    return showNotification(message, 'info', duration);
};

