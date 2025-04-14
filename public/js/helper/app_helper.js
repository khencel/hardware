function get_all_record(myUrl){
    axios.get(myUrl, null, {
        headers: {
        'Content-Type': 'application/json'
        }
    })
    .then(function (response) {
        return response
    })
    .catch(function (error) {
        alert('oops');
        console.log(error);
    });
}

async function store_data(myUrl, myData){
    try {
        const response = await axios.post(myUrl, myData);
        return response
    } catch (error) {
        if (error.response) {
            return error.response.data
        }
    }
}

async function get_data(myUrl){
    try {
        const response = await axios.get(myUrl);
        return response.data
    } catch (error) {
        if (error.response) {
            return error.response.data
        }
    }
}

async function update_data(myUrl,myData){
    try {
        const response = await axios.put(myUrl,myData);
        return response.data
    } catch (error) {
        if (error.response) {
            console.log(error.response.data);
            
            return error.response.data
        }
    }
}


function delete_record(myUrl){
    axios.delete(myUrl, {
        headers: {
        'Content-Type': 'application/json'
        }
    })
    .then(function (response) {
        console.log(response);
    })
    .catch(function (error) {
        alert('oops');
        console.log(error);
    });
}


function required_field(input,field){
    if(input == ""){
        return field + " field is required!"
    }
    return true
}

function toaster(text,status){
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: status,
        title: text
    });
}

