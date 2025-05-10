<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple POS System</title>
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="{{ session('theme', 'light') }}">
    <div class="container-fluid" style="padding: 50px">
        <div class="row">
            <div class="col-6 row d-flex ">
                <div class="col-3">
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        ← Back to CMS
                    </a>
                </div>
                <div class="col-9 text-start">
                    <h1>Welcome {{ $user->firstname.' '.$user->lastname }}</h1>
                </div>
              
             
            </div>
            <div class="col-6 d-flex justify-content-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="theme-toggle">
                    <label class="form-check-label" for="theme-toggle" id="theme-label">Dark Mode</label>
                </div>
            </div>
        </div>
     
        <div class="row" style="display: flex; height: 100vh; gap: 1rem; margin: 0;">
            <div id="statusMessage" class="status-message"></div>
            <div class="col-7" style="flex: 7; display: flex; flex-direction: column; ">
              
                <div class="products-section" style="height: 100vh; display: flex; flex-direction: column;">
                    <!-- Barcode Section -->
                    <div class="barcode-section">
                        <h2>
                            <img src="{{ asset('img/icon/barcode.png') }}" alt="Barcode Icon" class="barcode-icon">
                            Scan Barcode
                        </h2>
                        <input type="text" id="barcodeInput" class="barcode-input" placeholder="Scan barcode or enter product code...">
                        <div id="barcodeAlert" class="barcode-alert"></div>
                    </div>
                
                    <!-- Search Bar -->
                    <input 
                        type="text" 
                        id="productSearch" 
                        placeholder="Search by name or barcode..." 
                        style="margin-bottom: 1rem; padding: 0.5rem; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; width: 100%;"/>
                
                    {{--  Product list  --}}
                    <div style="display: flex; gap: 2rem; font-family: sans-serif; flex: 1; overflow: hidden;">
                        <!-- Categories -->
                        <div style="flex: 1; overflow-x: hidden; overflow-y: auto; padding-right: 1rem; max-height: 100%;">
                            <h2>Categories</h2>
                            <div style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;">
                                @foreach($categories as $category)
                                    <button 
                                        class="category-button"
                                        data-category-id="{{ $category->id }}"
                                        style="background-color: #2196F3; color: white; padding: 0.5rem; font-size: 14px; border: none; border-radius: 4px; transition: all 0.3s ease;">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    
                        <!-- Product / Order List Panel -->
                        <div style="flex: 4; display: flex; flex-direction: column; overflow: hidden;">
                            <h2 style="flex-shrink: 0;">Products</h2>
                        
                            <div style="flex: 1; overflow-y: auto;">
                                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                                    {{-- Product cards --}}
                                    @foreach($products as $product)
                                        <div 
                                            class="product-card"
                                            data-category-id="{{ $product->category_id }}"
                                            data-name="{{ $product->name }}" 
                                            data-barcode="{{ $product->barcode }}"
                                            data-id="{{ $product->id }}"
                                            data-price="{{ $product->price }}"
                                            data-quantity="{{ $product->quantity }}"
                                            
                                            style="flex: 1 1 calc(33.333% - 0.75rem); border: 1px solid #ccc; border-radius: 6px; padding: 0.75rem; background-color: #fdfdfd; display: block; flex-direction: column; gap: 0.3rem; min-width: 180px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                            <h3 style="margin: 0; font-size: 14px;">{{ $product->name }}</h3>
                                            <p style="margin: 0; font-size: 12px;"><strong>Price:</strong>₱{{ number_format($product->price, 2) }}</p>
                                            <p style="margin: 0; font-size: 12px; color: #555;"><strong>Barcode:</strong> {{ $product->barcode }}</p>
                                            <span style="margin: 0; font-size: 12px; color: #555;">{{  $product->quantity <= 0 ? 'Out of Stock'  : 'Stock:' }}  {{ $product->quantity <= 0 ?  '' : $product->quantity }}</span>
                                            <div style="margin-top: auto;">
                                                {{-- Add image or whatever else --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Hidden Product Data --}}
                    <div id="hidden-products" style="display: none;">
                        @foreach($products as $product)
                            <div class="hidden-product"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}"
                                data-barcode="{{ $product->barcode }}"
                                data-image="{{ $product->image }}">
                                {{ $product->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                
                
            </div>
            <div class="col-5" style="flex: 5; display: flex; flex-direction: column; background-color: rgba(255, 255, 255, 0.85); border-radius: 8px; padding: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">            
                <div class="cart-table-container">
                    <div class="cart-section cart-scroll">
                        <div class="mb-3">
                            <h2 for="customerSelect" class="form-label text-dark"><img src="{{ asset('img/icon/profile.png') }}" alt="Barcode Icon" width="30" height="30"> Customer</h2>
                            <select id="customerSelect" class="form-select" style="width: 100%;">
                                <option value="">Choose a Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                            data-name="{{ $customer->name }}"
                                            data-balance="{{ $customer->current_balance }}">
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <h4 id="balanceDisplay" style="display: none; color: black;">Remaining Balance: 0</h4>
                        </div>

                        <div class="mb-3">
                            <h2 for="rateTypeSelect" class="form-label text-dark">
                                <img src="{{ asset('img/icon/price-tag.png') }}" alt="Price Tag Icon" width="30" height="30"> Rate Type
                            </h2>
                            <select id="rateTypeSelect" class="form-select" style="width: 100%;">
                                <option value="">Choose Rate</option>
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                            </select>
                        </div>
                        
                        <h2><img src="{{ asset('img/icon/shopping-cart.png') }}" alt="Barcode Icon" width="30" height="30"> Shopping Cart</h2>
                        <table id="cartTable">
                            <thead class="table-header text-center">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <div id="cartScrollWrapper" style="max-height: 300px; overflow-y: auto;">
                                <table>
                                  <tbody id="cartItems">
                                    <!-- Cart items will be added here -->
                                  </tbody>
                                </table>
                              </div>
                        </table>
                        
                        <div class="cart-summary">
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax (7%):</span>
                                <span id="tax">$0.00</span>
                            </div>
                            <div class="summary-row total-row">
                                <span>Total:</span>
                                <span id="total">$0.00</span>
                            </div>
                            
                            {{--  <button id="checkoutBtn" class="action-btn checkout-btn"> <img src="{{ asset('img/icon/secure-payment.png') }}" alt="Barcode Icon" width="30" height="30"  style="filter: brightness(0) invert(1);">  Checkout</button>  --}}
                            <div id="printWrapper" style="text-align: center; display: none;">
                                <button id="printReceiptBtn" class="action-btn print-btn"> <img src="{{ asset('img/icon/secure-payment.png') }}" alt="Barcode Icon" width="30" height="30"  style="filter: brightness(0) invert(1);">  Payout Receipt</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Receipt Modal -->
    <div id="receiptModal" class="modal" style="overflow: hidden;">
        <div class="modal-content">
            <div id="receiptContent" class="receipt">
                <!-- Receipt content will be dynamically generated -->
            </div>
            <div class="modal-actions">
                <button id="actualPrintBtn" class="modal-btn print-receipt-btn"><img src="{{ asset('img/icon/printing.png') }}" alt="Barcode Icon" width="30" height="30"  style="filter: brightness(0) invert(1);">  Print</button>
                <button id="closeReceiptBtn" class="modal-btn close-receipt-btn"> ✖ Close</button>
            </div>
        </div>
    </div>

    <div id="passwordModal" class="modal" style="display: none;">
        <div class="modal-content">
          <h3 class="text-dark">Enter Password to Void this Item</h3>
      
          <div style="position: relative; margin-bottom: 10px;">
            <input type="password" id="passwordInput" placeholder="Password"
                   style="width: 100%; padding-right: 40px;" />
      
            <!-- Eye toggle icon -->
            <span id="togglePassword" style="
              position: absolute;
              right: 10px;
              top: 50%;
              transform: translateY(-50%);
              cursor: pointer;
              font-size: 16px;
            ">👁️</span>
          </div>
      
          <div class="modal-actions">
            <button id="confirmRemoveBtn" class="modal-btn">  ✔ Confirm</button>
            <button id="cancelRemoveBtn" class="modal-btn">    ✖ Cancel</button>
          </div>
        </div>
      </div>
      
      


    <script>

        // Cart data
        let cart = [];
        let lastOrderDetails = null;
        let itemToRemoveId = null;
        const password = @json(config('app.remove_item_password'));
        
        // DOM Elements
        const hiddenProducts = document.querySelectorAll('#hidden-products .hidden-product'); //fetching products
        const products = Array.from(hiddenProducts).map(product => ({
            id: product.dataset.id,
            name: product.dataset.name,
            price: parseFloat(product.dataset.price),
            barcode: product.dataset.barcode,
            image: product.dataset.image
        })); // getting the data from the hidden div
        const productList = document.querySelector('.product-list');
        const barcodeInput = document.getElementById('barcodeInput');
        const barcodeAlert = document.getElementById('barcodeAlert');
        const cartItems = document.getElementById('cartItems');
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const printReceiptBtn = document.getElementById('printReceiptBtn');
        const statusMessage = document.getElementById('statusMessage');
        const receiptModal = document.getElementById('receiptModal');
        const receiptContent = document.getElementById('receiptContent');
        const actualPrintBtn = document.getElementById('actualPrintBtn');
        const closeReceiptBtn = document.getElementById('closeReceiptBtn');
        const customer = document.getElementById('customerSelect');
        const rateType = document.getElementById('rateTypeSelect');

        // Initialize the page
        function init() {
            setupBarcodeInput();
            setupReceiptFunctionality();
        }
        

        document.addEventListener('DOMContentLoaded', () => {
            updateCart();
        });

        // Setup barcode input with auto-submit
        function setupBarcodeInput() {
            barcodeInput.addEventListener('input', handleBarcodeInput);
            
            // Clear any alert when user starts typing again
            
            barcodeInput.addEventListener('blur', () => {
                if (barcodeInput.value.trim() === '') {
                    hideBarcodeAlert();
                }
            });
        }
        
        // Setup remove item functionality
        document.getElementById('confirmRemoveBtn').addEventListener('click', () => {
            const entered = document.getElementById('passwordInput').value;
            if (entered === password) {
              removeItem(itemToRemoveId);
              closePasswordModal();
             showMessage('Product void successfully', 'success');
            } else {
              alert('Incorrect password.');
            }
          });
          
          document.getElementById('cancelRemoveBtn').addEventListener('click', closePasswordModal);
          
          function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
            document.getElementById('passwordInput').value = '';
            itemToRemoveId = null;
          }

        // Show barcode alert message
        function showBarcodeAlert(message) {
            barcodeAlert.textContent = message;
            barcodeAlert.style.display = 'block';
            
            // Highlight the input field with error
            barcodeInput.style.borderColor = '#dc3545';
            
            // Play a beep sound (optional - you would need to add an audio element)
            // document.getElementById('errorBeep').play();
        }
        
        // Hide barcode alert
        function hideBarcodeAlert() {
            barcodeAlert.style.display = 'none';
            barcodeInput.style.borderColor = '#ccc';
        }
        
        // Handle barcode input
        function handleBarcodeInput(e) {
            const barcode = e.target.value.trim();
            
            if (barcode.length >= 4) {  // Assuming barcodes are at least 4 characters
                const product = findProductByBarcode(barcode);
                
                if (product) {
                    // Product found - add to cart
                    addToCart(product);
                    barcodeInput.value = '';
                    showMessage(`Added ${product.name} to cart`, 'success');
                    hideBarcodeAlert();
                } else {
                    // Product not found - show error
                    showBarcodeAlert(`Item Not Found: Barcode "${barcode}" is invalid or not recognized`);
                    showMessage(`Product with barcode ${barcode} not found`, 'error');
                    
                    // Clear input after a short delay
                    setTimeout(() => {
                        barcodeInput.value = '';
                    }, 1500);
                    
                    // Hide the alert after a few seconds
                    setTimeout(() => {
                        hideBarcodeAlert();
                    }, 3000);
                }
            }
        }
        
        // Find product by barcode
        function findProductByBarcode(barcode) {
            return products.find(product => product.barcode === barcode);
        }

        // Disable product cards with zero stock
        document.querySelectorAll('.product-card').forEach(card => {
            const stock = parseInt(card.getAttribute('data-quantity')) || 0;
            if (stock === 0) {
                card.style.opacity = '0.5';
                card.style.pointerEvents = 'none';
            }
        });
        
        
        // Setup product card click event
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', () => {
                const product = {
                    id: card.getAttribute('data-id'),
                    name: card.getAttribute('data-name'),
                    price: parseFloat(card.getAttribute('data-price')),
                    barcode: card.getAttribute('data-barcode')
                };

                addToCart(product);
            });
        });


        function addToCart(product) {
            const existingItem = cart.find(item => item.id === product.id);

            // Find the corresponding product card
            const productCard = document.querySelector(`.product-card[data-id="${product.id}"]`);
            let currentStock = parseInt(productCard.getAttribute('data-quantity'));

            // Prevent adding if out of stock
            if (currentStock <= 0) {
                alert('This product is out of stock.');
    
                return;
            }

            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1
                });
            }

            // Decrease stock
            currentStock--;
            productCard.setAttribute('data-quantity', currentStock);

            // Update quantity display on the product card
            const qtySpan = productCard.querySelector('span');
            if (qtySpan) {
                qtySpan.textContent = `Stock: ${currentStock}`;
            }

            updateCart();
        }


        function updateCart() {
            cartItems.innerHTML = '';
        
            const cartSummary = document.getElementById('cartSummary');
            const printWrapper = document.getElementById('printWrapper');
            const customerSelect = document.getElementById('customerSelect');
            const printBtn = document.getElementById('printReceiptBtn');
            
            
            // Ensure that the cartSummary and printWrapper are visible only if:
            // - A customer is selected.
            // - The cart has items.
            if (customerSelect.value && cart.length > 0) {
                // Customer is selected and cart is not empty
                if (cartSummary) cartSummary.style.display = 'block';
                if (printWrapper) printWrapper.style.display = 'block';
                if (printBtn) printBtn.disabled = false;
            } else {
                // Either no customer selected or the cart is empty
                if (cartSummary) cartSummary.style.display = 'none';
                if (printWrapper) printWrapper.style.display = 'none';
                if (printBtn) printBtn.disabled = true;
            }
        
            if (cart.length === 0) {
                cartItems.innerHTML = `<tr><td colspan="5" style="text-align: center;">🛒 Your cart is empty</td></tr>`;
            } else {
                cart.forEach(item => {
                    const total = item.price * item.quantity;
        
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>₱${item.price.toFixed(2)}</td>
                        <td class="quantity-cell">
                            <button class="quantity-btn minus" data-id="${item.id}">-</button>
                            <span class="quantity-value"> ${item.quantity}</span>
                            <button class="quantity-btn plus" data-id="${item.id}">+</button>
                        </td>
                        <td>₱${total.toFixed(2)}</td>
                        <td><button class="remove-btn" data-id="${item.id}">✕</button></td>
                    `;
                    cartItems.appendChild(row);
                });
        
                document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const itemId = btn.dataset.id;
                        updateQuantity(itemId, -1);
                
                        // Return 1 item to stock
                        const productCard = document.querySelector(`.product-card[data-id="${itemId}"]`);
                        if (productCard) {
                            let currentStock = parseInt(productCard.getAttribute('data-quantity')) || 0;
                            currentStock += 1;
                            productCard.setAttribute('data-quantity', currentStock);
                            const qtySpan = productCard.querySelector('span');
                            if (qtySpan) qtySpan.textContent = `Stock: ${currentStock}`;
                        }
                    });
                });
                
                document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const itemId = btn.dataset.id;
                
                        // Deduct 1 from stock only if stock is available
                        const productCard = document.querySelector(`.product-card[data-id="${itemId}"]`);
                        if (productCard) {
                            let currentStock = parseInt(productCard.getAttribute('data-quantity')) || 0;
                            if (currentStock > 0) {
                                currentStock -= 1;
                                productCard.setAttribute('data-quantity', currentStock);
                                const qtySpan = productCard.querySelector('span');
                                if (qtySpan) qtySpan.textContent = `Stock: ${currentStock}`;
                                
                                updateQuantity(itemId, 1);
                            } else {
                                showMessage("No more stock available for this item.", "error");
                            }
                        }
                    });
                });
                
        
                document.querySelectorAll('.remove-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        itemToRemoveId = btn.dataset.id;
                        const itemName = cart.find(item => item.id === itemToRemoveId).name;
                        const productCard = document.querySelector(`.product-card[data-id="${itemToRemoveId}"]`);
                            if (productCard) {
                                let currentStock = parseInt(productCard.getAttribute('data-quantity')) || 0;
                                let removedQty = cart.find(item => item.id === itemToRemoveId)?.quantity || 1;
                                currentStock += removedQty;
                                productCard.setAttribute('data-quantity', currentStock);
                                const qtySpan = productCard.querySelector('span');
                                if (qtySpan) {
                                    qtySpan.textContent = `QTY: ${currentStock}`;
                                }
                                productCard.style.opacity = '1';
                                productCard.style.pointerEvents = 'auto';
                            }
                        document.getElementById('passwordModal').style.display = 'block';
                    });
                });
            }
        
            updateTotals();
        }
        
        document.getElementById('customerSelect').addEventListener('change', function() {
            updateCart();
        });
        

        // Update quantity of an item in the cart
        function updateQuantity(itemId, change) {
            const item = cart.find(item => item.id === itemId);

            if (item) {
                item.quantity += change;

                if (item.quantity <= 0) {
                    removeItem(itemId);
                } else {
                    updateCart();
                }
            }

            if (printReceiptBtn) printReceiptBtn.disabled = true;
        }

        // Remove item from cart
        function removeItem(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            const itemToRemove = cart.find(item => item.id === itemId);
            if (itemToRemove) {
                cart.push(itemToRemove);
            }
            const itemToRemoveIndex = cart.findIndex(item => item.id === itemId);
            if (itemToRemoveIndex !== -1) {
                cart.splice(itemToRemoveIndex, 1);
            }
            updateCart();

            if (printReceiptBtn) printReceiptBtn.disabled = true;
        }

        // Update totals in the carts
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.07;
            const total = subtotal + tax;

            subtotalEl.textContent = ` ₱${subtotal.toFixed(2)}`;
            taxEl.textContent = ` ₱${tax.toFixed(2)}`;
            totalEl.textContent = ` ₱${total.toFixed(2)}`;
        }
        
        // Setup receipt functionality
        function setupReceiptFunctionality() {
            printReceiptBtn.addEventListener('click', () => {
                if (cart.length === 0) {
                    showMessage('No items in cart to print receipt', 'error');
                    return;
                }
        
                const orderDetails = createOrderDetails();
                lastOrderDetails = orderDetails;
        
                generateReceipt(orderDetails);
                receiptModal.style.display = 'block';
            });
        
            actualPrintBtn.addEventListener('click', () => {
                const receiptContent = document.getElementById('receiptContent');
                const orderDetails = createOrderDetails();

                // Check if receiptContent is valid
                if (!receiptContent) {
                    showMessage('No receipt content found', 'error');
                    return;
                }
                // Check if orderDetails is valid
                if (!orderDetails) {
                    showMessage('No order details found', 'error');
                    return;
                }

                // Open a new window for printing
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                    <head>
                        <title>Receipt</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                padding: 20px;
                                margin: 0;
                                color: #000;
                                background-color: #fff;
                                font-size: 14px;
                                line-height: 1.6;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                                text-align: center;
                            }
                            .receipt {
                                width: 100%;
                                max-width: 400px;
                                margin: 0 auto;
                            }
                            .receipt-header, .receipt-footer {
                                text-align: center;
                                margin-bottom: 20px;
                            }
                            .receipt-header h2 {
                                margin: 0;
                                font-size: 22px;
                                font-weight: bold;
                            }
                            .receipt-header p,
                            .receipt-footer p {
                                margin: 4px 0;
                                font-size: 13px;
                            }
                            .order-info,
                            .receipt-items,
                            .receipt-summary {
                                margin: 10px 0;
                                padding: 10px 0;
                                border-top: 1px dashed #000;
                                border-bottom: 1px dashed #000;
                                font-size: 14px;
                            }
                            .receipt-item,
                            .summary-row {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                padding: 5px 0;
                            }
                            .item-details {
                                display: flex;
                                gap: 5px;
                            }
                            .receipt-total {
                                font-size: 16px;
                                font-weight: bold;
                                margin-top: 10px;
                            }
                            @media print {
                                button {
                                    display: none;
                                }
                                body {
                                    font-size: 12pt;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="receipt">
                            ${receiptContent.innerHTML}
                        </div>
                    </body>
                    </html>
                `);
                printWindow.document.close();
        
                printWindow.onload = () => {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                };

                saveOrderToBackend(orderDetails);
            });
        
            function saveOrderToBackend(orderDetails) {
                fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderDetails)
                })
                .then(async (response) => {
                    if (!response.ok) {
                        const message = response.status === 400
                            ? 'Insufficient balance'
                            : 'Failed to save order';
                        
                        showMessage(message, 'error');
                        receiptModal.style.display = 'none';
                        return;
                    }
                
                    const data = await response.json();
                    console.log(data);
                    showMessage(
                        `${data.message || 'Order successfully!'}\n${data.order.customer_name} current balance was ${data.remaining_balance}`,
                        'success'
                      );
                    receiptModal.style.display = 'none';
                
                    // Reset cart
                    cart = [];
                    updateCart();
                
                    // Reset stock on UI if needed
                    document.querySelectorAll('.product-card').forEach(card => {
                        card.style.opacity = '1';
                        card.style.pointerEvents = 'auto';
                    });
                
                    // Optionally use data.order or data.remaining_balance here
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while placing the order.', 'error');
                });
            }

            
            closeReceiptBtn.addEventListener('click', () => {
                receiptModal.style.display = 'none';
            });
        
            window.addEventListener('click', (event) => {
                if (event.target === receiptModal) {
                    receiptModal.style.display = 'none';
                }
            });
        }
        
        // Generate a random order number
        function generateOrderNumber() {
            return Math.floor(100000 + Math.random() * 900000);
        }
        
        // Create a reusable order details object
        function createOrderDetails() {
            const selectedCustomerId = customerSelect.value;
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerName = selectedOption?.dataset?.name || 'N/A';
            const rateType = rateTypeSelect?.value || 'retail'; // fallback to 'retail' if nothing is selected or element is missing
            return {
                customer_id: selectedCustomerId,
                customer_name: customerName,
                cashier_id : {{ $user->id }},
                order_number: generateOrderNumber(),
                date: new Date().toISOString(),
                items: cart,
                rate_type: rateType,
                subtotal: parseCurrency(subtotalEl.textContent),
                tax: parseCurrency(taxEl.textContent),
                total: parseCurrency(totalEl.textContent)
            };
        }

        function parseCurrency(str) {
            const cleaned = str?.replace(/[^\d.-]/g, ''); // still removes ₱, $, etc.
            const parsed = parseFloat(cleaned);
            return isNaN(parsed) ? 0 : parsed;
        }
        

        // Generate receipt HTML
        function generateReceipt(orderDetails) {
            const dateObj = new Date(orderDetails.date);
            console.log(orderDetails);
            const date = dateObj.toLocaleDateString();
            const time = dateObj.toLocaleTimeString();

        
            let receiptHTML = `
                <div class="receipt-header">
                    <h2>STORE NAME</h2>
                    <p>123 Main Street</p>
                    <p>City, State 12345</p>
                    <p>Tel: (123) 456-7890</p>
                    <p>--------------------------------</p>
                    <p>Order #: ${orderDetails.order_number}</p>
                    <p>Date: ${date}</p>
                    <p>Time: ${time}</p>
                    <p>--------------------------------</p>
                </div>
                <div class="receipt-items">
            `;
        
            orderDetails.items.forEach(item => {
                const itemTotal = item.price * item.quantity;
                receiptHTML += `
                    <div class="receipt-item">
                        <div class="item-details">
                            <span class="item-quantity">${item.quantity}x</span>
                            <span>${item.name}</span>
                        </div>
                        <div class="item-total"> ₱${itemTotal.toFixed(2)}</div>
                    </div>
                `;
            });
        
            receiptHTML += `
                </div>
                <div class="receipt-summary">
                     <div class="summary-row">
                        <span>Rate Type:</span>
                        <span>${orderDetails.rate_type}</span>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>₱${orderDetails.subtotal.toFixed(2)}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (7%):</span>
                        <span>₱${orderDetails.tax.toFixed(2)}</span>
                    </div>
                    <div class="receipt-total">
                        <span>Total:</span>
                        <span>₱${orderDetails.total.toFixed(2)}</span>
                    </div>
                </div>
                <div class="receipt-footer text-dark">
                    <p>Thank you, ${orderDetails.customer_name}!</p>
                    <p>For your order of ${orderDetails.items.length} items</p>
                    <p>--------------------------------</p>
                    <p>Please come again</p>
                </div>
            `;
        
            receiptContent.innerHTML = receiptHTML;
        }
        
        // Show status message
        function showMessage(message, type) {
            statusMessage.textContent = message;
            statusMessage.className = `status-message ${type}`;
            
            setTimeout(() => {
                statusMessage.style.display = 'none';
                statusMessage.className = 'status-message';
            }, 5000);
        }
        
        // Initialize the page
        window.addEventListener('load', init);
        
        // Auto focus barcode input when page loads
        window.addEventListener('load', () => {
            barcodeInput.focus();
        });
        
        // Function to set the theme based on user preference or system setting
        function setTheme(theme) {
            const label = document.getElementById('theme-label');
            if (theme === 'dark') {
                label.textContent = 'Dark Mode';
                document.body.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                label.textContent = 'Light Mode';
                document.body.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }

        // Check for saved theme preference or system default
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else {
            const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
            setTheme(prefersDarkScheme ? 'dark' : 'light');
        }

        // Toggle theme when user clicks the button (if you want a toggle button)
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            const currentTheme = document.body.classList.contains('dark') ? 'dark' : 'light';
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryButtons = document.querySelectorAll('.category-button');
            const productCards = document.querySelectorAll('.product-card');
    
            categoryButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const selectedCategory = this.dataset.categoryId;
    
                    productCards.forEach(card => {
                        const cardCategory = card.dataset.categoryId;
    
                        if (cardCategory === selectedCategory) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
    
            // Optional: Auto-click the first category on load
            if (categoryButtons.length) {
                categoryButtons[0].click();
            }
        });
    </script>

    <script>

        const searchInput = document.getElementById('productSearch');
        const productCards = document.querySelectorAll('.product-card');
    
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();
    
            productCards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const barcode = card.getAttribute('data-barcode').toLowerCase();
    
                // Check if the query matches either the product name or barcode
                if (name.includes(query) || barcode.includes(query)) {
                    card.style.display = 'block'; // Show card
                } else {
                    card.style.display = 'none'; // Hide card
                }
            });
        });
    </script>
    
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePassword = document.getElementById('togglePassword');
      
        togglePassword.addEventListener('click', () => {
          const isHidden = passwordInput.type === 'password';
          passwordInput.type = isHidden ? 'text' : 'password';
          togglePassword.textContent = isHidden ? '🙈' : '👁️';
        });
      </script>

      <script>
        document.getElementById('customerSelect').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const balanceDisplay = document.getElementById('balanceDisplay');
        
            if (this.value) {
                const balance = selectedOption.getAttribute('data-balance');
                balanceDisplay.textContent = `Remaining Balance: $${balance}`;
                balanceDisplay.style.display = 'block';
                balanceDisplay.style.color = 'black';
            } else {
                balanceDisplay.style.display = 'none';
            }
        });
        </script>
        
</body>
</html>