<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header text-white modal-color modal-padding">
      <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
    </div>
  <div class="modal-body">
    <div class="row">
      <div class="col">
        <label for="">Username:</label>
        <input type="text" class="form-control" id="username">
        <div>
          <small><span id="username_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">First Name:</label>
        <input type="text" class="form-control" id="firstname">
        <div>
          <small><span id="firstname_error" class="text-danger"></span></small>
        </div>
      </div>
      <div class="col">
        <label for="">Last Name:</label>
        <input type="text" class="form-control" id="lastname">
        <div>
          <small><span id="lastname_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Password:</label>
        <input type="password" class="form-control" id="password">
        <div>
          <small><span id="password_error" class="text-danger"></span></small>
        </div>
      </div>
      <div class="col">
        <label for="">Confirm Password:</label>
        <input type="password" class="form-control" id=confirm-password>
        <div>
          <small><span id="confirm-password_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Avatar:</label>
        <input type="file" class="form-control">
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Role:</label>
        <select name="" id="user_role" class="form-control">
          <option value="" disabled selected>-- Select Role --</option>
          <option value="1">Reservationist</option>
          <option value="2">Front Desk</option>
          <option value="3">Inventory Manager</option>
          <option value="4">Cashier</option>
          <option value="5">Server/Waiter</option>
          <option value="6">Food Server</option>
          <option value="7">Restaurant Inventory Manager</option>
          <option value="8">Supervisor Aide</option>
          <option value="9">Guest Counter</option>
          <option value="10">Errand</option>
        </select>
        <div>
          <small><span id="user_role_error" class="text-danger"></span></small>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col">
        <label for="">Is Active:</label>
        <input type="checkbox" id="is_active">
      </div>
    </div>


  </div>
  <div class="modal-footer modal-color modal-padding">
  <button type="button" class="btn btn-secondary" onclick="close_add_user()" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn button-success" onclick="add_user()">Create</button>
  </div>
  </div>
  </div>
</div>