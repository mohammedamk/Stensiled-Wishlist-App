<?php

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); //Do your magic here
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('global_helper');
        $this->load->helper('date');
        $this->load->model('Home_model');
    }
    public function getThemes($shop)
    {
        if(!isset($shop) || $shop == '')
        {
          $shop = $_GET['shop'];
        }
        $shopAccess = getShop_accessToken_byShop($shop);
        $this->load->library('Shopify', $shopAccess);
        $themes = $this->shopify->call(['METHOD' => 'GET', 'URL' => 'admin/api/2020-01/themes.json'], TRUE);
        if (count($themes->themes) > 0) {
            foreach ($themes->themes as $theme) {
                if ($theme->role == "main") {
                    $this->getProductPage($theme->id, $shop);
                }
            }
        } else {
            echo "something Wrong while getting theme info";
        }
    }
    public function getProductPage($themeId,$shop)
    {
        $data = array();
        $data['setting'] =  $this->Home_model->getsetting();
        $setting = $data['setting'];
        // echo "<pre>";
        // print_r($setting->callwish);
        // exit;
        $content_html_value = '<!--  vw wish list  -->
 <style>
   /* wish-list code */
  .vw-wish-name {
    background: grey;
    border: none;
    outline: none;
    color: #ffffff;
    font-family: FontAwesome;
    padding: 10px 35px;
    text-transform: uppercase;
    font-size: 15px;
    letter-spacing: 1px;
  }
  .vw-wish-name:after {
    content: "\f08a  '.$setting->btnTxtBeforeAdding.'";
  }
.vw-wish-active{
	pointer-events: none;
}
  .vw-wish-name.vw-wish-active:after{
    content: "\f004  '.$setting->btnTxtAfterAdding.'";

  }

/*----========== Wish list popup css ===========  */
    .wish-list-counter {
      position: relative;
    }
    .vw-wishlistcounter {
      position: absolute;
      bottom: 75%;
      left: -25px;
      background: #808080;
      border-radius: 50%;
      text-align: center;
      font-size: 14px;
      width: 25px;
      height: 25px;
      line-height: 25px;
    }
  body.vwmodal-open {
    overflow: hidden;
  }
  .my-wish-open {
    position: fixed;
    right: 1%;
    top: 25%;
    background: #31373d;
    color: #fff;
    padding: 8px 11px;
    z-index: 9999;
    cursor: pointer;
  }
  .vw-pop-up-wrapper {
    padding: 10px 30px;
  }
  .wish-list-header {
    border-bottom: 1px solid #000;
  }
  .vw-heading-close {
    display: flex;
    width: 100%;
  }
  .vw-heading-close {
    display: flex;
    width: 100%;
  }
  .vw-heading {
    width: 50%;
    margin: auto;
  }
  .vw-close {
    width: 50%;
    margin: auto;
    text-align: right;
  }
  .vwGrid-container {
    width: calc(100% + 16px);
    margin: -8px;
  }
  .vw-flex-wrap {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    box-sizing: border-box;
  }
  .vwGrid-grid-25 {
    flex-grow: 0;
    max-width: 25%;
    flex-basis: 25%;
    padding: 10px;
  }
  .vwwish-list-box {
    border: 1px solid #e3e3e3;
    color: rgba(0, 0, 0, 0.87);
    background-color: #fff;
    box-shadow: 1px 1px 4px #cecece9c;
    padding: 10px;
  }
  img.wish-listitem {
    width: 100%;
    max-width: 190px;
    height: 170px;
  }
  .vw-product-title {
    font-size: 1.1rem;
    padding: 5px 0 0;
    color: #000;
  }
  .vw-wish-list.last-save-date {
    font-size: 14px;
  }
  .vw-wishlist-product-price {
    padding-bottom: 7px;
  }
  .vw-wishlist-product-price span {
    color: #000;
    font-weight: 600;
  }
  .wish-list-body {
    padding-top: 15px;
    padding-bottom: 15px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    height: 30vw;
  }
  .wish-list-footer {
    border-top: 1px solid #000;
  }
  .vw-grop-btn {
    width: 100%;
    padding: 5px 0 5px;
    display: inline-block;
  }
  .btn-prime {
    min-width: 120px;
    text-transform: uppercase;
    background: #444444;
    font-size: 15px;
    padding: 7px 15px;
    border-radius: 3px;
    border: none;
    color: #fff;
  }
.btn-prime.disabled {
    background: #b5b5b5;
  	pointer-events: none;
  }
  .vw-remove-wishlidt {
    border: none;
    background: none;
    color: #000;
    font-size: 21px;
    text-align: right;
    width: 30px;
  }
  .wv-close {
    background: none;
    border: none;
    font-size: 31px;
    color: #000;
    position: relative;
    top: -5px;
  }
  .text-right {
    text-align: right !important;
  }
  .vw-share {
    font-size: 18px;
  }
  @media only screen and (max-width: 768px) {
    .wish-list-body {
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      height: 100vw;
    }
    img.wish-listitem {
      max-width: 45%;
      height: auto;
      margin: 0 auto;
    }
    .vwGrid-grid-25 {
      max-width: 100%;
      flex-basis: 100%;
    }
    .vwGrid-container {
      margin: 0;
    }
    .vwwish-list-box {
      padding: 15px;
    }
    .vw-img-wrapper {
      text-align: center;
    }
  }

  /*----=== endpopup css --======  */
/* Wishlist code end */
  #modal {
    position: fixed;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 99999;
    height: 100%;
    width: 100%;
  }
  #modal .modalconent {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    max-width:900px;
    border:1px solid #000;
    background-color:#fff;
    padding: 10px 0 10px;
  }

