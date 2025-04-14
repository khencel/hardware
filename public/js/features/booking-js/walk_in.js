var total = 0


async function walk_in(){
    const currentDate = new Date();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
    const day = String(currentDate.getDate()).padStart(2, '0');
    const year = currentDate.getFullYear();
    const formattedDate = month + day + year;
    var tr_num = null
    var myUrl = "/api/get-last-payment-for-tr-num"
    
    
    var res = await get_data(myUrl);
    
    
    

    var payment_id = res['id']+1

    const formattedNumber = String(payment_id).padStart(6, '0');

    if(res){
        tr_num = `${formattedDate}-${formattedNumber}`
    }else{
        tr_num = `${formattedDate}-000001`
    }
    
    
    $('#tr_number').text(tr_num)

    fetch_all_walking_row()
    $('#walk_in_modal').modal('show')
    $('#walk_in_customer_name_error').text("")
    $('#walk_in_add_ons_error').text("")
    $('.add_ons_table tbody').empty()
}


function confirm_transaction(){ 
    const walk_in_customer = $('#walk_in_customer_name').val()
    total = 0
    var is_valid = field_validation()

    
    if(is_valid == false){
        return;
    }

    $('#walk_in_receipt_name').text(walk_in_customer)
    
    $('.order_details_summary_walk_in tbody').empty()
    selectedAddOns.forEach(add_ons => {
        console.log(add_ons);
        
        const name = add_ons.name
        const qty = add_ons.qty
        const addon_price = add_ons.addon_price
        const sub_total = addon_price * qty

        total += sub_total
        const row = `
            <tr>
                <td style="font-size: 12px;padding:1px">${name}</td>
                <td style="font-size: 12px;padding:1px">${qty}</td>
                <td style="font-size: 12px;padding:1px">₱${addon_price}</td>
                <td style="font-size: 12px;padding:1px">₱${sub_total}</td>
            </tr>
        `
        $('.order_details_summary_walk_in tbody').append(row)
    });

    $('.order_details_summary_walk_in tbody').append(`
            <tr style="background-color:linen">
                <td class="table-custome-align" style="font-size: 12px;padding:1px"><strong>Total Payment:</strong></td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
                <td class="table-custome-align" style="font-size: 12px;padding:1px">₱${total}</td>
            </tr>
        `)

    $('#walk_in_total_balance').text(total)
    $('.btn_walk_in_confirm').hide()
    $('.btn_walk_in_cancel').show()
    $('.btn_walk_in_submit').show()
}

function cancel_transaction(){
    $('.btn_walk_in_confirm').show()
    $('.btn_walk_in_cancel').hide()
    $('.btn_walk_in_submit').hide()
}

function field_validation(){
    const walk_in_customer = $('#walk_in_customer_name').val()
    var status = true
   
    if(walk_in_customer == ""){
        $('#walk_in_customer_name_error').text("Customer field is required")
        status = false
    }else{
        $('#walk_in_customer_name_error').text("")
        status = true
    }

    if(selectedAddOns.length == 0){
        $('#walk_in_add_ons_error').text("Please select at least one add-ons")
        status = false
    }else{
        $('#walk_in_add_ons_error').text("")
        status = true
    }

    return status
}


function storeWalkinPayment(){
    const myUrl = "/checkout"
    const walk_in_customer_name = $('#walk_in_customer_name').val()
    const walk_in_quantity = $('#walk_in_quantity').val()
    const tr = $('#tr_number').text()
    

    const myData = {
        "customer_name":walk_in_customer_name,
        "initial_payment":total,
        "transaction_number":tr,
        "addons" : selectedAddOns
    }

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, save transaction!"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
            title: "Create!",
            text: "Your transaction has been created!.",
            icon: "success"
            });

            store_data(myUrl, myData).then(async response => {
                $('#walk_in_modal').modal('hide')
            })
        }
    });
}

function receipt_computation(walk_in_customer,walk_in_quantity){
    console.log(walk_in_customer);
    var total = parseInt(walk_in_quantity) * 20
    
    $('#walk_in_receipt_name').text(walk_in_customer)
    $('#walk_in_total_balance').text(`₱${total}`)
    
    $('.order_details_summary_walk_in tbody').empty()
    const row = `
        <tr>
            <td>Tour</td>
            <td>${walk_in_quantity}</td>
            <td>${total}</td>
        </tr>
        <tr style="background-color:linen">
            <td class="table-custome-align" style="font-size: 12px;padding:1px"><strong>Total Payment:</strong></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px"></td>
            <td class="table-custome-align" style="font-size: 12px;padding:1px">₱${total}</td>
        </tr>
    `
    $('.order_details_summary_walk_in tbody').append(row)

}


function validate_form(walk_in_customer,walk_in_quantity){
    let status = ""

    if(walk_in_customer == ""){
        $('#walk_in_customer_name_error').text("Customer field is required")
        status = false
    }else{
        $('#walk_in_customer_name_error').text("")
    }

    if(walk_in_quantity == ""){
        $('#walk_in_quantity_error').text("Quantity field is required")
        status = false
    }else{
        $('#walk_in_quantity_error').text("")
    }

    if(status === false){
        return false;
    }else{
        return true
    }
}


var no_of_page = 10;
var page = 1;
var total_pages = 1;
var today = new Date();
let default_date = dayjs(today).format("YYYY-MM-DD");

function next_page() {
    if (page < total_pages) {
        page++;
        fetch_all_walking_row();
        updatePaginationButtons();
    }
}

function prev_page() {
    if (page > 1) {
        page--;
        fetch_all_walking_row();
        updatePaginationButtons();
    }
}

function updatePaginationButtons() {
    $("#prev-button").prop('disabled', page === 1);
    $("#next-button").prop('disabled', page === total_pages);
    $("#page-info").text(`Page ${page} of ${total_pages}`);
}


async function fetch_all_walking_row(){
    const myUrl = `api/reports/walk-in/payments-summary?date=${default_date}?per_page=${no_of_page}&page=${page}`
    const response = await axios.get(myUrl);

    const data = response.data.data.data

    $('.transaction_receipt_walk_in tbody').empty()
    total_pages = response.data.data.last_page;

    if (data.length == 0){
        const noRecordRow = `
            <tr>
                <td colspan="5" class="text-center">No record found</td>
            </tr>
        `;
        $('.transaction_receipt_walk_in tbody').append(noRecordRow);
    }else{
        data.forEach(element => {
            const formattedDate = dayjs(element.created_at).format("MMM D, YYYY h:mm A");
            const row = `
                <tr>
                    <td>${element.customer}</td>
                    <td>${element.user_name}</td>
                    <td>₱${element.initial_payment}</td>
                    <td>${formattedDate}</td>
                    <td>
                        <span class="badge bg-danger">Void</span>
                        <span class="badge bg-info">View</span>
                    </td>
                </tr>
            `
            $('.transaction_receipt_walk_in tbody').append(row)
        });
        updatePaginationButtons();
    }

    
}


function selectedDate(input){
    var selected_date = $(input).val()
    default_date = selected_date
    fetch_all_walking_row()
}





