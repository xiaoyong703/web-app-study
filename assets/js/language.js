// Multi-language support system
class LanguageManager {
    constructor() {
        this.currentLanguage = localStorage.getItem('app_language') || 'en';
        this.translations = {};
        this.availableLanguages = {
            'en': { name: 'English', flag: '🇺🇸' },
            'es': { name: 'Español', flag: '🇪🇸' },
            'fr': { name: 'Français', flag: '🇫🇷' },
            'de': { name: 'Deutsch', flag: '🇩🇪' },
            'it': { name: 'Italiano', flag: '🇮🇹' },
            'pt': { name: 'Português', flag: '🇵🇹' },
            'zh': { name: '中文', flag: '🇨🇳' },
            'ja': { name: '日本語', flag: '🇯🇵' },
            'ko': { name: '한국어', flag: '🇰🇷' },
            'ar': { name: 'العربية', flag: '🇸🇦' },
            'hi': { name: 'हिन्दी', flag: '🇮🇳' },
            'ru': { name: 'Русский', flag: '🇷🇺' }
        };
        
        this.init();
    }
    
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.loadTranslations();
            this.setupLanguageSelector();
            this.applyLanguage();
            this.bindEvents();
        });
    }
    
    bindEvents() {
        // Language selector change
        const languageSelector = document.getElementById('language-selector');
        if (languageSelector) {
            languageSelector.addEventListener('change', (e) => {
                this.changeLanguage(e.target.value);
            });
        }
        
        // Language dropdown items
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('language-option')) {
                const language = e.target.dataset.lang;
                this.changeLanguage(language);
            }
        });
    }
    
    async loadTranslations() {
        try {
            // Load current language translations
            const response = await fetch(`assets/lang/${this.currentLanguage}.json`);
            if (response.ok) {
                this.translations = await response.json();
            } else {
                // Fallback to English if language file not found
                if (this.currentLanguage !== 'en') {
                    const fallbackResponse = await fetch('assets/lang/en.json');
                    this.translations = await fallbackResponse.json();
                }
            }
        } catch (error) {
            console.error('Error loading translations:', error);
            // Use fallback translations
            this.translations = this.getFallbackTranslations();
        }
    }
    
    setupLanguageSelector() {
        const languageSelector = document.getElementById('language-selector');
        if (languageSelector) {
            // Populate language options
            languageSelector.innerHTML = Object.entries(this.availableLanguages).map(([code, lang]) => 
                `<option value="${code}" ${code === this.currentLanguage ? 'selected' : ''}>${lang.flag} ${lang.name}</option>`
            ).join('');
        }
        
        // Setup dropdown version
        const languageDropdown = document.getElementById('language-dropdown');
        if (languageDropdown) {
            const currentLang = this.availableLanguages[this.currentLanguage];
            languageDropdown.innerHTML = `
                <button class="language-toggle">
                    ${currentLang.flag} ${currentLang.name}
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="language-options">
                    ${Object.entries(this.availableLanguages).map(([code, lang]) => 
                        `<div class="language-option ${code === this.currentLanguage ? 'active' : ''}" data-lang="${code}">
                            ${lang.flag} ${lang.name}
                        </div>`
                    ).join('')}
                </div>
            `;
            
            // Toggle dropdown
            languageDropdown.querySelector('.language-toggle').addEventListener('click', () => {
                languageDropdown.classList.toggle('open');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!languageDropdown.contains(e.target)) {
                    languageDropdown.classList.remove('open');
                }
            });
        }
    }
    
    async changeLanguage(newLanguage) {
        if (newLanguage === this.currentLanguage) return;
        
        this.currentLanguage = newLanguage;
        localStorage.setItem('app_language', newLanguage);
        
        // Show loading indicator
        this.showLanguageLoading();
        
        await this.loadTranslations();
        this.applyLanguage();
        this.updateLanguageSelector();
        
        // Hide loading indicator
        this.hideLanguageLoading();
        
        // Notify other components about language change
        this.notifyLanguageChange();
        
        // Show success message
        this.showNotification(this.translate('language_changed'), 'success');
    }
    
    applyLanguage() {
        // Update all elements with data-i18n attributes
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.dataset.i18n;
            const translation = this.translate(key);
            
            if (element.tagName === 'INPUT' && (element.type === 'text' || element.type === 'email')) {
                element.placeholder = translation;
            } else if (element.tagName === 'INPUT' && element.type === 'submit') {
                element.value = translation;
            } else {
                element.textContent = translation;
            }
        });
        
        // Update elements with data-i18n-title attributes (tooltips)
        document.querySelectorAll('[data-i18n-title]').forEach(element => {
            const key = element.dataset.i18nTitle;
            element.title = this.translate(key);
        });
        
        // Update document title
        const titleKey = document.querySelector('[data-i18n-page-title]')?.dataset.i18nPageTitle;
        if (titleKey) {
            document.title = this.translate(titleKey);
        }
        
        // Update HTML lang attribute
        document.documentElement.lang = this.currentLanguage;
        
        // Apply RTL for Arabic
        if (this.currentLanguage === 'ar') {
            document.documentElement.dir = 'rtl';
            document.body.classList.add('rtl');
        } else {
            document.documentElement.dir = 'ltr';
            document.body.classList.remove('rtl');
        }
    }
    
    updateLanguageSelector() {
        const languageSelector = document.getElementById('language-selector');
        if (languageSelector) {
            languageSelector.value = this.currentLanguage;
        }
        
        // Update dropdown
        this.setupLanguageSelector();
    }
    
    translate(key, params = {}) {
        let translation = this.getNestedTranslation(key) || key;
        
        // Replace parameters in translation
        Object.entries(params).forEach(([param, value]) => {
            translation = translation.replace(new RegExp(`{{${param}}}`, 'g'), value);
        });
        
        return translation;
    }
    
    getNestedTranslation(key) {
        return key.split('.').reduce((obj, k) => obj && obj[k], this.translations);
    }
    
    showLanguageLoading() {
        const loader = document.createElement('div');
        loader.id = 'language-loader';
        loader.className = 'language-loader';
        loader.innerHTML = `
            <div class="loader-content">
                <div class="spinner"></div>
                <span>${this.translate('loading_language')}</span>
            </div>
        `;
        document.body.appendChild(loader);
    }
    
    hideLanguageLoading() {
        const loader = document.getElementById('language-loader');
        if (loader) {
            loader.remove();
        }
    }
    
    notifyLanguageChange() {
        // Dispatch custom event for other components to listen to
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { language: this.currentLanguage }
        }));
        
        // Update other managers
        if (window.studyTimer) {
            window.studyTimer.updateLanguage();
        }
        if (window.flashcardManager) {
            window.flashcardManager.updateLanguage();
        }
        if (window.quizManager) {
            window.quizManager.updateLanguage();
        }
    }
    
    // Format numbers according to locale
    formatNumber(number) {
        return new Intl.NumberFormat(this.getLocale()).format(number);
    }
    
    // Format dates according to locale
    formatDate(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat(this.getLocale(), { ...defaultOptions, ...options }).format(date);
    }
    
    // Format time according to locale
    formatTime(date, options = {}) {
        const defaultOptions = {
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Intl.DateTimeFormat(this.getLocale(), { ...defaultOptions, ...options }).format(date);
    }
    
    // Get locale for Intl APIs
    getLocale() {
        const localeMap = {
            'en': 'en-US',
            'es': 'es-ES',
            'fr': 'fr-FR',
            'de': 'de-DE',
            'it': 'it-IT',
            'pt': 'pt-PT',
            'zh': 'zh-CN',
            'ja': 'ja-JP',
            'ko': 'ko-KR',
            'ar': 'ar-SA',
            'hi': 'hi-IN',
            'ru': 'ru-RU'
        };
        return localeMap[this.currentLanguage] || 'en-US';
    }
    
    // Get current language
    getCurrentLanguage() {
        return this.currentLanguage;
    }
    
    // Get available languages
    getAvailableLanguages() {
        return this.availableLanguages;
    }
    
    // Get language direction (for RTL support)
    isRTL() {
        return this.currentLanguage === 'ar';
    }
    
    // Fallback translations for essential UI elements
    getFallbackTranslations() {
        return {
            // Navigation
            dashboard: 'Dashboard',
            timer: 'Timer',
            todos: 'To-Do List',
            flashcards: 'Flashcards',
            quizzes: 'Quizzes',
            groups: 'Study Groups',
            analytics: 'Analytics',
            achievements: 'Achievements',
            settings: 'Settings',
            
            // Common actions
            save: 'Save',
            cancel: 'Cancel',
            delete: 'Delete',
            edit: 'Edit',
            add: 'Add',
            create: 'Create',
            update: 'Update',
            close: 'Close',
            confirm: 'Confirm',
            
            // Timer
            start: 'Start',
            pause: 'Pause',
            stop: 'Stop',
            reset: 'Reset',
            
            // Status messages
            success: 'Success',
            error: 'Error',
            loading: 'Loading...',
            loading_language: 'Loading language...',
            language_changed: 'Language changed successfully',
            
            // Time units
            seconds: 'seconds',
            minutes: 'minutes',
            hours: 'hours',
            days: 'days',
            weeks: 'weeks',
            months: 'months'
        };
    }
    
    showNotification(message, type = 'info') {
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            console.log(message);
        }
    }
}

