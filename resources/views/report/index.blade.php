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
            
            <div class="col-md-3">
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

            <div class="col-md-2">
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
                @foreach($reports as $report)
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
                            >
                                <i class="bx bx-show"></i> View
                        </button>

                        </td>
                    </tr>
                @endforeach
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
                    <h6>Total: <span id="modal-total"></span></h6>
                    <h6>Date: <span id="modal-date"></span></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                const driver = this.getAttribute('data-driver') || 'N/A';  // Handle missing driver
                const discount = this.getAttribute('data-discount') || 'N/A';  // Handle missing discount
                const deliveryOption = this.getAttribute('data-delivery-option') || 'N/A';  // Handle missing delivery option
                
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
            });
        });        
    </script>

@endsection
