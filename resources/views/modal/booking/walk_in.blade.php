<div class="modal fade" id="walk_in_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
  <div class="modal-content">
    <div class="modal-header text-white modal-color">
      <h5 class="modal-title" id="exampleModalLabel">Walk-in Payment</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body summary-div">
        <div class="row">
            <div class="col">      
                <div class="row">
                    <div class="col-6">
                        <label for="">Customer:</label>
                        <span><input type="text" class="form-control" id="walk_in_customer_name" placeholder="Enter Customer Name"></span>
                        <div>
                            <small><span id="walk_in_customer_name_error" class="text-danger"></span></small>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    
                    <div class="col">
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
                                  <div>
                                    <small><span id="walk_in_add_ons_error" class="text-danger"></span></small>
                                </div>
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
                <div class="row mt-2">
                    <div class="col text-end">
                        <span><button class="btn button-success btn_walk_in_confirm" onclick="confirm_transaction()">Confirm</button></span>
                        <span><button class="btn btn-danger btn_walk_in_cancel" style="display: none" onclick="cancel_transaction()">Cancel</button></span>
                        <span><button class="btn button-success btn_walk_in_submit" style="display: none" onclick="storeWalkinPayment()">Submit</button></span>
                    </div>
                </div>  
                
                {{-- <div class="row">
                    <div class="col-md-5">
                        <input type="date" class="form-control" onchange="selectedDate(this)">
                    </div> 
                </div> --}}
                    
                <div class="row mt-2">
                <div class="col">
                    {{-- <table class="table transaction_receipt_walk_in">
                        <thead>
                            <th class="summary_label">Customer </th>
                            <th class="summary_label">Staff</th>
                            <th class="summary_label">Payment</th>
                            <th class="summary_label">Date</th>
                            <th class="summary_label">Active</th>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table> --}}

                    {{-- <table class="table table-bordered table-striped transaction_receipt_walk_in">
                        <thead class="table-light">
                          <tr>
                            <th class="summary_label">Customer </th>
                            <th class="summary_label">Staff</th>
                            <th class="summary_label">Payment</th>
                            <th class="summary_label">Date</th>
                            <th class="summary_label">Active</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
                    <div class="row justify-content-end">
                        <div class="col-6 text-end">
                            <span id="page-info" class="me-3">Page 1 of 1</span>
                            <button id="prev-button" class="btn btn-secondary me-2" onclick="prev_page()">Prev</button>
                            <button id="next-button" class="btn btn-secondary" onclick="next_page()">Next</button>
                        </div>
                    </div> --}}
                </div>
                </div>
                
            </div>
            <div class="col-md-6 p-4 border">
                <div class="row border card position-relative">

                    <span class="position-absolute badge" onclick="printDiv('walkInPrint')" style="top: 0px;right:0px;width:40px;background:gray;cursor: pointer;"><img src="{{ asset('img/icon/printing.png') }}" class="icon-style" style="width: 100%;" alt=""></span>

                    <div class="col p-4" id="walkInPrint">
                        <img src="{{ asset('img/pantukan_logo.png') }}" class="mt-3" style="width: 70px" alt="">
                        <div class=" w-100 text-end" id="">
                            <small id="tr_number"></small>
                        </div>
                        <hr>
                        
                        <div class="position-relative">
                            <div class="position-absolute customers_copy" style="  
                                                                    font-size: 63px;
                                                                    font-weight: bold;
                                                                    color: gray;
                                                                    opacity: 0.3;
                                                                    display: inline-block;
                                                                    transform: rotate(-25deg);
                                                                    line-height: 22px;
                                                                    right: 94px;
                                                                    top: 176px;
                                                                    display:none
                                                                ">
                                Customer's Copy
                                <br>
                                <span style="font-size: 20px;margin-left: 123px;">This is not an official reciept</span>
                            </div>
                            <div class="row" style="border-bottom: 1px solid black">
                                <div class="col">
                                <small>
                                    <strong>
                                    Client Details
                                    </strong>
                                </small>
                                <table>
                                    <tbody>
                                    <tr style="height: 10px !important">
                                        <td class="summary_label">Name:</td><td style="text-align: left;font-size:14px"><span id="walk_in_receipt_name"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="summary_label">Email:</td><td style="text-align: left;font-size:14px"><span id="trans_email"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="summary_label">Contact No:</td><td style="text-align: left;font-size:14px"><span id="trans_phone"></span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                </div>
                                <div class="col">
                                <small>
                                    <strong>
                                    Booking Details
                                    </strong>
                                    <table>
                                    <tbody>
                                        <tr>
                                        <td class="summary_label">Address:</td><td style="text-align: left;font-size:14px"><span id="trans_address"></span></td>
                                        </tr>
                                        <tr>
                                        <td class="summary_label">Date:</td><td style="text-align: left"><span id="date_transaction_current">Jan 23, 2025</span></td>
                                        </tr>
                                        <tr>
                                        <td class="summary_label">Balance:</td><td style="text-align: left;font-size:20px;font-weight:bold"><span id="walk_in_total_balance">₱0</span></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col">
                                <small>
                                    <strong>
                                    Order Details
                                    </strong>
                                    <table class="table order_details_summary_walk_in">
                                    <thead>
                                        <th class="table-custome-align" style="font-size: 12px;padding:0px">Item Name</th>
                                        <th class="table-custome-align" style="font-size: 12px;padding:0px">Qty</th>
                                        <th class="table-custome-align" style="font-size: 12px;padding:0px">Price</th>
                                        <th class="table-custome-align" style="font-size: 12px;padding:0px">Sub total</th>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td style="font-size: 12px;padding:1px">Tour</td>
                                            <td style="font-size: 12px;padding:1px">0</td>
                                            <td style="font-size: 12px;padding:1px">₱0</td>
                                        </tr> --}}
                                        
                                    </tbody>
                                    </table>
                                </small>
                                </div>
                            </div>
                            
                            <div class="row rules_regulation" style="display: none">
                                <div class="col" style="font-size: 8px">
                                <p><strong>TO OUR VALUED GUESTS</strong></p>
                                <p>
                                    Thank you for choosing Water World Pantukan Beach Resort! It is our main priority to meet your expectations. We would like to remind you of our minimal Rules and Regulations to ensure a satisfying stay:
                                </p>
                                
                                <ol style="margin-left: 15px;">
                                    <li><strong>Liability for Personal Injury or Death:</strong> The resort is not liable for any personal injury or death arising due to the sickness or negligence of a guest.</li>
                                    <li><strong>Security for Valuables:</strong> A safety deposit box is available at the Cashier Area. The resort is not liable for the loss of valuables left in the room or anywhere on the resort premises.
                                    </li>
                                    <li><strong>Cancellation and Change Policy:</strong></li>
                                    <ul style="margin-left: 55px;">
                                        <li><strong>Cancellation Fee:</strong> Guests may cancel or change their reservation up to 48 hours before the scheduled arrival time. A cancellation fee equivalent to 20% of the total booking amount will be deducted from any deposit already paid.</li>
                                        <li>
                                        <strong>Non-Refundable Within 48 Hours:</strong>
                                        Any cancellations made within 48 hours of the arrival date are non-refundable. Changes to the reservation may be possible, subject to room availability and potential additional charges.
        
                                        </li>
                                    </ul>
                                    <li><strong>Room Occupancy:</strong> Each room is intended for 2 persons with a maximum allowance of 4 persons. An additional charge of PHP 450 will be applied for each excess person.
                                    </li>
                                    <li><strong>Check-In and Check-Out Times:</strong> Check-in time is at 2:00 PM, and check-out time is at 11:00 AM. An additional fee of PHP 350 per hour is charged for late check-outs. Stays extending beyond 4:00 PM will incur a full night’s charge.
                                    </li>
                                    <li><strong>Outside Food and Drink:</strong> Outside food and drinks are allowed; however, a corkage fee of PHP 1000 may apply for bringing outside food into the rooms.</li>
                                    <li>
                                    <strong>External Food Delivery:</strong>
                                    A corkage fee of PHP 500 is applied per order for any food delivered from outside the resort.
                                    </li>
                                    <li>
                                    <strong>Lost Key Fee:</strong>
                                    A fee of PHP 1,000 will be charged for lost keys.
                                    </li>
                                    <li>
                                    <strong>Damage to Property:</strong>
                                    Guests will be charged accordingly for any damage caused to linens or other property, including stains from henna, ink, dye, oil, or similar substances.
                                    </li>
                                    <li>
                                    <strong>Non-refundable Package Inclusions:</strong>
                                    Unavailed package inclusions are non-refundable.
                                    </li>
                                    <li>
                                    <strong>Meal Times:</strong>
                                    Breakfast is served from 6:00 AM to 9:00 AM.
                                    </li>
                                    <li>
                                    <strong>Pet Policy:</strong>
                                    Guests are allowed to bring pets, subject to a fee of PHP 300 per pet. Service animals are exempt from this fee and must be accompanied by valid identification.
                                    </li>
                                </ol>
                                
                                <p>
                                    By signing below, I/we agree to abide by the safety rules and regulations imposed by the management. Should any accident or incident occur while on the premises, I/we voluntarily release and discharge Water World Pantukan Beach Resort from any and all liabilities, whether resulting in bodily injury or loss of life. I/we also undertake to refrain from initiating any legal actions against the resort, its owners, officers, and employees, in connection with such incidents.
                                </p>
                                <p>
                                    I have reviewed and accept the terms and conditions of my stay.
                                </p>
                                <p>
                                    <strong>
                                    Check-In Staff Signature: _____________________
                                    <br>
                                    Customer Signature: _____________________
                                    </strong>
                                    
                                </p>
                                <p>
                                    <strong>Disclaimer: This Form is Not an Official Receipt</strong>
                                </p>
                                <p>
                                    <strong>Please note:</strong>
                                    This document is not an official receipt. It is provided for reference and informational purposes only and does not represent a formal acknowledgment of payment. For official transactions or receipts, please obtain the appropriate documentation from the authorized source.
                                </p>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    {{-- <div class="modal-footer modal-color">
      <button type="button" class="btn btn-secondary" onclick="close_summary_modal()" data-bs-dismiss="modal">Close</button>
      <button type="button" onclick="printDiv('walkInPrint')" class="btn btn-primary">Print</button>
    </div> --}}
  </div>
  </div>
</div>