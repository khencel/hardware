@extends('homepage')

@section('header', 'Driver')

@section('content')
    <div class="container mt-5">
        <x-table 
            :columns="['name', 'email', 'phone','address']" 
            :rows="$driver" 
            createRoute="{{ route('drivers.create') }}" 
            createLabel="New Driver"
            editRoute="drivers.edit"
            deleteRoute="drivers.destroy" 
        />
    </div>
@endsection
