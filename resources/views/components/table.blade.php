<a href="{{ $createRoute }}" class="btn btn-primary mb-3">
    <i class="bx bx-plus"></i> {{ $createLabel }}
</a>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
                <td>
                    @if (!empty($editRoute))
                        <a href="{{ route($editRoute, $row->id) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center px-3 py-2 rounded-3 shadow-sm border-0 transition-all hover:bg-warning hover:text-white">
                            <i class="bx bx-pencil me-2"></i> <span class="d-none d-sm-inline">Edit</span>
                        </a>
                    @endif
                    @if (!empty($deleteRoute))
                        <form action="{{ route($deleteRoute, $row->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + 1 }}" class="text-center"><i>No data found...</i></td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $rows->links() }}
@endif

@section('scripts')
    <script>
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
@endsection
