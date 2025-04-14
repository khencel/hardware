<!-- Modal -->
<div class="modal fade" id="room_category_modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header text-white modal-color modal-padding">
      <h5 class="modal-title" id="exampleModalLabel">Edit Room Category</h5>
    </div>
  <div class="modal-body">
    <div class="row">
      <div class="col">
        <label for="">Display Name:</label>
        <input type="text" class="form-control" id="room_category_name_edit">
        <div>
          <small><span id="room_category_name_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Bed Type:</label>
        <select name="" class="form-control" id="room_category_type_edit">
          <option value="" disabled selected>-- Select --</option>
          <option value="King Size">King Size</option>
          <option value="Queen Size">Queen Size</option>
          <option value="Tween Beds">Tween Beds</option>
          <option value="Mix">Mix</option>
          <option value="Not Applicable">Not Applicable</option>
        </select>
        <div>
          <small><span id="room_category_type_error" class="text-danger"></span></small>
        </div>
      </div>
      <div class="col">
        <label for="">Near At:</label>
        <select name="" class="form-control" id="room_category_near_at_edit">
          <option value="" disabled selected>-- Select --</option>
          <option value="Beach">Beach</option>
          <option value="Restaurant">Restaurant</option>
          <option value="Pool">Pool</option>
          <option value="Garder">Garder</option>
          <option value="Scenery">Scenery</option>
          <option value="Parking Space">Parking Space</option>
          <option value="Not Applicable">Not Applicable</option>
        </select>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Description:</label>
        <textarea name="" class="form-control" id="room_category_description_edit"></textarea>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Is Available:</label>
        <input type="checkbox" id="room_category_is_available_edit">
        <div>
          <small><span id="room_category_is_available_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <input type="hidden" id="room_category_id_edit">
  </div>
  <div class="modal-footer modal-color modal-padding">
  <button type="button" class="btn btn-secondary" onclick="close_add()"  data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn button-success" onclick="update_room_category()">Create</button>
  </div>
  </div>
  </div>
</div>