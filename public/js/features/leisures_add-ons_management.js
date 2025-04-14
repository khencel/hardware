
$(document).ready(function() {
    loadLeisuresList()
    // alert(123)
});


var no_of_page = 10;
var page = 1;
var total_pages = 1;

function next_page() {
    if (page < total_pages) {
        page++;
        loadLeisuresList();
        updatePaginationButtons();
    }
}

function prev_page() {
    if (page > 1) {
        page--;
        loadLeisuresList();
        updatePaginationButtons();
    }
}

function updatePaginationButtons() {
    $("#prev-button").prop('disabled', page === 1);
    $("#next-button").prop('disabled', page === total_pages);
    $("#page-info").text(`Page ${page} of ${total_pages}`);
}

function loadLeisuresList(){
    myUrl = "/api/leisures?per_page="+no_of_page+"&page="+page
    axios.get(myUrl, null, {
        headers: {
        'Content-Type': 'application/json'
        }
    })
    .then(function (response) {
        db_data = response.data.data.data;
        total_pages = response.data.data.last_page;
       
        $("#leisures_table_list tbody").empty();
        db_data.forEach(item => {
            const row = `
                <tr>
                    <td>Pending</td>
                    <td>${item.item_name}</td>
                    <td>${item.type}</td>
                    <td>${item.price_rate}</td>
                    <td>${item.counter}</td>
                    <td>Pending</td>
                   
                    <td>
                        ${item.availability == 0?`<span class="badge bg-danger">Inactive</span>`:`<span class="badge bg-success">Active</span>`}
                        
                    </td>
                    <td>
                        <span class="badge bg-primary edit-button" data-user='${JSON.stringify(item)}' onclick="edit_leisures(this)">Edit</span>
                        <span class="badge bg-danger delete-button" onclick="delete_leisures(${item.id})">Delete</span>
                    </td>
                </tr>
            `;
            $("#leisures_table_list tbody").append(row);
        });
        updatePaginationButtons();
    })
    .catch(function (error) {
        alert('oops');
        console.log(error);
    });
}


function add_leisures(){
    let leisures_name = $('#leisures_name').val()
    let leisures_type = $('#leisures_type').val()
    let leisures_rate = $('#leisures_rate').val()
    let leisures_counter = $('#leisures_counter').val()
    let leisures_package = $('#leisures_package').val()
    let isChecked = $('#leisures_avaiability').prop('checked');

    var myUrl = '/api/leisures';

    var myData = {
        item_name:leisures_name,
        type:leisures_type,
        price_rate:leisures_rate,
        counter:leisures_counter,
        package_id:1,
        availability:isChecked
    };

    store_data(myUrl, myData).then(response => {
        close_add()
        if (response && response.errors) {
            response.errors['item_name']?.[0] && $('#leisures_name_error').text(response.errors['item_name'][0]);
            response.errors['type']?.[0] && $('#leisures_type_error').text(response.errors['type'][0]);
            response.errors['price_rate']?.[0] && $('#leisures_rate_error').text(response.errors['price_rate'][0]);
            response.errors['counter']?.[0] && $('#leisures_counter_error').text(response.errors['counter'][0]);
        } else {
            loadLeisuresList()
            clear_form()
            $("#add_leisures_modal").modal('hide')
        }
    })
}

function edit_leisures(element){
    const data_db = JSON.parse(element.getAttribute('data-user'));
    console.log(data_db);
    
    $('#leisures_name_edit').val(data_db['item_name'])
    $('#leisures_type_edit').val(data_db['type'])
    $('#leisures_rate_edit').val(data_db['price_rate'])
    $('#leisures_counter_edit').val(data_db['counter'])
    $('#leisures_package_edit').val(data_db['package'])
    
    $('#leisures_id_edit').val(data_db['id'])

    isActive = data_db['availability']

    if(isActive == true){
        $('#leisures_avaiability_edit').prop('checked',true)
    }else{
        $('#leisures_avaiability_edit').prop('checked',false)
    }
    
    $('#edit_leisures_modal').modal('show')
   
}

function update_leisures(){
    const data_id = $('#leisures_id_edit').val()

    let leisures_name = $('#leisures_name_edit').val()
    let leisures_type = $('#leisures_type_edit').val()
    let leisures_rate = $('#leisures_rate_edit').val()
    let leisures_counter = $('#leisures_counter_edit').val()
    let leisures_package = $('#leisures_package_edit').val()
    let isChecked = $('#leisures_avaiability_edit').prop('checked');

    var myData = {
        item_name:leisures_name,
        type:leisures_type,
        price_rate:leisures_rate,
        counter:leisures_counter,
        package_id:1,
        availability:isChecked
    };
    
    url = "/api/leisures/"+data_id
    update_data(url,myData).then(response => {
        toaster("Leisures/Add-ons successfully updated!","success")
        $('#edit_leisures_modal').modal('hide')
        loadLeisuresList()
    })
}


function delete_leisures(leisures_id){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            myUrl = "/api/leisures/"+leisures_id
            delete_record(myUrl)
            Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
            });
            loadLeisuresList()
        }
    });
}

function close_add(){
    $('#leisures_name_error').text("")
    $('#leisures_type_error').text("")
    $('#leisures_rate_error').text("")
    $('#leisures_counter_error').text("")
    clear_form()
}

function clear_form(){
    $('#leisures_name').val("")
    $('#leisures_type').val("")
    $('#leisures_rate').val("")
    $('#leisures_counter').val("")
    $('#leisures_package').val("")
    $('#leisures_avaiability').prop('checked',false);
}