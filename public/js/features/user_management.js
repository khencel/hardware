
$(document).ready(function() {
    loadUser()
});

function loadUser(){
    myUrl = "users"
    axios.get(myUrl, null, {
        headers: {
        'Content-Type': 'application/json'
        }
    })
    .then(function (response) {
        $("#data-table tbody").empty();
        response.data.data.data.forEach(item => {
            const row = `
                <tr>
                    <td>${item.username}</td>
                    <td>${item.firstname}</td>
                    <td>${item.lastname}</td>
                    <td>${item.roles.length === 0?"n/a":item.roles[0]['role']['name']}</td>
                    <td>
                        ${item.is_active == 0?`<span class="badge bg-danger">Inactive</span>`:`<span class="badge bg-success">Active</span>`}
                        
                    </td>
                    <td>
                        <span class="badge bg-primary edit-button" data-user='${JSON.stringify(item)}' onclick="edit_User(this)">Edit</span>
                        <span class="badge bg-danger delete-button" onclick="delete_user(${item.id})">Delete</span>
                    </td>
                </tr>
            `;
            $("#data-table tbody").append(row);
        });
    })
    .catch(function (error) {
        alert('oops');
        console.log(error);
    });
}


function add_user(){
    let username = $('#username').val()
    let firstname = $('#firstname').val()
    let lastname = $('#lastname').val()
    let password = $('#password').val()
    let user_role = $('#user_role').val()
    let password_confirmation = $('#confirm-password').val()
    let isChecked = $('#is_active').prop('checked');
    var myUrl = 'users';

    var myData = {
        username:username,
        firstname:firstname,
        lastname:lastname,
        password:password,
        role:user_role,
        password_confirmation:password_confirmation,
        is_active:isChecked
    };

    store_data(myUrl, myData).then(response => {
        close_add_user()
        if (response && response.errors) {
            response.errors['username']?.[0] && $('#username_error').text(response.errors['username'][0]);
            response.errors['firstname']?.[0] && $('#firstname_error').text(response.errors['firstname'][0]);
            response.errors['lastname']?.[0] && $('#lastname_error').text(response.errors['lastname'][0]);
            response.errors['password']?.[0] && $('#password_error').text(response.errors['password'][0]);
            response.errors['role']?.[0] && $('#user_role_error').text(response.errors['role'][0]);
          
        } else {
            loadUser()
            clear_form()
            $("#exampleModal").modal('hide')
        }
    })
}

function edit_User(element){
    const user = JSON.parse(element.getAttribute('data-user'));

    $('#username_edit').val(user['username'])
    $('#firstname_edit').val(user['firstname'])
    $('#lastname_edit').val(user['lastname'])
    $('#user_role_edit').val(user['roles'][0]['role_id'])
    $('#user_id_edit').val(user['id'])
    isActive = user['is_active']
    if(isActive == true){
        $('#is_active_edit').prop('checked',true)
    }else{
        $('#is_active_edit').prop('checked',false)
    }
    
    $('#user_edit_modal').modal('show')
   
}

function update_user(){
    const user_id = $('#user_id_edit').val()

    let username = $('#username_edit').val()
    let firstname = $('#firstname_edit').val()
    let lastname = $('#lastname_edit').val()
    let user_role = $('#user_role_edit').val()
    let isChecked = $('#is_active_edit').prop('checked');

    var myData = {
        username:username,
        firstname:firstname,
        lastname:lastname,
        role:user_role,
        is_active:isChecked
    };
    
    url = "users/"+user_id
    update_data(url,myData).then(response => {
        toaster("User successfully updated!","success")
        $('#user_edit_modal').modal('hide')
        loadUser()
    })
}


function delete_user(user_id){
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
            myUrl = "users/"+user_id
            delete_record(myUrl)
            Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
            });
            loadUser()
        }
    });
}

function close_add_user(){
    $('#username_error').text("")
    $('#firstname_error').text("")
    $('#lastname_error').text("")
    $('#password_error').text("")
    $('#user_role_error').text("")
    clear_form()
}

function clear_form(){
    $('#username').val("")
    $('#firstname').val("")
    $('#lastname').val("")
    $('#password').val("")
    $('#user_role').val("")
    $('#confirm-password').val("")
    $('#is_active').prop('checked',false);
}