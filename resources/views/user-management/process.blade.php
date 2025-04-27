@extends('homepage')

@section('header', isset($user) ? 'Edit User' : 'Add User')

@section('content')
<div class="container mt-5">
    <a href="{{ route('users.index') }}" class="btn btn-info mb-3">
        <i class="bx bx-arrow-back"></i> Back
    </a>

    <div class="card shadow-sm p-4">
        <x-user.form 
            :roles="$roles" 
            :user="$user ?? null" 
            action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" 
            method="{{ isset($user) ? 'PUT' : 'POST' }}" />
    </div>
</div>
@endsection
