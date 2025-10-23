/**
 * Système de notifications professionnel utilisant Laravel flash sessions
 * Affiche les notifications via le composant Blade réutilisable
 */
window.showNotification = function(message, type = 'info', duration = 0) {
    // Configuration des styles
    const config = {
        success: {
            bg: 'bg-green-100',
            border: 'border-green-400',
            text: 'text-green-900',
            icon: 'check-circle',
            iconColor: 'text-green-700'
        },
        error: {
            bg: 'bg-red-100',
            border: 'border-red-400',
            text: 'text-red-900',
            icon: 'alert-circle',
            iconColor: 'text-red-700'
        },
        warning: {
            bg: 'bg-yellow-100',
            border: 'border-yellow-400',
            text: 'text-yellow-900',
            icon: 'alert-triangle',
            iconColor: 'text-yellow-700'
        },
        info: {
            bg: 'bg-blue-100',
            border: 'border-blue-400',
            text: 'text-blue-900',
            icon: 'info',
            iconColor: 'text-blue-700'
        }
    };

    const styles = config[type] || config.info;
    const container = document.getElementById('notifications-container');
    
    if (!container) {
        console.error('notifications-container not found');
        return;
    }

    // Créer un div avec les classes Tailwind complètes
    const notificationDiv = document.createElement('div');
    notificationDiv.className = `p-8 rounded-2xl border-2 ${styles.bg} ${styles.border} shadow-2xl max-w-2xl w-11/12 pointer-events-auto cursor-pointer transform transition-all duration-300 opacity-100 scale-100 notification-alert`;
    notificationDiv.setAttribute('data-type', type);
    
    notificationDiv.innerHTML = `
        <div class="flex items-center gap-4">
            <i data-lucide="${styles.icon}" class="w-10 h-10 ${styles.iconColor} flex-shrink-0"></i>
            <div class="flex-1">
                <p class="${styles.text} text-lg font-medium">${message}</p>
            </div>
            <button class="ml-4 text-gray-500 hover:text-gray-700 flex-shrink-0 transition notification-close" type="button">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
    `;

    // Event listener pour le bouton close
    const closeBtn = notificationDiv.querySelector('.notification-close');
    closeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        closeNotification(notificationDiv);
    });

    // Click sur la notification pour fermer
    notificationDiv.addEventListener('click', function(e) {
        if (e.target === notificationDiv || e.target.closest('.flex.items-center')) {
            closeNotification(notificationDiv);
        }
    });

    container.appendChild(notificationDiv);

    // Initialiser les icônes Lucide
    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }

    // Fermeture automatique si duration > 0
    if (duration > 0) {
        setTimeout(() => {
            closeNotification(notificationDiv);
        }, duration);
    }

    return notificationDiv;
};

/**
 * Ferme une notification avec animation
 */
window.closeNotification = function(notificationDiv) {
    if (!notificationDiv) return;
    
    notificationDiv.style.opacity = '0';
    notificationDiv.style.transform = 'scale(0.95)';
    setTimeout(() => notificationDiv.remove(), 300);
};

/**
 * Fonctions de commodité pour chaque type
 */
window.alertSuccess = function(message, duration = 0) {
    return showNotification(message, 'success', duration);
};

window.alertError = function(message, duration = 0) {
    return showNotification(message, 'error', duration);
};

window.alertWarning = function(message, duration = 0) {
    return showNotification(message, 'warning', duration);
};

window.alertInfo = function(message, duration = 0) {
    return showNotification(message, 'info', duration);
};

/**
 * Initialiser les event listeners sur les notifications existantes
 */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.notification-alert').forEach(function(alert) {
        const closeBtn = alert.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeNotification(alert);
            });
        }
        
        alert.addEventListener('click', function(e) {
            if (e.target === alert || e.target.closest('.flex.items-center')) {
                closeNotification(alert);
            }
        });
    });
    
    // Initialiser les icônes
    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
});

