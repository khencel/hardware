<!-- Modal -->
<div class="modal fade" id="add_leisures_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header text-white modal-color modal-padding">
      <h5 class="modal-title" id="exampleModalLabel">Create Leisures/Add-Ons</h5>
    </div>
  <div class="modal-body">
    <div class="row">
      <div class="col">
        <label for="">Item Name:</label>
        <input type="text" class="form-control" id="leisures_name">
        <div>
          <small><span id="leisures_name_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Type:</label>
        <select name="" id="leisures_type" class="form-control">
          <option value="" disabled selected>-- Select --</option>
          <option value="Entrance">Entrance</option>
          <option value="Transportation">Transportation</option>
          <option value="Sports">Sports</option>
          <option value="Leisures">Leisures</option>
          <option value="Items">Items</option>
          <option value="Corkage">Corkage</option>
          <option value="Misc">Misc</option>
        </select>
        <div>
          <small><span id="leisures_type_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label for="">Price Rate:</label>
        <input type="number" class="form-control" id="leisures_rate">
        <div>
          <small><span id="leisures_rate_error" class="text-danger"></span></small>
        </div>
      </div>
      <div class="col">
        <label for="">Counter:</label>
        <select name="" id="leisures_counter" class="form-control">
          <option value="" disabled selected>-- Select --</option>
          <option value="Head">Head</option>
          <option value="Hour">Hour</option>
          <option value="Usage">Usage</option>
          <option value="Unit">Unit</option>
        </select>
        <div>
          <small><span id="leisures_counter_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <!-- <div class="row mt-2">
      <div class="col">
        <label for="">Package:</label>
        <select name="" class="form-control" id="leisures_package">
          <option value="" disabled selected>-- Select --</option>
          <option value="">Yes</option>
          <option value="">No</option>
        </select>
      </div>
    </div> -->

    <div class="row mt-2">
      <div class="col">
        <label for="">Availablity:</label>
        <input type="checkbox" id="leisures_avaiability">
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Avatar:</label>
        <input type="file" class="form-control">
      </div>
    </div>


  </div>
  <div class="modal-footer modal-color modal-padding">
  <button type="button" class="btn btn-secondary" onclick="close_add()" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn button-success" onclick="add_leisures()">Create</button>
  </div>
  </div>
  </div>
</div>