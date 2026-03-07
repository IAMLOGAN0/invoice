class InvoiceForm {
    constructor(options = {}) {
        this.rowCount = options.rowCount || 1;
        this.products = options.products || [];
        this.itemsContainer = document.getElementById('itemsContainer');
        this.mobileItemsContainer = document.getElementById('mobileItemsContainer');
        this.grandTotalElement = document.getElementById('grandTotal');
        this.subtotalElement = document.getElementById('subtotal');
        this.totalTaxElement = document.getElementById('totalTax');
        
        this.init();
    }

    init() {
        // Delegate event listeners
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('quantity-input') ||
                e.target.classList.contains('price-input') ||
                e.target.classList.contains('tax-input')) {
                this.updateTotal(e.target);
            }
            if (e.target.classList.contains('product-select-input')) {
                this.handleProductChange(e.target);
            }
        });

        document.addEventListener('input', (e) => {
            if (e.target.classList.contains('quantity-input') ||
                e.target.classList.contains('price-input') ||
                e.target.classList.contains('tax-input')) {
                this.updateTotal(e.target);
            }
        });

        // Initial calculation
        this.updateGrandTotal();
    }

    addProductRow() {
        const productOptionsHTML = this.getCustomProductOptions();

        // Add desktop table row
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-200 product-row';
        newRow.setAttribute('data-row-index', this.rowCount);
        
        newRow.innerHTML = `
            <td class="px-4 py-3">
                <div class="flex gap-2 items-start">
                    <div class="product-select-wrapper flex-1">
                        <input type="hidden" name="items[${this.rowCount}][product_id]" class="product-select-input" value="" onchange="invoiceForm.handleProductChange(this)">
                        <div class="product-select-header" data-index="${this.rowCount}" onclick="toggleProductSelect(this)">
                            <span class="product-select-value placeholder">Select product...</span>
                            <div class="product-select-icons">
                                <button type="button" class="product-select-clear" onclick="clearProductSelect(event, this)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                                <span class="product-select-arrow">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="product-select-dropdown">
                            <div class="product-select-search">
                                <input type="text" placeholder="Search products..." class="product-search-input" onkeyup="filterProducts(this)">
                            </div>
                            <div class="product-select-options">
                                ${productOptionsHTML}
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="openProductModal(this)" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition whitespace-nowrap font-medium text-sm" title="Add new product">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${this.rowCount}][quantity]" value="1" step="1" min="1" class="w-full px-3 py-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input" placeholder="Qty">
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${this.rowCount}][unit_price]" value="0" step="0.01" min="0" class="w-full px-3 py-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input" placeholder="Price">
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${this.rowCount}][tax_rate]" value="0" step="0.01" min="0" max="100" class="w-full px-3 py-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 tax-input" placeholder="Tax %">
            </td>
            <td class="px-4 py-3 text-right font-semibold text-gray-900 row-total">₹0.00</td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="invoiceForm.removeProductRow(this)" class="text-red-600 hover:text-red-900 text-sm font-medium">Remove</button>
            </td>
        `;
        
        this.itemsContainer.appendChild(newRow);

        // Add mobile card
        if (this.mobileItemsContainer) {
            const newCard = document.createElement('div');
            newCard.className = 'border border-gray-200 rounded-lg p-3 bg-white product-card';
            newCard.setAttribute('data-row-index', this.rowCount);
            newCard.innerHTML = `
                <div class="flex justify-between items-start mb-3">
                    <label class="text-xs font-semibold text-gray-500 uppercase">Product</label>
                    <button type="button" onclick="removeProductCard(this)" class="text-red-500 hover:text-red-700 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex gap-2 items-start mb-3">
                    <div class="product-select-wrapper flex-1">
                        <input type="hidden" name="items[${this.rowCount}][product_id]" class="product-select-input mobile-product-input" value="" onchange="invoiceForm.handleProductChange(this)">
                        <div class="product-select-header" data-index="${this.rowCount}" onclick="toggleProductSelect(this)">
                            <span class="product-select-value placeholder">Select product...</span>
                            <div class="product-select-icons">
                                <button type="button" class="product-select-clear" onclick="clearProductSelect(event, this)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                                <span class="product-select-arrow">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                            </div>
                        </div>
                        <div class="product-select-dropdown">
                            <div class="product-select-search">
                                <input type="text" placeholder="Search products..." class="product-search-input" onkeyup="filterProducts(this)">
                            </div>
                            <div class="product-select-options">
                                ${productOptionsHTML}
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="openProductModal(this)" class="px-3 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" title="Add new product">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Qty</label>
                        <input type="number" name="items[${this.rowCount}][quantity]" value="1" step="1" min="1" class="w-full h-10 px-2 py-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Price (₹)</label>
                        <input type="number" name="items[${this.rowCount}][unit_price]" value="0" step="0.01" min="0" class="w-full h-10 px-2 py-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 font-medium">Tax %</label>
                        <input type="number" name="items[${this.rowCount}][tax_rate]" value="0" step="0.01" min="0" max="100" class="w-full h-10 px-2 py-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 tax-input">
                    </div>
                </div>
                <div class="flex justify-end">
                    <span class="text-sm font-semibold text-gray-900 row-total">Total: ₹0.00</span>
                </div>
            `;
            this.mobileItemsContainer.appendChild(newCard);
        }

        this.rowCount++;
        this.updateGrandTotal();
    }

    removeProductRow(button) {
        const row = button.closest('tr');
        if (this.itemsContainer.querySelectorAll('tr').length > 1) {
            row.remove();
            this.updateGrandTotal();
        } else {
            alert('At least one item is required');
        }
    }

    handleProductChange(input) {
        const container = input.closest('tr') || input.closest('.product-card');
        const productId = input.value;
        
        const product = this.products.find(p => p.id == productId);
        
        if (product && container) {
            const price = product.price || 0;
            const taxPercentage = product.gst_percentage || 0;
            
            const priceInput = container.querySelector('.price-input');
            const taxInput = container.querySelector('.tax-input');
            
            if (priceInput) priceInput.value = price;
            if (taxInput) taxInput.value = taxPercentage;
            
            if (priceInput) this.updateTotal(priceInput);
        }
    }

    updateTotal(element) {
        const container = element.closest('tr') || element.closest('.product-card');
        if (!container) return;

        const quantity = parseFloat(container.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(container.querySelector('.price-input').value) || 0;
        const tax = parseFloat(container.querySelector('.tax-input').value) || 0;
        
        const subtotal = quantity * price;
        const taxAmount = subtotal * (tax / 100);
        const total = subtotal + taxAmount;
        
        const rowTotalElement = container.querySelector('.row-total');
        if (container.tagName === 'TR') {
            rowTotalElement.textContent = '₹' + this.formatCurrency(total);
        } else {
            rowTotalElement.textContent = 'Total: ₹' + this.formatCurrency(total);
        }
        
        this.updateGrandTotal();
    }

    updateGrandTotal() {
        let grandTotal = 0;
        let totalTax = 0;
        let subtotalAmount = 0;
        
        // Use whichever container is currently visible
        const isMobile = window.innerWidth < 640;
        const items = isMobile
            ? document.querySelectorAll('.product-card')
            : document.querySelectorAll('.product-row');
        
        items.forEach(item => {
            const quantity = parseFloat(item.querySelector('.quantity-input')?.value) || 0;
            const price = parseFloat(item.querySelector('.price-input')?.value) || 0;
            const tax = parseFloat(item.querySelector('.tax-input')?.value) || 0;
            
            const subtotal = quantity * price;
            const taxAmount = subtotal * (tax / 100);
            
            subtotalAmount += subtotal;
            totalTax += taxAmount;
            grandTotal += subtotal + taxAmount;
        });
        
        // Get discount amount
        const discountDisplay = document.getElementById('discount-display');
        let discountAmount = 0;
        if (discountDisplay && !discountDisplay.classList.contains('hidden')) {
            const discountText = document.getElementById('discountAmount').textContent;
            discountAmount = parseFloat(discountText.replace('₹', '').trim()) || 0;
        }
        
        // Apply discount to grand total
        grandTotal = Math.max(0, grandTotal - discountAmount);
        
        this.grandTotalElement.textContent = '₹' + this.formatCurrency(grandTotal);
        this.totalTaxElement.textContent = '₹' + this.formatCurrency(totalTax);
        this.subtotalElement.textContent = '₹' + this.formatCurrency(subtotalAmount);

        // Update due amount if the function exists
        if (typeof updateDueAmount === 'function') {
            updateDueAmount();
        }
    }

    formatCurrency(value) {
        return parseFloat(value).toFixed(2);
    }

    getProductOptions() {
        const products = this.products || [];
        return products.map(product => 
            `<option value="${product.id}" data-price="${product.price}" data-gst="${product.gst_percentage}">${product.name}</option>`
        ).join('');
    }

    getCustomProductOptions() {
        const products = this.products || [];
        return products.map(product => 
            `<button type="button" class="product-select-option" data-product-id="${product.id}" data-product-name="${product.name}" data-product-hsn="${product.hsn_code || ''}" data-product-price="${product.price}" data-product-gst="${product.gst_percentage}" onclick="selectProduct(event, this)">
                <div class="product-select-option-main">
                    <span class="product-select-option-name">${product.name}</span>
                    ${product.hsn_code ? `<span class="product-select-option-hsn">${product.hsn_code}</span>` : ''}
                </div>
                <span class="product-select-checkmark">✓</span>
            </button>`
        ).join('');
    }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
    // Get initial row count and products from data attributes or define globally
    const rowCount = window.invoiceRowCount || 1;
    const products = window.invoiceProducts || [];
    
    window.invoiceForm = new InvoiceForm({
        rowCount: rowCount,
        products: products
    });
});
