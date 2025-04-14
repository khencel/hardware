let sum = 0; // Total price

function change_status(status){

    var confirm_button = ""
    var title = ""
    var text = ""
    
    if(status=="checkout"){
        confirm_button = "Yes, Check-out!"
        title = "Check-out!"
        text = "Booked has been check-out."
    }

    if(status=="checkin"){
        confirm_button = "Yes, Check-in!"
        title = "Check-in!"
        text = "Booked has been check-out."
    }

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirm_button
    }).then(async (result) => {
        if (result.isConfirmed) {

            const reservation_id = $('#edit_reservation_id').val()
            const room_id = $('#edit_room_id').val()
            
            const myUrl = "/reservations/"+reservation_id+"/update-status/"+room_id
            const myData = {
                status:status
            }
            const update_reservation = await update_data(myUrl,myData)
            const category_id = update_reservation.data.room_details.room_category_id;
            
            Swal.fire({
                title: title,
                text: text,
                icon: "success"
            });
            liveReload(category_id)
            $('#edit_booking').modal('hide')
            changeDate(start_date_value_after_Action)
        }
    });
}

function extend_book(){
    const textbox = document.getElementById("edit_daterange");
    textbox.disabled = false;
    textbox.focus();   
    trans_type = "extend_date"
}



let payment = 0; // Default initial payment value
const total_price = []; // Array to store room prices
let total_balance = 0;
let initial_pyment_list = []

async function view_summary() {
    const reservation_id = $('#edit_reservation_id').val();
    const myUrl = "/reservations/" + reservation_id;
    const res = await get_data(myUrl);
    var currentDate = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = currentDate.toLocaleDateString('en-US', options);

    let new_price = null
    let total_res = 0
    let total_guest = [];

    if(res.data.discount){
        $('#type_rate').val(res.data.discount)
        type_rate = res.data.discount
    }

    $('#transaction_name').text(res.data.name);
    $('#trans_email').text(res.data.email);
    $('#trans_phone').text(res.data.phone);
    $('#trans_address').text(res.data.address);
    $('#date_transaction_current').text(formattedDate);

    $('.order_details_summary tbody').empty();
    total_price.length = 0; // Clear previous values
    res.data.reservation_details.forEach(details => {

        let startDate = new Date(details.check_in_date);
        let endDate = new Date(details.check_out_date);

        let diffInMilliseconds = Math.abs(endDate - startDate);
        let diffInDays = Math.ceil(diffInMilliseconds / (1000 * 60 * 60 * 24)) == 0?1:Math.ceil(diffInMilliseconds / (1000 * 60 * 60 * 24));

        total_guest.push(parseInt(details.guest))
        
        if(type_rate){
            if (/^\d+%$/.test(type_rate)) {
                    new_price = parseInt(details.room_details.price)
                    type_rate_value = type_rate.replace("%","")
                    const per = parseInt(type_rate_value) / 100;
                    const res = parseInt(details.room_details.price) * per.toFixed(2);
                    total_res = parseInt(details.room_details.price) - res
                    total_price.push(total_res * diffInDays);

                }else{
                    total_res = parseInt(details.room_details.price) - parseInt(type_rate);
                    total_price.push(total_res * diffInDays);
                }
        }else{
            total_price.push(details.room_details.price * diffInDays);
        }

        const row = `
            <tr>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">${details.room_details.name}(<sup>guest:${details.guest}</sup>)(<sup>₱${details.room_details.price}</sup>)</td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">${details.room_details.room_category_name}</td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">${diffInDays}<sup>day/s</sup></td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">
                    
                    <span>
                    ${type_rate?"<span class='text-danger'><s>₱"+details.room_details.price * diffInDays+"</s><sup>-"+type_rate+"<sup></span>":"₱"+details.room_details.price * diffInDays}
                    </span>
                    <br>
                    ${type_rate?"₱"+total_res*diffInDays:''}
                </td>
            </tr>
        `;
        $('.order_details_summary tbody').append(row);
    });

    res.data.addons.forEach(addon => {
        total_price.push(parseInt(addon.addon_price) * parseInt(addon.qty));
        
        $('.order_details_summary tbody').append(`
            <tr>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">${addon.addon_name}<sup>₱${addon.addon_price}</sup></td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">${addon.qty}</td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">₱${parseInt(addon.addon_price) * parseInt(addon.qty)}</td>
            </tr>    
        `);
    });

    const guests = total_guest.reduce((acc, value) => acc + value, 0);
    total_price.push(guests * 450);
    
    sum = total_price.reduce((acc, val) => acc + val, 0);

    let total_payment = 0
    
    
    if(res.data.payments.length == 0){
        total_balance = sum
        $('#total_balance').text("₱"+sum)
    }else{
        
        res.data.payments.forEach(payment => {
            total_payment += parseInt(payment.initial_payment)
        });
        
        balance = sum - total_payment
       
        
        $('#total_balance').html(balance <= 0 
            ? "<span class='text-success'><strong>Paid</strong></span>" 
            : "₱" + balance);
    }

    fetchTransaction(res.data.payments)
    update_summary(guests);

    $('#intial_customer').val("")
    $('#intial_payment').val("")

    $('#view_summary_modal').modal('show');
}

