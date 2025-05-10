@extends('homepage')

@section('header', 'Taxes')

@section('content')
    <div class="container mt-5">
        <x-table 
            :columns="['name', 'description', 'percentage','is_active']" 
            :rows="$tax" 
            createRoute="{{ route('taxes.create') }}" 
            createLabel="New Tax"
            editRoute="taxes.edit"
            deleteRoute="taxes.destroy" 
        />
    </div>
@endsection
