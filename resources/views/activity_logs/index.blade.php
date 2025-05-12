@extends('homepage')

@section('header', 'Activity Logs')

@section('content')
<div class="container">
    <h2 class="mb-4">📜 Logs</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Log Name</th>
                <th>Description</th>
                <th>Causer</th>
                <th>Subject</th>
                <th>Properties</th>
                <th>When</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->log_name }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->causer?->username ?? 'System' }}</td>
                    <td>
                        {{ str_contains(class_basename($log->subject_type), 'Food') ? str_replace('Food', 'Item', class_basename($log->subject_type)) : class_basename($log->subject_type) }}
                        #{{ $log->subject_id }}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary mb-2 toggle-json">Toggle JSON</button>
                        <pre class="json-properties" style="display: none;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                    </td>
                    <td>{{ $log->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No activity yet. 💤</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-json').forEach(function (button) {
            button.addEventListener('click', function () {
                const pre = this.nextElementSibling;
                pre.style.display = (pre.style.display === 'none') ? 'block' : 'none';
            });
        });
    });
</script>

