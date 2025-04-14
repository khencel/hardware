var order_list = []

$(document).on('click', '#selectedItem',async function() {
    const item = $(this).data('item');
    clearForm()

    $('#item_id').val(item.id)
    $('#item_name').val(item.name)
    $('#item_price').val(item.price)

    $('#add_item_modal').modal('show')
    console.log(item);
})


function addItemCart(){
    var id = $('#item_id').val()
    var qty = $('#item_qty').val()
    var name = $('#item_name').val()
    var price = $('#item_price').val()

    // $('#cart_table tbody').empty()
 
    const row = `
        <tr>
            <td >${name}</td>
            <td class="text-center">${qty}</td>
            <td class="text-center">₱ ${price}</td>
            <td class="text-center">₱ ${qty*price}</td>
        </tr>
    `
    $('#cart_table tbody').append(row)

    var data = {
        "id":id,
        "qty":qty,
        "name":name,
        "price":price
    }
    get_sub_total(data)
    $('#add_item_modal').modal('hide')
}

function get_sub_total(data){
   
    order_list.push(data);
    console.log(order_list);
    
   
    let subtotal = 0;
    for(let i = 0; i < order_list.length; i++){
       
        subtotal += parseFloat(order_list[i].price) * parseInt(order_list[i].qty);
    }
    
    $('#order_subtotal').text(subtotal.toFixed(2)); 

    $('#order_total').text(subtotal.toFixed(2)); 
}



function clearForm(){
    $('#item_qty').val(1)
}