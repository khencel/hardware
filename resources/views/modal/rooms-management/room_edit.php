<!-- Modal -->
<div class="modal fade" id="room_modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header text-white modal-color modal-padding">
      <h5 class="modal-title" id="exampleModalLabel">Edit Room</h5>
    </div>
  <div class="modal-body">
    <div class="row">
      <div class="col">
        <label for="">Name:</label>
        <input type="text" class="form-control" id="room_name_edit">
        <div>
          <small><span id="room_name_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Price:</label>
        <input type="number" class="form-control" id="room_price_edit">
        <div>
          <small><span id="room_price_error" class="text-danger"></span></small>
        </div>
      </div>
      <div class="col">
        <label for="">Pax:</label>
        <input type="number" class="form-control" id="room_pax_edit">
        <div>
          <small><span id="room_pax_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
    <div class="col">
        <label for="">Type:</label>
        <select name="" id="room_type_edit" class="form-control room_type_list" >
          <option value="" disabled selected>-- Select Type --</option>
        </select>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Availablity:</label>
        <input type="checkbox" id="is_available_edit">
      </div>
    </div>

    <input type="hidden" id="room_id_edit">
  </div>
  <div class="modal-footer modal-color modal-padding">
  <button type="button" class="btn btn-secondary" onclick="close_add()" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn button-success" onclick="update_data_set()">Save Change</button>
  </div>
  </div>
  </div>
</div>