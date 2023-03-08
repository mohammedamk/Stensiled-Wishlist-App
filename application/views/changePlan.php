<!-- change plan -->
<div class="container" style="background-color: transparent">
   <div class="row">
      <div class="col-md-10 p-5 mx-auto" style="background-color:white; min-height:300px;max-height:500px;">
         <?php $shop=$_GET[ "shop"];
         if($shop !='' ) {
            $data=$this->db->select('id, plan_id')->where('domain', $shop)->get('shopify_stores')->row();
         }
         $plan_id = $data->plan_id;
         ?>
         <div class="row">
            <div class="col-12">
               <div class="row">
                  <div class="col-6">
                     <h3>1. Basic Plan</h3>
                  </div>
                  <div class="col-6 text-right">
                     <a href="<?php echo base_url().'Auth/CreateCharge?shop='.$_GET["shop"]; ?>" class="btn btn-info ml-3" <?php echo $plan_id == 0 ? 'style="pointer-events: none; opacity: 0.4;"' : ''; ?>><?php echo $plan_id == 0 ? 'Activated' : 'Active'; ?></a>
                  </div>
               </div>
               <ul>
                  <li>Customise your app</li>
                  <li>Change the wishlist icon</li>
               </ul>
            </div>
            <div class="col-12">
               <div class="row">
                  <div class="col-6">
                     <h3>2. Premium Plan</h3>
                  </div>
                  <div class="col-6 text-right">
                     <a href="<?php echo base_url().'Auth/CreateCharge?shop='.$_GET["shop"].'&price=9.99'; ?>" class="btn btn-info ml-3" <?php echo $plan_id == 1 ? 'style="pointer-events: none; opacity: 0.4;"' : ''; ?>><?php echo $plan_id == 1 ? 'Activated' : 'Active'; ?></a>
                  </div>
               </div>
               <ul style="list-style-type:circle">
                  <li>Customise your app</li>
                  <li>Change the wishlist icon</li>
                  <li>Control whether remove products from wishlist after adding to cart or not </li>
                  <li>Send remainder emails for wishlisted items</li>
                  <li>Send notifier emails for wishlisted items is in sale</li>
                  <li>Customise your email configuration</li>
                  <li>Customise email template</li>
                  <li>Test sample email before sending your  customers</li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
