@extends('homepage')

@section('header', 'Inventory Management')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('inventories.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add Item
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Qty</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Supplier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $index => $item)
                        <tr>
                            <td class="text-center small">{{ $item->id }}</td>
                            <td class="text-nowrap">{{ $item->item_name }}</td>
                            <td class="text-center text-capitalize small">
                                <span class="badge bg-{{ $item->status === 'in stock' ? 'success' : 'danger' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="text-center small">
                                <span class="fw-bold text-{{ $item->quantity <= 5 ? 'danger' : 'success' }}">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="small">{{ $item->category ?? 'N/A' }}</td>
                            <td class="text-center small">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="small">{{ $item->supplier ?? 'Unknown' }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    <a href="{{ route('inventories.show', $item->id) }}" class="btn btn-sm btn-info"
                                        title="View" data-bs-toggle="tooltip">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="{{ route('inventories.edit', $item->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit" data-bs-toggle="tooltip">
                                        <i class="bx bx-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-success open-inventory-modal" data-bs-toggle="modal"
                                        data-bs-target="#inventoryModal" data-id="{{ $item->id }}"
                                        data-name="{{ $item->item_name }}" data-type="addition" title="Add Stock"
                                        data-bs-toggle="tooltip">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger open-inventory-modal" data-bs-toggle="modal"
                                        data-bs-target="#inventoryModal" data-id="{{ $item->id }}"
                                        data-name="{{ $item->item_name }}" data-type="deduction" title="Release Item"
                                        data-bs-toggle="tooltip">
                                        <i class="bx bx-minus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center"><i>No data found...</i></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="mt-3 d-flex justify-content-center">
            {{ $items->links() }}
        </div>
    </div>

    <!-- Inventory Transaction Modal -->
    <div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inventoryModalLabel">Inventory Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="inventoryForm" method="POST" action="{{ route('inventory.transaction') }}">
                        @csrf
                        <input type="hidden" name="inventory_id" id="inventory_id">
                        <input type="hidden" name="transaction_type" id="transaction_type">

                        <div class="mb-3">
                            <label for="item_name" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="item_name" readonly>
                        </div>

                        <!-- Quantity Input -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                required>
                        </div>

                        <!-- Used By (Only for Release) -->
                        <div class="mb-3 d-none" id="used_by_field">
                            <label for="used_by" class="form-label">Used By</label>
                            <input type="text" class="form-control" id="used_by" name="used_by">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let inventoryModal = document.getElementById('inventoryModal');

            inventoryModal.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget;
                let inventoryId = button.getAttribute('data-id');
                let itemName = button.getAttribute('data-name');
                let transactionType = button.getAttribute('data-type');

                document.getElementById('inventory_id').value = inventoryId;
                document.getElementById('item_name').value = itemName;
                document.getElementById('transaction_type').value = transactionType;

                let usedByField = document.getElementById('used_by_field');
                if (transactionType === 'deduction') {
                    usedByField.classList.remove('d-none'); // Show 'Used By' field
                } else {
                    usedByField.classList.add('d-none'); // Hide 'Used By' field
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
