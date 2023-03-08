<?php
  // echo "<pre>";
  // print_r($wishlists);
  // exit();
?>
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <a href="<?php base_url().'Home/customer?shop='.$_GET['shop'] ?>" class="btn-link text-secondary text-lg"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
      </div>
    </div>
  </div>
</section>
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 py-3">
        <h3>Wishlist of <?php foreach($wishlists as $item ) {
              echo ucfirst($item->cust_name);
              // break loop after first iteration
              break;
          }  ?></h3>
      </div>
      <div class="col-md-12">
        <table class="table">
          <tr>
            <th><span class="h5 text-secondary">Total items in wishlist</span></th>
            <th><span class="h5 text-warning">Added to wishlist</span></th>
            <th><span class="h5 text-danger">Removed from cart</span></th>
            <th><span class="h5 text-primary">New wishist</span></th>
            <th><span class="h5 text-success">Added To Cart</span></th>
          </tr>
          <?php
          foreach ($actionCounts as $actionCount ) { ?>
            <tr>
              <th><?php echo $actionCount->actionCount ?></th>
              <th><?php echo $actionCount->added_to_list ?></th>
              <th><?php echo $actionCount->removed_from_list ?></th>
              <th><?php echo $actionCount->new_list ?></th>
              <th><?php echo $actionCount->added_to_cart ?></th>
            </tr>
          <?php } ?>
        </table>
      </div>
      <div class="col-md-12 py-3 bg-white">
        <table class="table">
          <tr>
            <th>#</th>
            <th>Product image</th>
            <th>Product name</th>
            <th>Product price</th>
            <th>Last Wishlist Addition</th>
            <th>Action Type</th>
          </tr>
          <?php
          $i = 1;
          foreach ($wishlists as $item ) { ?>
          <tr>
            <td><?php echo $i ?></td>
            <td><img src="<?php echo $item->product_image == '' ?base_url('assets/img/no-image.gif'): $item->product_image  ?>" style="width:70px;height: 80px" alt="<?php echo $item->product_name ?>"></td>
            <td><?php echo $item->product_name ?></td>
            <td><?php echo $item->product_price ?></td>
            <td><?php echo $item->created_at ?></td>
            <td><?php echo $item->action_type ?></td></tr>
          <?php $i++; } ?>
        </table>
      </div>
    </div>


  </div>  <!--container-->
</section>
