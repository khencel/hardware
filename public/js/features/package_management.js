
$(document).ready(function() {
    loadPackageList()
});


var no_of_page = 10;
var page = 1;
var total_pages = 1;

function next_page() {
    if (page < total_pages) {
        page++;
        loadPackageList();
        updatePaginationButtons();
    }
}

function prev_page() {
    if (page > 1) {
        page--;
        loadPackageList();
        updatePaginationButtons();
    }
}

function updatePaginationButtons() {
    $("#prev-button").prop('disabled', page === 1);
    $("#next-button").prop('disabled', page === total_pages);
    $("#page-info").text(`Page ${page} of ${total_pages}`);
}

function loadPackageList(){
    myUrl = "/api/packages?per_page="+no_of_page+"&page="+page
    axios.get(myUrl, null, {
        headers: {
        'Content-Type': 'application/json'
        }
    })
    .then(function (response) {
        db_data = response.data.data.data;
        total_pages = response.data.data.last_page;
        console.log(response);
        
        $("#package_table_list tbody").empty();
        db_data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.display_name}</td>
                    <td>${item.description}</td>
                    <td>
                        ${item.availability == 0?`<span class="badge bg-danger">Inactive</span>`:`<span class="badge bg-success">Active</span>`}
                        
                    </td>
                    <td>
                        <span class="badge bg-primary edit-button" data-user='${JSON.stringify(item)}' onclick="edit_package(this)">Edit</span>
                        <span class="badge bg-danger delete-button" onclick="delete_package(${item.id})">Delete</span>
                    </td>
                </tr>
            `;
            $("#package_table_list tbody").append(row);
        });
        updatePaginationButtons();
    })
    .catch(function (error) {
        alert('oops');
        console.log(error);
    });
}


function add_package(){
    let package_name = $('#package_name').val()
    let package_description = $('#package_description').val()
    let isChecked = $('#package_is_available').prop('checked');

    var myUrl = '/api/packages';

    var myData = {
        display_name:package_name,
        room_id:9,
        description:package_description,
        price:0,
        availability:isChecked
    };

    store_data(myUrl, myData).then(response => {
        close_add()
        if (response && response.errors) {
            response.errors['display_name']?.[0] && $('#package_name_error').text(response.errors['display_name'][0]);
        } else {
            loadPackageList()
            clear_form()
            $("#add_package_modal").modal('hide')
        }
    })
}

function edit_package(element){
    const data_db = JSON.parse(element.getAttribute('data-user'));
    console.log(data_db);
    
    $('#package_name_edit').val(data_db['display_name'])
    $('#package_description_edit').val(data_db['description'])


    $('#package_id_edit').val(data_db['id'])

    isActive = data_db['availability']

    if(isActive == true){
        $('#package_is_available_edit').prop('checked',true)
    }else{
        $('#package_is_available_edit').prop('checked',false)
    }
    
    $('#edit_package_modal').modal('show')
   
}

function update_package(){
    const data_id = $('#package_id_edit').val()

    let package_name = $('#package_name_edit').val()
    let package_description = $('#package_description_edit').val()
    let isChecked = $('#package_is_available_edit').prop('checked');

    var myData = {
        display_name:package_name,
        room_id:9,
        description:package_description,
        price:0,
        availability:isChecked
    };
    
    url = "/api/packages/"+data_id
    update_data(url,myData).then(response => {
        toaster("Package successfully updated!","success")
        $('#edit_package_modal').modal('hide')
        loadPackageList()
    })
}


function delete_package(package_id){
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
            myUrl = "/api/packages/"+package_id
            delete_record(myUrl)
            Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
            });
            loadPackageList()
        }
    });
}

function close_add(){
    $('#package_name_error').text("")
    clear_form()
}

function clear_form(){
    $('#package_name').val("")
    $('#package_description').val("")
    $('#package_is_available').prop('checked',false);
}