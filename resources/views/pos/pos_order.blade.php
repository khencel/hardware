<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple POS System</title>
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="{{ session('theme', 'light') }}">
    <div class="container-fluid" style="padding: 50px">
        <div class="row">
            <div class="col-6">
                <h1>Welcome {{ $user->firstname.' '.$user->lastname }}</h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="theme-toggle">
                    <label class="form-check-label" for="theme-toggle" id="theme-label">Dark Mode</label>
                </div>
            </div>
        </div>
     
        <div class="row">
            <div class="col-7">
                <div id="statusMessage" class="status-message"></div>
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
                        <div style="flex: 3; display: flex; flex-direction: column; overflow: hidden;">
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
                                            style="flex: 1 1 calc(33.333% - 0.75rem); border: 1px solid #ccc; border-radius: 6px; padding: 0.75rem; background-color: #fdfdfd; display: block; flex-direction: column; gap: 0.3rem; min-width: 180px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                            <h3 style="margin: 0; font-size: 16px;">{{ $product->name }}</h3>
                                            <p style="margin: 0; font-size: 14px;"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                                            <p style="margin: 0; font-size: 13px; color: #555;"><strong>Barcode:</strong> {{ $product->barcode }}</p>
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
            <div class="col-5">
                <div class="cart-table-container">
                    <div class="cart-section">
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
                            <tbody id="cartItems">
                                <!-- Cart items will be added here -->
                            </tbody>
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
                            <button id="printReceiptBtn" class="action-btn print-btn"> <img src="{{ asset('img/icon/secure-payment.png') }}" alt="Barcode Icon" width="30" height="30"  style="filter: brightness(0) invert(1);">  Payout Receipt</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Receipt Modal -->
    <div id="receiptModal" class="modal">
        <div class="modal-content">
            <div id="receiptContent" class="receipt">
                <!-- Receipt content will be dynamically generated -->
            </div>
            <div class="modal-actions">
                <button id="actualPrintBtn" class="modal-btn print-receipt-btn"><img src="{{ asset('img/icon/printing.png') }}" alt="Barcode Icon" width="30" height="30"  style="filter: brightness(0) invert(1);">  Print</button>
                <button id="closeReceiptBtn" class="modal-btn close-receipt-btn">Close</button>
            </div>
        </div>
    </div>

    <script>

        // Cart data
        let cart = [];
        let lastOrderDetails = null;
        
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
        console.log(products); 
        // Initialize the page
        function init() {
            //renderProducts();
            setupBarcodeInput();
            //setupCheckoutButton();
            setupReceiptFunctionality();
        }
        
        // Render product cards
        // function renderProducts() {
        //     productList.innerHTML = '';
            
        //     products.forEach(product => {
        //         const productCard = document.createElement('div');
        //         productCard.className = 'product-card';
        //         productCard.dataset.barcode = product.barcode;
                
        //         productCard.innerHTML = `
        //             <img src="${product.image}" alt="${product.name}">
        //             <h3>${product.name}</h3>
        //             <p>$${product.price.toFixed(2)}</p>
        //         `;
                
        //         productCard.addEventListener('click', () => {
        //             addToCart(product);
        //         });
                
        //         productList.appendChild(productCard);
        //     });
        // }
        
        // Setup barcode input with auto-submit
        function setupBarcodeInput() {
            barcodeInput.addEventListener('input', handleBarcodeInput);
            
            // Clear any alert when user starts typing again
            barcodeInput.addEventListener('focus', () => {
                hideBarcodeAlert();
            });
            barcodeInput.addEventListener('blur', () => {
                if (barcodeInput.value.trim() === '') {
                    hideBarcodeAlert();
                }
            });
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
        
       // 🔁 Attach event listener to each product card
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

    // ➕ Add product to cart
    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        
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

        updateCart();
        if (printReceiptBtn) printReceiptBtn.disabled = true;
    }

    // 🔄 Update cart display
    function updateCart() {
        cartItems.innerHTML = '';

        if (cart.length === 0) {
            cartItems.innerHTML = `<tr><td colspan="5" style="text-align: center;">Your cart is empty</td></tr>`;
        } else {
            cart.forEach(item => {
                const total = item.price * item.quantity;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td class="quantity-cell">
                        <button class="quantity-btn minus" data-id="${item.id}">-</button>
                        <span class="quantity-value">${item.quantity}</span>
                        <button class="quantity-btn plus" data-id="${item.id}">+</button>
                    </td>
                    <td>$${total.toFixed(2)}</td>
                    <td><button class="remove-btn" data-id="${item.id}">✕</button></td>
                `;

                cartItems.appendChild(row);
            });

            document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                btn.addEventListener('click', () => {
                    updateQuantity(btn.dataset.id, -1);
                });
            });

            document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                btn.addEventListener('click', () => {
                    updateQuantity(btn.dataset.id, 1);
                });
            });

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    removeItem(btn.dataset.id);
                });
            });
        }

            updateTotals();
        }

        // 🔧 Change item quantity
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

        // ❌ Remove item
        function removeItem(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCart();

            if (printReceiptBtn) printReceiptBtn.disabled = true;
        }

        // 💰 Calculate totals
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.07;
            const total = subtotal + tax;

            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            taxEl.textContent = `$${tax.toFixed(2)}`;
            totalEl.textContent = `$${total.toFixed(2)}`;
        }
        
        // Setup checkout button
        //function setupCheckoutButton() {
            //checkoutBtn.addEventListener('click', () => {
                //if (cart.length === 0) {
                   // showMessage('Your cart is empty', 'error');
                    //return;
                //}
                
                // Process checkout (in a real app, this would connect to payment processing)
                //const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                //const tax = subtotal * 0.07;
                //const total = subtotal + tax;
                
                // Store order details for receipt
                //lastOrderDetails = {
                  //  orderNumber: generateOrderNumber(),
                   // date: new Date(),
                   // items: [...cart],
                   // subtotal: subtotal,
                   // tax: tax,
                   /// total: total
               // };
                
                //showMessage('Order processed successfully!', 'success');
                
                // Enable print receipt button after successful checkout
               // printReceiptBtn.disabled = false;
                
                // Clear cart
               // cart = [];
               // updateCart();
           // });
       // }
        
        // Setup receipt functionality
        function setupReceiptFunctionality() {
            printReceiptBtn.addEventListener('click', () => {
                if (lastOrderDetails) {
                    generateReceipt(lastOrderDetails);
                    receiptModal.style.display = 'block';
                } else {
                    showMessage('No recent order to print receipt for', 'error');
                }
            });
            
            actualPrintBtn.addEventListener('click', () => {
                window.print();
            });
            
            closeReceiptBtn.addEventListener('click', () => {
                receiptModal.style.display = 'none';
            });
            
            // Close modal if clicked outside
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
        
        // Generate receipt HTML
        function generateReceipt(orderDetails) {
            const date = orderDetails.date.toLocaleDateString();
            const time = orderDetails.date.toLocaleTimeString();
            
            let receiptHTML = `
                <div class="receipt-header">
                    <h2>STORE NAME</h2>
                    <p>123 Main Street</p>
                    <p>City, State 12345</p>
                    <p>Tel: (123) 456-7890</p>
                    <p>--------------------------------</p>
                    <p>Order #: ${orderDetails.orderNumber}</p>
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
                        <div class="item-total">$${itemTotal.toFixed(2)}</div>
                    </div>
                `;
            });
            
            receiptHTML += `
                </div>
                
                <div class="receipt-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$${orderDetails.subtotal.toFixed(2)}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (7%):</span>
                        <span>$${orderDetails.tax.toFixed(2)}</span>
                    </div>
                    <div class="receipt-total">
                        <span>Total:</span>
                        <span>$${orderDetails.total.toFixed(2)}</span>
                    </div>
                </div>
                
                <div class="receipt-footer">
                    <p>Thank you for your purchase!</p>
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
            }, 3000);
        }
        
        // Initialize the page
        window.addEventListener('load', init);
        
        // Auto focus barcode input when page loads
        window.addEventListener('load', () => {
            barcodeInput.focus();
        });
        
        // Auto focus barcode input after clicking anywhere on the page
        document.addEventListener('click', (event) => {
            // Don't focus if clicking on the receipt modal
            if (!event.target.closest('.modal-content') && !event.target.closest('.print-btn')) {
                setTimeout(() => {
                    barcodeInput.focus();
                }, 100);
            }
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
        // Function to filter products based on search input
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
</body>
</html>