// Date and time utilities with i18n support
class I18nDateUtils {
    constructor(languageManager) {
        this.lang = languageManager;
    }
    
    formatRelativeTime(date) {
        const now = new Date();
        const diff = now - date;
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const weeks = Math.floor(days / 7);
        const months = Math.floor(days / 30);
        
        if (months > 0) {
            return this.lang.translate('relative_time.months_ago', { count: months });
        } else if (weeks > 0) {
            return this.lang.translate('relative_time.weeks_ago', { count: weeks });
        } else if (days > 0) {
            return this.lang.translate('relative_time.days_ago', { count: days });
        } else if (hours > 0) {
            return this.lang.translate('relative_time.hours_ago', { count: hours });
        } else if (minutes > 0) {
            return this.lang.translate('relative_time.minutes_ago', { count: minutes });
        } else {
            return this.lang.translate('relative_time.just_now');
        }
    }
    
    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return this.lang.translate('duration.hours_minutes', { hours, minutes });
        } else if (minutes > 0) {
            return this.lang.translate('duration.minutes_seconds', { minutes, seconds: secs });
        } else {
            return this.lang.translate('duration.seconds', { seconds: secs });
        }
    }
}

// Language management utilities
class LanguageUtils {
    static async loadLanguageFile(languageCode) {
        try {
            const response = await fetch(`assets/lang/${languageCode}.json`);
            return await response.json();
        } catch (error) {
            console.error(`Error loading language file for ${languageCode}:`, error);
            return null;
        }
    }
    
