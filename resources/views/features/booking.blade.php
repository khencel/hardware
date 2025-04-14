@extends('homepage')

@section('header')
    Booking
@endsection

@section('content')

<style scoped>
    table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            /* border: 1px solid rgba(0, 0, 0, 0.32); */
            padding: 3px;
            width: 80px;
            height: 7px;
            position: relative;
        }
        /* Modified room name cell styles */
        td:first-child {
            font-size: 0.85em;
            white-space: nowrap;
            padding: 5px;
            width: 60px;
            min-width: 60px;
            background-color: white !important;
        }
        th {
            background-color: #f4f4f4;
        }
        #dateRow th {
            font-size: 0.75em;
            padding: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border: 1px solid rgba(0, 0, 0, 0.32);
        }
        #amPmRow th {
            font-size: 0.75em;
            padding: 5px;
            border: 1px solid rgba(0, 0, 0, 0.32);
        }
        th.current-date {
            background-color: #205099;
            color: white;
        }
        .current-date div.dark-blue {
            background-color: #00008B;
            color: white;
        }
        /* td.current-date {
            background-color: #ffff00 !important;
        } */
        /* .current-date {
            border-left: 2px solid #ffd700;
            border-right: 2px solid #ffd700;
        } */
        
        th.current-date {
            border-top: 2px solid #205099;
        }
        tr:last-child td.current-date {
            border-bottom: 2px solid #205099;
        }
        .red { 
            background-color: red;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dark-blue { 
            background-color: darkblue; 
            color: white; 
            font-weight: bold;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .dark-blue:hover {
            background-color: #0000b3;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        .dark-blue.current-date { 
            background-color: #ffff00 !important;
            color: #000000 !important;
        }
        td.current-date div {
            border: 2px solid #ffd700;
        }
        
        .radius-left {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        .radius-right {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        td {
            min-height: 20px;
            font-size: 10px !important;
        }
        td span {
            position: relative;
            z-index: 1;
        }
        .controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .controls button, .controls select {
            padding: 8px 16px;
            cursor: pointer;
        }
        .controls button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* dropdown */
        .dropdown-menu {
            max-height: 280px;
            overflow-y: auto;
        }
        .search-box {
            position: sticky;
            top: 0;
            background-color: white;
            padding: 10px;
            z-index: 1;
        }
        .selected-options {
            max-height: 38px;
            padding: 0.375rem 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            cursor: pointer;
            background: white;
            overflow: auto
        }
        .selected-item {
            display: inline-block;
            padding: 0.2em 0.4em;
            margin: 0.2em;
            font-size: 0.875em;
            border-radius: 0.25rem;
            background-color: #e9ecef;
        }
        .remove-item {
            margin-left: 0.5em;
            cursor: pointer;
        }
        /* end dropdown  */
        .EE534F{
            background-color: #EE534F;
            font-weight: bold;
            color: white;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .C5E6AC0{
            background-color: #5E6AC0;
            font-weight: bold;
            color: white;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .C65BB6A{
            background-color: #65BB6A;
            font-weight: bold;
            color: white;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .C42A5F6{
            background-color: #42A5F6;
            font-weight: bold;
            color: white;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .FFEE58{
            background-color: #FFEE58;
            font-weight: bold;
            color: Black;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .F6911B{
            background-color: #F6911B;
            font-weight: bold;
            color: Black;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .tableBodyCalendar td{
            border: 1px solid rgba(0, 0, 0, 0.32);
        }

        


</style>
<div class="container-fluid">
    <div class="row mb-2 justify-content-end">
        <div class="col-2">
            <div class="dropdown" style="max-height: 100%">
                <div class="selected-options" id="multiSelectTrigger" data-bs-toggle="dropdown">
                    <span class="placeholder">Select options...</span>
                </div>
                
                <ul class="dropdown-menu w-100" id="multiSelectMenu">
                    <li class="search-box">
                        <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <div id="dropdownItemsContainer"></div>
                </ul>
            </div>
        </div>
        <div class="col-8 text-end">
            <div class="row">
                <div class="col-3">
                    
                    <select id="monthSelect" class="form-control"  onchange="handleMonthChange()">
                        <option value="2025-01">January 2025</option>
                        <option value="2025-02">February 2025</option>
                        <option value="2025-03">March 2025</option>
                        <option value="2025-04">April 2025</option>
                        <option value="2025-05">May 2025</option>
                        <option value="2025-06">June 2025</option>
                        <option value="2025-07">July 2025</option>
                        <option value="2025-08">August 2025</option>
                        <option value="2025-09">September 2025</option>
                        <option value="2025-10">October 2025</option>
                        <option value="2025-11">November 2025</option>
                        <option value="2025-12">December 2025</option>
                    </select>
                </div>
                <div class="col-2">
                    <input type="date" class="form-control" onchange="selectDateCalendarChart()">
                </div>
                <div class="col text-center">
                    <button id="prevBtn" class="btn btn-success" style="font-size: 12px" onclick="previousPage()" disabled>Previous 15 Days</button>
                    <span id="pageInfo"></span>
                    <button id="nextBtn" class="btn btn-success" style="font-size: 12px" onclick="nextPage()">Next 15 Days</button>
                </div>
            </div>

        </div>
        <div class="col-1 text-end" style="padding-right:2px">
            <button class="btn button-success w-100" style="margin-top: 3px;" onclick="walk_in()">Walk In</button>
        </div>
        <div class="col-1 text-end" style="padding-left:2px">
            <button class="btn button-success w-100" style="margin-top: 3px;" onclick="add_booking_modal()">Add New</button>
        </div>

        @include('modal.booking.add_booking')
        @include('modal.booking.edit_booking')
        @include('modal.booking.view_summary')
        @include('modal.booking.walk_in')
        @include('modal.booking.void_password')
    </div>
    <div class="row">
        <div class="col-12" style="overflow: auto"> 
            
        
            <table id="scheduleTable">
                <thead>
                    <tr id="dateRow">
                        <th>Room</th>
                    </tr>
                    <tr id="amPmRow">
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="tableBodyCalendar">
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.8/dayjs.min.js"></script>
    <script src="{{ asset('js/helper/app_helper.js') }}"></script>
    <script src="{{ asset('js/features/booking-js/calendar1.js') }}"></script>
    <script src="{{ asset('js/features/booking-js/calendar1_function.js') }}"></script>
    {{-- <script src="{{ asset('js/features/booking-js/calendar.js') }}"></script> --}}
    <script src="{{ asset('js/features/booking-js/booking_action.js') }}"></script>
    <script src="{{ asset('js/features/booking.js') }}"></script>
    
    <script src="{{ asset('js/features/booking-js/summary.js') }}"></script>
    <script src="{{ asset('js/features/booking-js/walk_in.js') }}"></script>
    <script src="{{ asset('js/features/booking-js/void.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
@endsection
