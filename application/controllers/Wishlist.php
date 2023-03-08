<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
class Wishlist extends CI_Controller
{
    public function __construct() {
      parent::__construct();
      $this->load->model('Home_model');
    }


    public function share()
    {
      $shop = $_GET['shop'];
      $shop_id =  isset($_GET['shop']) && $_GET['shop'] != '' ? $this->Home_model->GetShopId($shop) : '';
      $cust_id = isset($_GET['cust_id']) && $_GET['cust_id'] != '' ? base64_decode($_GET['cust_id']) : '';
      $tp = (isset($_GET['tp']) && $_GET['tp'] != '') ? base64_decode($_GET['tp']) : '';
      $dif =  $this->isValidTimeStamp($tp) == TRUE ? time() - $tp : 1589879421 ;
      ob_start();
      ?>

        <div class="Polaris-TextContainer" style="width:90%;margin:auto">
          <?php if($shop_id !== '' &&  $cust_id !== '' && $tp !== '' ) { ?>
            <div><h1>
              <?php
              $shopAccess = getShop_accessToken_byShop($shop);
              $this->load->library('Shopify', $shopAccess);
              $customer = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.getYear().'/customers/'.$cust_id.'.json'], TRUE);
              $cust_name = isset($customer)? $customer->customer->first_name." ".$customer->customer->last_name."'s wishlist": "Can't get customer name";
              echo ucfirst($cust_name);
              ?>
            </h1></div>
              <?php
                if($dif < 10800)  //60*60*3
                {
               ?>
                 <div class="vwGrid-container vw-flex-wrap">
                   <?php
                   $data = $this->db->select('*')->from('wishlist_tbl')->where(['shop_id'=>$shop_id,'cust_id'=>$cust_id,'action_type'=>1])->get()->result();
                    if(count($data)>0){
                      foreach ($data as $item) { ?>
                        <div class="vwGrid-grid-25">
                             <div class="vwwish-list-box">
                                <div class="vw-img-wrapper"><img src="<?=$item->product_image?>" class="wish-listitem" alt="wish-list" style="max-width:100%; height:180px"></div>
                                <h4 class="vw-product-title"><a href="<?='https://'.$shop.$item->product_url ?>"><?=$item->product_name?></h4>
                                <p class="vw-wish-list last-save-date"><?php $created_at = $item->created_at;  $toTime = date("jS \of F Y", strtotime($created_at)); echo $toTime ?></p>
                                <div class="vw-wishlist-product-price"><span><?=$item->product_price?></span></div>
                             </div>
                          </div>
                          <script>
                          setInterval(function() {
                              window.location.reload();
                          }, 180 * 1000); // 3 * 60 * 1000 milsec reload window after 3 minutes
                          </script>
                    <?php } //for ends
                  } //if(count($data)>0) ends
                   else{ ?>
                    <center><h1>No items in wishlist</h1></center>
                  <?php }//else ends  ?>
                </div>
           <?php } //condition hr conpariring ends here
           else{ ?>
             <div style="min-height:500;min-width:100%">
                  <div style="color:#31373d; text-align:center">
                       <img src="<?=base_url().'assets/img/sad-emoji.png' ?>" style="width:225px; height:225px; margin:auto" >
                    <h3>
                      Your link is expired! For security purpose, link was valid for 3 hour.<br> Contact the person who shared the link with you for recieving a valid link.
                    </h3>
                  </div>
             </div>
          <?php }?>
         <?php }else{ //end of first if condition ?>
          <div style="min-height:500;min-width:100%"><img src="<?=base_url().'assets/img/404.png' ?>" style="width:100%; height:500px" ></div>
       <?php } //first else ends here ?>
        </div>;
      <?php
      $html = ob_get_clean();
      return $this->output->set_content_type('application/liquid')->set_status_header(200)->set_output($html);
    }

    public function isValidTimeStamp($timestamp)
    {
      //validating timestamp
        return ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

  }
