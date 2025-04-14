@extends('homepage')

@section('header')
    Room Category
@endsection


@section('custom_css')
    {{-- <link rel="stylesheet" href="{{ asset('css/user_management/user_management.css') }}"> --}}
@endsection

@section('content')

<style scoped>
    #line-loader {
        width: 0%;
        height: 4px;
        background: #007bff;
    }

</style>
<div class="container-fluid">
    <div class="row border position-relative header-content" style="background-image: url('../img/header/header2.jpeg');">
    
    </div>
    <div class="row pt-2">
        <div class="col text-end"><button class="btn button-success" data-bs-toggle="modal" data-bs-target="#room_category_modal">Create Rooms Category</button></div>
    </div>

    @include('modal.room-category-management.room_category_create')
    @include('modal.room-category-management.room_category_edit')

    <div class="row">
        <div class="col position-relative">
            <div id="line-loader"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12"> 
            <table class="table table-hover" id="data-table-rooms_category">
                <thead>
                    <th>Name</th>
                    <th>Bed Types</th>
                    <th>Near At</th>
                    <th>Description</th>
                    <th>Is Available?</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-3 text-end">
            <span id="page-info" class="me-3">Page 1 of 1</span>
            <button id="prev-button" class="btn btn-secondary me-2" onclick="prev_page()">Prev</button>
            <button id="next-button" class="btn btn-secondary" onclick="next_page()">Next</button>
        </div>
    </div>
</div>
@endsection


@section('js')
    <script src="{{ asset('js/features/room_category_management.js') }}"></script>
    <script src="{{ asset('js/helper/app_helper.js') }}"></script>
@endsection
