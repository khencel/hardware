<a href="{{ $createRoute }}" class="btn btn-primary mb-3">
    <i class="bx bx-plus"></i> {{ $createLabel }}
</a>

@if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: @json(session('success')),
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#f0fff0', // optional light green bg
                        iconColor: '#28a745',
                        customClass: {
                            popup: 'colored-toast'
                        },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                });
            </script>
        @endif

<table class="table table-sm table-bordered table-hover align-middle">
    <thead class="table-dark text-center">
        <tr>
            @foreach ($columns as $column)
                <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
            @endforeach
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $row)
            <tr>
                @foreach ($columns as $column)
                    <td class="text-center small">
                        @if ($column == 'is_percentage')
                            <span class="badge {{ $row[$column] ? 'bg-success' : 'bg-danger' }}">
                                {!! $row[$column] ? '<i class="bx bx-check"></i>' : '<i class="bx bx-x"></i>' !!}
                            </span>
                        @elseif ($column == 'created_at')
                            {{ \Carbon\Carbon::parse($row[$column])->format('d M Y') }}
                        @elseif ($column == 'status')
                            <a href="javascript:void(0);" class="status-toggle" data-id="{{ $row->id }}" data-status="{{ $row->status }}"
                                style="color: {{ $row->status == 'Active' ? 'green' : 'red' }};">
                                {!! $row->status == 'Active' ? 'Active' : 'Inactive' !!}
                            </a>
                        @else
                            {{ $row[$column] ?? '-' }}
                        @endif
                    </td>
                @endforeach
                <td class="text-center">

                    <div class="d-flex justify-content-center flex-wrap gap-1">
                        @if (Route::currentRouteName() === 'customers.index')
                            <a href="javascript:void(0);" 
                                data-bs-toggle="modal" 
                                data-bs-target="#topUpModal" 
                                data-id="{{ $row->id }}" 
                                class="btn btn-info btn-sm" 
                                title="Top-Up this customer">
                                <i class="bx bx-wallet"></i> Top Up
                            </a>
                        @endif
                    
                        @if (!empty($editRoute))
                            <a href="{{ route($editRoute, $row->id) }}" class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                <i class="bx bx-pencil"></i> <span class="d-none d-sm-inline">Edit</span>
                            </a>
                        @endif

                        @if (!empty($deleteRoute))
                            <form action="{{ route($deleteRoute, $row->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm d-flex align-items-center gap-1 delete-btn" data-name="{{ $row->name ?? 'this item' }}">
                                    <i class="bx bx-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + 1 }}" class="text-center"><i>No data found...</i></td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- top up Modal -->
@if (Route::currentRouteName() === 'customers.index') <!-- Check if the current page is the customer page -->
    <div class="modal fade" id="topUpModal" tabindex="-1" aria-labelledby="topUpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="topUpModalLabel">Top Up on this Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.topup', $row->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" id="customer_id">

                        <!-- Initial Amount Input -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Enter Amount to Top Up</label>
                            <input type="number" name="amount" id="amount" class="form-control" placeholder="Amount" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif




@if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $rows->links() }}
@endif

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const topUpButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
            topUpButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const customerId = button.getAttribute('data-id');
                    document.getElementById('customer_id').value = customerId;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const statusElements = document.querySelectorAll('.status-toggle');
            statusElements.forEach(function (statusElement) {
                statusElement.addEventListener('click', function () {
                    const status = statusElement.dataset.status === 'Active' ? 'Inactive' : 'Active';
                    const userId = statusElement.dataset.id;
    
                    statusElement.style.color = (status === 'Active') ? 'green' : 'red';
                    statusElement.innerHTML = status;
    
                    fetch(`/user/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                       alert(data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert status if AJAX fails
                        alert('Failed to toggle status!');
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    const itemName = this.dataset.name;

                    Swal.fire({
                        title: "Are you sure?",
                        text: `You won't be able to revert deleting "${itemName}"!`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form if confirmed
                        }
                    });
                });
            });
        });
    </script>
@endsection
