<section>
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-2">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Wishlist items</h6>
                  <p class="card-text"><?=$wishlistCounts->wishitems ?></php></p>
               </div>
               <div class="card-header">
                 <a href="<?=base_url().'Home/activity?shop='.$_GET['shop'] ?>" class="font-weight-bolder">
                   View Activities
                 </a>
               </div>
            </div>
         </div>
         <div class="col-md-2">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Average per customer</h6>
                  <p class="card-text">
                    <?php $foo= $wishlistCounts->customers != 0 ? $wishlistCounts->wishitems/$wishlistCounts->customers :0; echo number_format((float)$foo, 2, '.', ''); ?>
                  </p>
               </div>
               <div class="card-header"><a href=""  class="font-weight-bolder" style="color:transparent">View Activities</a></div>
            </div>
         </div>
         <div class="col-md-2">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Customers</h6>
                  <p class="card-text"><?=$wishlistCounts->customers ?></p>
               </div>
               <div class="card-header"><a href="<?=base_url().'Home/customers?shop='.$_GET['shop'] ?>" class="font-weight-bolder">View Customers</a></div>
            </div>
         </div>
         <div class="col-md-2">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Products</h6>
                  <p class="card-text"><?=$wishlistCounts->products ?></p>
               </div>
               <div class="card-header"><a href="<?=base_url().'Home/products?shop='.$_GET['shop'] ?>" class="font-weight-bolder">View Products</a></div>
            </div>
         </div>
         <?php
            $shop = $_GET["shop"];
            if($shop != '')
            {
              $data = $this->db->select('id, plan_id')->where('domain', $shop)->get('shopify_stores')->row();
              $plan_id = $data->plan_id;
            ?>
         <div class="col-md-4" <?=$plan_id == 0 ? 'style=pointer-events:none;opacity:0.4': '' ?>>
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Email Reminders Count</h6>
                  <p class="card-text">Wishlisted items: <?=$wishlistCounts->remainders ?> | Product on sale : <?=$wishlistCounts->onsale ?></p>
               </div>
               <div class="card-header"><a href="<?=base_url().'Home/settings?shop='.$_GET['shop'] ?>" class="font-weight-bolder">Configure email reminders</a></div>
            </div>
         </div>
         <?php   }          ?>
      </div>
   </div>
</section>
<section>
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <!-- chart -->
            <div class="chart-container">
               <canvas id="myChart"></canvas>
            </div>
         </div>
      </div>
   </div>
</section>
<section>
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-8">
            <h5>Statistics</h5>
         </div>
         <div class="col-md-3">
            <form>
               <div>
                  <?php
                     $today = date("Y-m-d");
                     $oneweekago=date('Y-m-d',strtotime('-7 days'));
                     ?>
                  <input class="date flatpickr-input" type="text" placeholder="Select Date.." id="dashboardpicker" data-id="range" readonly="readonly" value="<?=$today. " to " .$oneweekago;?>">
               </div>
            </form>
         </div>
         <div class="col-md-1"></div>
      </div>
   </div>
</section>
<section>
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-3">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Wishlist items</h6>
                  <p class="card-text dashbordWishlist">1</p>
               </div>
               <div class="card-header"><a href="" class="font-weight-bolder">View Activities</a></div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Average</h6>
                  <p class="card-text dashbordAverage">1.875</p>
               </div>
               <div class="card-header"><a href=""  class="font-weight-bolder" style="color:transparent">View Activities</a></div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Customers</h6>
                  <p class="card-text dashbordCustomers">5</p>
               </div>
               <div class="card-header"><a href="" class="font-weight-bolder">View Customers</a></div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="card border-secondary mb-3">
               <div class="card-body text-secondary">
                  <h6 class="card-title">Products</h6>
                  <p class="card-text dashbordProducts">9</p>
               </div>
               <div class="card-header"><a href="" class="font-weight-bolder">View Products</a></div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6  col-sm-12 mt-5">
            <div class="card" style="min-height:147px">
               <div class="card-header">
                  Top 5 Products by Wishlist Additions
               </div>
               <?php
                  if(count($topProducts)>0)
                  {
                    $i =0;
                  foreach ($topProducts as $topProduct) {
                    echo '<div class="card-body">
                      <div class="row">
                        <div class="col-2">
                          <img src="'.$topProduct->product_image.'" alt="my-first-product-front-ff0000" class="Polaris-Thumbnail__Image" style="width:51px;height:60px">
                        </div>
                        <div class="col-8 card-title">
                          <p class="font-weight-bold">'.$topProduct->product_name.'</h5>
                        </div>
                        <div class="col-2 card-text"></p>'.$topProduct->items.'</p></div>
                      </div>
                    </div>';
                    $i++;
                    if($i == 5)
                    {
                      break;
                    }
                  }//end foreach
                  } //end if
                  else{
                  echo '<div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <p>No product have been added</p>
                    </div>
                  </div></div>';
                  }
                  ?>
            </div>
         </div>
         <div class="col-md-6  col-sm-12 mt-5 ">
            <div class="card" style="min-height:147px">
               <div class="card-header">
                  Top 5 Customers by Wishlist Additions
               </div>
               <?php
                  if(count($topCustomers)>0)
                  {
                    $i =0;
                  foreach ($topCustomers as $topCustomer) {
                    echo '<div class="card-body">
                      <div class="row">
                        <div class="col-2">
                          <div style="width: 60px; height: 60px; border-radius: 50%; background: whitesmoke;position:relative "><span style="color: black; font-size: 36px;    top: 7%;position:absolute;    right: 35%;">'.ucfirst(substr($topCustomer->cust_name,0,1)).'</span></div>
                        </div>
                        <div class="col-8 card-title">
                          <p class="font-weight-bold">'.ucfirst($topCustomer->cust_name).'</h5>
                        </div>
                        <div class="col-2 card-text"></p>'.$topCustomer->items.'</p></div>
                      </div>
                    </div>';
                    $i++;
                    if($i == 5)
                    {
                      break;
                    }
                  }//end foreach
                  } //end if
                  else{
                  echo '<div class="card-body">
                      <p class="card-text">No sale info in range</p>
                      </div>';
                  }
                  ?>
            </div>
         </div>
      </div>
   </div>
</section>
