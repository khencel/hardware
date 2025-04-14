<!-- Modal -->
<div class="modal fade" id="add_booking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-white modal-color modal-padding">
                <h5 class="modal-title" id="exampleModalLabel">Reservation Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="row mb-3">
                            <div class="col">
                                <h6 class="fw-bold">Booking Details</h6>
                            </div>
                        </div>
        
                        <div class="row g-3">
                            <div class="col">
                                <div>
                                    <label for="room" class="form-label">Category:</label>
                                    <select class="form-control room_list category_list" id="category" data-live-search="true">
                                        
                                    </select>
                                    <small id="room_category_error" class="text-danger"></small>
                                </div>
                                <div class="mt-2" style="display: none" id="booking_date_picker">
                                    <label for="daterange" class="form-label">Check-in Date:</label>
                                    <input type="text" name="daterange" id="daterange" class="form-control" placeholder="Select date range" />
                                    <small id="date_error" class="text-danger"></small>
                                </div>

                                <div class="mt-2" style="display: none" id="tour_date_picker">
                                    <label for="daterange" class="form-label">Check-in Date:</label>
                                    <input type="date" class="form-control" id="tour_date" onchange="loadAvailability()" placeholder="Select date range" />
                                    <small id="date_tour_error" class="text-danger"></small>
                                </div>
                                
                                {{-- <div class="select_room_div" style="display: none">
                                    <label for="room" class="form-label">Room:</label>
                                    <select class="form-control room_list room_list_data room_list_selection" id="room_list_selection" data-live-search="true">
                                        
                                    </select>
                                    <small id="room_error" class="text-danger"></small>
                                </div> --}}
                            </div>
        
                        </div>
        
                        <hr class="my-4">
        
                        <div class="row g-3">
                            <!-- Left Column -->
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" class="form-control text-capitalize" id="name" placeholder="Enter full name">
                                    <small id="name_error" class="text-danger"></small>
                                </div>
        
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address:</label>
                                    <input type="text" class="form-control text-capitalize" id="address" placeholder="Enter address">
                                    <small id="address_error" class="text-danger"></small>
                                </div>
        
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality:</label>
                                    <select id="nationality" class="nationality_list form-control">
                                        <option value="" selected hidden>Filipino</option>
                                    </select>
                                    <small id="nationality_error" class="text-danger"></small>
                                </div>
                            </div>
        
                            <!-- Right Column -->
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter email">
                                    <small id="email_error" class="text-danger"></small>
                                </div>
        
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone:</label>
                                    <input type="number" class="form-control" id="phone" placeholder="Enter phone number">
                                    <small id="phone_error" class="text-danger"></small>
                                </div>
        
                                <div class="mb-3">
                                    <label for="bookingType" class="form-label">Type:</label>
                                    <select name="" id="bookingType" class="form-control">
                                        <option value="" selected hidden>-- Select Type --</option>
                                        <option value="walk-in">WALK IN</option>
                                        <option value="online">ONLINE BOOKING</option>
                                    </select>
                                    <small id="bookingType_error" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        
        
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks:</label>
                            <textarea class="form-control" id="remarks" cols="30" rows="4" placeholder="Enter remarks or additional information"></textarea>
                            <small id="remarks_error" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-5 border">
                        <div class="">
                            <div class="" style="min-height: 270px">
                                <label for="daterange" class="form-label">Room List:</label>
                                <table class="table room_list_display">
                                    <thead>
                                        <th class="table-custome-align">Room name</th>
                                        <th class="table-custome-align" style="width:20px">Excess</th>
                                        <th class="table-custome-align">Action</th>
                                    </thead>

                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="">
                                <div>
                                    <label for="daterange" class="form-label">Add-ons:</label>
                                    <select name="" class="form-control selected_add_ons">
                                        <option value="" disabled selected>-- Select --</option>
                                        <option value="Entrance">Entrance</option>
                                        <option value="Transportation">Transportation</option>
                                        <option value="Sports">Sports</option>
                                        <option value="Leisures">Leisures</option>
                                        <option value="Items">Items</option>
                                        <option value="Corkage">Corkage</option>
                                        <option value="Misc">Misc</option>
                                      </select>
                                </div>
                                <div>
                                    <table class="table add_ons_table">
                                        <thead>
                                            <th class="table-custome-align"></th>
                                            <th class="table-custome-align" >Add-ons</th>
                                            <th class="table-custome-align" style="width: 15%">Qty</th>
                                            <th class="table-custome-align">Price</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer modal-color modal-padding">
                <button type="button" class="btn btn-secondary" onclick="" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="add_booking()">Create</button>
                {{-- <button type="button" class="btn btn-primary" id="getCheckedRooms">Create</button> --}}
            </div>
        </div>
    </div>
</div>
