<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2>Customers</h2>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-12  bg-white py-3 ">
              <div class="form-row">
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#myModal2">
              			More Filter
              		</button>
                  <!-- <button type="submit" class="btn btn-secondary">Search</button> -->
                </div>
              </div>
          </div>
          <!-- <div class="col-12 bg-white ">
            <p>Showing 25 Customers</p>
          </div> -->
          <div class="col-12  bg-white ">
            <div class="table-responsive">
              <table class="table table-hover custom--table" id="customers-table">
                <thead>
                  <tr>
                  <th>Customer Name</th>
                  <th>No. of items in list</th>
                  <th>Item first time in wishlist</th>
                  <th>Last Wishlist Addition On</th>
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
            <!-- <form id="filterData"> -->
              <div id="accordion" role="tablist">
                    <div>
                      <div  role="tab" id="headingOne">
                          <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapse-link">
                          Last updated wishlist on
                          </a>
                      </div>
                      <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                        <div>
                          <input class="flatpickr flatpickr-input" type="text" placeholder="Select Date.." data-id="range" readonly="readonly" required id="customerpicker">
                        </div>
                        <div class="invalid-feedback" style="display:none">
                          Date is required
                        </div>
                      </div>
                  </div><!-- tab list one:action time -->

                </div><!-- accordion -->

          </div><!-- model body -->
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary" id="resetCustomers">Clear all filter</button>
            <button type="button" id="submitCustomerDate" class="ml-5 btn btn-primary">Apply Filter</button>
            <!-- </form> -->
          </div>
        </div>
      </div>
    </div>
  </div>  <!--container-->
</section>
