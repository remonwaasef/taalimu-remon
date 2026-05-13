/**
 * Taalimu Offline Sync & Status Indicator
 * Handles internet connectivity detection and sync visual cues.
 */

class OfflineSync {
    constructor() {
        this.statusIndicator = null;
        this.isOnline = navigator.onLine;
        this.init();
    }

    init() {
        this.createIndicator();
        this.attachEvents();
        this.interceptForms();
        this.updateUI();
        
        // Initial sync check if starting online
        if (this.isOnline) {
            this.processQueue();
        }
    }

    createIndicator() {
        const div = document.createElement('div');
        div.id = 'connectivity-status';
        div.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
            padding: 10px 15px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(100px);
            opacity: 0;
            pointer-events: none;
        `;
        document.body.appendChild(div);
        this.statusIndicator = div;
    }

    attachEvents() {
        window.addEventListener('online', () => this.handleStatusChange(true));
        window.addEventListener('offline', () => this.handleStatusChange(false));
    }

    handleStatusChange(online) {
        this.isOnline = online;
        this.updateUI();
        
        if (online) {
            this.showToast('success', 'عُدت متصلاً بالإنترنت', 'تمت استعادة الاتصال بنجاح.');
            this.processQueue();
        } else {
            this.showToast('warning', 'أنت الآن في وضع عدم الاتصال', 'يمكنك الاستمرار في التصفح، سيتم حفظ التغييرات محلياً.');
        }
    }

    updateUI() {
        if (!this.statusIndicator) return;

        if (this.isOnline) {
            this.statusIndicator.style.transform = 'translateY(100px)';
            this.statusIndicator.style.opacity = '0';
        } else {
            this.statusIndicator.innerHTML = `
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #ff4d4f; margin-right: 10px; animation: pulse 1.5s infinite;"></div>
                <span style="color: #333;">وضع عدم الاتصال (أوفلاين)</span>
            `;
            this.statusIndicator.style.backgroundColor = '#fff1f0';
            this.statusIndicator.style.border = '1px solid #ffa39e';
            this.statusIndicator.style.transform = 'translateY(0)';
            this.statusIndicator.style.opacity = '1';
        }
    }

    triggerSync() {
        // Visual cue for syncing
        this.statusIndicator.innerHTML = `
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            <span style="color: #333;">جاري مزامنة البيانات...</span>
        `;
        this.statusIndicator.style.backgroundColor = '#e6f7ff';
        this.statusIndicator.style.border = '1px solid #91d5ff';
        this.statusIndicator.style.transform = 'translateY(0)';
        this.statusIndicator.style.opacity = '1';

        setTimeout(() => {
            this.statusIndicator.style.transform = 'translateY(100px)';
            this.statusIndicator.style.opacity = '0';
        }, 3000);
    }

    showToast(icon, title, text) {
        if (window.Swal) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }
    }

    // Phase 7: Offline Queue Implementation
    interceptForms() {
        document.addEventListener('submit', (e) => {
            if (!this.isOnline && e.target.method && e.target.method.toUpperCase() === 'POST') {
                e.preventDefault();
                
                // Exclude forms that have data-no-offline attr
                if (e.target.hasAttribute('data-no-offline')) return;

                const formData = new FormData(e.target);
                const dataObj = {};
                formData.forEach((value, key) => { dataObj[key] = value; });

                const queueItem = {
                    url: e.target.action,
                    method: 'POST',
                    data: dataObj,
                    timestamp: Date.now(),
                    id: 'queue_' + Date.now()
                };

                const queue = JSON.parse(localStorage.getItem('taalimu_offline_queue') || '[]');
                queue.push(queueItem);
                localStorage.setItem('taalimu_offline_queue', JSON.stringify(queue));

                this.showToast('info', 'تم الحفظ محلياً', 'أنت غير متصل. سيتم إرسال البيانات تلقائياً عند عودة الإنترنت.');
                
                // Optionally reset the form
                e.target.reset();
            }
        });
    }

    async processQueue() {
        const queue = JSON.parse(localStorage.getItem('taalimu_offline_queue') || '[]');
        if (queue.length === 0) return;

        this.triggerSync();
        let remainingQueue = [];

        for (const item of queue) {
            try {
                // Convert object back to FormData for standard Laravel endpoints, or send as JSON
                // Sending as JSON might require headers. We'll send standard JSON for now.
                const response = await fetch(item.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(item.data)
                });

                if (!response.ok) {
                    console.warn('Offline item failed to sync', item);
                    remainingQueue.push(item);
                }
            } catch (err) {
                console.error('Network error during sync', err);
                remainingQueue.push(item);
            }
        }

        localStorage.setItem('taalimu_offline_queue', JSON.stringify(remainingQueue));
        
        if (remainingQueue.length === 0) {
            setTimeout(() => this.showToast('success', 'اكتملت المزامنة', 'تم رفع كافة البيانات المحفوظة بنجاح.'), 3000);
        }
    }
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }
`;
document.head.appendChild(style);

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.offlineSync = new OfflineSync();
});