    static detectBrowserLanguage() {
        const browserLang = navigator.language || navigator.userLanguage;
        const langCode = browserLang.split('-')[0]; // Get just the language part
        
        // Check if we support this language
        const supportedLanguages = ['en', 'es', 'fr', 'de', 'it', 'pt', 'zh', 'ja', 'ko', 'ar', 'hi', 'ru'];
        return supportedLanguages.includes(langCode) ? langCode : 'en';
    }
    
    static pluralize(count, translations) {
        // Simple pluralization rules (can be extended for complex languages)
        if (count === 1) {
            return translations.singular || translations.one;
        } else {
            return translations.plural || translations.other;
        }
    }
}

// Global language functions
function changeLanguage(languageCode) {
    if (window.languageManager) {
        window.languageManager.changeLanguage(languageCode);
    }
}

function translate(key, params = {}) {
    if (window.languageManager) {
        return window.languageManager.translate(key, params);
    }
    return key;
}

function formatDate(date, options = {}) {
    if (window.languageManager) {
        return window.languageManager.formatDate(date, options);
    }
    return date.toLocaleDateString();
}

function formatTime(date, options = {}) {
    if (window.languageManager) {
        return window.languageManager.formatTime(date, options);
    }
    return date.toLocaleTimeString();
}

// Initialize language manager
document.addEventListener('DOMContentLoaded', () => {
    window.languageManager = new LanguageManager();
    window.i18nDateUtils = new I18nDateUtils(window.languageManager);
});