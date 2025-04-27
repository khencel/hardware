<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple POS System</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
        }
        
        h1 {
            grid-column: 1 / -1;
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        
        .products-section {
            padding-right: 20px;
        }
        
        .barcode-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .barcode-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .barcode-input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }
        
        .cart-section {
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        
        .product-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .product-card {
            display: inline-block;
            width: calc(33.33% - 10px);
            margin: 5px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .product-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        
        .product-card h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #333;
        }
        
        .product-card p {
            margin: 0;
            font-weight: bold;
            color: #4CAF50;
        }
        
        .quantity-cell {
            display: flex;
            align-items: center;
        }
        
        .quantity-btn {
            background-color: #ddd;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .quantity-btn:hover {
            background-color: #ccc;
        }
        
        .quantity-value {
            margin: 0 8px;
            width: 30px;
            text-align: center;
        }
        
        .remove-btn {
            background-color: #ff5252;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .remove-btn:hover {
            background-color: #ff0000;
        }
        
        .cart-summary {
            margin-top: 20px;
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .total-row {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.2s;
        }
        
        .checkout-btn:hover {
            background-color: #45a049;
        }
        
        .action-btn {
            width: 100%;
            padding: 12px;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.2s;
        }
        
        .checkout-btn {
            background-color: #4CAF50;
        }
        
        .checkout-btn:hover {
            background-color: #45a049;
        }
        
        .print-btn {
            background-color: #2196F3;
        }
        
        .print-btn:hover {
            background-color: #0b7dda;
        }
        
        .status-message {
            grid-column: 1 / -1;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            display: none;
        }
        
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            display: block;
        }
        
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            display: block;
        }
        
        /* Alert styles */
        .barcode-alert {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 8px;
            border-radius: 4px;
            margin-top: 10px;
            display: none;
            font-weight: bold;
            text-align: center;
        }
        
        /* Receipt Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            width: 350px;
            max-width: 95%;
            border-radius: 5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .receipt {
            padding: 20px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .receipt-header h2 {
            margin: 0;
            font-size: 18px;
        }
        
        .receipt-header p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .receipt-items {
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }
        
        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-quantity {
            margin-right: 10px;
        }
        
        .receipt-summary {
            margin-bottom: 15px;
        }
        
        .receipt-total {
            font-weight: bold;
            font-size: 16px;
            margin-top: 5px;
        }
        
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .modal-actions {
            display: flex;
            border-top: 1px solid #eee;
        }
        
        .modal-btn {
            flex: 1;
            padding: 12px;
            border: none;
            background-color: #f5f5f5;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s;
        }
        
        .print-receipt-btn {
            background-color: #2196F3;
            color: white;
        }
        
        .print-receipt-btn:hover {
            background-color: #0b7dda;
        }
        
        .close-receipt-btn:hover {
            background-color: #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .products-section {
                padding-right: 0;
            }
            
            .product-card {
                width: calc(50% - 10px);
            }
        }
        
        @media (max-width: 480px) {
            .product-card {
                width: 100%;
            }
            
            .modal-content {
                width: 95%;
            }
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            
            .modal-content, .modal-content * {
                visibility: visible;
            }
            
            .modal-actions {
                display: none;
            }
            
            .modal-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                background-color: white;
            }
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid">
        <h1>Simple POS System</h1>
        <div class="row">
            <div class="col-7">
                <div id="statusMessage" class="status-message"></div>
        
                <div class="products-section">
                    <div class="barcode-section">
                        <h2>Scan Barcode</h2>
                        <input type="text" id="barcodeInput" class="barcode-input" placeholder="Scan barcode or enter product code..." autofocus>
                        <div id="barcodeAlert" class="barcode-alert"></div>
                    </div>
                    
                    <h2>Products</h2>
                    <div class="product-list">
                        <div class="product-card" onclick="searchItem()">
                            <strong>(F1)</strong>
                            <h3>Search</h3>
                            <p>Search Product</p>
                        </div>
                        @include('modal.pos.search_item')
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="cart-section">
                    <h2>Shopping Cart</h2>
                    <table id="cartTable">
                        <thead>
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
                        
                        <button id="checkoutBtn" class="action-btn checkout-btn">Checkout</button>
                        <button id="printReceiptBtn" class="action-btn print-btn" disabled>Print Receipt</button>
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
                <button id="actualPrintBtn" class="modal-btn print-receipt-btn">Print</button>
                <button id="closeReceiptBtn" class="modal-btn close-receipt-btn">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Sample product database
        const products = [
            { id: "1001", name: "Coffee Mug", price: 9.99, barcode: "4803746880907", image: "/api/placeholder/80/80" },
            { id: "1002", name: "Headphones", price: 49.99, barcode: "4800047862557", image: "/api/placeholder/80/80" },
            { id: "1003", name: "Notebook", price: 4.99, barcode: "1003", image: "/api/placeholder/80/80" },
            { id: "1004", name: "Water Bottle", price: 14.99, barcode: "1004", image: "/api/placeholder/80/80" },
            { id: "1005", name: "Bluetooth Speaker", price: 39.99, barcode: "1005", image: "/api/placeholder/80/80" },
            { id: "1006", name: "USB Cable", price: 7.99, barcode: "1006", image: "/api/placeholder/80/80" },
            { id: "1007", name: "Phone Case", price: 19.99, barcode: "1007", image: "/api/placeholder/80/80" },
            { id: "1008", name: "Mouse Pad", price: 6.99, barcode: "1008", image: "/api/placeholder/80/80" },
            { id: "1009", name: "Keyboard", price: 29.99, barcode: "1009", image: "/api/placeholder/80/80" }
        ];

        // Cart data
        let cart = [];
        let lastOrderDetails = null;
        
        // DOM Elements
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
        
        // Initialize the page
        function init() {
            renderProducts();
            setupBarcodeInput();
            setupCheckoutButton();
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
        
        // Add product to cart
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
            // Disable print receipt button when cart changes
            printReceiptBtn.disabled = true;
        }
        
        // Update cart display
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
                
                // Add event listeners for quantity buttons
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
        
        // Update item quantity
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
            
            // Disable print receipt button when cart changes
            printReceiptBtn.disabled = true;
        }
        
        // Remove item from cart
        function removeItem(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCart();
            
            // Disable print receipt button when cart changes
            printReceiptBtn.disabled = true;
        }
        
        // Update totals
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.07;
            const total = subtotal + tax;
            
            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            taxEl.textContent = `$${tax.toFixed(2)}`;
            totalEl.textContent = `$${total.toFixed(2)}`;
        }
        
        // Setup checkout button
        function setupCheckoutButton() {
            checkoutBtn.addEventListener('click', () => {
                if (cart.length === 0) {
                    showMessage('Your cart is empty', 'error');
                    return;
                }
                
                // Process checkout (in a real app, this would connect to payment processing)
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const tax = subtotal * 0.07;
                const total = subtotal + tax;
                
                // Store order details for receipt
                lastOrderDetails = {
                    orderNumber: generateOrderNumber(),
                    date: new Date(),
                    items: [...cart],
                    subtotal: subtotal,
                    tax: tax,
                    total: total
                };
                
                showMessage('Order processed successfully!', 'success');
                
                // Enable print receipt button after successful checkout
                printReceiptBtn.disabled = false;
                
                // Clear cart
                cart = [];
                updateCart();
            });
        }
        
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
    </script>
</body>
</html>