// Theme Management Enhanced
class ThemeManager {
    constructor() {
        this.init();
        this.bindEvents();
    }

    init() {
        // Check for saved theme preference or default to 'light' mode
        const savedTheme = localStorage.getItem('theme');
        const systemPreference = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        const currentTheme = savedTheme || systemPreference;
        
        this.setTheme(currentTheme);
        this.updateToggleButtons(currentTheme);
    }

    bindEvents() {
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                const newTheme = e.matches ? 'dark' : 'light';
                this.setTheme(newTheme);
                this.updateToggleButtons(newTheme);
            }
        });

        // Bind all theme toggle buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-theme-toggle]')) {
                e.preventDefault();
                this.toggle();
            }
        });
    }

    setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    toggle() {
        const isDark = document.documentElement.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';
        
        this.setTheme(newTheme);
        localStorage.setItem('theme', newTheme);
        this.updateToggleButtons(newTheme);
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
    }

    updateToggleButtons(theme) {
        const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
        
        toggleButtons.forEach(button => {
            const sunIcon = button.querySelector('.sun-icon');
            const moonIcon = button.querySelector('.moon-icon');
            
            if (theme === 'dark') {
                if (sunIcon) sunIcon.style.display = 'block';
                if (moonIcon) moonIcon.style.display = 'none';
                button.setAttribute('title', 'Cambiar a tema claro');
                button.setAttribute('aria-label', 'Cambiar a tema claro');
            } else {
                if (sunIcon) sunIcon.style.display = 'none';
                if (moonIcon) moonIcon.style.display = 'block';
                button.setAttribute('title', 'Cambiar a tema oscuro');
                button.setAttribute('aria-label', 'Cambiar a tema oscuro');
            }
        });
    }

    getCurrentTheme() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Global toggle function for backwards compatibility
window.toggleTheme = function() {
    if (window.themeManager) {
        window.themeManager.toggle();
    }
};