@extends('homepage')

@section('header', 'Reports')

@section('content')
    <div class="container mt-5">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end mb-4">
            <div class="col-md-2">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
        
            <div class="col-md-2">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            
            <div class="col-md-2">
                <label for="customer_id" class="form-label">Customer</label>
                <select name="customer_id" id="customer_id" class="form-select">
                    <option value="">All Customers</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-filter-alt"></i> Filter
                </button>
            </div>
            
            <div class="col-md-3">
                <a href="{{ route('reports.export.csv', [
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                    'customer_id' => request('customer_id'),
                ]) }}" class="btn btn-success w-100">
                    <i class="bx bx-download"></i> Export to CSV
                </a>                
            </div>
            
        </form>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bx bx-slider"></i> Print Report
            </button>
        </div>
        <table  class="table table-sm table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Customer Name</th>
                    <th>Cashier Name</th>
                    <th>Order Number</th>
                    <th>Date Purchase</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->customer_name }}</td>
                        <td>{{ $report->cashier->firstname }} {{ $report->cashier->lastname }}</td>
                        <td>{{ $report->order_number }}</td>
                        <td>{{ $report->date->format('F d, Y h:i A') }} </td>
                        <td>
                            <!-- View Button -->
                            <button class="btn btn-info btn-sm view-report-btn" data-bs-toggle="modal" data-bs-target="#reportModal"
                                data-customer="{{ $report->customer_name }}"
                                data-cashier="{{ $report->cashier->firstname }} {{ $report->cashier->lastname }}"
                                data-order="{{ $report->order_number }}"
                                data-ratetype="{{ $report->rate_type }}"
                                data-items="{{ json_encode($report->items) }}"
                                data-category="{{ json_encode(is_array($report->items) ? array_map(function($item) {
                                    return $item['category'] ?? 'N/A';
                                }, $report->items) : $report->items->map(function($item) {
                                    return $item->category ?? 'N/A';
                                })) }}"
                                data-total="₱{{ $report->total }}"
                                data-date="{{ $report->date->format('F d, Y h:i A') }}"
                                data-delivery-option="{{ $report->delivery_option }}"
                                @if($report->delivery_option == 'delivery' && $report->driver_id)
                                    data-driver="{{ $report->driver->name }}"
                                @endif
                                @if(isset($report->discount))
                                    data-discount="₱{{ $report->discount }}"
                                @endif

                                data-payment_method="{{ $report->payment_method }}"
                                data-subtotal="{{ $report->subtotal }}"
                                data-tax="{{ $report->tax }}"
                            >
                                <i class="bx bx-show"></i> View
                        </button>

                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center"><i>No data found...</i></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $reports->links() }}
        
    </div>

    <!-- Modal for Detailed View -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Order Number: <span id="modal-order"></span></h6>
                    <h6>Customer Name: <span id="modal-customer"></span></h6>
                    <h6>Cashier Name: <span id="modal-cashier"></span></h6>
                    <h6>Rate Type: <span id="modal-ratetype"></span></h6>
                    <h6>Item Category: <span id="modal-itemcategory"></span></h6>
                    <h6>Items:</h6>
                    <ul id="modal-items" class="ps-3"></ul>
                    <h6>Option: <span id="modal-delivery-option"></span></h6>
                    <h6>Driver Name: <span id="modal-driver"></span></h6>
                    <h6>Discount: <span id="modal-discount"></span></h6>
                    <h6>Payment Method: <span id="modal-payment-method"></span></h6>
                    <h6>Subtotal: <span id="modal-subtotal"></span></h6>
                    <h6>Tax: <span id="modal-tax"></span></h6>
                    <h6>Total: <span id="modal-total"></span></h6>
                    <h6>Date: <span id="modal-date"></span></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="reprintBtn">🖨️ Reprint</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="filterForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Reports</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal-start-date" class="form-label">Start Date</label>
                        <input type="date" id="modal-start-date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="modal-end-date" class="form-label">End Date</label>
                        <input type="date" id="modal-end-date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Cashier</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->firstname }} {{ $user->lastname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="applyFilterButton" class="btn btn-primary">
                        <i class="bx bx-filter-alt"></i> Print Report
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <iframe id="printFrame" style="display: none;"></iframe>

    <script>
        let currentReportData = null;
        let isReprint = false;
        document.querySelectorAll('.view-report-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes from the clicked button
                const customer = this.getAttribute('data-customer');
                const cashier = this.getAttribute('data-cashier');
                const order = this.getAttribute('data-order');
                const rateType = this.getAttribute('data-ratetype');
                const items = JSON.parse(this.getAttribute('data-items')); // Parse items
                const category = JSON.parse(this.getAttribute('data-category')); // Parse category
                const total = this.getAttribute('data-total');
                const date = this.getAttribute('data-date');
                const driver = this.getAttribute('data-driver') || 'N/A'; 
                const discount = this.getAttribute('data-discount') || 'N/A'; 
                const deliveryOption = this.getAttribute('data-delivery-option') || 'N/A'; 
                const paymentMethod = this.getAttribute('data-payment_method') || 'N/A'; 
                const subtotal = this.getAttribute('data-subtotal') || 'N/A'; 
                const tax = this.getAttribute('data-tax') || 'N/A'; 

                // Set values in the modal
                document.getElementById('modal-customer').innerText = customer;
                document.getElementById('modal-cashier').innerText = cashier;
                document.getElementById('modal-order').innerText = order;
                document.getElementById('modal-ratetype').innerText = rateType;
                document.getElementById('modal-total').innerText = total;
                document.getElementById('modal-date').innerText = date;
                document.getElementById('modal-driver').innerText = driver;
                document.getElementById('modal-discount').innerText = discount;
                document.getElementById('modal-delivery-option').innerText = deliveryOption;
                document.getElementById('modal-payment-method').innerText = paymentMethod;
                document.getElementById('modal-subtotal').innerText = subtotal;
                document.getElementById('modal-tax').innerText = tax;
        
                // Populate item categories
                const itemCategoryElement = document.getElementById('modal-itemcategory');
                itemCategoryElement.innerHTML = ''; // Clear previous categories
                if (category && category.length > 0) {
                    itemCategoryElement.innerText = category.join(', ');  // Join multiple categories with commas
                } else {
                    itemCategoryElement.innerText = 'N/A'; // Default message if no category is available
                }
        
                // Populate items list
                const itemsList = document.getElementById('modal-items');
                itemsList.innerHTML = ''; // Clear previous items
                items.forEach(item => {
                    let listItem = document.createElement('li');
                    if (typeof item === 'object') {
                        listItem.innerHTML = `${item.name} <span class="badge bg-info">X ${item.quantity} (${item.type ?? 'Selling Price'})</span>`;
                    } else {
                        listItem.innerText = item;
                    }
                    itemsList.appendChild(listItem);
                });

                currentReportData = {
                    customer_name: customer,
                    cashier: { firstname: cashier.split(' ')[0], lastname: cashier.split(' ')[1] },
                    order_number: order,
                    rate_type: rateType,
                    items: items,
                    category: category,
                    total: total.replace('₱', ''),
                    date: new Date(date),
                    delivery_option: deliveryOption,
                    driver: driver !== 'N/A' ? { name: driver } : null,
                    discount: discount !== 'N/A' ? discount.replace('₱', '') : null,
                    payment_method: paymentMethod,
                    subtotal: subtotal.replace('₱', ''),
                    tax: tax.replace('₱', '')
                };
            });
        });       
        
        document.getElementById('reprintBtn').addEventListener('click', function () {
            if (currentReportData) {
                printContent([currentReportData], null, true);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No report data available for reprinting.',
                    confirmButtonText: 'Okay'
                });
            }
        });

        document.getElementById('applyFilterButton').addEventListener('click', function () {
            // Get form data
            let formData = new FormData(document.getElementById('filterForm'));
            let orderDetails = {};
        
            // Convert FormData to an object for easy manipulation
            formData.forEach((value, key) => {
                orderDetails[key] = value;
            });
        
            // Trigger the API call (POST or GET based on your requirements)
            fetch('/api/print-reports', {
                method: 'POST', // Or 'GET' if needed
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderDetails)
            })
            .then(async (response) => {
                
                // Assuming response is JSON and contains the order/payment information
                const data = await response.json();
                if (data.reports.length === 0) {
                    Swal.fire({
                      icon: 'info',
                      title: 'No reports found',
                      text: 'No reports found for the selected criteria.',
                      confirmButtonText: 'Okay'
                    });
                    return;
                  }
                  

                printContent(data.reports, formData, false);
                $('#filterModal').modal('hide'); // Close modal after success
            })
        });
        
        function printContent(content, formData, rePrint) {
            // Get the start and end dates from the content
            const startDate = new Date(content[0].date).toLocaleString(); // Assuming first report has start date
            const endDate = new Date(content[content.length - 1].date).toLocaleString(); // Assuming last report has end date
        
            // Create a hidden iframe
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
   
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`
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
                        .receipt-footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #333;
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
                   <body>
                        <div class="receipt-container">
                            ${content.map(report => `
                            <div class="receipt">
                                <div class="receipt-header">
                                ${rePrint ? `
                                    <h2>{{ $globalOptions['company_name'] ?? 'Hardware' }}</h2>
                                    <p>123 Main Street</p>
                                    <p>City, State 12345</p>
                                    <p>Tel: (123) 456-7890</p>
                                    <p>--------------------------------</p>
                                    <p>${report.order_number}</p>
                                    <p>Date: ${new Date(report.date).toLocaleDateString()}</p>
                                    <p>Time: ${new Date(report.date).toLocaleTimeString()}</p>
                                    <p>Option: ${report.delivery_option}</p>
                                    ${report.delivery_option === 'delivery' ? `<p>Driver Name: ${report.driver_name || (report.driver?.name || 'N/A')}</p>` : ''}
                                    <p>--------------------------------</p>
                                ` : `
                                    <p>Order Number: ${report.order_number}</p>
                                    <p>Date: ${new Date(report.date).toLocaleString()}</p>
                                `}
                                </div>

                                ${rePrint ? `
                                <div class="receipt-items">
                                    <div class="receipt-item text-dark">
                                    <div class="item-details">
                                        ${report.items.map(item => `
                                        <div class="item-row">
                                            <span class="item-quantity">${item.quantity}x</span>
                                            <span>${item.name}</span>
                                        </div>
                                        <div class="item-total">(₱${(item.price * item.quantity).toFixed(2)})</div>
                                        `).join('')}
                                    </div>
                                    </div>
                                </div>

                                <div class="receipt-summary">
                                    <div class="summary-row">
                                        <span>Subtotal:</span>
                                        <span>₱${parseFloat(report.subtotal).toFixed(2)}</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Tax (7%):</span>
                                        <span>₱${parseFloat(report.tax).toFixed(2)}</span>
                                    </div>
                                    <div class="receipt-total text-left">
                                        <span>Total:</span>
                                        <span>₱${parseFloat(report.total).toFixed(2)}</span>
                                    </div>
                                </div>
                                ` : `
                                <div class="order-info">
                                    <p>Customer: ${report.customer_name}</p>
                                    <p>Cashier: ${report.cashier?.firstname || ''} ${report.cashier?.lastname || ''}</p>
                                    ${report.delivery_option === 'delivery' ? `
                                    <p>Delivery Option: ${report.delivery_option}</p>
                                    <p>Driver Name: ${report.driver?.name || 'N/A'}</p>
                                    ` : ''}

                                    <div class="items">
                                    <h4>Items:</h4>
                                    ${report.items.map(item => `
                                        <div class="item-row">
                                        <span>${item.name}</span> - <span>₱${item.price.toFixed(2)}</span>
                                        </div>
                                    `).join('')}
                                    </div>

                                    <div class="items">
                                    <h4>Transaction Process</h4>
                                    ${report.discount && report.discount !== '0' ? `<p>Discount: ₱${parseFloat(report.discount).toFixed(2)}</p>` : ''}
                                    <p>Payment Method: ${report.payment_method || 'N/A'}</p>
                                    <p>Total: ₱${parseFloat(report.total).toFixed(2)}</p>
                                    </div>
                                </div>
                                `}
                            </div>
                            `).join('')}

                            ${rePrint === false ? `
                            <div class="grand-total">
                                <h3>Overall Total Sales: ₱${content.reduce((sum, report) => sum + parseFloat(report.total), 0).toFixed(2)}</h3>
                            </div>
                            ` : ''}

                            ${rePrint ? `
                            ${content.map(report => `
                                <div class="receipt-footer text-dark">
                                <p>Thank you, ${report.customer_name}!</p>
                                <p>For your order of ${report.items.length} item${report.items.length > 1 ? 's' : ''}</p>
                                <p>--------------------------------</p>
                                <p>Payment Method: ${report.payment_method || 'N/A'}</p>
                                <p>--------------------------------</p>
                                <p>Please come again</p>
                                </div>
                            `).join('')}
                            ` : `
                            <!-- Footer with Date Range -->
                            <div class="receipt-footer">
                                <p>This includes sales from ${startDate} to ${endDate}</p>
                            </div>
                            `}
                        </div>
                    </body>
                </html>
            `);
            doc.close();
        
            // Wait for the content to load before printing
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
        
                // Remove the iframe after printing
                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 1000);
            };
        }
        
    </script>
@endsection