<style scoped>
    .modal .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-in-out;
    }

    .modal.show .modal-dialog {
    transform: scale(1);
    }

    /* Remove fade effect */
    .modal {
    transition: opacity 0.3s linear;
    }
</style>


<div class="modal" id="add_item_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    Quantity:
                </div>
                <div class="col">
                    <input type="number" id="item_qty" class="form-control" value="1" min="1">
                    
                    <input type="hidden" id="item_id">
                    <input type="hidden" id="item_name">
                    <input type="hidden" id="item_price">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="addItemCart()">Add to Cart</button>
        </div>
      </div>
    </div>
</div>