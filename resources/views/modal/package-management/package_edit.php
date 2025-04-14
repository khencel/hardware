<!-- Modal -->
<div class="modal fade" id="edit_package_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header text-white modal-color modal-padding">
      <h5 class="modal-title" id="exampleModalLabel">Edit Package</h5>
    </div>
  <div class="modal-body">
    <div class="row">
      <div class="col">
        <label for="">Name:</label>
        <input type="text" class="form-control" id="package_name_edit">
        <div>
          <small><span id="package_name_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label for="">Description:</label>
        <textarea name="" class="form-control" id="package_description_edit"></textarea>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Availablity:</label>
        <input type="checkbox" id="package_is_available_edit">
      </div>
    </div>

    <input type="hidden" id="package_id_edit">
  </div>
  <div class="modal-footer modal-color modal-padding">
  <button type="button" class="btn btn-secondary" onclick="close_add()" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn button-success" onclick="update_package()">Update</button>
  </div>
  </div>
  </div>
</div>