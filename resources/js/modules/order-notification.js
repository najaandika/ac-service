/**
 * Admin Order Notification Module
 * Polls for new orders and plays notification sound when count increases
 */

class OrderNotification {
    constructor() {
        this.lastPendingCount = null;
        this.audio = null;
        this.settings = {
            enabled: true,
            interval: 30000,
            audioUrl: null
        };
        this.pollingId = null;
    }

    // Default notification sound (short beep)
    static DEFAULT_SOUND = 'data:audio/mp3;base64,SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjU4Ljc2LjEwMAAAAAAAAAAAAAAA//tQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWGluZwAAAA8AAAACAAABhgC7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7u7//////////////////////////////////////////////////////////////////8AAAAATGF2YzU4LjEzAAAAAAAAAAAAAAAAJAAAAAAAAAAAAYYNDkvOAAAAAAD/+9DEAAAGkAN19AAAAJ4Ydz7MAAA/h/D+H8oc/8Px/4fyhz/w/D/h/lDn/h/8P//y5//8oIed/8Ef/+D//h+H7///E/xAgCAIQBAEP/BAEABA/4IA/lHQSxWKfBAHz/FYpA+sU+IAOfLFYpA+D5/8Fgf/5YJIHwQB8EA+D58sVin4LA//ywIP/8sCD//+sU/+D/LA+//4Ig/1AQBAEAQBAEAQBAEAQBAEAQBA//tQxBkAAADSAAAAAAAAANIAAAAA//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8=';

    /**
     * Initialize the notification system
     */
    async init() {
        if (!document.body.classList.contains('admin-panel')) return;

        await this.loadSettings();
        this.initAudio();
        this.requestBrowserNotificationPermission();
        this.startPolling();

        console.log(`📢 Order notification initialized (enabled=${this.settings.enabled}, interval=${this.settings.interval / 1000}s)`);
    }

    /**
     * Load settings from server
     */
    async loadSettings() {
        try {
            const response = await fetch('/admin/api/notification-settings', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                this.settings.enabled = data.enabled;
                this.settings.interval = (data.interval || 30) * 1000;
                this.settings.audioUrl = data.audioUrl;
            }
        } catch (error) {
            console.warn('Failed to load notification settings:', error);
        }
    }

    /**
     * Initialize audio element
     */
    initAudio() {
        const source = this.settings.audioUrl || OrderNotification.DEFAULT_SOUND;
        this.audio = new Audio(source);
        this.audio.volume = 0.7;
    }

    /**
     * Play notification sound
     */
    playSound() {
        if (!this.settings.enabled || !this.audio) return;

        this.audio.currentTime = 0;
        this.audio.play().catch(() => {
            // Silently fail if autoplay blocked
        });
    }

    /**
     * Update badge count in sidebar
     */
    updateBadge(count) {
        document.querySelectorAll('[data-pending-badge]').forEach(badge => {
            badge.textContent = count > 9 ? '9+' : count;
            badge.classList.toggle('hidden', count === 0);
        });
    }

    /**
     * Show browser notification
     */
    showBrowserNotification(newOrderCount) {
        if (Notification.permission !== 'granted') return;

        new Notification('🔔 Order Baru!', {
            body: `Ada ${newOrderCount} order baru masuk`,
            icon: '/favicon.ico'
        });
    }

    /**
     * Request browser notification permission
     */
    requestBrowserNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    /**
     * Check for new orders
     */
    async checkOrders() {
        if (!this.settings.enabled) return;

        try {
            const response = await fetch('/admin/api/pending-orders-count', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            if (!response.ok) return;

            const { count } = await response.json();

            // Play notification if count increased
            if (this.lastPendingCount !== null && count > this.lastPendingCount) {
                const newOrders = count - this.lastPendingCount;
                this.playSound();
                this.showBrowserNotification(newOrders);
            }

            this.lastPendingCount = count;
            this.updateBadge(count);

        } catch (error) {
            console.error('Error checking orders:', error);
        }
    }

    /**
     * Start polling for new orders
     */
    startPolling() {
        // Initial check
        this.checkOrders();

        // Start interval
        this.pollingId = setInterval(() => {
            this.checkOrders();
        }, this.settings.interval);
    }

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollingId) {
            clearInterval(this.pollingId);
            this.pollingId = null;
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    const notification = new OrderNotification();
    notification.init();

    // Expose for debugging
    window.orderNotification = notification;

    // Show activation banner if not activated yet
    if (!localStorage.getItem('notifActivated') && document.body.classList.contains('admin-panel')) {
        document.getElementById('notif-activation-banner')?.classList.remove('hidden');
    }
});

/**
 * Activate notification sound (unlock audio context)
 * Must be called from user interaction (click)
 */
function activateNotification() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        gainNode.gain.value = 0.1;
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';

        oscillator.start();
        setTimeout(() => {
            oscillator.stop();
            audioContext.close();
        }, 100);

        localStorage.setItem('notifActivated', 'true');
        document.getElementById('notif-activation-banner')?.classList.add('hidden');

        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Notifikasi suara aktif!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);

    } catch (err) {
        console.error('Audio activation failed:', err);
        localStorage.setItem('notifActivated', 'true');
        document.getElementById('notif-activation-banner')?.classList.add('hidden');
    }
}

// Expose globally for onclick handler
window.activateNotification = activateNotification;
