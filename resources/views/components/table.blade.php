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
                        @else
                            {{ $row[$column] ?? '-' }}
                        @endif
                    </td>
                @endforeach
                <td>
                    @if (!empty($editRoute))
                        <a href="{{ route($editRoute, $row->id) }}" class="btn btn-warning btn-sm">
                            <i class="bx bx-pencil"></i> Edit
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