</style>
<div class="my-wish-open" {% unless customer %} title="You must logged in first!"{% endunless %}>
    <div class="wish-list-counter">
      <div class="vw-wishlistcounter">
          {% unless customer %}
        	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
          <i class="fa fa-exclamation"></i>
          {% endunless %}
      </div>
      <i class="fa fa-heart" aria-hidden="true"></i>
    </div>
</div>


<div id="modal" style="display: none;">
  <div class="modalconent">
    <div class="wish-list-header">
      <div class="vw-pop-up-wrapper">
        <div class="vw-heading-close">
          <div class="vw-heading">
            <h3>'.$setting->callwish.'</h3>
          </div>
          <div class="vw-close">
            <a id="close" href="JavaScript:void(0)"> <button id="button" class="wv-close">&times;</button></a>
          </div>
        </div>
      </div>
    </div>
    <div class="wish-list-body">
      <div class="vw-pop-up-wrapper">
        <div class="vwGrid-container vw-flex-wrap" id="wishbody">

        </div>
      </div>
    </div>
    <div class="wish-list-footer">
      <div class="vw-pop-up-wrapper">
        <div class="text-right">
          <span class="vw-share"><i class="fa fa-share-alt" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>
  </div>
</div>
<!--  wishlist popup-code end   -->
  {% if customer %}
  <script>
  var cust_id = \'{{ customer.id }}\';
  </script>
  {% endif %}
{{ "api.jquery.js" | shopify_asset_url | script_tag }}
	<script>
      {% if customer %}
        wishbtncode = \'<div class="vw-whishlist-product-page"> <button class="vw-wish-name add-to-list " type="button" data-vw-shop="{{ shop.domain }}" data-vw-product_id="{{ product.id }}" data-vw-product_variant_id = "{{ product.variants.first.id }}" data-vw-product_name="{{ product.title }}" data-vw-product_url="{{ product.url }}" data-vw-product_price="{{ product.price | money }}" data-vw-product_image="{{ product | img_url }}" data-vw-cust_id="{{ customer.id }}" data-vw-cust_name="{{ customer.name }}" id="button_{{ product.id }}" onclick="addCollectionItem({{ product.id }});return false;"></button></span> </div>\';
      {% else %}
      wishbtncode =\'<div class="vw-whishlist-product-page"> <a class="vw-wish-name" id="submitForm" href="https://{{ shop.permanent_domain }}/account" style="color:white"></a> </div>\';
      {% endif %}
      $(\'form[action="/cart/add"]\').append(wishbtncode);

    checklist();
    function checklist()
              {

							{% if template contains \'product\' %}
                          		var product_id =$(".vw-wish-name").attr(\'data-vw-product_id\');
                          	{% endif %}
                var html = "";var wishbtncode="";
                $.ajax({
                  url: "'.base_url().'Home/checklist?shop={{ shop.domain }} ",
                  type: "POST",
                   dataType: "json",
                   data: {
                     cust_id : cust_id,
                 	},
                  	success: function(data) {
                  	console.log(data);
                     var wishlistCount = data.length;
                  	 $(".vw-wishlistcounter").text(wishlistCount);
                      if(data.length > 0){
                          for (i = 0; i < data.length; i++) {
                            html += "<div class=\'vwGrid-grid-25\'>";
                            html +=  "<div class=\'vwwish-list-box\'>";
                            html +=    "<div class=\'vw-img-wrapper\'>";
                            html +=    "<img src="+ data[i].product_image +" class=\'wish-listitem\' alt=\'wish-list\'>";
                            html +=    "</div>";
                            html +=    "<h4 class=\'vw-product-title\'>"+ data[i].product_name +"</h4>";
                            html +=    "<p class=\'vw-wish-list last-save-date\'>"+ data[i].created_at + "</p>";
                            html +=    "<div class=\'vw-wishlist-product-price\'>";
                            html +=     "<span>"+ data[i].product_price +"</span>";
                            html +=   "</div>";
                            html +=     "<button class=\'btn-prime addtocart\' type=\'button\' onclick=\'addItem(" + data[i].product_variant_id +", 1); return false\' id=\'product_" + data[i].product_variant_id +"\'>'.$setting->btnTxtForCart.'</button>";
                            html +=     "<button class=\'vw-remove-wishlidt removeFromWishlist\'  type=\'button\' onclick=\'removeFromWishlist("+ data[i].product_id +")\'><i class=\'fa fa-trash\' aria-hidden=\'true\'></i></button>";
                            html +=   "</div>";
                            html +=   "</div>";
                            html +=   "</div>";

                            //product is already added to list
							{% if template contains \'product\' %}

                              if(product_id == data[i].product_id)
                              {
                                    $(".vw-wish-name").addClass("vw-wish-active");
                              } //if
                            {% endif %}

                            {% if template contains \'collection\' %}
                            	$(\'#button_\'+data[i].product_id).addClass(\'active\');
                            {% endif %}

                            {% if  template == \'index\' %}
                            $(\'#button_\'+data[i].product_id).addClass(\'active\');
                            {% endif %}
                          } //for

                  } //if
                  else{
                    html += "<div><center><h3>'.$setting->textWhenNoItem.'<h3></center></div>";
                  } //else

                  $("#wishbody").html(html);
//                   console.log(html);
                   }, //success

                });
              }
//               ===================
              function addCollectionItem(product_id)
     {
       var wishbtn = $(\'#button_\'+product_id);
       var shop = wishbtn.attr(\'data-vw-shop\');
       var product_id = wishbtn.attr(\'data-vw-product_id\');
       var product_variant_id = wishbtn.attr(\'data-vw-product_variant_id\');
       var product_name = wishbtn.attr(\'data-vw-product_name\');
       var product_url = wishbtn.attr(\'data-vw-product_url\');
       var product_price = wishbtn.attr(\'data-vw-product_price\');
       var product_image = wishbtn.attr(\'data-vw-product_image\');
       var cust_id = wishbtn.attr(\'data-vw-cust_id\');
       var cust_name = wishbtn.attr(\'data-vw-cust_name\');
       var url = \''.base_url().'Home/addToWishlist?shop=\'+shop;
       $.ajax({
                  url: url,
                  type: "POST",
                  dataType: \'json\',
                  data: {
                    shop : shop,
                    product_id : product_id,
                    product_variant_id: product_variant_id,
                    product_name : product_name,
                    product_url : product_url,
                    product_price : product_price,
                    product_image : product_image,
                    cust_id : cust_id,
                    cust_name : cust_name,
                 } ,
//                    beforeSend: function() {  },
//                    complete: function() {  },
                  success: function(data) {
                    checklist();
//                     wishbtn.addClass(\'active\');
//                     $(\'.vw-wish-name\').addClass(\'vw-wish-active\');

                  },
                  error: function() {
                    alert(\'Failed to item to wishlist! Please try again.\');
                  },
              });
     }//addCollectionItem ends
// =======================================================================
 function removeFromWishlist(product_id) {
//    	var pr_id = product_id;
//       alert(cust_id);alert(pr_id);
   if(product_id ==" " || cust_id ==" ")
   {
      alert("customer id or product id is not selelcted");
   }
      if(confirm(\' '.$setting->mgsForRemoveItem.'\'))
      {
        $.ajax({
        url : "'.base_url().'Home/removeFromWishlist?shop={{ shop.domain }} ",
        method : "POST",
        dataType: "json",
        data: {
        product_id : product_id,
        cust_id : cust_id,
      },
        success: function(data) {
          checklist();
        alert("removed from list");
         {% if template contains \'product\' %}
           $("#button_"+ product_id).removeClass("vw-wish-active");
          {% endif %}
        {% if template contains \'collection\' %}
        $("#button_"+ product_id).removeClass("active");
        {% endif %}

      },
        error: function(error) {
        	alert("Error");

        }

      });
      }
    }
 //remove from wishlist ends here
//adding item to function
// -------------------------------------------------------------------------------------
// POST to cart/add.js returns the JSON of the line item associated with the added item.
// -------------------------------------------------------------------------------------
function addItem(variant_id, quantity, callback) {
  var quantity = quantity || 1;
  $("p[title =\'Tomorrow\']")
  var wishbtn = $("button[data-vw-product_variant_id = \'+ variant_id +\']");
//   alert(wishbtn);
  var params = {
    type: "POST",
    url: "/cart/add.js",
    data: "quantity=" + quantity + "&id=" + variant_id,
    dataType: "json",
    beforeSend: function() { $(".addtocart").addClass("disabled"); },
    complete: function() { $(".addtocart").removeClass("disabled"); },
    success: function(line_item) {
      if ((typeof callback) === "function") {
        callback(line_item);
      }
      else {
        $.ajax({
        url : "'.base_url().'Home/addToCart?shop={{ shop.domain }}",
        method : "POST",
        dataType: "json",
        data: {
        product_variant_id : variant_id,
        cust_id : cust_id,
      },
        success: function(data) {
        console.log(data);

        Shopify.onItemAdded(line_item);
        checklist();


      },
        error: function(error) {
        	alert("Error Updated Status");
        }

      });
      }  //else ends here
    }, //success
    error: function(XMLHttpRequest, textStatus) {
      Shopify.onError(XMLHttpRequest, textStatus);
    }
  };
  jQuery.ajax(params);
};



//               ===================

               //remove from wishlist

    $(".my-wish-open").click(function(){
      $("#modal").show().fadeIn("slow");
      $("body").addClass("vwmodal-open");
    });

    $(\'#close, #not_now\').click(function(){
      $(\'#modal\').hide();
      $("body").removeClass("vwmodal-open")
    });
          </script>';

        $content_html = array(
              "asset" => array(
                  "key" => "snippets/wish-list-popup.liquid",
                  "value" =>  $content_html_value
              )
          );

        $putForm = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/2020-01/themes/' . $themeId . '/assets.json', 'DATA' => $content_html], TRUE);
        // if($putForm)
        // {
        //   print_r('true');
        // }
    }

    public function dashboard()
    {
        $data = array();
        $data['wishlistCounts'] = $this->Home_model->wishlistcounts();
        $data['topCustomers'] = $this->Home_model->topCustomers();
        $data['topProducts'] = $this->Home_model->topProducts();
        $this->load->load_admin('dashboard', $data);
    }

    public function wishgraph()
    {
      $data = array();
      $data = $this->Home_model->wishgraph();
      // echo "<pre>";
      // print_r($data);
      // exit;
      echo json_encode($data);
      // exit;
    }

    public function getListInRange()
    {
      $start_date = $this->input->post('start_date');
      $end_date = $this->input->post('end_date');
      if(isset($start_date) && isset($end_date))
      {
        $data = $this->Home_model->getListInRange($start_date,$end_date);
      }
      if(isset($data)){
      echo json_encode($data);
      }
      else {
        $error = array(code=> 403, 'mgs'=>'Server Error');
      }
      exit();
    }
    public function activity()
    {
        $this->load->load_admin('activity');
    }
    public function settings()
    {
        $data = array();
        $data['getsetting'] = $this->Home_model->getsetting();
        $this->load->load_admin('setting',$data);
    }
    public function savesetting() {
      $shop = $_GET['shop'];
      $data = array(
        'shop'                    => $shop,
        'callwish'                => $this->input->post('callwish'),
        'btnTxtBeforeAdding'      => $this->input->post('btnTxtBeforeAdding'),
        'btnTxtAfterAdding'       => $this->input->post('btnTxtAfterAdding'),
        'btnIcon'                 => $this->input->post('btnIcon'),
        'btnTxtForCart'           => $this->input->post('btnTxtForCart'),
        'textWhenNoItem'          => $this->input->post('textWhenNoItem'),
        'mgsForRemoveItem'        => $this->input->post('mgsForRemoveItem'),
      );

      $ok = $this->Home_model->savesetting($data);
      //updating assets
      $this->getThemes($shop);
      if(isset($ok))
      {
        $this->settings();
      }
      echo json_encode($ok);
      exit;
    }
    // fetch data for activity data
    public function fetchactivitydata()
    {
      $shop = $_GET['shop'];
      $draw = intval($this->input->post("draw"));
      $start = intval($this->input->post("start"));
      $length = intval($this->input->post("length"));
      $order = $this->input->post("order");
      $search= $this->input->post("search");
      $search = $search['value'];
      $is_date_search = $this->input->post("is_date_search");
      $col = 0;
      $dir = "";
      if(!empty($order))
      {
          foreach($order as $o)
          {
              $col = $o['column'];
              $dir= $o['dir'];
          }
      }

      if($dir != "asc" && $dir != "desc")
      {
          $dir = "desc";
      }

      $valid_columns = array(
          0=>'product_id',
          1=>'product_name',
          2=>'cust_name',
            3=>'cust_id',
      );

      if(!isset($valid_columns[$col]))
      {
          $order = null;
      }
      else
      {
          $order = $valid_columns[$col];
      }

      if($order !=null)
      {
          $this->db->order_by($order, $dir);
      }
      // if(!empty($search))
      // {
      //   $query = " SELECT * FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' ";
      //   $this->db->limit($length,$start);
      //   $products = $this->Home_model->fetchUniqueProducts($query);
      // }

      // if(!empty($search))
      // {
      //     $x=0;
      //     foreach($valid_columns as $sterm)
      //     {
      //         if($x==0)
      //         {
      //             $this->db->like($sterm,$search);
      //         }
      //         else
      //         {
      //             $this->db->or_like($sterm,$search);
      //
      //         }
      //         $x++;
      //     }
      // }
      if($is_date_search =='yes')
      {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $added_to_list = $this->input->post('added_to_list');
        $removed_from_list = $this->input->post('removed_from_list');
        $new_list = $this->input->post('new_list');
        $added_to_cart = $this->input->post('added_to_cart');
        if(!empty($search)) //if date serch is ''yes and serch is not empty
        {
          $query = "SELECT * FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' ";

          if($start_date !== '' && $end_date !== '')
          {
            $query .="AND `created_at` >= '".$start_date."' AND `created_at` >= '".$start_date."'   ";
          }
          if($added_to_list !== '' && $added_to_list == 1)
          {
            $query .="and action_type='".$added_to_list."' ";
          }
          if($removed_from_list !== '' && $removed_from_list == 2)
          {
            $query .="and action_type='".$removed_from_list."' ";
          }
          if($new_list !== '' && $new_list == 3)
          {
            $query .="and action_type='".$new_list."' ";
          }
          if($added_to_cart !== '' && $added_to_cart == 4)
          {
            $query .="and action_type='".$added_to_cart."' ";
          }
        }
        else{
          $query = "SELECT * FROM wishlist_tbl WHERE  ";

          if($start_date !== '' && $end_date !== '')
          {
            $query .=" `created_at` >= '".$start_date."' AND `created_at` <= '".$end_date."'   ";
          }
          if($start_date !== '' && $end_date !== '' && ($added_to_list !== '' || $removed_from_list !== '' || $new_list !== '' || $added_to_cart !== ''))
          {
            $query .="AND ";
          }
          if($added_to_list !== '' && $added_to_list == 1)
          {
            $query .=" `action_type` = '".$added_to_list."' ";
          }

          if( ($removed_from_list !== '' && $added_to_list !== '')  )
          {
            $query .="or ";
          }

          if($removed_from_list !== '' && $removed_from_list == 2)
          {
            $query .=" `action_type` = ' ".$removed_from_list."' ";
          }

          if( ($new_list !== '' && $added_to_list !== '')  || ($new_list !== '' && $removed_from_list !== ''))
          {
            $query .="or ";
          }

          if($new_list !== '' && $new_list == 3)
          {
            $query .="action_type='".$new_list."' ";
          }

          if(($added_to_cart !== '' && $new_list !== '') || ($added_to_cart !== '' && $added_to_list !== '') ||($added_to_cart !== '' && $removed_from_list !== ''))
          {
            $query .="or ";
          }

          if($added_to_cart !== '' && $added_to_cart == 4)
          {
            $query .="action_type='".$added_to_cart."' ";
          }

                    // print_r($query);
                    // exit;
        }
        $this->db->limit($length,$start);
        $activities = $this->Home_model->fetchActivity($query);
      }
      elseif($is_date_search =='no') {
        if(!empty($search))
        {
          $query = "SELECT * FROM `wishlist_tbl` WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' ";
        }else{
          $query ='SELECT * FROM `wishlist_tbl`';
        }
        $this->db->limit($length,$start);
        $query .= 'order by created_at desc';
        $activities = $this->Home_model->fetchActivity($query);
      }

      $data = array();
      foreach($activities as $row)
      {
            $sub_array = array();
            $sub_array[] = ucfirst($row->cust_name);
            $sub_array[] = '<a href="https://'.$shop.''.$row->product_url.'">'.$row->product_name.'</a>';
            $sub_array[] = $this->timeElapsed($row->created_at);
            $sub_array[] = $this->action_type($row->action_type);
            $sub_array[] = '<a href="'.base_url().'Home/getWishlist?shop='.$_GET['shop'].'&cust_id='.$row->cust_id.'" target="_blank">View wishlist <img src="https://static.thenounproject.com/png/1381335-200.png" style="width: 20px; height: 20px"></a>';
            $data[] = $sub_array;

      }
      $totalCount = $this->totalCount();
      $output = array(
          "draw" => $draw,
          "recordsTotal" => $totalCount,
          "recordsFiltered" => count($activities),
          "data" => $data
      );
      echo json_encode($output);
      exit();
    }
// ------------------------------------------------------------------------------------------------------

// fetch data for customers data
public function fetchProductsdata()
{
    $shop = $_GET['shop'];
    $draw = intval($this->input->post("draw"));
    $start = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $order = $this->input->post("order");
    $search= $this->input->post("search");
    $search = $search['value'];
    $is_date_search = $this->input->post("is_date_search");
    $col = 0;
    $dir = "";
    if(!empty($order))
    {
        foreach($order as $o)
        {
            $col = $o['column'];
            $dir= $o['dir'];
        }
    }

    if($dir != "asc" && $dir != "desc")
    {
        $dir = "desc";
    }

    $valid_columns = array(
        0=>'product_id',
        1=>'product_name',
        2=>'cust_name',
          3=>'cust_id',
    );

    if(!isset($valid_columns[$col]))
    {
        $order = null;
    }
    else
    {
        $order = $valid_columns[$col];
    }

    if($order !=null)
    {
        $this->db->order_by($order, $dir);
    }
    // if(!empty($search))
    // {
    //   $query = " SELECT * FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' ";
    //   $this->db->limit($length,$start);
    //   $products = $this->Home_model->fetchUniqueProducts($query);
    // }

    // if(!empty($search))
    // {
    //     $x=0;
    //     foreach($valid_columns as $sterm)
    //     {
    //         if($x==0)
    //         {
    //             $this->db->like($sterm,$search);
    //         }
    //         else
    //         {
    //             $this->db->or_like($sterm,$search);
    //
    //         }
    //         $x++;
    //     }
    // }
    if($is_date_search =='yes')
    {
      $start_date = $this->input->post('start_date');
      $end_date = $this->input->post('end_date');
      if(!empty($search)) //if date serch is ''yes and serch is not empty
      {
        $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' AND `created_at` >= '".$start_date."' AND `created_at` >= '".$start_date."' GROUP BY product_name,product_id,product_image ";
        // $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' GROUP BY product_name,product_id,product_image ";
      }else{
        $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  `created_at` >= '".$start_date." 00:00:00' AND `created_at` <= '".$end_date." 23:59:59' GROUP BY product_name,product_id,product_image ";
      }

      $this->db->limit($length,$start);
      $products = $this->Home_model->fetchUniqueProducts($query);
    }
    elseif($is_date_search =='no') {
      if(!empty($search))
      {
        $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' GROUP BY product_name,product_id,product_image ";
      }else{
        $query ='SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl GROUP BY product_name,product_id,product_image';
      }
      $this->db->limit($length,$start);
      // $query .= 'order by created_at desc';
      $products = $this->Home_model->fetchUniqueProducts($query);
    }

    $data = array();
    foreach($products as $row)
    {
          $sub_array = array();
          $sub_array[] = '<img src="'.$row->product_image.'" alt="'.$row->product_name.'" style="width:51px;height:60px"/>'." ".ucfirst($row->product_name);
          $sub_array[] = $row->items;
          $sub_array[] = $this->timeElapsed($row->mindate);
          $sub_array[] = $this->timeElapsed($row->maxdate);
          $sub_array[] = '<a href="https://'.$shop.'/admin/products/'.$row->product_id.'" target="_blank">View on store</a>';
          $data[] = $sub_array;

    }
    $totalCount = $this->totalCount();
    $output = array(
        "draw" => $draw,
        "recordsTotal" => $totalCount,
        "recordsFiltered" => count($products),
        "data" => $data
    );
    echo json_encode($output);
    exit();
}
// ==========================================================================================================================
public function fetchCustomersdata()
{
    $shop = $_GET['shop'];
    $draw = intval($this->input->post("draw"));
    $start = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $order = $this->input->post("order");
    $search= $this->input->post("search");
    $search = $search['value'];
    $is_date_search = $this->input->post("is_date_search");
    $col = 0;
    $dir = "";
    if(!empty($order))
    {
        foreach($order as $o)
        {
            $col = $o['column'];
            $dir= $o['dir'];
        }
    }

    if($dir != "asc" && $dir != "desc")
    {
        $dir = "desc";
    }

    //search on this colunms
    $valid_columns = array(
        0=>'cust_id',
        1=>'cust_name',
        2=>'product_name',
        3=>'product_id',
    );

    if(!isset($valid_columns[$col]))
    {
        $order = null;
    }
    else
    {
        $order = $valid_columns[$col];
    }

    if($order !=null)
    {
        $this->db->order_by($order, $dir);
    }

    // if(!empty($search))
    // {
    //     $x=0;
    //     foreach($valid_columns as $sterm)
    //     {
    //         if($x==0)
    //         {
    //             $this->db->like($sterm,$search);
    //         }
    //         else
    //         {
    //             $this->db->or_like($sterm,$search);
    //         }
    //         $x++;
    //     }
    // }

    if($is_date_search =='yes')
    {
      $start_date = $this->input->post('start_date');
      $end_date = $this->input->post('end_date');
      if(!empty($search)) //if date serch is ''yes and serch is not empty
      {
        $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' AND `created_at` >= '".$start_date."' AND `created_at` >= '".$start_date."' GROUP BY cust_id,cust_name ";
        // $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' GROUP BY product_name,product_id,product_image ";
      }else{
        $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  `created_at` >= '".$start_date." 00:00:00' AND `created_at` <= '".$end_date." 23:59:59' GROUP BY cust_id,cust_name ";
      }

      $this->db->limit($length,$start);
      $products = $this->Home_model->fetchUniqueCustomers($query);
    }
    elseif($is_date_search =='no') {
      if(!empty($search))
      {
        $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' GROUP BY cust_id,cust_name ";
      }else{
        $query ='SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate, min(created_at) as mindate FROM wishlist_tbl GROUP BY cust_id,cust_name';
      }
      $this->db->limit($length,$start);
      $products = $this->Home_model->fetchUniqueCustomers($query);
    }

    // if($is_date_search =='yes' )
    // {
    //   $start_date = $this->input->post('start_date');
    //   $end_date = $this->input->post('end_date');
    //   $query = 'SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate,min(created_at) as mindate FROM wishlist_tbl WHERE  `created_at` >= "'.$start_date.'" AND `created_at` <= "'.$end_date.'"  GROUP BY cust_id,cust_name';
    // }
    // elseif($is_date_search =='no') {
    //   $query ='SELECT cust_id,cust_name, count(cust_id) AS items,max(created_at) as maxdate, min(created_at) as mindate FROM wishlist_tbl GROUP BY cust_id,cust_name';
    // }

    $this->db->limit($length,$start);
    $customers = $this->Home_model->fetchUniqueCustomers($query);

    $data = array();
    foreach($customers as $row)
    {
          $sub_array = array();
          $sub_array[] = ucfirst($row->cust_name);
          $sub_array[] = $row->items;
          $sub_array[] = $this->timeElapsed($row->mindate);
          $sub_array[] = $this->timeElapsed($row->maxdate);
          $sub_array[] = '<a href="'.base_url().'Home/getWishlist?shop='.$_GET['shop'].'&cust_id='.$row->cust_id.'" target="_blank">View wishlist <img src="https://static.thenounproject.com/png/1381335-200.png" style="width: 20px; height: 20px"></a>';
          $data[] = $sub_array;

    }
    $totalCount = $this->totalCount();
    $output = array(
        "draw" => $draw,
        "recordsTotal" => $totalCount,
        "recordsFiltered" => count($customers),
        "data" => $data
    );
    echo json_encode($output);
    exit();
}
// ==========================================================================================================================

    public function totalCount()
    {
        $query = $this->db->select("COUNT(*) as num")->get("wishlist_tbl");
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }
// =========================================================================================================================
    //define action type
    public function action_type($action_type)
    {
      if($action_type == 1) { return "<span class=\"badge badge-warning\">Added To wishlist</span>"; }
       elseif ($action_type == 2) { return "<span class=\"badge badge-danger\">Removed from wishlist</span>"; }
       elseif ($action_type == 3) { return "<span class=\"badge badge-primary\">New wishlist</span>"; }
      elseif($action_type == 4){ return '<span class="badge badge-success">Added To Cart</span>';}
    }

// ==============================================================================================================================

    public function getWishlist()
    {
      $shop = $_GET['shop'];
      $cust_id = $_GET['cust_id'];
      $data['actionCounts'] = $this->Home_model->getActionTypeCount($cust_id);
      $data['wishlists'] = $this->Home_model->getWishlist($cust_id);
      foreach ($data['wishlists'] as $item) {
        $action_type = $this->action_type($item->action_type);
        $item->action_type = $action_type;

        $timeago = $this->timeElapsed($item->created_at);
        $item->created_at = $timeago;
      }
      // echo "<pre>";
      // print_r($data['wishlists']);
      // exit;
      $this->load->load_admin('wishlist', $data);

    }

    //timespan()
    public function timeElapsed($time)
    {
      $is_valid = $this->is_date_time_valid($time);

    	if ($is_valid) {
    		$timestamp = strtotime($time);
        // print_r($timestamp);
        // exit;
    		$difference = time() - $timestamp;
    		$periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
    		$lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    		if ($difference > 0) { // this was in the past time
    			$ending = "ago";
    		} else { // this was in the future time
    			$difference = -$difference;
    			$ending = "to go";
    		}

    		for ($j = 0; $difference >= $lengths[$j]; $j++)
    			$difference /= $lengths[$j];

    		$difference = round($difference);

    		if ($difference > 1)
    			$periods[$j].= "s";

    		$text = "$difference $periods[$j] $ending";

    		return $text;
    	} else {
    		return 'Date Time must be in "yyyy-mm-dd hh:mm:ss" format';
    	}
    }

    function is_date_time_valid($time) {
      	if (date('Y-m-d H:i:s', strtotime($time)) == $time) {
      		return TRUE;
      	} else {
      		return FALSE;
      	}
      }
      public function getTimestamp($dateToConvert)
      {
        $Datesconv=strtotime($dateToConvert);       //15/03/2015

        $travelDates=date('Y-m-d H:i:s',$Datesconv);
        return $travelDates;
      }
    public function customers()
    {
        // $this->load->view('layouts/header');
        $this->load->load_admin('customers');
        // $this->load->view('layouts/footer');
    }
    public function products()
    {
        // $this->load->view('layouts/header');
        $this->load->load_admin('products');
        // $this->load->view('layouts/footer');
    }
    public function checklist()
    {
      $shop = $_GET['shop'];
      $product_id = $this->input->post('product_id');
      $cust_id = $this->input->post('cust_id', true);
      $data = $this->Home_model->checklist($shop, $product_id, $cust_id);
      foreach ($data as $k) {
        $created_at = $k->created_at;
        $k->created_at = date("jS \of F Y", strtotime($created_at));
      }
      if(!isset($data))
      {
        $error = array( 'code' => 403, 'mgs' => 'Internal server error');
        echo json_encode($error);
      }
      else{
        echo json_encode($data);
      }
      exit();

    }

    public function addToWishlist()
    {
      $shop = $_GET['shop'];
      $cust_id = $this->input->post('cust_id', true);
      $cust_name = $this->input->post('cust_name', true);
      $product_id = $this->input->post('product_id', true);
      //check the recored is already exixtsed in recordsTotal
      $isExisted = $this->Home_model->checkExisingRecord($shop,$cust_id,$product_id);
      // print_r($isExisted);
      // exit;
      if($isExisted>0)
      {
        $data = $this->Home_model->UpdateToWishlist($shop,$cust_id,$product_id);
        echo json_encode($data);
        exit();
      }
      else{
        $formdata =array(
          'shop' => $this->input->post('shop', true),
          'product_id' => $product_id,
          'product_name' => $this->input->post('product_name', true),
          'product_variant_id' => $this->input->post('product_variant_id', true),
          'product_url' => $this->input->post('product_url', true),
          'product_price' => $this->input->post('product_price', true),
          'product_image' => $this->input->post('product_image', true),
          'cust_id' =>  $cust_id == ''? '0' : $cust_id ,
          'cust_name' => $cust_name == '' ? 'Guest' : $cust_name,
          'action_type' => '1',
          'created_at' => date("Y-m-d H:i:s"),
        );
        if (empty($formdata)) {
            $error = array("code" => 403,"msg" => "Form data is empty");
        }else{
            $data = $this->Home_model->addToWishlist($formdata);
            echo json_encode($data);
            exit();
        }
    } //end else
  } //end function

    public function removeFromWishlist()
    {
     $shop = $_GET['shop'];
     $cust_id = $this->input->post('cust_id', true);
     $product_id = $this->input->post('product_id', true);
     $data = $this->Home_model->removeFromWishlist($shop,$cust_id,$product_id);
     if (!isset($data)) {
         $error = array("code" => 403,"msg" => "Error");
         echo json_encode($error);
         exit;
     }else{
         echo json_encode($data);
         exit();
     }

    }

    public function addToCart()
    {
     $shop = $_GET['shop'];
     $cust_id = $this->input->post('cust_id', true);
     $product_variant_id = $this->input->post('product_variant_id', true);
     $data = $this->Home_model->addToCart($shop,$cust_id,$product_variant_id);
     if (!isset($data)) {
         $error = array("code" => 403,"msg" => "Error");
         echo json_encode($error);
         exit;
     }else{
         echo json_encode($data);
         exit();
     }

    }











}
