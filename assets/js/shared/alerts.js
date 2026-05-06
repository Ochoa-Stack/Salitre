/* Sistema de Alertas Modales para Salitre */
class SalitreAlerts {
    constructor() {
        this.container = this.createContainer();
    }

    createContainer() {
        const container = document.createElement('div');
        container.className = 'salitre-alerts-container';
        document.body.appendChild(container);
        return container;
    }

    show(type, title, message, duration = 5000) {
        const alert = document.createElement('div');
        alert.className = `salitre-alert alert-${type} fade-in`;
        
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'i'
        };

        alert.innerHTML = `
            <div class="alert-icon">${icons[type]}</div>
            <div class="alert-content">
                <h4 class="alert-title">${title}</h4>
                <p class="alert-message">${message}</p>
            </div>
            <button class="alert-close" aria-label="Cerrar">×</button>
            <div class="alert-progress" style="--duration: ${duration}ms"></div>
        `;

        this.container.appendChild(alert);
        // Activamos la animación de entrada
        requestAnimationFrame(() => alert.classList.add('is-visible'));
        // Aplicamos 'event listener' para cerrar
        const closeBtn = alert.querySelector('.alert-close');
        closeBtn.addEventListener('click', () => this.close(alert));
        // Aplicamos cierre automático si se especifica duración
        if (duration > 0) {
            setTimeout(() => this.close(alert), duration);
        }
    }

    close(alert) {
        alert.classList.remove('is-visible');
        setTimeout(() => alert.remove(), 300);
    }
}
// Instanciamos globalmente
window.showAlert = new SalitreAlerts();
