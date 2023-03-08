<?php
$shop = $_GET['shop'];
if ($shop !='') {
  $shop_id = $this->Home_model->GetShopId($shop);
  if($shop_id != ''){
      $setting = $this->Home_model->getallSetting($shop_id);
      $emailText_remainder = $setting->emailText_remainder;
      $emailText_sale      = $setting->emailText_sale;
   }
}
?>
<div class="contact" >
  <a href="<?=base_url().'Home/feedback?shop='.$_GET['shop'] ;?>"><i class="fas fa-comment" style="color:white;font-size:25px"></i></a>
</div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
   <script src="https://kit.fontawesome.com/784056f904.js" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
   <script src="<?php echo base_url()?>assets/js/custome.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>

   <script>
   var ajaxUrls={
      getWishlistSetting:'<?=base_url().'Home/getWishlistSetting?shop='.$_GET['shop']; ?>',
      enableWishlist:'<?=base_url().'Home/enableWishlist?shop='.$_GET['shop']; ?>',
      getListInRange:'<?=base_url().'Home/getListInRange?shop='.$_GET['shop']; ?>',
      fetchactivitydata:'<?=base_url().'Home/fetchactivitydata?shop='.$_GET['shop']; ?>',
      fetchCustomersdata:'<?=base_url().'Home/fetchCustomersdata?shop='.$_GET['shop']; ?>',
      fetchProductsdata:'<?=base_url().'Home/fetchProductsdata?shop='.$_GET['shop']; ?>',
      testEmail:'<?=base_url().'Home/testEmail?shop='.$_GET['shop']; ?>',
      savesetting:'<?=base_url().'Home/savesetting?shop='.$_GET['shop']; ?>',
      saveFeedback:'<?=base_url().'Home/saveFeedback?shop='.$_GET['shop']; ?>',
      wishgraph:'<?=base_url().'Home/wishgraph?shop='.$_GET['shop']; ?>',
   }
   var emailText_sale = "<?= preg_replace(" / [\r\ n] * /","",$emailText_sale) ?>";
   var emailText_remainder = "<?= preg_replace(" / [\r\ n] * /","",$emailText_remainder) ?>";

   </script>
   <script src="<?php echo base_url()?>assets/js/app.js"></script>

</body>
</html>
