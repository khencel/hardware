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
                    <th>Rate Type</th>
                    <th>Items</th>
                    <th>total</th>
                    <th>Date Purchase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->customer_name }}</td>
                        <td>{{ $report->cashier->firstname }} {{ $report->cashier->lastname }}</td>
                        <td>{{ $report->order_number }}</td>
                        <td>{{ $report->rate_type }}</td>
                        <td>
                            @if(is_array($report->items))
                                <ul class="mb-0 ps-3">
                                    @foreach($report->items as $item)
                                    <li>
                                        {{ is_array($item) ? ($item['name'] ?? json_encode($item)) : $item }}
                                        @if(is_array($item) && isset($item['quantity']))
                                            <span class="badge bg-info">X  {{ $item['quantity'] }}</span>
                                        @endif
                                    </li>
                                @endforeach
                                </ul>
                            @else
                                {{ $report->items }}
                            @endif
                        </td>
                        <td>{{ $report->total }}</td>
                        <td>{{ $report->date->format('F d, Y h:i A') }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $reports->links() }}
        
    </div>
@endsection