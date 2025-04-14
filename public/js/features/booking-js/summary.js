
let type_rate = null
let customer = null
let initial = null
let bal = null
let fields = null

function savePayment(){
    type_rate = $('#type_rate').val()
    $('.transaction_history tbody').empty()
    view_summary()
}

function preview(){
    const intial_customer = $('#intial_customer').val()
    const intial_payment = $('#intial_payment').val()
    const type_payment = $('#type_payment').val()
    const selected_bank = $('#selected_bank').val()
    const bank_transaction_no = $('#bank_transaction_no').val()
    const total_bal = $('#total_balance').text()
    
    fields = {
        customer_name:intial_customer,
        payment:intial_payment,
        type_payment:type_payment,
        selected_bank:selected_bank,
        bank_transaction_no:bank_transaction_no
    }   

    if(check_if_valid_field(fields)){
        const currentDate = new Date();
        const formattedDate = dayjs(currentDate).format("MMM D, YYYY h:mm A");

        customer = intial_customer;
        initial = intial_payment
        
        const row = `   
            <tr class="bg-success text-white">
                <td style="font-size: 12px;padding:1px">${intial_customer}</td>
                <td style="font-size: 12px;padding:1px">₱${intial_payment}</td>
                <td style="font-size: 12px;padding:1px">₱${parseInt(total_bal.replace(/₱/, "")) - parseInt(initial)}</td>
                <td style="font-size: 12px;padding:1px">${formattedDate}</td>
                <td style="font-size: 12px;padding:1px">${type_payment}</td>
                <td style="font-size: 12px;padding:1px">${selected_bank}</td>
                <td style="font-size: 12px;padding:1px">${bank_transaction_no}</td>
            </tr>
        `
        $('.transaction_history tbody').append(row)

        $('#btn_save_transaction_receipt').show()

        $('#btn_preview_transaction').hide()
        $('#intial_customer_error').text("")
        $('#intial_payment_error').text("")

        
        toaster("Transaction history successfully updated!","success")
    }
}


function check_if_valid_field(fields){
    var res = false
    var cus_name    = false
    var cus_payment = false
    var bank = true
    var transaction_no = true
    
    if(fields.customer_name == ""){
        $('#intial_customer_error').text("Customer field is required!")
    }else{
        $('#intial_customer_error').text("")
        cus_name = true
    }

    if(fields.payment == ""){
        $('#intial_payment_error').text("Customer payment field is required!")
    }else{
        $('#intial_payment_error').text("")
        cus_payment = true
    }

    if(fields.type_payment == "online"){
        bank = false
        transaction_no = false

        if(fields.selected_bank == ""){
            $('#selected_bank_error').text("Type of bank field is required!")
        }else{
            $('#selected_bank_error').text("")
            bank = true
        }

        if(fields.bank_transaction_no == ""){
            $('#bank_transaction_no_error').text("Transaction number field is required!")
        }else{
            $('#bank_transaction_no_error').text("")
            transaction_no = true
        }
    }

    

    if (cus_name & cus_payment & bank & transaction_no){
        res = true
    }
 
    return res
}

function store_transaction(){
    const reservation_id = $('#edit_reservation_id').val()
    const transaction_num = $('#tr_num').text()
    const myUrl = "/checkout"
    const myData = {
        "customer_name":customer,
        "initial_payment":initial,
        "reservation_id":reservation_id,
        "discount":type_rate,
        "total":sum,
        "payment_method":fields,
        "transaction_number":transaction_num
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
            store_data(myUrl, myData).then(response => {
                const payment = response.data;
            
                const payment_list = payment.data.payments
                fetchTransaction(payment_list);
                const a = payment_list[payment_list.length - 1];
                $('#total_balance').html(a.balance <= 0 
                ? "<span class='text-success'><strong>Paid</strong></span>" 
                : "₱" + a.balance);
                initial_pyment_list = []
                change_status_after_payment(a.balance)
                $('#btn_preview_transaction').show()

                changeDate(start_date_value_after_Action)
            })
        }
    });
    
    

    $('#btn_save_transaction_receipt').hide()
}

