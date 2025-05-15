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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <div class="col-6" style="flex: 6; display: flex; flex-direction: column; ">
              
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
                            <h4 class="text-dark fs-6 fs-sm-5 fs-md-4 fs-lg-3">Categories</h4>
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
                            <h4 class="text-dark fs-6 fs-sm-5 fs-md-4 fs-lg-3" style="flex-shrink: 0;">Products</h4>
                        
                            <div style="flex: 1; overflow-y: auto;">
                                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                                    {{-- Product cards --}}
                                    @foreach($products as $product)
                                        <div 
                                            class="product-card"
                                            data-category-id="{{ $product->category_id }}"
                                            data-name="{{ $product->name }}" 
                                            data-barcode="{{ $product->barcode }}"
                                            data-category="{{ $product->category->name}}"
                                            data-id="{{ $product->id }}"
                                            data-price="{{ $product->price }}"
                                            data-wholesale="{{ $product->wholesale_price}}"
                                            data-retail="{{ $product->retail_price}}"
                                            data-quantity="{{ $product->quantity }}"
                                            
                                            style="flex: 1 1 calc(33.333% - 0.75rem); border: 1px solid #ccc; border-radius: 6px; padding: 0.75rem; background-color: #fdfdfd; display: block; flex-direction: column; gap: 0.3rem; min-width: 180px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                            <h3 style="margin: 0; font-size: 14px;">{{ $product->name }}</h3>
                                            <p style="margin: 0; font-size: 12px;"><strong>Wholesale Price : </strong>₱{{ number_format($product->wholesale_price, 2) }}</p>
                                            <p style="margin: 0; font-size: 12px;"><strong>Retail Price: </strong>₱{{ number_format($product->retail_price, 2) }}</p>
                                            <p style="margin: 0; font-size: 12px;"><strong>Selling Price: </strong>₱{{ number_format($product->price, 2) }}</p>
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
                                data-wholesale="{{ $product->wholesale_price}}"
                                data-retail="{{ $product->retail_price}}"
                                data-barcode="{{ $product->barcode }}"
                                data-category="{{ $product->category->name}}"
                                data-image="{{ $product->image }}">
                                {{ $product->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                
                
            </div>
            <div class="col-6" style="flex: 6; display: flex; flex-direction: column; background-color: rgba(255, 255, 255, 0.85); border-radius: 8px; padding: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">            
                <div class="cart-table-container">
                    <div class="cart-section cart-scroll">
                        <div class="row mb-3">
                            <div class="col-6">
                                <h2 for="HoldOderSelect" class="form-label text-dark"><img src="{{ asset('img/icon/hold.png') }}" alt="Barcode Icon" width="30" height="30"> Hold Order</h2>
                                <select id="HoldOderSelect" class="form-select" style="width: 100%;">
                                    <option value="">Choose order Number</option>
                                    @foreach($holdOrder as $order)
                                        <option value="{{ $order->id }}"
                                                data-name="{{ $order->customer_name }}">
                                            {{ $order->customer_name}} (order no : {{ $order->order_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <h2 for="QuotationSelect" class="form-label text-dark"><img src="{{ asset('img/icon/quotation.png') }}" alt="Barcode Icon" width="30" height="30">Quotation</h2>
                                <select id="QuotationSelect" class="form-select" style="width: 100%;">
                                    <option value="">Choose Quotation Number</option>
                                    @foreach($quotationOrder as $order)
                                        <option value="{{ $order->id }}"
                                                data-name="{{ $order->customer_name }}">
                                            {{ $order->customer_name}} (order no : {{ $order->order_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h2><img src="{{ asset('img/icon/shopping-cart.png') }}" alt="Barcode Icon" width="30" height="30"> Shopping Cart</h2>
                        <table id="cartTable" class="fs-6 fs-sm-5 fs-md-4 fs-lg-3">
                            <thead class="table-header text-center ">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th></th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <div id="cartScrollWrapper" class="cart-scroll-wrapper">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody id="cartItems">
                                            <!-- Cart items will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </table>
                        
                        <div class="cart-summary">
                            <div class="summary-row" id="discountSection">
                                <label class="text-dark">
                                    Apply Discount
                                    <input type="checkbox" id="applyDiscountCheckbox">
                                </label>
                                <span id="discount">₱0.00</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax ({{ number_format($taxes, 0) }}%):</span>
                                <span id="tax">00.00</span>
                            </div>
                            <div class="summary-row total-row">
                                <span>Total:</span>
                                <span id="total">$0.00</span>
                            </div>
                            
                            <div id="printWrapper"  class="row">
                                <div class="col-4">
                                    <button id="printReceiptBtn" class="action-btn print-btn"> <img src="{{ asset('img/icon/secure-payment.png') }}" alt="Barcode Icon" width="30" height="30"  style="filter: brightness(0) invert(1);">  
                                        Payout Receipt (F1)
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button id="printQuotationBtn" class="action-btn quotation-btn" style="margin-left: 10px;">
                                        <img src="{{ asset('img/icon/quotation.png') }}" alt="Quotation Icon" width="30" height="30">  
                                        Make a Quotation (F2)
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button id="printHoldBtn" class="action-btn hold-btn" style="margin-left: 10px;">
                                        <img src="{{ asset('img/icon/hold.png') }}" alt="Quotation Icon" width="30" height="30">  
                                       Hold (F3)
                                    </button>
                                </div>
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

      {{--  discount modal  --}}
      <div id="discountModal" class="modal" style="display: none;">
        <div class="modal-content text-dark">
            <span class="close" id="closeDiscountModal">&times;</span>
            <h3>Select a Discount</h3>
            <form id="discountForm">
                @foreach ($discounts as $discount)
                    <div>
                        <label>
                            <input 
                                type="radio" 
                                name="discount_option" 
                                value="{{ $discount->id }}"
                                data-type="{{ $discount->is_percentage ? 'percent' : 'fixed' }}"
                                data-value="{{ $discount->discount }}"
                            >
                            {{ $discount->title_name }} 
                            @if($discount->is_percentage)
                                ({{ $discount->discount }}%)
                            @else
                                (₱{{ number_format($discount->discount, 2) }})
                            @endif
                        </label>
                    </div>
                @endforeach
            </form>
            <button id="applySelectedDiscount" class="apply-discount-btn">
                <span class="icon">🎟️</span> Apply Discount
            </button>
        </div>
    </div>
    
    {{--  // Product Modal  --}}
    <div id="product-modal" class="modal text-dark" style="display: none;">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <h3 id="product-name"></h3>
          <div id="price-options-container" ></div>
          <button id="changeProductType" class="apply-discount-btn mt-4">🔁 Update This Item</button>
        </div>
      </div>

      {{-- Hold Modal --}}
        <div id="holdModal" class="modal">
            <div class="modal-content">
            <span id="closeHoldModal" class="close">&times;</span>
            <h2>Put Order on Hold for</h2>
            <label for="customerNameInput">Customer Name:</label>
            <input type="text" id="customerNameInput" placeholder="Enter customer name" />
        
            <label for="holdReason">Reason:</label>
            <textarea id="holdReason" rows="4" cols="40" placeholder="Enter reason..."></textarea>
            
            <button id="confirmHoldBtn" class="apply-discount-btn mt-4"> 🕓 Confirm Hold</button>
            </div>
        </div>
  


        {{--  payment modal  --}}
        <div id="paymentModal" class="modal text-dark" style="display:none;">
            <div class="modal-content">
                <span id="closePaymentMethodModal" class="close">&times;</span>
                <h3>Select Payment Method</h3>
        
                <div class="payment-options">
                    <div class="payment-option" data-method="Cash">💵 Cash</div>
                    <div class="payment-option" data-method="GCash">📱 GCash</div>
                    <div class="payment-option" data-method="Bank Transfer">🏦 Bank Transfer</div>
                    <div class="payment-option" data-method="Bank Checks">🧾 Bank Checks</div>
                    <div class="payment-option" data-method="Credit">💳 Credit</div> 
                    <div class="payment-option" data-method="Other">💳 Other</div> 
                </div>
        
                <!-- Customer Name -->
                <div  id="customerContainer" >
                    <label for="customerName" style="font-weight: bold; font-size: 14px;">Customer Name:</label>
                    <input 
                        type="text" 
                        id="customerName"
                        style="padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; width: 100%; outline: none; transition: border-color 0.3s ease; margin-top: 5px;" 
                        placeholder="Enter Customer Name"
                    >
            
                </div>
               
                <!-- Reference Number -->
                <div id="refContainer" style="margin-top:20px; display:none;">
                    <label for="refNumber" style="font-weight: bold; font-size: 14px;">Reference Number:</label>
                    <input 
                        type="text" 
                        id="refNumber" 
                        style="padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; width: 100%; outline: none; transition: border-color 0.3s ease; margin-top: 5px;" 
                        placeholder="Enter reference number"
                    >
                </div>
        
                <!-- Customer Select Dropdown (Initially hidden) -->
                <div id="creditCustomerContainer" style="display:none; margin-top: 20px;">
                    <h2 for="customerSelect" class="form-label text-dark">
                        <img src="{{ asset('img/icon/profile.png') }}" alt="Profile Icon" width="30" height="30"> Customer
                    </h2>
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

                <!-- Delivery Option -->
                <div class="row mt-3">
                  <div class="col-6 mb-3">
                    <label for="deliveryOption" class="form-label fw-bold text-dark d-flex align-items-center gap-2">
                      <img src="{{ asset('img/icon/delivery-man.png') }}" alt="Delivery Icon" width="30" height="30">
                      Delivery Option
                    </label>
                    <select id="deliveryOption" class="form-select">
                      <option value="pickup">Pickup</option>
                      <option value="delivery">Delivery</option>
                    </select>
                  </div>
            
                  <!-- Driver Selection -->
                  <div class="col-6 mb-3" id="driverSelectionContainer" style="display: none;">
                    <label for="driverSelect" class="form-label fw-bold text-dark d-flex align-items-center gap-2">
                      <img src="{{ asset('img/icon/fast-delivery.png') }}" alt="Driver Icon" width="30" height="30">
                      Choose a Driver
                    </label>
                    <select id="driverSelect" class="form-select">
                      <option value="">Select Driver</option>
                      @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" data-name="{{ $driver->name }}">{{ $driver->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              
        
                <!-- Confirm Button -->
                <div class="mt-4">
                    <button 
                        id="confirmPaymentBtn" 
                        class="apply-discount-btn" 
                        style="width: 100%; padding: 12px; font-size: 16px; border: none; border-radius: 6px; background-color: #28a745; color: white; cursor: pointer; transition: background-color 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#218838'"
                        onmouseout="this.style.backgroundColor='#28a745'"
                    >
                        ✔ Confirm
                    </button>
                </div>
            </div>
        </div>
        
        

    <script>

        // Cart data
        let cart = [];
        let lastOrderDetails = null;
        let itemToRemoveId = null;
        let selectedDiscountType = 'none';
        let selectedDiscountValue = 0;
        let driverId = null;
        let driverName = null;
        let selectedHoldOrderId = null;
        let selectedQuotationOrderId = null;
        let isQuotation = false;
        let isHold = false;
        let selectedMethod = "";
        let customerNameInputed = '';
        let itemBeingEditedId = null;
        const password = @json(config('app.remove_item_password'));
        
        
        // DOM Elements
        const hiddenProducts = document.querySelectorAll('#hidden-products .hidden-product'); //fetching products
        const products = Array.from(hiddenProducts).map(product => ({
            id: product.dataset.id,
            name: product.dataset.name,
            price: parseFloat(product.dataset.price),
            sellingPrice: parseFloat(product.dataset.price),
            wholesalePrice: parseFloat(product.dataset.wholesale) || 0,
            retailPrice: parseFloat(product.dataset.retail) || 0,
            barcode: product.dataset.barcode,
            category: product.dataset.category?.name,
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
        const printQuotationBtn  = document.getElementById("printQuotationBtn");
        const printHoldBtn = document.getElementById('printHoldBtn');
        const statusMessage = document.getElementById('statusMessage');
        const receiptModal = document.getElementById('receiptModal');
        const receiptContent = document.getElementById('receiptContent');
        const actualPrintBtn = document.getElementById('actualPrintBtn');
        const closeReceiptBtn = document.getElementById('closeReceiptBtn');
        const customer = document.getElementById('customerSelect');
        const rateType = document.getElementById('rateTypeSelect');
        const taxPercentage = {{ $taxes }};
        const functionbuttons = [printReceiptBtn, printQuotationBtn, printHoldBtn];

        const paymentOptions = document.querySelectorAll('.payment-option');
        const customerNameInput = document.getElementById('customerName');
        const customerContainer = document.getElementById('customerContainer');
        const refContainer = document.getElementById('refContainer');
        const creditCustomerContainer = document.getElementById('creditCustomerContainer');
        const balanceDisplay = document.getElementById('balanceDisplay');
        const customerSelect = document.getElementById('customerSelect');
        //discount
        const discountModal = document.getElementById('discountModal');
        const applyDiscountCheckbox = document.getElementById('applyDiscountCheckbox');
        const closeDiscountModal = document.getElementById('closeDiscountModal');
        const applySelectedDiscountBtn = document.getElementById('applySelectedDiscount');
        const discountEl = document.getElementById('discount'); 

        // Initialize the page
        function init() {
            setupBarcodeInput();
            setupReceiptFunctionality();
            setButtonsDisabledState(functionbuttons, true);      // disable
        }
        
   
        function setButtonsDisabledState(buttons, isDisabled) {
            functionbuttons.forEach(btn => {
              if (btn) btn.disabled = isDisabled;
            });
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
              Swal.fire({
                toast: true,
                position: 'top-end', // can be 'top', 'bottom-end', etc.
                icon: 'success',
                title: 'Product void successfully!',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect password.',
                });
                return;
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
                    addToCart(product, product.sellingPrice, 'Selling Price');
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
                    id: card.dataset.id,
                    name: card.dataset.name,
                    barcode: card.dataset.barcode,
                    category: card.dataset.category || 'Uncategorized',
                    sellingPrice: parseFloat(card.dataset.price) || 0,
                    wholesalePrice: parseFloat(card.dataset.wholesale) || 0,
                    retailPrice: parseFloat(card.dataset.retail) || 0,
                };


                // Auto-add to cart with Selling Price on click
                addToCart(product, product.sellingPrice, 'Selling Price');
                setButtonsDisabledState(functionbuttons, false); // enable
            });
        });

   
        
        function openModal(product) {
            const modal = document.getElementById('product-modal');
            document.getElementById('product-name').textContent = product.name;
        
            const priceOptionsContainer = document.getElementById('price-options-container');
            priceOptionsContainer.innerHTML = '';
        
            const prices = [
                { label: 'Selling Price', value: product.sellingPrice },
                { label: 'Wholesale Price', value: product.wholesalePrice },
                { label: 'Retail Price', value: product.retailPrice }
            ];
        
            // Create radio buttons dynamically for each price
            prices.forEach((priceOption, index) => {
                const radioId = `price-${index}`;
                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
                    <input type="radio" id="${radioId}" name="price" value="${priceOption.value}" ${index === 0 ? 'checked' : ''}>
                    <label for="${radioId}">${priceOption.label} - ₱${priceOption.value.toFixed(2)}</label>
                `;
                priceOptionsContainer.appendChild(wrapper);
            });
        
            // Function to get the selected price and label
            function getSelectedPrice() {
                const selectedPrice = parseFloat(document.querySelector('input[name="price"]:checked').value); // Get selected price (as number)
                
                // Get the corresponding label for the selected price
                const selectedOption = prices.find(p => p.value === selectedPrice);
                const selectedLabel = selectedOption ? selectedOption.label : 'Unknown Price';
                
                return { selectedPrice, selectedLabel };
            }
        
            // Get the selected price and label when the modal is opened
            const { selectedPrice, selectedLabel } = getSelectedPrice();
    
            itemBeingEditedId = product.id; // Store the ID of the item being edited
            modal.style.display = 'block';
        }

        document.getElementById('changeProductType').addEventListener('click', () => {
            if (!itemBeingEditedId) {
                console.warn("No item being edited.");
                return;
            }
        
            const selectedRadio = document.querySelector('input[name="price"]:checked');
            if (!selectedRadio) {
                console.warn("No price option selected.");
                return;
            }
        
            const selectedPrice = parseFloat(selectedRadio.value);
            const selectedLabel = selectedRadio.nextElementSibling.textContent.split(' - ')[0]; // Grab label
        
            const itemIndex = cart.findIndex(item => item.id === itemBeingEditedId);
            if (itemIndex !== -1) {
                cart[itemIndex].price = selectedPrice;
                cart[itemIndex].type = selectedLabel;
                updateCart();
                closeModal();
            } else {
                console.warn(`Item with ID ${itemBeingEditedId} not found in cart.`);
            }
        
            // Reset
            itemBeingEditedId = null;
            modalProductData = null;
        });
        
        
      
          function closeModal() {
            document.getElementById('product-modal').style.display = 'none';
          }
      
       
        // Close modal if user clicks outside the modal content
        window.addEventListener('click', (event) => {
            const modal = document.getElementById('product-modal');
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('product-type-btn')) {
                const productId = e.target.getAttribute('data-id');
                const productType = e.target.getAttribute('data-type');
                const sellingPrice = parseFloat(e.target.getAttribute('data-selling-price'));
                const wholesalePrice = parseFloat(e.target.getAttribute('data-wholesale-price'));
                const retailPrice = parseFloat(e.target.getAttribute('data-retail-price'));
                const product = cart.find(item => item.id === productId);
                
                if (product) {
                    openModal(product); // pass the specific product
                    updateCart();
                } else {
                    console.warn(`Product with ID ${productId} not found in cart.`);
                }
            }
        });
        
       


        function addToCart(product, price, selectedLabel) {
            // Check if the same product with the same price type already exists in the cart
            const existingItem = cart.find(item => item.id === product.id && item.type === selectedLabel);
        
            // Find the corresponding product card in the DOM
            const productCard = document.querySelector(`.product-card[data-id="${product.id}"]`);
            let currentStock = parseInt(productCard.getAttribute('data-quantity'));
            if (currentStock <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'This product is out of stock.',
                });
                return;
            }
        
            if (existingItem) {
                existingItem.quantity++;
            } else {
                // Treat product with different price type as a unique item
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: price,
                    type: selectedLabel,
                    category: product.category,
                    retailPrice : product.retailPrice,
                    sellingPrice : product.sellingPrice,
                    wholesalePrice: product.wholesalePrice,
                    quantity: 1
                });
            }
        
            // Decrease stock
            currentStock--;
            productCard.setAttribute('data-quantity', currentStock);
        
            // Update stock display
            const qtySpan = productCard.querySelector('span');
            if (qtySpan) {
                qtySpan.textContent = `Stock: ${currentStock}`;
            }

            
            setButtonsDisabledState(functionbuttons, false); // enable
            updateCart();
            closeModal();
        }

        function updateCart() {
            cartItems.innerHTML = '';
        
            const cartSummary = document.getElementById('cartSummary');
            const customerSelect = document.getElementById('customerSelect');
            const printBtn = document.getElementById('printReceiptBtn');
            
         
            if (cartSummary) cartSummary.style.display = 'flex';
            if (printWrapper) printWrapper.style.display = 'flex';
            if (printBtn) printBtn.disabled = false;


            if (cart.length === 0) {
                setButtonsDisabledState(functionbuttons, true);
                cartItems.innerHTML = `<tr><td colspan="5" style="text-align: center;">🛒 Your cart is empty</td></tr>`;
            } else {
                setButtonsDisabledState(functionbuttons, false);
                cart.forEach(item => {
                    const total = item.price * item.quantity;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name} ${item.price}</td>
                        <td>₱${item.price.toFixed(2)}</td>
                        <td class="quantity-cell">
                            <button class="quantity-btn minus" data-id="${item.id}">-</button>
                            <span class="quantity-value"> ${item.quantity}</span>
                            <button class="quantity-btn plus" data-id="${item.id}">+</button>
                        </td>
                        <td>₱${total.toFixed(2)}</td>
                        <td><button class="remove-btn" data-id="${item.id}">✕</button></td>
                         <td>
                            ${item.type}
                        <button 
                            class="product-type-btn" 
                            data-id="${item.id}" 
                            data-type="${item.type}" 
                            data-selling-price="${item}" 
                            data-wholesale-price="${item.wholesalePrice}"
                            data-retail-price="${item.retailPrice}"
                            data-selling-Price="${item.sellingPrice}"
                            style="background-color: transparent; border: none; cursor: pointer; color: #007bff;"
                        >
                            🔄
                        </button>
                        </td>
                       
                       
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
        }
        
        // Discount Checkbox
        applyDiscountCheckbox.addEventListener('change', () => {
            if (applyDiscountCheckbox.checked) {
                discountModal.style.display = 'block';
            } else {
                selectedDiscountType = 'none';
                selectedDiscountValue = 0;
                updateTotals();
                document.getElementById('discount').textContent = '₱0.00';
            }
        });
        
        closeDiscountModal.addEventListener('click', () => {
            discountModal.style.display = 'none';
            applyDiscountCheckbox.checked = false;
            document.getElementById('discountModal').style.display = 'none';
        });
        
        applySelectedDiscountBtn.addEventListener('click', () => {
            const selectedOption = document.querySelector('input[name="discount_option"]:checked');
            if (selectedOption) {
                selectedDiscountType = selectedOption.dataset.type;
                selectedDiscountValue = parseFloat(selectedOption.dataset.value);
        
                // Validate: Check if the discount is greater than the subtotal
                const subtotal = parseCurrency(subtotalEl.textContent); 
                if (selectedDiscountValue > subtotal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Discount cannot be greater than the subtotal price.',
                    });
                    selectedDiscountType = 'none'; 
                    selectedDiscountValue = 0; 
                    updateTotals();
                    document.getElementById('discount').textContent = '₱0.00';
                    return;
                }
        
                updateTotals(); // Update totals with the selected discount
            }
            discountModal.style.display = 'none';
        });
        


        // Update totals in the carts
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * (taxPercentage / 100);
    
            let discount = 0;
            if (selectedDiscountType === 'percent') {
                discount = subtotal * (selectedDiscountValue / 100);
            } else if (selectedDiscountType === 'fixed') {
                discount = selectedDiscountValue;
            }
    
            const total = subtotal + tax - discount;
    
            subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;
            taxEl.textContent = `₱${tax.toFixed(2)}`;
            discountEl.textContent = `₱${discount.toFixed(2)}`;
            totalEl.textContent = `₱${total.toFixed(2)}`;
        }
        
        //Quotation Functionality
        function setupReceiptFunctionality() {
            // Print receipt functionality
            printReceiptBtn.addEventListener('click', () => {
                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No items in cart to print receipt',
                    });
            
                    return;
                }
            
                // Show the payment method modal
                document.getElementById('paymentModal').style.display = 'block';
            });

           //close payment method modal
            closePaymentMethodModal.addEventListener('click', () => {
                document.getElementById('paymentModal').style.display  = 'none';
            });

            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', () => {
                    // Clear old selection
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            
                    // Set new selection
                    option.classList.add('selected');
                    selectedMethod = option.getAttribute('data-method');
                    //enan
                    if (selectedMethod === 'Cash') {
                        customerContainer.style.display = 'block';
                        refContainer.style.display = 'none';
                        creditCustomerContainer.style.display = 'none';
                    }else if(selectedMethod === 'Credit') {
                        customerContainer.style.display = 'none';
                        refContainer.style.display = 'none';
                        creditCustomerContainer.style.display = 'block';        
                    }else {
                        customerContainer.style.display = 'block';
                        refContainer.style.display = 'block';
                        creditCustomerContainer.style.display = 'none';
                    }
                });
            });

            document.getElementById('confirmPaymentBtn').addEventListener('click', () => {
                const refNumber = document.getElementById('refNumber').value.trim();
                const customerName = customerNameInput ? customerNameInput.value.trim() : '';

                if (!selectedMethod && !isQuotation ) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Name is Required',
                        text: 'Please select a payment method',
                    });
                    return;
                }
                
                if (customerName === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Errors',
                        text: 'Please Enter Name',
                    });
                    return;
                }
                
             
                const methodsRequiringRef = ['Bank Transfer', 'Bank Checks', 'Gcash', 'Other'];

                if (methodsRequiringRef.includes(selectedMethod) && !refNumber) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Reference Required',
                        text: 'Reference number is required for non-cash and credit payments',
                    });
                    return;
                } 
                
                if(selectedMethod === 'Cash' && !customerName){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Customer Name  Required',
                        text: 'Please Input Customer name',
                    });
                    return;
                }

            
    
                const orderDetails = createOrderDetails();
                orderDetails.payment_method = selectedMethod;
                orderDetails.reference_number = selectedMethod === 'Cash' ? null : refNumber;
                orderDetails.customer_name = selectedMethod === 'Credit' ? 
                document.getElementById('customerSelect').options[document.getElementById('customerSelect').selectedIndex].text : 
                customerName;
                customerNameInputed = customerName;
                lastOrderDetails = orderDetails;

                if(isQuotation){
                    generateQuotation(orderDetails) ;
                }else{
                    generateReceipt(orderDetails);
                }
              
                receiptModal.style.display = 'block';
                document.getElementById('paymentModal').style.display = 'none';
            
            });
        
            // Print quotation functionality
            printQuotationBtn.addEventListener('click', () => {
                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No items in cart to make a quotation',
                    });
                    return;
                }
        
                const orderDetails = createOrderDetails();
                lastOrderDetails = orderDetails;
                isQuotation = true;
                document.getElementById('paymentModal').style.display = 'block';
                const paymentOptions = document.querySelectorAll('.payment-option');
                paymentOptions.forEach(option => {
                  option.style.display = 'none';
                });
            });

   
            printHoldBtn.addEventListener('click', () => {
                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No items in cart to make a hold',
                    });
                    return;
                }
                holdModal.style.display = 'block';
            });
            
            // Close modal logic
            closeHoldModal.addEventListener('click', () => {
                holdModal.style.display = 'none';
            });
            
            // Confirm Hold logic
            confirmHoldBtn.addEventListener('click', () => {
                const reason = holdReason.value.trim();
                const customerName = document.getElementById('customerNameInput').value.trim(); // Get customer name
                
                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No items in cart to make a hold',
                    });
                    return;
                }
            
                if (reason === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Reason Required',
                        text: 'Please enter a reason for holding the order.',
                    });
                    return;
                }
            
                if (customerName === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Customer Name Required',
                        text: 'Please enter the customer name.',
                    });
                    return;
                }
                
                
                const orderDetails = createOrderDetails();
                orderDetails.reason = reason;
                orderDetails.customer_name = customerName;  // Add customer name to orderDetails
                lastOrderDetails = orderDetails;
                orderDetails.order_number = cart[0].order_number;
              
                saveHoldOrder(orderDetails);
                Swal.fire({
                    toast: true,
                    position: 'top-end', // can be 'top', 'bottom-end', etc.
                    icon: 'success',
                    title: 'Order has been put on hold!',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                // Reset the modal
                holdModal.style.display = 'none';
                holdReason.value = ''; 
                document.getElementById('customerNameInput').value = ''; 
                 //Reset the dropdown
                const holdOrderSelect = document.getElementById('HoldOderSelect');
                if (holdOrderSelect) {
                    holdOrderSelect.selectedIndex = 0; // Reset to default "Choose order Number"
                }

                
            });
            // Function to save hold order
            function saveHoldOrder(orderDetails) {
                fetch('/api/hold-orders', {
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

                    receiptModal.style.display = 'none';
                
                    // Reset cart
                    cart = [];
                    updateCart();
                
                    // Reset stock on UI if needed
                    document.querySelectorAll('.product-card').forEach(card => {
                        card.style.opacity = '1';
                        card.style.pointerEvents = 'auto';
                    });

                })
            }
        
            // Actual print functionality
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
        
                // Only save order to backend if it's not a quotation
                if (isQuotation) {
                    saveOrderToQuotation(orderDetails);
                }else{
                    saveOrderToBackend(orderDetails);
                }
                
                receiptModal.style.display = 'none';
                // Reset isQuotation flag
                isQuotation = false;
            });
        }
        
            function saveOrderToQuotation(orderDetails) {
                console.log('Saving order to backend:', orderDetails);
                fetch('/api/quotation', {
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

                    Swal.fire({
                        toast: true,
                        position: 'top-end', // can be 'top', 'bottom-end', etc.
                        icon: 'success',
                        title: 'Qoutation successfully Made!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    
                    receiptModal.style.display = 'none';
                    selectedMethod = ''; 
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                    document.getElementById('refNumber').value = '';
                    document.getElementById('refContainer').style.display = 'none';
                    document.getElementById('customerName').value = '';
                    document.getElementById('customerContainer').style.display = 'none';
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
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.payment_method.toLowerCase() === 'credit' 
                            ? `${data.message || 'Order successful!'}\n${data.order.customer_name} current balance was ${data.remaining_balance}`
                            : 'Order placed successfully!',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });


                    receiptModal.style.display = 'none';
                    selectedMethod = ''; 
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                    document.getElementById('refNumber').value = '';
                    document.getElementById('refContainer').style.display = 'none';
                    document.getElementById('customerName').value = '';
                    document.getElementById('customerContainer').style.display = 'none';
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
        
        // Generate a random order number
        function generateOrderNumber() {
            const randomNum = Math.floor(100000 + Math.random() * 900000);
            return `${randomNum}`;
        }
        
        // Create a reusable order details object
        function createOrderDetails() {
            const selectedCustomerId = customerSelect.value;
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerName = selectedOption?.dataset?.name || 'N/A';
            
            //delivery 
            const deliveryOption = document.getElementById('deliveryOption')?.value || 'pickup';
            const subtotal = parseCurrency(subtotalEl.textContent);
            const tax = parseCurrency(taxEl.textContent);
            const total = parseCurrency(totalEl.textContent);
        
            let discount = 0; // Default value for discount
    
            // Check if discount is applied
            if (applyDiscountCheckbox.checked && selectedDiscountType && selectedDiscountValue !== null) {
                if (selectedDiscountType === 'percent') {
                    discount = subtotal * (selectedDiscountValue / 100);
                } else if (selectedDiscountType === 'fixed') {
                    discount = selectedDiscountValue;
                }
            }
            //check if option is delivery
            if (deliveryOption === 'delivery') {
                const driverSelect = document.getElementById('driverSelect');
                const selectedDriverOption = driverSelect.options[driverSelect.selectedIndex];
                driverId = selectedDriverOption?.value || null;
                driverName = selectedDriverOption?.dataset?.name || null;
            }   


            const itemsWithCategory = cart.map(item => ({
                ...item,
                category: item.category || 'Uncategorized',  // Default to 'Uncategorized' if no category
                type : item.type || 'N/A'  // Default to 'N/A' if no type
            }));

            const referenceNumber = selectedMethod !== 'Cash' ? document.getElementById('refNumber').value.trim() : null;
            const customerNameX = selectedMethod === 'Credit' ? customerName :  customerNameInputed;
            return {
                customer_id: 1,
                customer_name: customerNameX,
                cashier_id: {{ $user->id }},
                order_number: generateOrderNumber(),
                date: new Date().toISOString(),
                items: itemsWithCategory,
                subtotal: subtotal,
                discount: discount,
                tax: tax,
                total: total,
                delivery_option: deliveryOption,
                driver_id: driverId,  
                driver_name: driverName,
                payment_method: selectedMethod,
                reference_number: referenceNumber
            };
        }
        
        function parseCurrency(str) {
            const cleaned = str?.replace(/[^\d.-]/g, ''); // still removes ₱, $, etc.
            const parsed = parseFloat(cleaned);
            return isNaN(parsed) ? 0 : parsed;
        }
        
        // Generate quotation HTML
        function formatDateTime(dateString) {
            const dateObj = new Date(dateString);
            return {
                date: dateObj.toLocaleDateString(),
                time: dateObj.toLocaleTimeString()
            };
        }
        
        function generateReceiptItemsHTML(items) {
            return items.map(item => {
                const itemTotal = item.price * item.quantity;
                return `
                    <div class="receipt-item text-dark">
                        <div class="item-details">
                            <span class="item-quantity">${item.quantity}x</span>
                            <span>${item.name}</span>
                        </div>
                        <div class="item-total">${item.type} (₱${itemTotal.toFixed(2)})</div>
                    </div>
                `;
            }).join('');
        }
        
        function generateSummaryHTML(orderDetails) {
            const discountHTML = orderDetails.discount && orderDetails.discount > 0
                ? `<div class="summary-row">
                        <span>Discount:</span>
                        <span>-₱${orderDetails.discount.toFixed(2)}</span>
                   </div>`
                : '';
        
            return `
                <div class="receipt-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>₱${orderDetails.subtotal.toFixed(2)}</span>
                    </div>
                    ${discountHTML}
                    <div class="summary-row">
                        <span>Tax (7%):</span>
                        <span>₱${orderDetails.tax.toFixed(2)}</span>
                    </div>
                    <div class="receipt-total">
                        <span>Total:</span>
                        <span>₱${orderDetails.total.toFixed(2)}</span>
                    </div>
                </div>
            `;
        }
        
        function generateHeaderHTML(orderDetails, isQuotation) {
            const { date, time } = formatDateTime(orderDetails.date);
            return `
                <div class="receipt-header">
                    <h2>STORE NAME</h2>
                    <p>123 Main Street</p>
                    <p>City, State 12345</p>
                    <p>Tel: (123) 456-7890</p>
                    <p>--------------------------------</p>
                    <p>${isQuotation ? 'Quotation' : 'Order'} #: ${orderDetails.order_number}</p>
                    <p>Date: ${date}</p>
                    <p>Time: ${time}</p>
                    <p>Option: ${orderDetails.delivery_option}</p>
                    ${orderDetails.delivery_option === 'delivery' ? `<p>Driver Name: ${orderDetails.driver_name}</p>` : ''}
                    <p>--------------------------------</p>
                </div>
            `;
        }
        
        function generateFooterHTML(orderDetails, isQuotation) {
            const isCash = orderDetails.payment_method?.toLowerCase() === 'cash';
            return `
                <div class="receipt-footer text-dark">
                    <p>${isQuotation ? 'Prepared for' : 'Thank you'}, ${orderDetails.customer_name}!</p>
                    <p>${isQuotation ? 'Quotation includes' : 'For your order of'} ${orderDetails.items.length} items</p>
                    <p>--------------------------------</p>
                    <p>Payment Method: ${orderDetails.payment_method || 'N/A'}</p>
                    ${!isCash && orderDetails.reference_number ? `<p>Reference #: ${orderDetails.reference_number}</p>` : ''}
                    <p>--------------------------------</p>
                    <p>${isQuotation ? 'This is not a receipt' : 'Please come again'}</p>
                </div>
            `;
        }
        
        function generateDocumentHTML(orderDetails, isQuotation = false) {
            return `
                ${generateHeaderHTML(orderDetails, isQuotation)}
                <div class="receipt-items">
                    ${generateReceiptItemsHTML(orderDetails.items)}
                </div>
                ${generateSummaryHTML(orderDetails)}
                ${generateFooterHTML(orderDetails, isQuotation)}
            `;
        }
        
        function generateQuotation(orderDetails) {
            receiptContent.innerHTML = generateDocumentHTML(orderDetails, true);
        }
        
        function generateReceipt(orderDetails) {
            receiptContent.innerHTML = generateDocumentHTML(orderDetails, false);
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


        document.getElementById('HoldOderSelect').addEventListener('change', function () {
            const selectedHoldOrderId = this.value;
        
            // Reset QuotationSelect
            const quotationSelect = document.getElementById('QuotationSelect');
            if (quotationSelect) quotationSelect.value = '';
        
            fetchOrderData(`/api/hold-orders/${selectedHoldOrderId}`, 'hold order');
        });
        
        document.getElementById('QuotationSelect').addEventListener('change', function () {
            const selectedQuotationOrderId = this.value;
        
            // Reset HoldOderSelect
            const holdSelect = document.getElementById('HoldOderSelect');
            if (holdSelect) holdSelect.value = '';
        
            fetchOrderData(`/api/quotation/${selectedQuotationOrderId}`, 'quotation');
        });
        
        function fetchOrderData(url, orderType = 'order') {
            fetch(url)
                .then(async (response) => {
                    if (!response.ok) {
                        showMessage(`Could not retrieve ${orderType}`, 'error');
                        return;
                    }
        
                    const data = await response.json();
        
                    const customerSelect = document.getElementById('customerSelect');
                    if (customerSelect && data.customer_id) {
                        customerSelect.value = data.customer_id;
                        customerSelect.dispatchEvent(new Event('change'));
                    }
        
                    cart = [];
        
                    data.items.forEach(item => {
                        cart.push({
                            id: item.id,
                            name: item.name,
                            price: item.price,
                            type: item.type,
                            category: item.category,
                            quantity: item.quantity,
                            order_number: data.order_number,
                        });
                    });
        
                    updateCart();
                })
                .catch(error => {
                    console.error(`GET ${orderType} failed:`, error);
                    showMessage(`Error fetching ${orderType}`, 'error');
                });
        }
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
        document.addEventListener('DOMContentLoaded', () => {
            const deliveryOption = document.getElementById('deliveryOption');
            const driverSelectionContainer = document.getElementById('driverSelectionContainer');
        
            deliveryOption.addEventListener('change', () => {
                if (deliveryOption.value === 'delivery') {
                    driverSelectionContainer.style.display = 'block';
                } else {
                    driverSelectionContainer.style.display = 'none';
                }
            });
        });
        </script>

        <script>
            document.addEventListener('keydown', function (event) {
                // Prevent browser default F1 help popup
                if (event.key === "F1") {
                    event.preventDefault();
                    document.getElementById("printReceiptBtn").click();
                }
        
                if (event.key === "F2") {
                    event.preventDefault();
                    document.getElementById("printQuotationBtn").click();
                }
        
                if (event.key === "F3") {
                    event.preventDefault();
                    document.getElementById("printHoldBtn").click();
                }
            });
        </script>
        
        
</body>
</html>