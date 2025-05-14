@extends('homepage')

@section('header', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="col-12">
            <div class="row py-3">
                <div class="col-6 text-start">
                    <h1>Welcome {{ $user->firstname.' '.$user->lastname }}</h1>
                </div>
            </div>
            @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome Back!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                });
            </script>
            @endif
            <div class="row">
                <div class="col-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Total Customer</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalCustomers }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Total Items Available</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $items }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-header">Total Sales This year</div>
                        <div class="card-body">
                            <h5 class="card-title">₱{{ $totalSales }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header">Average monthly sales</div>
                        <div class="card-body">
                            <h5 class="card-title">₱{{ $averageMonthlySales }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card border-danger mb-4">
                        <div class="card-header bg-danger text-white">
                            Low Inventory Products
                        </div>
                
                        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm mb-0 table-bordered">
                                <thead class="thead-light sticky-top bg-white">
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                
                                <tbody>
                                    @forelse ($lowItemsInInventory as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            <button 
                                                class="btn btn-sm btn-outline-primary restock-btn"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                            >
                                                Restock
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">All items sufficiently stocked.</td>
                                    </tr>
                                @endforelse
                                
                                    @if ($lowItemsInInventory->hasPages())
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                {!! $lowItemsInInventory->links('pagination::bootstrap-4') !!}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                
                
                
            </div>

            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Sales Overview</div>
                        <div class="card-body">
                            <canvas id="salesChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');

            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Sales',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart.js already here...
    
            // Restock button click handler
            document.querySelectorAll('.restock-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.dataset.id;
                    const itemName = this.dataset.name;
    
                    Swal.fire({
                        title: `Restock ${itemName}`,
                        input: 'number',
                        inputLabel: 'Enter quantity to restock',
                        inputAttributes: {
                            min: 1,
                            step: 1
                        },
                        inputValidator: (value) => {
                            if (!value || value <= 0) {
                                return 'You need to enter a valid quantity!';
                            }
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Restock',
                        showLoaderOnConfirm: true,
                        preConfirm: (quantity) => {
                            return fetch(`/restock/${itemId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({ quantity })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(response.statusText)
                                }
                                return response.json();
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error}`
                                );
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Restocked!',
                                text: `Item "${itemName}" has been restocked.`,
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                });
            });
        });
    </script>
    
@endsection

