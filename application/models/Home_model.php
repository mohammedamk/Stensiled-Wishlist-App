<?php
class Home_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    public function GetShopId($shop){
        $shop_id = $this->db->select('id')->where('domain', $shop)->get('shopify_stores')->row()->id;
        return $shop_id;
    }
    public function savesetting($data,$shop_id) {
      //check if table exits
      if ($this->db->table_exists('setting')) {
          $this->db->where('shop_id',$shop_id);
          $q = $this->db->get('setting');
          if ( $q->num_rows() > 0 )
          {
            $this->db->where('shop_id',$shop_id);
            $data = $this->db->update('setting', $data);
            return $data;
            } else {
            $this->db->where('shop_id',$shop_id);
            $data = $this->db->insert('setting', $data);
            return $data;
          }
        }
    }

    public function getsetting($shop_id) {
      $result = $this->db->query('SELECT * FROM `setting` where `shop_id`="'.$shop_id.'" ');
      $data= $result->row();
      return $data;
    }
      public function enableWishlist($data)
      {
        $this->db->where('shop_id',$data['shop_id']);
        $this->db->set('enable_wishlist', $data['enable_wishlist']);
        $data = $this->db->update('setting');
        return $data;
      }
      public function getWishlistSetting($shop_id)
      {
        $this->db->where('shop_id',$shop_id);
        $this->db->select('enable_wishlist');
        $data=  $this->db->get('setting')->row();
        return $data;
      }

    public function wishlistcounts($shop_id) {
      // $this->db->distinct();
      $result = $this->db->query('SELECT
                                  COUNT(*) AS wishitems,
                                  COUNT(DISTINCT `cust_id` ) AS customers,
                                  COUNT(DISTINCT  `product_id` ) AS products
                                  FROM `wishlist_tbl` WHERE `shop_id` = "'.$shop_id.'"' ) ;
      $data1= $result->row();
      $query ="SELECT
                  COUNT(CASE WHEN `email_type`=1 THEN 1 END) AS remainders,
                  COUNT(CASE WHEN `email_type`=2 THEN 1 END) AS onsale
                  FROM `emailRemainder_tbl` where shop_id = '".$shop_id."' ";
      $data2= $this->db->query($query)->row();

      $data = new StdClass;
      $data->wishitems = $data1->wishitems;
      $data->customers = $data1->customers;
      $data->products  = $data1->products;
      $data->remainders= $data2->remainders;
      $data->onsale    = $data2->onsale;
      // print_r($data);
      // exit;
      return $data;
    }

    public function wishgraph($shop_id) {
      $query ='SELECT count(DATE_FORMAT(created_at, "%d-%m-%Y")) as total,
                DATE_FORMAT(created_at, "%d-%m-%Y") as datecreated
                from wishlist_tbl  WHERE shop_id = "'.$shop_id.'" GROUP BY datecreated
                order by datecreated desc';
      $result = $this->db->query($query)->result();
      return $result;
    }

    public function getListInRange($start_date,$end_date,$shop_id) {
      $query =  " SELECT COUNT(*) AS wishitems,
                                  COUNT(DISTINCT `cust_id` ) AS customers,
                                  COUNT(DISTINCT  `product_id` ) AS products
                                  FROM `wishlist_tbl` WHERE `shop_id` = '".$shop_id."' AND `created_at` >= '".$start_date." 00:00:00' AND `created_at` <= '".$end_date." 23:59:59' " ;
      $result = $this->db->query($query);

      $data= $result->row();
      return $data;
    }

    public function topCustomers($shop_id) {
      $query = 'SELECT cust_id,cust_name, count(cust_id) AS items FROM wishlist_tbl where shop_id = "'.$shop_id.'" GROUP BY cust_id,cust_name order by items DESC';
      $data = $this->db->query($query)->result();
      return $data;
    }

    public function topProducts($shop_id) {
      $query = 'SELECT product_id,product_name,product_image, count(product_id) AS items FROM wishlist_tbl
                where shop_id = "'.$shop_id.'" GROUP BY product_id,product_name,product_image order by items DESC';
      $data = $this->db->query($query)->result();
      return $data;
    }

    //fetctching ubnique customers
    public function fetchActivity($query) {
      $data = $this->db->query($query)->result();
      return $data;
    }

    //fetctching ubnique customers
    public function fetchUniqueCustomers($query) {
      // $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate FROM wishlist_tbl GROUP BY cust_id,cust_name ";
      // if($query != '')
      // {
      //   $query .= $queryPara;
      // }
      $data = $this->db->query($query)->result();
      return $data;
    }

    public function fetchUniqueProducts($query) {
      // $data = $this->db->query("SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate FROM wishlist_tbl GROUP BY product_name,product_id,product_image")->result();
      $data = $this->db->query($query)->result();
      return $data;
    }

    //get wishlist
    public function getWishlist($cust_id,$shop_id) {
      $this->db->order_by("created_at", "desc");
      $q =  $this->db->select('*')->where(array('shop_id'=>$shop_id,'cust_id' => $cust_id))->get('wishlist_tbl');
      $data = $q->result();
      return $data;
    }

    public function getActionTypeCount($cust_id,$shop_id) {
      $result = $this->db->query('SELECT
                                  COUNT(*) AS actionCount,
                                  COUNT(CASE WHEN `action_type`="1" THEN 1 END) AS added_to_list,
                                  COUNT(CASE WHEN `action_type`="2" THEN 1 END) AS removed_from_list,
                                  COUNT(CASE WHEN `action_type`="3" THEN 1 END) AS new_list,
                                  COUNT(CASE WHEN `action_type`="4" THEN 1 END) AS added_to_cart
                                  FROM `wishlist_tbl` WHERE `shop_id` = "'.$shop_id.'" AND cust_id='.$cust_id );
      $data= $result->result();
      return $data;
    }

    public function UpdatePlanIdForSetting($shop_id)
    {
        $plan_id = $this->db->select('plan_id')->where('id', $shop_id)->get('shopify_stores')->row()->plan_id;
        if(isset($plan_id)){
            return $plan_id;
          }
          else {
            return false;
          }
    }
    public function removeItemFromWishlist($shop_id)
    {
        $removeItemFromWishlist = $this->db->select('removeItemFromWishlist')->where('shop_id', $shop_id)->get('setting')->row()->removeItemFromWishlist;
        if(isset($removeItemFromWishlist)){
            return $removeItemFromWishlist;
          }
          else {
            return false;
          }

    }
    //add item to wishlist_tbl
    // 1. check the plan if basic plan=>0 and premium plan=>1
    // 2. if plan is basic then check existing record. if item is exists then update else insert
    // 2. if plan is premium then check existing record. limit the enrty to 50 and if item is exists then update else insert
    public function addToWishlist($formdata) {
            $this->db->where(array('shop' => $formdata['shop'],
                                   'shop_id'=> $formdata['shop_id'],
                                   'product_id' => $formdata['product_id'],
                                   'product_name' => $formdata['product_name'],
                                   'cust_id' => $formdata['cust_id'],
                                   'product_url' => $formdata['product_url'],
                                   'action_type' => $formdata['action_type'],));
            $q = $this->db->get('wishlist_tbl');
            if ( $q->num_rows() > 0 )
            {
              $this->db->where(array('shop' => $formdata['shop'],
                                     'shop_id'=> $formdata['shop_id'],
                                     'product_id' => $formdata['product_id'],
                                     'product_name' => $formdata['product_name'],
                                     'cust_id' => $formdata['cust_id'],
                                     'product_url' => $formdata['product_url'],
                                     'action_type' => $formdata['action_type'],));
              $data = $this->db->update('wishlist_tbl', $formdata);
              return $data;
              } else {
              $data = $this->db->insert('wishlist_tbl', $formdata);
              return $data;
            }

    }

    public function checklist($shop_id, $product_id, $cust_id) {

      //check plan
      // for premium user they can they can customise that item can or can not added to cart item should display in wishlist
      if($this->UpdatePlanIdForSetting($shop_id) == 1){
      //$removeItemFromWishlist is defult 1
        $removeItemFromWishlist = $this->removeItemFromWishlist($shop_id);
        if($removeItemFromWishlist == 1 )
        {
          // print_r($removeItemFromWishlist);exit;
          $query = $this->db->query("SELECT * FROM `wishlist_tbl` WHERE
                                    `action_type` = '1' and
                                    `cust_id` = '".$cust_id."' AND
                                    `shop_id` = '".$shop_id."'
                                    order by `created_at` desc");

          $result = $query->result();
          return $result;
        }
        elseif ($removeItemFromWishlist == 0) {
          // print_r($removeItemFromWishlist);exit;
          $query = $this->db->query("SELECT * FROM `wishlist_tbl` WHERE
                                    `action_type` = '1' OR
                                    `action_type` = '4' AND
                                    `cust_id` = '".$cust_id."' AND
                                    `shop_id` = '".$shop_id."'
                                    order by `created_at` desc");
          $result = $query->result();
          // print_r($result);exit;
          return $result;
        }
      }
      elseif ($this->UpdatePlanIdForSetting($shop_id) == 0) {
        $query = $this->db->query("SELECT * FROM `wishlist_tbl` WHERE
                                  `action_type` = '1' and
                                  `cust_id` = '".$cust_id."' AND
                                  `shop_id` = '".$shop_id."'
                                  order by `created_at` desc");

        $result = $query->result();
        return $result;
      }
    }

    public function removeFromWishlist($shop_id,$cust_id,$product_id)
    {
      $actionUpdate = array(
          'action_type' => '2',
          'updated_at' => date("Y-m-d H:i:s")
      );
      $this->db->where(array('shop_id'=>$shop_id, 'product_id' => $product_id, 'cust_id' => $cust_id));
      $data = $this->db->update('wishlist_tbl', $actionUpdate);
      return $data;
    }

    public function removeAll($shop_id,$cust_id)
    {
      $actionUpdate = array(
          'action_type' => '2',
          'updated_at' => date("Y-m-d H:i:s")
      );
      $this->db->where(array('shop_id'=>$shop_id, 'cust_id' => $cust_id));
      $data = $this->db->update('wishlist_tbl', $actionUpdate);
      return $data;
    }

    public function addToCart($shop_id,$cust_id,$product_variant_id) {
      $actionUpdate = array(
          'action_type' => '4',
          'updated_at' => date("Y-m-d H:i:s")
      );
      $this->db->where(array('shop_id'=>$shop_id, 'product_variant_id' => $product_variant_id, 'cust_id' => $cust_id));
      $data = $this->db->update('wishlist_tbl', $actionUpdate);
      return $data;
    }
    public function getAllProducts($shop_id,$cust_id)
    {

      $query = $this->db->query("SELECT `product_variant_id` FROM `wishlist_tbl` WHERE
                                `action_type` = '1' and
                                `cust_id` = '".$cust_id."' AND
                                `shop_id` = '".$shop_id."'
                                order by `created_at` desc");
        foreach($query->result() as $row)
      {
          $array[] = $row->product_variant_id; // add each user id to the array
      }
      return $array;
      // $result = $query->result();
      // return $result;
    }
    public function checkExisingRecord($shop_id,$cust_id,$product_id)
    {
      $query = $this->db->query("SELECT * FROM `wishlist_tbl` WHERE
                                  `action_type` != '1' and
                                   `cust_id` = '".$cust_id."' AND
                                    `shop_id` = '".$shop_id."' and
                                    `product_id` ='".$product_id."' ");

      $result = $query->result();
      $count = count($result);
        return $count;

    }

    public function UpdateToWishlist($shop_id,$cust_id,$product_id) {
      $actionUpdate = array(
          'action_type' => '1',
          'updated_at' => date("Y-m-d H:i:s"),
      );
      $this->db->where(array('shop_id'=>$shop_id, 'product_id' => $product_id, 'cust_id' => $cust_id));
      $data = $this->db->update('wishlist_tbl', $actionUpdate);
      return $data;
    }

    public function countProductAdded($shop_id,$product_id)
    {
      $query = 'SELECT COUNT(*) as productAdded FROM `wishlist_tbl` WHERE
                `shop_id` = "'.$shop_id.'" and
                `product_id`="'.$product_id.'" ';
      $result = $this->db->query($query)->result();
      return $result;
    }

    public function saveFeedback($data)
    {
      $this->db->where('shop_id',$data['shop_id']);
      $data = $this->db->insert('feedback', $data);
      return $data;
    }
    public function premiumShop($day)
    {
        $today = date('Y-m-d');
        $query = 'SELECT shop_id,domain FROM setting LEFT JOIN
                    shopify_stores ON shopify_stores.id = setting.shop_id WHERE
                    shopify_stores.plan_id = 1 AND
                    enable_email = 1  AND
                    email_subscr = "'.$day.'" AND
                    nextMailSendOn = "'.$today.'" ';
                    // print_r($query);exit;
        $result = $this->db->query($query)->result();
        // print_r($result);
        // exit;
        return $result;
    }
    public function getCustomersList($shop_id)
    {
      $since_date = $this->db->select('since_date')->where('shop_id', $shop_id)->get('setting')->row()->since_date;
      // print_r($since_date);

      if(isset($since_date)){
          // exit;
        $query = 'SELECT cust_id FROM wishlist_tbl LEFT JOIN
                    shopify_stores ON shopify_stores.id = wishlist_tbl.shop_id WHERE
                    shopify_stores.plan_id = 1 AND
                    shop_id = "'.$shop_id.'"  AND
                    wishlist_tbl.updated_at >= Unix_timestamp((DATE(NOW()) - INTERVAL '.$since_date.')) GROUP BY cust_id';
                    // print_r($query);exit;
        $result = $this->db->query($query)->result_array();

          // print_r($result);
          // exit;
        return $result;
        }
        else {
          return false;
        }

    }
    public function getallSetting($shop_id)
    {
      $row = $this->db->get_where('setting', array('shop_id' => $shop_id))->row();
                            // echo "<pre>";  print_r($row);exit;
      return $row;
    }
    public function getProductsList($cust_id,$shop_id,$product_variant_id)
    {
        $row = $this->db->get_where('wishlist_tbl', array('action_type' => '1',
                                                  'cust_id' => $cust_id,
                                                  'shop_id' => $shop_id,
                                                  'product_variant_id' => $product_variant_id))->row();
                              // echo "<pre>";  print_r($row);exit;
        return $row;
    }
    public function getProductsRemainder($cust_id,$shop_id)
    {
      $data = new stdClass;
      $since_date = $this->db->select('since_date')->where('shop_id', $shop_id)->get('setting')->row()->since_date;
      if(isset($since_date)){
        //produtcs
        $query  = "SELECT product_id,product_variant_id,product_name,product_url,product_image,product_price,created_at FROM `wishlist_tbl` WHERE
                          `action_type`   = '1' AND
                          `cust_id`       = '".$cust_id."' AND
                          `shop_id`       = '".$shop_id."'
                          AND
                          `created_at` >= Unix_timestamp((DATE(NOW()) - INTERVAL $since_date))
                          ";
        $result = $this->db->query($query)->result();

        //get cust name and shop name
        $query2  = "SELECT DISTINCT `cust_name`, `shop` FROM `wishlist_tbl` WHERE
                          `action_type`   = '1' AND
                          `cust_id`       = '".$cust_id."' AND
                          `shop_id`       = '".$shop_id."'
                          AND
                          `created_at` >= Unix_timestamp((DATE(NOW()) - INTERVAL $since_date))
                          ";
        $result2 = $this->db->query($query2)->row();

        //explode the first name
        $name = explode(" ", $result2->cust_name);
        $data->name = ucfirst($name[0]);
        $data->shop = $result2->shop;
        $data->products = $result;
        // echo "<pre>";print_r($data);exit;
        return $data;
      }
    }

    public function checkEmailFacilty($shop_id)
    {
      $enable_email = "SELECT enable_email FROM `setting`
                        LEFT JOIN shopify_stores ON shopify_stores.id = setting.shop_id WHERE
                        shopify_stores.plan_id = 1  AND
                        `enable_email`         = '1'AND
                        `shop_id`             = '".$shop_id."'
                        ";
      $result = $this->db->query($enable_email)->row()->enable_email;
      return $result;
    }

    public function notifyOnProductUpdate($shop_id,$product_variant_id,$updated_price)
    {
        $query  = "SELECT DISTINCT cust_id FROM `wishlist_tbl`
                          LEFT JOIN shopify_stores ON shopify_stores.id = wishlist_tbl.shop_id WHERE
                          shopify_stores.plan_id= 1  AND
                          `action_type`         = '1'AND
                          `product_variant_id`  = '".$product_variant_id."' AND
                          `product_price`       > '".$updated_price."' AND
                          `shop_id`             = '".$shop_id."'
                          ";
                          // print_r($query);exit;
        $result = $this->db->query($query)->result_array();
        return $result;
    }
    public function updateProductPrice($shop_id,$product_variant_id,$updated_price)
    {
      $updateProductPrice = array(
          'product_price' => $updated_price,
      );
      $this->db->where(array('shop_id'=>$shop_id, 'product_variant_id' => $product_variant_id));
      $data = $this->db->update('wishlist_tbl', $updateProductPrice);
      return $data;
    }
    public function sendMailsData($data)
    {
      $email_subscr = $this->db->select('email_subscr')->where('shop_id', $data['shop_id'])->get('setting')->row()->email_subscr;
      if($email_subscr)
      {
         $settingData =[];
         if($email_subscr == '1 DAY')
         {
            $settingData['nextMailSendOn'] = date("Y-m-d", strtotime('+1 day'));
         }
         elseif($email_subscr == '7 DAY')
         {
            $settingData['nextMailSendOn'] = date("Y-m-d", strtotime('+7 day'));
         }
         elseif($email_subscr == '30 DAY')
         {
            $settingData['nextMailSendOn'] = date("Y-m-d", strtotime('+30 day'));
         }

         $settingData['mailSendOn'] = date("Y-m-d");
         // dd($settingData);
         $ok = $this->db->update('setting', $settingData);
         if($ok)
         {
            $data1 = $this->db->insert('emailRemainder_tbl', $data);
            return $data1;
         }
      }
    }














}
?>
