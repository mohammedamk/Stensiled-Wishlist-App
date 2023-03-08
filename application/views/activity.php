<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2>Activity</h2>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-12  bg-white py-3 ">
            <form>
              <div class="form-row">
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#myModal2">
              			More Filter
              		</button>
                </div>
              </div>
            </form>
          </div>
          <!-- <div class="col-12 bg-white ">
            <p>Showing 25 Activities</p>
          </div> -->
          <div class="col-12  bg-white ">
            <div class="table-responsive">
              <table class="table table-hover custom--table" id="activity-table">
                <thead>
                  <tr>
                  <th>Customer Name</th>
                  <th>Product Name</th>
                  <th>Action Time</th>
                  <th>Action Type</th>
                  <th>View Wishlist</th>
                </tr>
              </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
      <di v class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">More Filters</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="formFilter" method="post">
              <div id="accordion" role="tablist">
                    <div>
                      <div  role="tab" id="headingOne">
                          <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapse-link">
                            Action Time
                          </a>
                      </div>
                      <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                          <div>
                            <input class="flatpickr flatpickr-input" type="text" placeholder="Select Date.." data-id="range" readonly="readonly" id="activitypicker">
                          </div>
                      </div>
                  </div><!-- tab list one:action time -->
                  <hr></hr>
                  <div>
                      <div role="tab" id="headingTwo">
                          <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="collapse-link">
                            Action Type
                          </a>
                       </div>
                        <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                          <div >
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input checkbox" id="added_to_list" value="1" name="action_type[]">
                              <label class="custom-control-label" for="added_to_list">Added To wishlist</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input checkbox" id="removed_from_list"  value="2" name="action_type[]">
                              <label class="custom-control-label" for="removed_from_list">Removed from wishlist</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input checkbox" id="new_list"  value="3" name="action_type[]">
                              <label class="custom-control-label" for="new_list">New wishlist</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input checkbox" id="added_to_cart" value="4" name="action_type[]" >
                              <label class="custom-control-label" for="added_to_cart">Added To Cart</label>
                            </div>
                          </div>
                        </div>
                  </div><!-- tab list two -->
                </div><!-- accordion -->
            </form>
          </div><!-- model body -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="resetActivity">Clear all filter</button>
            <button type="button" id="submitActivityfilter" class="ml-5 btn btn-primary">Apply Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div>  <!--container-->
</section>
