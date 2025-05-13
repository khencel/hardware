@extends('homepage')

@section('header', 'Quotation Report')

@section('content')
    <div class="container mt-5">
      
  
        <table  class="table table-sm table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Customer Name</th>
                    <th>Cashier Name</th>
                    <th>Quotation Number</th>
                    <th>Date Purchase</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $report)
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
                                data-category="{{ json_encode(
                                    is_array($report->items)
                                        ? array_map(fn($item) => $item['category'] ?? 'N/A', $report->items)
                                        : ($report->items instanceof \Illuminate\Support\Collection
                                            ? $report->items->map(fn($item) => $item->category ?? 'N/A')->toArray()
                                            : ['N/A'])
                                ) }}"
                                data-total="₱{{ $report->total }}"
                                data-date="{{ $report->date->format('F d, Y h:i A') }}"
                                data-delivery-option="{{ $report->delivery_option }}"
                                @if($report->delivery_option == 'delivery' && $report->driver_id)
                                    data-driver="{{ $report->driver->name }}"
                                @endif
                                @if(isset($report->discount))
                                    data-discount="₱{{ $report->discount }}"
                                @endif
                                data-reason="{{ $report->reason }}" 
                            >
                                <i class="bx bx-show"></i> View
                        </button>

                        <form action="{{ route('quotation.cancel', $report->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="bx bx-x-circle"></i> Cancel
                            </button>
                        </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center"><i>No data found...</i></td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
        
        {{ $quotations->links() }}
        
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
                    <h6>Reason: <span id="modal-reason"></span></h6> 
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
                const reason = this.getAttribute('data-reason') || 'N/A'; // Handle missing reason

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
                document.getElementById('modal-reason').innerText = reason; 
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
