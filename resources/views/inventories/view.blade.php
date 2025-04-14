@extends('homepage')

@section('header', 'Inventory Management - View Item')

@section('content')
    <div class="container mt-4">
        <!-- Back & Edit Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('inventories.index') }}" class="btn btn-outline-primary">
                <i class="bx bx-arrow-back"></i> Back
            </a>
            <a href="{{ route('inventories.edit', $item->id) }}" class="btn btn-warning">
                <i class="bx bx-edit"></i> Edit Item
            </a>
        </div>

        <div class="row g-4">
            <!-- Inventory Details -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bx bx-box"></i> Inventory Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Item Name</p>
                                <h6 class="fw-bold">{{ $item->item_name }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Category</p>
                                <h6><span class="badge bg-info">{{ $item->category }}</span></h6>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted">Quantity</p>
                                <h6 class="fw-bold">{{ $item->quantity }}</h6>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted">Unit Price</p>
                                <h6 class="fw-bold">${{ number_format($item->unit_price, 2) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted">Total Cost</p>
                                <h6 class="fw-bold">${{ number_format($item->quantity * $item->unit_price, 2) }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Supplier</p>
                                <h6 class="fw-bold">{{ $item->supplier }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Location</p>
                                <h6 class="fw-bold">{{ $item->location }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Status</p>
                                <h6>
                                    <span class="badge {{ $item->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Image</p>
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded shadow"
                                        style="max-width: 150px;">
                                @else
                                    <p class="text-muted">No image available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History (General List) -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bx bx-history"></i> Transaction History</h5>
                    </div>
                    <div class="card-body">
                        @if ($item->transactions->isEmpty())
                            <p class="text-muted text-center">No transactions recorded for this item.</p>
                        @else
                            <ul class="list-unstyled">
                                @foreach ($item->transactions as $transaction)
                                    <li class="border-bottom py-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i
                                                    class="bx {{ $transaction->transaction_type == 'addition' ? 'bx-plus-circle text-success' : 'bx-minus-circle text-danger' }} fs-3"></i>
                                            </div>
                                            <div>
                                                <p class="mb-1 text-muted">
                                                    {{ $transaction->created_at->format('d/m/Y h:i A') }}
                                                </p>
                                                <p class="mb-0">
                                                    <strong>{{ ucfirst($transaction->transaction_type) }}</strong>
                                                    {{ $transaction->quantity_used }} units.
                                                </p>
                                                <small class="text-muted">
                                                    Previous: {{ $transaction->previous_quantity }} | Remaining:
                                                    <span
                                                        class="{{ $transaction->remaining_amount < 5 ? 'text-danger fw-bold' : '' }}">
                                                        {{ $transaction->remaining_amount }}
                                                    </span>
                                                </small><br>
                                                <small class="text-muted">
                                                    Used by: {{ $transaction->used_by }} | Processed by:
                                                    {{ $transaction->user->firstname . ' ' . $transaction->user->lastname }}
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
