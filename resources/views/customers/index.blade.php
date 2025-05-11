@extends('homepage')

@section('header', 'Customers')

@section('content')
    <div class="container mt-5">
        <x-table 
            :columns="['name', 'email', 'initial_balance', 'current_balance', 'phone']" 
            :rows="$customers" 
            createRoute="{{ route('customers.create') }}" 
            createLabel="New Customer"
            editRoute="customers.edit"
            topUpRoute="{{ route('customers.create') }}"
            deleteRoute="" 
    />
    </div>
@endsection
