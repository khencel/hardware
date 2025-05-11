@extends('homepage')

@section('header', 'Discount Management')

@section('content')
    <div class="container mt-5">
        <x-table 
            :columns="['title_name', 'discount', 'is_percentage']" 
            :rows="$discounts ?? collect([])" 
            createRoute="{{ route('discounts.create') }}" 
            createLabel="Add Discount"
            editRoute="discounts.edit"
            deleteRoute="discounts.destroy" 
        />
    </div>
@endsection
