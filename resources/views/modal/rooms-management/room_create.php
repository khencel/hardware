<!-- Modal -->
<div class="modal fade" id="room_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header text-white modal-color modal-padding">
      <h5 class="modal-title" id="exampleModalLabel">Create Room</h5>
    </div>
  <div class="modal-body">
    <div class="row">
      <div class="col">
        <label for="">Name:</label>
        <input type="text" class="form-control" id="room_name">
        <div>
          <small><span id="room_name_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Price:</label>
        <input type="number" class="form-control" id="room_price">
        <div>
          <small><span id="room_price_error" class="text-danger"></span></small>
        </div>
      </div>
      <div class="col">
        <label for="">Pax:</label>
        <input type="number" class="form-control" id="room_pax">
        <div>
          <small><span id="room_pax_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
    <div class="col">
        <label for="">Type:</label>
        <select name="" id="room_type" class="form-control room_type_list" >
          
        </select>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Availablity:</label>
        <input type="checkbox" id="is_available">
      </div>
    </div>


  </div>
  <div class="modal-footer modal-color modal-padding">
  <button type="button" class="btn btn-secondary" onclick="close_add()" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn button-success" onclick="add_rooms()">Create</button>
  </div>
  </div>
  </div>
</div>