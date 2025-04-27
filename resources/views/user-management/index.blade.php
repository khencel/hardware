@extends('homepage')

@section('header', 'users')

@section('content')
    <div class="container mt-5">
        <x-table 
            :columns="['full_name', 'email', 'role', 'status', 'created_at']" 
            :rows="$users"
            createRoute="{{ route('users.create') }}" 
            createLabel="New User"
            editRoute="users.edit"
            deleteRoute="" 
        />
    </div>
@endsection