async function change_status_after_payment(balance){
    const reservation_id = $('#edit_reservation_id').val()
    const room_id = $('#edit_room_id').val()
    let status = null;

    if(balance <= 0 && (current_status == "checkin" || current_status == "checkin_partial")){
        status = "checkin_paid"
    }else{
        status = "checkin_partial"
    }

    if(current_status == "booked"){
        status = "reserved_partial"
    }
    
    const myUrl = `/api/update-status-per-reservation_id/${reservation_id}`
    const myData = {
        status:status
    }
    await store_data(myUrl,myData)
    // window.location.reload();
    
}


async function fetchTransaction(payments){
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
    
    $('#tr_num').text(tr_num)


    $('.transaction_history tbody').empty()
    $('.transaction_receipt_logs tbody').empty()

    payments.forEach(payment => {
        const formattedDate = dayjs(payment.created_at).format("MMM D, YYYY h:mm A");

        const row = `
            <tr>
                <td style="font-size: 12px;padding:1px">${payment.customer}</td>
                <td style="font-size: 12px;padding:1px">₱${payment.initial_payment}</td>
                <td style="font-size: 12px;padding:1px">₱${payment.balance}</td>
                <td style="font-size: 12px;padding:1px">${formattedDate}</td>
                <td style="font-size: 12px;padding:1px">${payment.payment_method.type_payment.toUpperCase()}</td>
                <td style="font-size: 12px;padding:1px">${payment.payment_method.selected_bank?payment.payment_method.selected_bank.toUpperCase():"N/A"}</td>
                <td style="font-size: 12px;padding:1px">${payment.payment_method.bank_transaction_no?payment.payment_method.bank_transaction_no:"N/A"}</td>
            </tr>
        `
        $('.transaction_history tbody').append(row)

        const row_internal = `
            <tr>
                <td class="summary_label">
                    ${payment.customer}
                </td>
                <td class="summary_label">
                    Pantukan Staff
                </td>
                <td class="summary_label">
                    ₱${payment.initial_payment}
                </td>
                <td class="summary_label">
                    ₱${payment.balance}
                </td>
                <td class="summary_label">
                    ${formattedDate}
                </td>
                <td class="summary_label">
                    ${payment.payment_method.type_payment.toUpperCase()}
                </td>
                <td class="summary_label">
                    ${payment.payment_method.selected_bank?payment.payment_method.selected_bank.toUpperCase():"N/A"}
                </td>
                <td class="summary_label">
                    ${payment.payment_method.bank_transaction_no?payment.payment_method.bank_transaction_no:"N/A"}
                </td>
            </tr>
        `
        $('.transaction_receipt_logs tbody').append(row_internal)
    });

}

function printDiv(divId) {
    const printContents = document.getElementById(divId).innerHTML;
    const originalContents = document.body.innerHTML;

    // Temporarily change the page content to only the div's content
    document.body.innerHTML = printContents;

    // Show elements with class 'rules_regulation' (if any)
    $('.rules_regulation').show();
    $('.customers_copy').show()

    // Log before printing


    // Add event listeners for beforeprint and afterprint
    window.addEventListener("beforeprint", function() {
        console.log("Print dialog is opening...");
    });

    window.addEventListener("afterprint", function() {
        console.log("Print dialog has closed.");
        // You can infer the action here (e.g., print was completed or canceled)
        document.body.innerHTML = originalContents;
        window.location.reload();
    });

    // Trigger the print dialog
    window.print();
}


function close_summary_modal(){
    $('#view_summary_modal').modal('hide')
    $('#btn_preview_transaction').show()
}

function typeOfPayment(){
    var payment_type = $('#type_payment').val()
    
    if(payment_type == "cash"){
        $('#bank_type_div').hide()
    }else{
        $('#bank_type_div').show()
    }
}

async function get_payment_method(){
    myUrl = "/api/payment-methods"
    var data = await get_data(myUrl)

    $('#selected_bank').empty()
    $('#selected_bank').append(`<option value="" selected hidden>--Select Bank--</option>`)
    data.forEach(element => {
        var row = `
            <option value="${element.name}">${element.name}</option>
        `
        $('#selected_bank').append(row)
    });
}


$(document).ready(() => {
    get_payment_method()
});