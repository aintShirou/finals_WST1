<!-- ADD Account -->

<div class="modal fade" id="addaccountModal" tabindex="-1" aria-labelledby="addaccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content bg-dark">
            <div class="modal-header" style="color: #fff;">
              <h5 class="modal-title" id="addaccountModalLabel">Add Account</h5>
            </div>
            <form id="editProductForm" method="post">
              <div class="modal-body" style="color: #fff;">
                <input type="hidden" id="editProductId" name="id">
                <div class="mb-3">
                  <label for="editProductBrand" class="form-label">Email</label>
                  <input type="email" class="form-control" id="editProductBrand" name="editProductBrand">
                </div>
                <div class="mb-3">
                  <label for="editProductName" class="form-label">UserName</label>
                  <input type="text" class="form-control" id="editProductName" name="editProductName">
                </div>
                <div class="mb-3">
                  <label for="editProductPrice" class="form-label">Password</label>
                  <input type="password" class="form-control" id="editProductPrice" name="editProductPrice">
                </div>
                <div class="mb-3">
                  <label for="editProductQuantity" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" id="editProductQuantity" name="editProductQuantity">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" id="saveChangesButton" name="saveChangesButton">Add Account</button>
              </div>
            </form>
          </div>
        </div>
      </div>