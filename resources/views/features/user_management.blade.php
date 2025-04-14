@extends('homepage')

@section('header')
    User Management
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('css/user_management/user_management.css') }}">
@endsection

@section('content')


<div class="container">
    <div class="row border position-relative header-content" style="background-image: url('../img/header/header1.jpg');">
    
    </div>
    
    <div class="row pt-2">
        <div class="col text-end"><button class="btn button-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Create User</button></div>
    </div>

    @include('modal.user-management.user_create')
    @include('modal.user-management.user_edit')

    <div class="row">
        <div class="col-12"> 
            <table class="table table-hover" id="data-table">
                <thead>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Is Active</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@section('js')
    <script src="{{ asset('js/features/user_management.js') }}"></script>
    <script src="{{ asset('js/helper/app_helper.js') }}"></script>
@endsection
