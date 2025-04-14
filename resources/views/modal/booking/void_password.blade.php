<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white modal-color modal-padding">
                <h5 class="modal-title" id="passwordModalLabel">Enter Credential to Void</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <div class="modal-body">
            
            <div class="mb-3">
                <label for="passwordInput" class="form-label">Username:</label>
                <input type="text" class="form-control" id="void_username" placeholder="Enter your username">

                <label for="passwordInput" class="form-label">Password:</label>
                <input type="password" class="form-control" id="void_password" placeholder="Enter your password">
                <small id="void_cred_error" class="text-danger"></small>
            </div>

        </div>
        <div class="modal-footer modal-color modal-padding">
            <button type="button" class="btn btn-secondary" onclick="cancelVoid()" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitVoidPassword()">Submit</button>
        </div>
        </div>
    </div>
</div>