/**
 * ============================================================
 * Taalimu Image Compressor — Smart Client-side Compression
 * ============================================================
 * يضغط الصور تلقائياً قبل رفعها للسيرفر لتقليل الاستهلاك
 * يدعم: JPEG, PNG, WebP
 * الحد الأقصى: 1MB بعد الضغط | الدقة: 1200px عرض
 * ============================================================
 */

(function() {
    'use strict';

    const MAX_WIDTH = 1200;
    const MAX_HEIGHT = 1200;
    const QUALITY = 0.8; // 80% quality
    const MAX_SIZE_MB = 1;
    const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;

    /**
     * Initialize image compression on all file inputs
     */
    function init() {
        document.addEventListener('change', handleFileInput);
    }

    /**
     * Handle file input change events
     */
    function handleFileInput(e) {
        const input = e.target;
        if (!input || input.type !== 'file') return;
        if (!input.accept || !input.accept.includes('image')) {
            // Only process inputs that accept images
            if (!input.name?.includes('photo') && !input.name?.includes('image') && !input.name?.includes('avatar') && !input.name?.includes('logo')) {
                return;
            }
        }

        const files = input.files;
        if (!files || !files.length) return;

        const file = files[0];
        
        // Only compress images
        if (!file.type.startsWith('image/')) return;
        
        // Skip small files (under 500KB)
        if (file.size < 500 * 1024) return;

        // Show compression indicator
        showCompressingUI(input);
        
        compressImage(file)
            .then(compressedFile => {
                if (compressedFile.size < file.size) {
                    // Replace the file in the input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    input.files = dataTransfer.files;

                    const savedPercent = Math.round((1 - compressedFile.size / file.size) * 100);
                    const savedKB = Math.round((file.size - compressedFile.size) / 1024);
                    
                    showCompressionResult(input, savedPercent, savedKB);
                } else {
                    hideCompressingUI(input);
                }
            })
            .catch(err => {
                console.warn('[ImageCompressor] Compression failed:', err);
                hideCompressingUI(input);
            });
    }

    /**
     * Compress an image file
     * @param {File} file - The original image file
     * @returns {Promise<File>} - The compressed file
     */
    function compressImage(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    try {
                        const canvas = document.createElement('canvas');
                        let { width, height } = img;

                        // Calculate new dimensions
                        if (width > MAX_WIDTH || height > MAX_HEIGHT) {
                            const ratio = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
                            width = Math.round(width * ratio);
                            height = Math.round(height * ratio);
                        }

                        canvas.width = width;
                        canvas.height = height;

                        const ctx = canvas.getContext('2d');
                        
                        // Use high-quality resizing
                        ctx.imageSmoothingEnabled = true;
                        ctx.imageSmoothingQuality = 'high';
                        ctx.drawImage(img, 0, 0, width, height);

                        // Determine output format
                        let outputType = 'image/jpeg';
                        let quality = QUALITY;

                        if (file.type === 'image/png') {
                            // Check if image has transparency
                            const imageData = ctx.getImageData(0, 0, width, height);
                            const hasTransparency = checkTransparency(imageData);
                            
                            if (hasTransparency) {
                                outputType = 'image/png';
                                quality = undefined; // PNG doesn't use quality
                            }
                        }

                        canvas.toBlob(function(blob) {
                            if (!blob) {
                                reject(new Error('Canvas toBlob failed'));
                                return;
                            }

                            // If compressed is larger than original, return original
                            if (blob.size >= file.size) {
                                resolve(file);
                                return;
                            }

                            const compressedFile = new File([blob], file.name, {
                                type: outputType,
                                lastModified: Date.now()
                            });

                            resolve(compressedFile);
                        }, outputType, quality);
                    } catch (err) {
                        reject(err);
                    }
                };
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    /**
     * Check if image data has transparency
     */
    function checkTransparency(imageData) {
        const data = imageData.data;
        for (let i = 3; i < data.length; i += 4) {
            if (data[i] < 255) return true;
        }
        return false;
    }

    /**
     * Show compressing UI next to the input
     */
    function showCompressingUI(input) {
        const wrapper = input.closest('.mb-3') || input.closest('.form-group') || input.parentElement;
        if (!wrapper) return;

        let indicator = wrapper.querySelector('.compress-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'compress-indicator';
            indicator.style.cssText = `
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 6px 12px;
                margin-top: 6px;
                border-radius: 8px;
                font-size: 0.75rem;
                font-weight: 600;
                background: #eff6ff;
                border: 1px solid #bfdbfe;
                color: #3b82f6;
                animation: fadeIn 0.3s ease;
            `;
            wrapper.appendChild(indicator);
        }

        indicator.innerHTML = `
            <i class="fas fa-compress-alt fa-spin"></i>
            <span>جاري ضغط الصورة...</span>
        `;
    }

    /**
     * Show compression result
     */
    function showCompressionResult(input, savedPercent, savedKB) {
        const wrapper = input.closest('.mb-3') || input.closest('.form-group') || input.parentElement;
        if (!wrapper) return;

        let indicator = wrapper.querySelector('.compress-indicator');
        if (!indicator) return;

        indicator.style.background = '#E6F4F3';
        indicator.style.borderColor = '#B2DDD9';
        indicator.style.color = '#10b981';
        indicator.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>تم ضغط الصورة — تم توفير ${savedPercent}% (${savedKB} KB)</span>
        `;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (indicator) {
                indicator.style.transition = 'opacity 0.3s ease';
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }
        }, 5000);
    }

    /**
     * Hide compressing UI
     */
    function hideCompressingUI(input) {
        const wrapper = input.closest('.mb-3') || input.closest('.form-group') || input.parentElement;
        if (!wrapper) return;

        const indicator = wrapper.querySelector('.compress-indicator');
        if (indicator) indicator.remove();
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
