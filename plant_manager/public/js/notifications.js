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
        container.className = 'fixed top-4 right-4 space-y-2 z-50';
        document.body.appendChild(container);
    }

    // Créer l'alerte
    const alertDiv = document.createElement('div');
    alertDiv.className = `p-4 rounded-lg border ${styles.bg} ${styles.border} shadow-lg transform transition-all duration-300 ease-in-out translate-x-0 opacity-100 max-w-md`;
    alertDiv.innerHTML = `
        <div class="flex items-start">
            <i data-lucide="${styles.icon}" class="w-5 h-5 ${styles.iconColor} mt-0.5 flex-shrink-0"></i>
            <p class="ml-3 ${styles.text} text-sm font-medium">${message}</p>
            <button class="ml-3 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.remove()">
                <i data-lucide="x" class="w-5 h-5"></i>
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
            alertDiv.classList.add('translate-x-96', 'opacity-0');
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
