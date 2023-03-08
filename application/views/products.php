<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h2>Products</h2>
      </div>
      <div class="col-md-12" style="min-height:300px">
        <div class="row">
          <div class="col-12  bg-white py-3 ">
            <form>
              <div class="form-row">
                <!-- <div class="col-8">
                  <div class="input-group w-100">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-search" aria-hidden="true"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control border-left-0" placeholder="Filter by product name" aria-describedby="basic-addon1" id="search">
                  </div>
                </div> -->
                <div class="col-12 text-right">
                  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#myModal2">
              			More Filter
              		</button>
                </div>
              </div>
            </form>
          </div>
          <!-- <div class="col-12 bg-white ">
            <p>Showing 25 Products</p>
          </div> -->
          <div class="col-12  bg-white ">
            <div class="table-responsive">
              <table class="table table-hover custom--table" id="products-table">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>No. of times in wishlist</th>
                    <th>Item first time in wishlist</th>
                    <th>Last Wishlist Addition On</th>
                    <th>View products</th>
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
            <form>
              <div id="accordion" role="tablist">
                    <div>
                      <div  role="tab" id="headingOne">
                          <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapse-link">
                            Last updated wishlist on
                          </a>
                      </div>
                      <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                        <div>
                          <input class="flatpickr-input" type="text" placeholder="Select Date.." id="productspicker" data-id="range" readonly="readonly">
                        </div>
                      </div>
                  </div><!-- tab list one:action time -->

                </div><!-- accordion -->
            </form>
          </div><!-- model body -->
          <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" id="resetProducts" >Clear all filter</button>
            <button type="submit" id="submitProductDate"class="ml-5 btn btn-primary">Apply Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div>  <!--container-->
</section>
