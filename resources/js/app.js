import './bootstrap';
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// UI Store for global state
Alpine.store('ui', {
    mobileMenu: false,
});

// Cart Store
Alpine.store('cart', {
    count: 0,

    async init() {
        await this.fetchCount();
    },

    async fetchCount() {
        try {
            const response = await fetch('/api/cart/count');
            const data = await response.json();
            this.count = data.count || 0;
        } catch (error) {
            console.error('Error fetching cart count:', error);
        }
    }
});

// Suit Configurator Store
Alpine.store('configurator', {
    // Current selections
    suitModelId: null,
    fabricId: null,
    selectedOptions: {},

    // Data from server
    suitModel: null,
    fabrics: [],
    optionTypes: [],

    // UI state
    currentView: 'front', // front, back, side
    currentTab: 'style',
    isLoading: false,
    isMobileDrawerOpen: false,

    // Pricing
    basePrice: 0,
    totalPrice: 0,

    // Initialize with data
    init(data) {
        // Skip if no data provided (page uses inline Alpine component instead)
        if (!data) return;

        this.suitModel = data.suitModel;
        this.suitModelId = data.suitModel?.id;
        this.fabrics = data.fabrics || [];
        this.optionTypes = data.optionTypes || [];
        this.basePrice = data.suitModel?.base_price || 0;

        // Set default fabric
        if (this.fabrics.length > 0) {
            this.selectFabric(this.fabrics[0].id);
        }

        // Set default options
        this.initDefaultOptions();
        this.calculatePrice();
    },

    initDefaultOptions() {
        this.optionTypes.forEach(type => {
            const defaultOption = type.values.find(v => v.is_default) || type.values[0];
            if (defaultOption) {
                this.selectedOptions[type.slug] = defaultOption.id;
            }
        });
    },

    selectFabric(fabricId) {
        this.fabricId = fabricId;
        this.calculatePrice();
        this.updateLayers();
    },

    selectOption(typeSlug, valueId) {
        this.selectedOptions[typeSlug] = valueId;
        this.calculatePrice();
        this.updateLayers();
    },

    calculatePrice() {
        let price = this.basePrice;

        // Add fabric price modifier
        const fabric = this.fabrics.find(f => f.id === this.fabricId);
        if (fabric) {
            price += parseFloat(fabric.price_modifier) || 0;
        }

        // Add option price modifiers
        this.optionTypes.forEach(type => {
            const selectedId = this.selectedOptions[type.slug];
            const value = type.values.find(v => v.id === selectedId);
            if (value) {
                price += parseFloat(value.price_modifier) || 0;
            }
        });

        this.totalPrice = price;
    },

    updateLayers() {
        // Dispatch event to update image layers
        window.dispatchEvent(new CustomEvent('configurator:update', {
            detail: {
                fabricId: this.fabricId,
                options: this.selectedOptions,
                view: this.currentView
            }
        }));
    },

    setView(view) {
        this.currentView = view;
        this.updateLayers();
    },

    setTab(tab) {
        this.currentTab = tab;
    },

    toggleMobileDrawer() {
        this.isMobileDrawerOpen = !this.isMobileDrawerOpen;
    },

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
    },

    getSelectedFabric() {
        return this.fabrics.find(f => f.id === this.fabricId);
    },

    getSelectedOptionValue(typeSlug) {
        const type = this.optionTypes.find(t => t.slug === typeSlug);
        if (!type) return null;
        return type.values.find(v => v.id === this.selectedOptions[typeSlug]);
    },

    isOptionSelected(typeSlug, valueId) {
        return this.selectedOptions[typeSlug] === valueId;
    },

    // Save configuration
    async saveConfiguration() {
        this.isLoading = true;
        try {
            const response = await fetch('/api/configurator/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    model_id: this.suitModelId,
                    fabric_id: this.fabricId,
                    options: this.selectedOptions,
                    price: this.totalPrice
                })
            });
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error saving configuration:', error);
            throw error;
        } finally {
            this.isLoading = false;
        }
    },

    // Add to cart
    async addToCart() {
        this.isLoading = true;
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    model_id: this.suitModelId,
                    fabric_id: this.fabricId,
                    options: this.selectedOptions,
                    price: this.totalPrice
                })
            });
            const data = await response.json();

            if (data.success) {
                Alpine.store('cart').count = data.cart_count;
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: 'Đã thêm vào giỏ hàng!', type: 'success' }
                }));
            }

            return data;
        } catch (error) {
            console.error('Error adding to cart:', error);
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: 'Có lỗi xảy ra!', type: 'error' }
            }));
            throw error;
        } finally {
            this.isLoading = false;
        }
    }
});

// Image layer manager component
Alpine.data('imageLayerManager', () => ({
    layers: [],
    loadedImages: {},

    init() {
        window.addEventListener('configurator:update', (e) => {
            this.updateLayers(e.detail);
        });
    },

    updateLayers(config) {
        // This will be called when configuration changes
        // The actual layer URLs come from the server data
    },

    preloadImage(url) {
        return new Promise((resolve, reject) => {
            if (this.loadedImages[url]) {
                resolve(this.loadedImages[url]);
                return;
            }

            const img = new Image();
            img.onload = () => {
                this.loadedImages[url] = img;
                resolve(img);
            };
            img.onerror = reject;
            img.src = url;
        });
    }
}));

// Cart component
Alpine.data('cartManager', () => ({
    items: [],
    isOpen: false,

    init() {
        // Cart is managed server-side now
    },

    async removeItem(itemId) {
        try {
            const response = await fetch(`/api/cart/item/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            if (data.success) {
                Alpine.store('cart').count = data.cart_count;
                window.location.reload();
            }
        } catch (error) {
            console.error('Error removing item:', error);
        }
    },

    async updateQuantity(itemId, quantity) {
        try {
            const response = await fetch(`/api/cart/item/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity })
            });
            const data = await response.json();
            if (data.success) {
                Alpine.store('cart').count = data.cart_count;
                window.location.reload();
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    }
}));

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', () => {
    Alpine.store('cart').init();
});

// Start Alpine
Alpine.start();
