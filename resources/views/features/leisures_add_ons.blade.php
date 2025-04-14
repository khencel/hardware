@extends('homepage')

@section('header')
    Leisures/Add-Ons
@endsection

@section('content')


<div class="container">
    <div class="row border position-relative header-content" style="background-image: url('../img/header/header1.jpg');">
    
    </div>
    <div class="row pt-2">
        <div class="col text-end"><button class="btn button-success" data-bs-toggle="modal" data-bs-target="#add_leisures_modal">Create Leisures/Add-Ons</button></div>
    </div>

    @include('modal.leisures-add-ons-management.leisures_add_ons_create')
    @include('modal.leisures-add-ons-management.leisures_add_ons_edit')

    <div class="row">
        <div class="col-12"> 
            <table class="table table-hover" id="leisures_table_list">
                <thead>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Price Rate</th>
                    <th>Counter</th>
                    <th>Package</th>
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
    <script src="{{ asset('js/features/leisures_add-ons_management.js') }}"></script>
    <script src="{{ asset('js/helper/app_helper.js') }}"></script>
@endsection