function update_summary(guests) {
    $('.order_details_summary tbody').append(`
        <tr>
            <td class="table-custome-align" style="font-size: 12px;padding:1px">Guest<sup><i>₱450/guest</i></sup></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px">${guests}</td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px">₱${guests * 450}</td>
        </tr>    
    `);

    $('.order_details_summary tbody').append(`
        <tr style="background-color:linen">
            <td class="table-custome-align" style="font-size: 12px;padding:1px"><strong>Total Payment:</strong></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px">₱${sum}</td>
        </tr>    
    `);

    // $('.order_details_summary tbody').append(`
    //     <tr style="background-color:linen">
    //         <td class="table-custome-align"><strong>Balance:</strong></td>
    //         <td class="table-custome-align"></td>
    //         <td class="table-custome-align"></td>
    //         <td class="table-custome-align">₱${sum - payment}</td>
    //     </tr>    
    // `);
}



function check_in(){
    var reservation_details_id = $('#reservation_room_details_id').val()
    
}

function display_logs_history(history_data){
    
    var res = history_data;
    var type = "pending"
    
    $('#history_logs tbody').empty()

    res.forEach(item => {
        
        if(item.action == "create"){
            type = "Reserved"
        }

        if(item.action == "checkin"){
            type = "Check-in"
        }
        if(item.action == "checkout"){
            type = "Check-out"
        }
        const formattedDate = dayjs(item.created_at).format("MMM D, YYYY h:mm A");
        const row = `
            <tr>
                <td>${type}</td>
                <td>${item.user_name}</td>
                <td>${item.message}</td>
                <td>${formattedDate}</td>
            </tr>
        `
        $('#history_logs tbody').append(row)
    });
    
}


// add-ons add reservation modal 

let selectedAddOns = [];

$(".selected_add_ons").on("change", function () {
    const selectedValue = $(this).val();
    
    const filteredAddOns = all_add_ons.filter(addOn => addOn.type === selectedValue);

    $('.add_ons_table tbody').empty();
    filteredAddOns.forEach(add_ons => {
        const row = `
            <tr>
                <td class="table-custome-align">
                    <input type="checkbox" class="add-on-checkbox" data-id="${add_ons.id}" data-name="${add_ons.item_name}">
                </td>
                <td class="table-custome-align"><span>${add_ons.item_name}</span></td>
                <td class="table-custome-align">
                    <input type="number" class="form-control add-on-qty" data-id="${add_ons.id}" value="1" min="1">
                </td>
                <td class="table-custome-align">₱${add_ons.price_rate}</td>
            </tr>
        `;
        $('.add_ons_table tbody').append(row);
    });

    $('.add-on-checkbox').each(function () {
        const addOnId = $(this).data("id");
        const selectedAddOn = selectedAddOns.find(addOn => addOn.addon_id === addOnId);

        if (selectedAddOn) {
            $(this).prop("checked", true);
            $(`.add-on-qty[data-id="${addOnId}"]`).val(selectedAddOn.qty);
        }
    });

});

