@extends('homepage')

@section('header', 'Settings')

@section('content')
    <div class="container mt-5">
        <x-table 
            :columns="['name','value','type','status']" 
            :rows="$option" 
            createRoute="{{ route('option.create') }}" 
            createLabel="Add Option"
            editRoute="option.edit"
            deleteRoute="option.destroy" 
        />
    </div>
@endsection
