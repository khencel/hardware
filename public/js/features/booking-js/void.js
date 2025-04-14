function cancelVoid(){
    $('#passwordModal').modal('hide')
    $('#edit_booking').modal('show')
}


function cancelReservation(){
    $('#passwordModal').modal('show')
    $('#edit_booking').modal('hide')
}

async function submitVoidPassword(){
    var myUrl = "/api/check-fo-password"
    var entry_username = $('#void_username').val()
    var entry_password = $('#void_password').val()
    var myData = {
        "username":entry_username,
        "password":entry_password
    }

    
    if(entry_username=="" || entry_password==""){
        
        $('#void_cred_error').text("Your Credentials is not correct!")
    }else{
        var res = await store_data(myUrl, myData)
        
        if(res.data.message != "Password is correct."){
            $('#void_cred_error').text("Your Credentials is not correct!")
        }else{
            cancelReservationcheck()
        }
        
    }
   
}

function cancelReservationcheck() {
    const reservation_room_details_id = $('#reservation_room_details_id').val();
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(async (result) => {
        if (result.isConfirmed) {
            let myUrl = "/api/reservation-rooms/" + reservation_room_details_id;
            await delete_record(myUrl); 

            window.location.reload();
        }
    });
}