$(".add_ons_table").on("change", ".add-on-checkbox", function () {
    const addOnId = $(this).data("id");
    const addOnName = $(this).data("name"); // ✅ Now correctly retrieves item_name
    const quantityInput = $(`.add-on-qty[data-id="${addOnId}"]`);
    const qty = parseInt(quantityInput.val()) || 1; 

    const priceRate = parseFloat($(this).closest("tr").find("td:nth-child(4)").text().replace("₱", ""));

    if ($(this).is(":checked")) {
        if (!selectedAddOns.some(addOn => addOn.addon_id === addOnId)) {
            selectedAddOns.push({ 
                addon_id: addOnId, 
                qty: qty, 
                addon_price: priceRate,
                name: addOnName
            });
        }
    } else {
        selectedAddOns = selectedAddOns.filter(addOn => addOn.addon_id !== addOnId);
    }
});


$(".add_ons_table").on("input", ".add-on-qty", function () {
    const addOnId = $(this).data("id");
    const newQty = parseInt($(this).val()) || 1; 

    const priceRate = parseFloat($(`td:contains("₱")`, $(this).closest('tr')).text().replace("₱", ""));
    
    const selectedAddOn = selectedAddOns.find(addOn => addOn.addon_id === addOnId);
    if (selectedAddOn) {
        selectedAddOn.qty = newQty;
        selectedAddOn.price = priceRate; 
    }
});
//end add-ons add reservation modal



// add rooms on create reservation

let rooms_selected = []; 

async function load_available_room_per_category(category_id, start_book, end_book) {
    const myUrl = "/api/categories/" + category_id + "/available-rooms?start=" + start_book + "&end=" + end_book;
    const roomDataList = await get_data(myUrl);
    
    $('.room_list_display tbody').empty();

    roomDataList.forEach(roomData => {
        
        const existingRoom = rooms_selected.find(room => room.room_id === roomData.id);
        const isChecked = !!existingRoom; 
        const guestCount = existingRoom ? existingRoom.guest : 0; 

        const row = `
            <tr data-room-id="${roomData.id}">
                <td class="table-custome-align">${roomData.name}<small class="text-danger" id="room_${roomData.id}"></small></td>
                <td class="table-custome-align"><input class="form-control guest-input" name="guests[]" type="number" min="1" value="${guestCount}"></td>
                <td class="table-custome-align"><input type="checkbox" class="room-checkbox" data-room-id="${roomData.id}" ${isChecked ? "checked" : ""}></td>
            </tr>
        `;
        $('.room_list_display tbody').append(row);
    });

    
    $('.room-checkbox').on('change', function () {
        const roomId = $(this).data('room-id');
        const guestInput = $(this).closest('tr').find('.guest-input'); 
        const guestCount = parseInt(guestInput.val()) || 0; 

        if ($(this).is(':checked')) {
            
            if (!rooms_selected.some(room => room.room_id === roomId)) {
                rooms_selected.push({
                    room_id: roomId,
                    guest: guestCount
                });
            }
        } else {
           
            const index = rooms_selected.findIndex(room => room.room_id === roomId);
            if (index > -1) {
                rooms_selected.splice(index, 1);
            }
        }
    });

    
    $('.guest-input').on('input', function () {
        const roomId = $(this).closest('tr').find('.room-checkbox').data('room-id');
        const guestCount = parseInt($(this).val()) || 1; 

        
        const roomIndex = rooms_selected.findIndex(room => room.room_id === roomId);
        if (roomIndex > -1) {
            rooms_selected[roomIndex].guest = guestCount;
        }

    });
}

// end add rooms on create reservation


// edit room guest number 
function enable_room_guest(){
    $(".current_room_guest").prop("disabled", false).focus();
    $(".room_guest").prop("disabled", false).focus();
}


// end edit room guest number 

function loadAvailability(){
    const selectedDate = document.getElementById('tour_date').value;
    const dateObj1 = new Date(selectedDate);
    const dateObj2 = new Date(selectedDate);

    dateObj1.setDate(dateObj1.getDate() - 1);
    const previousDay = dateObj1.toISOString().split('T')[0];

    dateObj2.setDate(dateObj2.getDate() + 1);
    const nextDay = dateObj2.toISOString().split('T')[0];

    var start_book = previousDay
    var end_book = nextDay
  
    load_available_room_per_category(selectedCategory,start_book, end_book)
}
