<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
class Home extends CI_Controller {
  public function __construct() {
    parent::__construct(); //Do your magic here
    $this->load->database();
    $this->load->helper('url');
    $this->load->helper('global_helper');
    $this->load->helper('date');
    $this->load->model('Home_model');
    // $this->load->library('input');
  }

  public function getThemes($shop) {
    if(!isset($shop) || $shop == '') {
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

  public function check() {
    $msg = $this->load->view('some_view', '', true);
    echo $msg;
  }

  public function getProductPage($themeId,$shop) {
    $shop_id = $this->Home_model->GetShopId($shop);
    $plan_id = $this->Home_model->UpdatePlanIdForSetting($shop_id);
    $data = array();
    $data['setting'] =  $this->Home_model->getsetting($shop_id);
    $setting = $data['setting'];

    $icons = array(
      'fa fa-star' => array('regular' => 'f006', 'solid' => 'f005'),
      'fa fa-heart' => array('regular' => 'f08a', 'solid' => 'f004'),
      'fa fa-bookmark' => array('regular' => 'f097', 'solid' => 'f02e'),
    );


if($setting->enable_wishlist == 0)
{
  $content_html_value = '';
}
else{
    $content_html_value = '<!--  vw wish list  -->
<div>
<script src="https://use.fontawesome.com/2d26e67c24.js"></script>
<style>
/* wish-list code */

.vw-whishlist {
  position: absolute;
  right: 15px;
  z-index: 9999;
  text-align: center;
  min-width: 0px;
  max-width: 60px;
  vertical-align: baseline;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -ms-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  outline: none;
  display: inline-block;
  height: auto;
  overflow: visible;
}
.vw-whishlist .icon {
  width: 34px;
  height: 34px;
}
.vw-whishlist .icon:after {
  clear: both;
  display: block;
  width: 100%;
  content: \' \';
}
button.vw-productwish-list {
  background: none;
  border: none;
  outline: none;
  color: #'.$setting->btnbckcolr.';
  font-family: FontAwesome;
}
button.vw-productwish-list:after{
  content: "'.'\\'.$icons[$setting->btnIcon]['regular'].'";
  font-size: 25px;
}
.vwproduct-card {
  position: relative;
}
button.vw-productwish-list.active
{
pointer-events: none;
}
button.vw-productwish-list.active:after{
content: "'.'\\'.$icons[$setting->btnIcon]['solid'].'";
}

.vw-whishlist-product-page {
padding: 15px 0;
}
.vw-productpagewishlist {
background: none;
padding: 8px 25px;
border: 1px solid #000;
font-weight: 600;
}
.wish-list-icon {
font-size: 18px;
color: #ff0a0a;
}


.vw-wish-name {
  background: #'.$setting->btnbckcolr.';
  border: none;
  outline: none;
  color: #'.$setting->btntxtcolr.';
  font-family: FontAwesome;
  padding: 10px 35px;
  text-transform: uppercase;
  font-size: 15px;
  letter-spacing: 1px;
}
.vw-wish-name:before {
  content: "'.'\\'.$icons[$setting->btnIcon]['regular'].' '.$setting->btnTxtBeforeAdding.'";
}
.vw-wish-active{
  pointer-events: none;
}

.vw-wish-name.vw-wish-active:before{
  content: "'.'\\'.$icons[$setting->btnIcon]['solid'].' '.$setting->btnTxtAfterAdding.'";
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
  right: 0;
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
#vwmodal {
  position: fixed;
  top: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.8);
  z-index: 99999;
  height: 100%;
  width: 100%;
}
#vwmodal .modalconent {
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
.deactive{
  pointer-events : none;
  opacity : 0.4;
}
</style>

<div class="my-wish-open" {% unless customer %} title="You must logged in first!"{% endunless %}>
  <div class="wish-list-counter">
    <div class="vw-wishlistcounter">
    {% unless customer %}
    <i class="fa fa-exclamation" aria-hidden="true"></i>
    {% endunless %}
    </div>
    <i class="'.$setting->btnIcon.'" aria-hidden="true"></i>
  </div>
</div>


<div id="vwmodal" style="display: none;">
  <div class="modalconent">
    <div class="wish-list-header">
      <div class="vw-pop-up-wrapper">
        <div class="vw-heading-close">
          <div class="vw-heading">
            <h3>'.$setting->callwish.'</h3>
          </div>
          <div class="vw-close">
          <a href="/cart" class="site-header__icon site-header__cart"  id="cart-number">
            <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-cart" viewBox="0 0 37 40"><path d="M36.5 34.8L33.3 8h-5.9C26.7 3.9 23 .8 18.5.8S10.3 3.9 9.6 8H3.7L.5 34.8c-.2 1.5.4 2.4.9 3 .5.5 1.4 1.2 3.1 1.2h28c1.3 0 2.4-.4 3.1-1.3.7-.7 1-1.8.9-2.9zm-18-30c2.2 0 4.1 1.4 4.7 3.2h-9.5c.7-1.9 2.6-3.2 4.8-3.2zM4.5 35l2.8-23h2.2v3c0 1.1.9 2 2 2s2-.9 2-2v-3h10v3c0 1.1.9 2 2 2s2-.9 2-2v-3h2.2l2.8 23h-28z"></path></svg>
            <span class="icon__fallback-text">Cart</span>
            <div id="CartCount" class="site-header__cart-count" data-cart-count-bubble="">
              <span data-cart-count="" id="item_count">{{cart.item_count}}</span>
              <span class="icon__fallback-text medium-up--hide">items</span>
            </div>
          </a>
            <a id="close" href="JavaScript:void(0)"> <button id="button" class="wv-close">&times;</button></a>
          </div>
        </div>
      </div>
    </div>
    <div class="wish-list-body">
      <div class="vw-pop-up-wrapper">
        <div class="vwGrid-container vw-flex-wrap" style="margin-bottom:1em">';
          if($plan_id == 1)
          {
          $content_html_value .= '<button type="submit" id="addAllToCart" onclick="addAllItems(\'{{shop.permanent_domain }}\',{{ customer.id }}); return false;" style="margin:10px">ADD ALL TO CART</button>
          <button type="button" id="removeAll" class="btn btn-warning" onclick="removeAll(\'{{shop.permanent_domain }}\',{{ customer.id }});return false;" style="margin:10px">Remove all</button>';
          }
          $content_html_value .= '<div class="vwGrid-container vw-flex-wrap" id="wishbody"></div>
        </div>
      </div>
    </div>

    <div class="wish-list-footer">
      <div class="vw-pop-up-wrapper">';
      if($plan_id == 1){
      $content_html_value .= '
        <div>
          <span class="vw-share">
          	<a class="shareBtn shareWhatsApp" title="Share on What\'sApp" data-action="share/whatsapp/share"><span><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 455.731 455.731" style="enable-background:new 0 0 455.731 455.731; width:40px;height:40px;" xml:space="preserve"><g><rect x="0" y="0" style="fill:#1BD741;" width="455.731" height="455.731"/><g><path style="fill:#FFFFFF;" d="M68.494,387.41l22.323-79.284c-14.355-24.387-21.913-52.134-21.913-80.638c0-87.765,71.402-159.167,159.167-159.167s159.166,71.402,159.166,159.167c0,87.765-71.401,159.167-159.166,159.167c-27.347,0-54.125-7-77.814-20.292L68.494,387.41z M154.437,337.406l4.872,2.975c20.654,12.609,44.432,19.274,68.762,19.274c72.877,0,132.166-59.29,132.166-132.167S300.948,95.321,228.071,95.321S95.904,154.611,95.904,227.488c0,25.393,7.217,50.052,20.869,71.311l3.281,5.109l-12.855,45.658L154.437,337.406z"/><path style="fill:#FFFFFF;" d="M183.359,153.407l-10.328-0.563c-3.244-0.177-6.426,0.907-8.878,3.037c-5.007,4.348-13.013,12.754-15.472,23.708c-3.667,16.333,2,36.333,16.667,56.333c14.667,20,42,52,90.333,65.667c15.575,4.404,27.827,1.435,37.28-4.612c7.487-4.789,12.648-12.476,14.508-21.166l1.649-7.702c0.524-2.448-0.719-4.932-2.993-5.98l-34.905-16.089c-2.266-1.044-4.953-0.384-6.477,1.591l-13.703,17.764c-1.035,1.342-2.807,1.874-4.407,1.312c-9.384-3.298-40.818-16.463-58.066-49.687c-0.748-1.441-0.562-3.19,0.499-4.419l13.096-15.15c1.338-1.547,1.676-3.722,0.872-5.602l-15.046-35.201C187.187,154.774,185.392,153.518,183.359,153.407z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span></a>
            <a class="shareBtn shareFacebook"  title="Share on Facebook"><span><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 455.73 455.73" style="enable-background:new 0 0 455.73 455.73;width:40px;height:40px;" xml:space="preserve"><path style="fill:#3A559F;" d="M0,0v455.73h242.704V279.691h-59.33v-71.864h59.33v-60.353c0-43.893,35.582-79.475,79.475-79.475h62.025v64.622h-44.382c-13.947,0-25.254,11.307-25.254,25.254v49.953h68.521l-9.47,71.864h-59.051V455.73H455.73V0H0z"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span></a>
            <a class="shareBtn shareTwitter"  title="Share on Twitter"><span><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 455.731 455.731" style="enable-background:new 0 0 455.731 455.731;width:40px;height:40px;" xml:space="preserve"><g><rect x="0" y="0" style="fill:#50ABF1;" width="455.731" height="455.731"/><path style="fill:#FFFFFF;" d="M60.377,337.822c30.33,19.236,66.308,30.368,104.875,30.368c108.349,0,196.18-87.841,196.18-196.18c0-2.705-0.057-5.39-0.161-8.067c3.919-3.084,28.157-22.511,34.098-35c0,0-19.683,8.18-38.947,10.107c-0.038,0-0.085,0.009-0.123,0.009c0,0,0.038-0.019,0.104-0.066c1.775-1.186,26.591-18.079,29.951-38.207c0,0-13.922,7.431-33.415,13.932c-3.227,1.072-6.605,2.126-10.088,3.103c-12.565-13.41-30.425-21.78-50.25-21.78c-38.027,0-68.841,30.805-68.841,68.803c0,5.362,0.617,10.581,1.784,15.592c-5.314-0.218-86.237-4.755-141.289-71.423c0,0-32.902,44.917,19.607,91.105c0,0-15.962-0.636-29.733-8.864c0,0-5.058,54.416,54.407,68.329c0,0-11.701,4.432-30.368,1.272c0,0,10.439,43.968,63.271,48.077c0,0-41.777,37.74-101.081,28.885L60.377,337.822z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span></a>
            <a class="shareBtn shareTelegram"  title="Share on Telegram"><span><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 455.731 455.731" style="enable-background:new 0 0 455.731 455.731;width:40px;height:40px;" xml:space="preserve"><g><rect x="0" y="0" style="fill:#61A8DE;" width="455.731" height="455.731"/><path style="fill:#FFFFFF;" d="M358.844,100.6L54.091,219.359c-9.871,3.847-9.273,18.012,0.888,21.012l77.441,22.868l28.901,91.706c3.019,9.579,15.158,12.483,22.185,5.308l40.039-40.882l78.56,57.665c9.614,7.057,23.306,1.814,25.747-9.859l52.031-248.76C382.431,106.232,370.443,96.08,358.844,100.6z M320.636,155.806L179.08,280.984c-1.411,1.248-2.309,2.975-2.519,4.847l-5.45,48.448c-0.178,1.58-2.389,1.789-2.861,0.271l-22.423-72.253c-1.027-3.308,0.312-6.892,3.255-8.717l167.163-103.676C320.089,147.518,324.025,152.81,320.636,155.806z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span></i></a>
            <a class="shareBtn shareMail"  title="Share on Mail"><span><svg id="Capa_1" enable-background="new 0 0 455.731 455.731" height="45px" viewBox="0 0 455.731 455.731" width="45px" xmlns="http://www.w3.org/2000/svg"><g><path d="m0 60v392l256-30v-332z" fill="#14cfff"/><path d="m512 60-286 30v332l286 30z" fill="#28abfa"/><path d="m512 437c0-20.032-7.801-38.867-21.967-53.033l-181-181c-14.166-14.166-33-21.967-53.033-21.967l-60 135.5 60 135.5h256z" fill="#14cfff"/><path d="m202.967 202.967-181 181c-14.166 14.166-21.967 33.001-21.967 53.033v15h256v-271c-20.033 0-38.867 7.801-53.033 21.967z" fill="#4fdbff"/><path d="m256 60-60 135.5 60 135.5c20.033 0 38.867-7.801 53.033-21.967l181-181c14.166-14.166 21.967-33.001 21.967-53.033v-15z" fill="#4fdbff"/><path d="m0 60v15c0 20.032 7.801 38.867 21.967 53.033l181 181c14.166 14.166 33 21.967 53.033 21.967v-271z" fill="#8ae7ff"/><path d="m271 120h-15l-10 15 10 15h15z" fill="#3857bc"/><path d="m241 120h15v30h-15z" fill="#3a6fd8"/><path d="m301 120h30v30h-30z" fill="#3857bc"/><path d="m181 120h30v30h-30z" fill="#3a6fd8"/></g></svg></span></a>
          </span>
          </div>';
        }
          $content_html_value .= '
          <div style="margin-top:10px" >
            <input type="text" readonly value="https://{{ shop.permanent_domain }}/apps/vw-wishlist/share-wishlist" size="60" id="shareLinkInput">
            <button type="button" title="copy the link"  id="shareLinkBtn" class="btn">Copy</button>
          </div>
      </div>
    </div>
  </div>
</div>
<!--  wishlist popup-code ends   -->

<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
<script>

  {% unless customer %}
  {% if template contains \'product\' %}
    var wishbtncode = \'\';
  wishbtncode = \'<div class="vw-whishlist-product-page"> <a class="vw-wish-name" id="submitForm" href="https://{{ shop.domain }}/account" style="color:white"></a> </div>\';
  $(\'form[action="/cart/add"]\').append(wishbtncode);
  {% endif %}
  {% endunless %}

  {% if customer %}
  	var cust_id = \'{{ customer.id }}\';
  	var cust_name = \'{{ customer.name }}\';
    var shop = \'{{ shop.permanent_domain }}\';
  appendbtn();
  function appendbtn() {
	var collection= [];
	collection = document.querySelectorAll(\'a[href*="/collections/"][href*="/products/"]\');
    for(var k = 0; k < collection.length; k++) {
      var currentCollection = collection[k];
      callAjax(currentCollection);
    }

  }
  window.CheckNumber = 0;
  $checknterval = setInterval(CheckForButtonAdded, 2000);
  function CheckForButtonAdded() {
    var collection = document.querySelectorAll(\'a[href*="/collections/"][href*="/products/"]\');
    if(window.CheckNumber == collection.length){
      clearInterval($checknterval);
      checklist();
    }
  }

  function callAjax(currentCollection) {
    var url = currentCollection.href+\'.json\';
    return $.ajax({
      url : url,
      type: "GET",
      dataType: "json",
      success: function(data) {
        var img = "";
        if(data.product.images <= 0){
          img = "'.base_url().'assets/img/no-image.gif";
        }else{
          img = data.product.images[0].src;
          }
        var wishbtncolltn = \'<div class="vw-whishlist"><span class="icon"><button class="vw-productwish-list add-to-list" type="button" data-vw-shop="\'+ shop +\'" data-vw-product_id="\' + data.product.id + \'" data-vw-product_variant_id="\'+ data.product.variants[0].id +\'" data-vw-product_name="\' + data.product.title + \'" data-vw-product_url="/products/\' + data.product.handle + \'" data-vw-product_price="\' + data.product.variants[0].price + \'" data-vw-product_image="\'+ img +\'" data-vw-cust_id="\'+ cust_id +\'" data-vw-cust_name="\'+ cust_name +\'" id="button_\'+ data.product.id +\'" onclick="addCollectionItem(\'+ data.product.id +\');return false;"></button> </span> </div>\';
        $(currentCollection).after(wishbtncolltn);
        window.CheckNumber = window.CheckNumber + 1;
        console.log(window.CheckNumber);
      }
    });
  }

  {% if template contains \'product\' %}
        countProductAdded();
        function countProductAdded()
        {
          var form = $(\'form[action="/cart/add"]\');
          var product_id = {{ product.id }};
  		$.ajax({
        url: "'.base_url().'Home/countProductAdded?shop={{ shop.permanent_domain }} ",
        type: "POST",
        dataType: "json",
            data: { cust_id : cust_id,product_id : product_id },
                 success: function(data) {
            var productAdded =  nFormatter(data[0].productAdded);
            var pro = "{{ product.title }}" ;
            var product_title = esc(pro);
            var wishbtncode = \'\';
            wishbtncode += \'<div class="vw-whishlist-product-page">\';
            wishbtncode += \'<button class="vw-wish-name add-to-list" type="button" data-vw-shop="{{ shop.permanent_domain }}" data-vw-product_id="{{ product.id }}" data-vw-product_variant_id = "{{ product.variants.first.id }}" data-vw-product_name="\' + product_title + \'" data-vw-product_url="{{ product.url }}" data-vw-product_price="{{ product.price | money }}" data-vw-product_image="{{ product | img_url }}" data-vw-cust_id="{{ customer.id }}" data-vw-cust_name="{{ customer.name }}" id="button_{{ product.id }}" onclick="addCollectionItem({{ product.id }});return false;"></button></span> </div>\';
  		      form.append(wishbtncode);
            $(".vw-wish-name").append(" (" + productAdded + ")");
          }
        });

        }

        function esc(str)
          {
           var stre= str.replace(/\'/g, "\\\'");
            return stre;
          }
        function nFormatter(num) {
           if (num >= 1000000000) {
              return (num / 1000000000).toFixed(1).replace(/\.0$/, "") + "G";
           }
           if (num >= 1000000) {
              return (num / 1000000).toFixed(1).replace(/\.0$/, "") + "M";
           }
           if (num >= 1000) {
              return (num / 1000).toFixed(1).replace(/\.0$/, "") + "K";
           }
           if (num >= 100) {
              return (num / 1000).toFixed(1).replace(/\.0$/, "") + "K";
           }

           return num;
        }

    {% endif %}
  function checklist() {
    {% if template contains \'product\' %}
      var product_id = $(".vw-wish-name").attr(\'data-vw-product_id\');
    {% endif %}

    var html = "";
    var wishbtncode="";
    return $.ajax({
      url: "'.base_url().'Home/checklist?shop={{ shop.permanent_domain }} ",
      type: "POST",
      dataType: "json",
      data: { cust_id : cust_id },
      success: function(data) {
        var wishlistCount = data.length;
      	$(".vw-wishlistcounter").text(wishlistCount);
      	if(data.length > 0){
          for (var i = 0; i < data.length; i++) {
            html += "<div class=\'vwGrid-grid-25\'>";
            html += "<div class=\'vwwish-list-box\'>";
            html += "<div class=\'vw-img-wrapper\'>";
            html += "<img src="+ data[i].product_image +" class=\'wish-listitem\' alt=\'wish-list\'>";
            html += "</div>";
            html += "<a href="+ data[i].product_url +"><h4 class=\'vw-product-title\'>"+ data[i].product_name +"</h4></a>";
            html += "<p class=\'vw-wish-list last-save-date\'>"+ data[i].created_at + "</p>";
            html += "<div class=\'vw-wishlist-product-price\'>";
            html += "<span>"+ data[i].product_price +"</span>";
            html += "</div>";
            html += "<button class=\'btn-prime addtocart\' type=\'button\' onclick=\'addItem(" + data[i].product_variant_id + \',\'+ data[i].product_id + ", 1); return false\' id=\'product_" + data[i].product_variant_id +"\'>'.$setting->btnTxtForCart.'</button>";
            html += "<button class=\'vw-remove-wishlidt removeFromWishlist\'  type=\'button\' onclick=\'removeFromWishlist("+ data[i].product_id +")\'><i class=\'fa fa-trash\' aria-hidden=\'true\'></i></button>";
            html += "</div>";
            html += "</div>";
            html += "</div>";

            //product is already added to list
            {% if template contains \'product\' %}
            if(product_id == data[i].product_id) {
              $(".vw-wish-name").addClass("vw-wish-active");
            } //if
            {% endif %}

            {% if template contains \'collection\' or template == \'index\' %}
            $("#button_"+data[i].product_id).addClass("active");
            {% endif %}
          } //for';

            if($plan_id == 1)
            {
          $content_html_value .= '
          $("#removeAll").removeAttr("disabled");
          $("#addAllToCart").removeAttr("disabled");';
        }
        $content_html_value .= '   } else {';

          if($plan_id == 1)
          {
        $content_html_value .= '  $("#removeAll").attr("disabled", true);
          $("#addAllToCart").attr("disabled", true);';
        }

        $content_html_value .= '  html += "<div><center><h3>'.$setting->textWhenNoItem.'<h3></center></div>";
        }
      	$("#wishbody").html(html);
      }, //success
  	});
  }
//===================checklist ends here===============

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // shake effect function (used for wishlist popup button while adding a item wishlist)
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function shake() {
   interval =  100 ;
   distance =  10 ;
   times = 3;
   var jTarget = $(".my-wish-open");
   for(var iter=0;iter<(times+1);iter++){
      jTarget.animate({ right: ((iter%2==0 ? distance : distance*-1))}, interval);
   }
   return jTarget.animate({ right: 0},interval);

}
// ============shake effect function ends here==========

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      // add item to wishlist
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  function addCollectionItem(product_id) {
    var wishbtn = $("#button_" + product_id);
    var shop = wishbtn.attr("data-vw-shop");
    var product_id = wishbtn.attr("data-vw-product_id");
    var product_variant_id = wishbtn.attr("data-vw-product_variant_id");
    var product_name = wishbtn.attr("data-vw-product_name");
    var product_url = wishbtn.attr("data-vw-product_url");
    var product_price = wishbtn.attr("data-vw-product_price");
    var product_image = wishbtn.attr("data-vw-product_image");
    var cust_id = wishbtn.attr("data-vw-cust_id");
    var cust_name = wishbtn.attr("data-vw-cust_name");
    var url = \''.base_url().'Home/addToWishlist?shop=\'+shop;
    $.ajax({
      url: url,
      type: "POST",
      dataType: "json",
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
      //beforeSend: function() {  },
      //complete: function() {  },
      success: function(data) {
        if(data.status == 0)
        {
          alert(data.mgs);
        }
        checklist();
        //wishbtn.addClass("active");
        shake();
      },
      error: function() {
        alert("Failed to item to wishlist! Please try again.");
      },
    });
  }//addCollectionItem ends
// =======================================================================

  function removeFromWishlist(product_id) {
    var wishbtn = $("#button_"+product_id);
    //alert(cust_id);alert(pr_id);
    if(product_id ==" " || cust_id ==" ") {
      alert("customer id or product id is not selelcted");
    }

    if(confirm(\' '.$setting->mgsForRemoveItem.'\')) {
      $.ajax({
        url : "'.base_url().'Home/removeFromWishlist?shop={{ shop.permanent_domain }} ",
        method : "POST",
        dataType: "json",
        data: {
      	  product_id: product_id,
      	  cust_id: cust_id,
        },
        success: function(data) {
          {% if template contains "product" %}
          wishbtn.removeClass("vw-wish-active");
          {% endif %}
          {% if template contains "collection"  or template == \'index\' %}
          wishbtn.removeClass("active");
          {% endif %}
          checklist();
          // alert("removed from list");
        },
        error: function(error) {
          alert("Error");
        }
      });
    }
  }
//remove from wishlist ends here
// +--------------------------------------------------------------------------------------
// + Share link starts here
// +---------------------------------------------------------------------------------------
$("#shareLinkBtn").on(\'click\', function () {
    var shareLinkInput = $(this);
    var originalText = shareLinkInput.text();

     var tpen  = window.btoa(Math.floor(Date.now() / 1000));
     var cust_iden = window.btoa(cust_id);
   var shareLink = \'https://{{ shop.permanent_domain }}/apps/vw-wishlist/share-wishlist?cust_id=\'+ cust_iden + \'&tp=\'+ tpen;
		console.log(shareLinkInput);
       // Create a dummy input to copy the string array inside it
       var dummy = document.createElement("input");
       // Add it to the document
       document.body.appendChild(dummy);
       // Set its ID
         dummy.setAttribute("id", "dummy_id");
       // Output the array into it
       $("#dummy_id").val(shareLink);
     dummy.select();
       document.execCommand("copy");

       //change text of button
      shareLinkInput.text(\'Copied!\');
      setTimeout(function() {
        shareLinkInput.text(originalText)
      }, 5000);

});';
if($plan_id == 1){
$content_html_value .= '
$(".shareBtn").on(\'click\', function () {
    var btn = $(this);
   var tpen  = window.btoa(Math.floor(Date.now() / 1000));
    var cust_iden = window.btoa(cust_id);
   	var shareLink = \'https://{{ shop.permanent_domain }}/apps/vw-wishlist/share-wishlist?cust_id=\'+ cust_iden + \'&tp=\'+ tpen;
    var shareLinkEncoded = encodeURIComponent(shareLink);
    if(btn.hasClass(\'shareWhatsApp\'))
    {
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 		window.open("whatsapp://send?text=" + shareLinkEncoded);
      }
      else{
        window.open("https://web.whatsapp.com/send?text=" + shareLinkEncoded);
      }
    }
    else if(btn.hasClass(\'shareFacebook\'))
    {
          window.open("https://www.facebook.com/sharer/sharer.php?u=" + shareLinkEncoded);
    }
    else if(btn.hasClass(\'shareTwitter\'))
    {
      window.open("https://twitter.com/intent/tweet?text=" + shareLinkEncoded);
    }
    else if(btn.hasClass(\'shareTelegram\'))
    {
      window.open("https://telegram.me/share/?url=" + shareLinkEncoded +"text=" + cust_name + "\'s Wishlist" );
    }
    else if(btn.hasClass(\'shareMail\'))
    {
      window.open("mailto:?subject="+ cust_name + "\'s Wishlist&body=Check out this link "+ shareLinkEncoded);
    }
});

// +--------------------------------------------------------------------------------------
// + Share link ends here
// +---------------------------------------------------------------------------------------

//adding item to function
  function addAllItems(shopP,cust_idP)
  {
    var wishbody = $(\'#wishbody\');
    var shop = shop != ""  ? shopP : shop;
    var cust_id = cust_idP != ""  ? cust_idP : cust_id;
        if(confirm(\'Do you really want to add all items to cart?\')) {
              wishbody.addClass(\'deactive\'); //disable the area
      $.ajax({
        url : "'.base_url().'Home/getAllProducts?shop={{ shop.permanent_domain }} ",
        method : "POST",
        dataType: "json",
        data: {
      	  cust_id: cust_id,
        },
        success: function(data) {
          if(data.code == 403)
          {
            checklist();
            alert(data.mgs);
          }else{
            var products = data;
//             console.log(data);
            Shopify.queue = [];
            var quantity = {{ cart.item_count }} ;
            var newArray = products;
            for (var i = 0; i < newArray.length; i++) {
              product = newArray[i]
              Shopify.queue.push({
                variantId: product,
              });
            } //for loop
            Shopify.moveAlong = function() {
	             // If we still have requests in the queue, let\'s process the next one.
              if (Shopify.queue.length) {
                var request = Shopify.queue.shift();
                var data = \'id=\'+ request.variantId + \'&quantity=1\';
                $.ajax({
                  type: \'POST\',
                      url: \'/cart/add.js\',
                  dataType: \'json\',
                  data: data,
                  success: function(res){
                    Shopify.moveAlong();
                    quantity += 1;

                    $.ajax({
                      url: "'.base_url().'Home/addToCart?shop={{ shop.permanent_domain }} ",
                      type: "POST",
                      dataType: "json",
                      data: {
                        shop : shop,
                        product_variant_id: request.variantId,
                        cust_id : cust_id,
                      } ,
                  //beforeSend: function() {  },
                  //complete: function() {  },
                  success: function(data) {
                    if(data.status == 0)
                    {
                      alert(data.mgs);
                    }
                  	checklist();
                  },
                  error: function() {
                    alert("Failed to item to wishlist! Please try again.");
                  },
                });
                 },
                     error: function(){
                 // if it\'s not last one Move Along else update the cart number with the current quantity
                  if (Shopify.queue.length){
                    Shopify.moveAlong();
                  } else {
                    $(\'#item_count\').text(quantity);
                  }
                  }
                   });

                    if(Shopify.queue.length == 1)
                    {
                      console.log(Shopify.queue.length);
                        wishbody.removeClass(\'deactive\');
                    	alert("Your items are added to cart. Some items might not added to cart because they may already sold out or present in your cart.");

                    }
                }
             // If the queue is empty, we add 1 to cart
            else {
              quantity += 1;
              addToCartOk(quantity);
             }
               };
            Shopify.moveAlong();
          }//else ends
        },
        error: function(error) {
          alert("Error!");
        }
      });
    }
  }
  function addToCartOk(quantity){
	$(\'#item_count\').text(quantity);
}
//remove all from wishitems
function removeAll(shopP,cust_idP)
  {
    {% if template contains "product" %}
    var wishbtn = $("button.vw-wish-active");
    {% endif %}
    {% if template contains "collection"  or template == \'index\' %}
    var wishbtn = $("button.active");
    {% endif %}
    var shop = shop != ""  ? shopP : shop;
    var cust_id = cust_idP != ""  ? cust_idP : cust_id;

    if(confirm(\' Are you sure you want to remove all wishlist item?\')) {
      $.ajax({
        url : "'.base_url().'Home/removeAll?shop={{ shop.permanent_domain }} ",
        method : "POST",
        dataType: "json",
        data: {
      	  cust_id: cust_id,
        },
        success: function(data) {
        if(data.code == 403)
        {
          checklist();
          alert(data.mgs);
        }else{
          {% if template contains "product" %}
          		wishbtn.removeClass("vw-wish-active");
          {% endif %}
          {% if template contains "collection"  or template == \'index\' %}
          		wishbtn.removeClass("active");
          {% endif %}
          checklist();
          alert("All wishlist item are removed from list.");
        } //ends else

        },
        error: function(error) {
          alert("Error!");
        }
      });
    }
  }';

}
$content_html_value .= '
//adding item to function
// -------------------------------------------------------------------------------------
// POST to cart/add.js returns the JSON of the line item associated with the added item.
// -------------------------------------------------------------------------------------

  function addItem(variant_id,product_id,quantity, callback) {
    var wishbtn1 = $("#button_" + product_id);
    var quantity = quantity || 1;
    $("p[title =\'Tomorrow\']");
    var wishbtn = $("button[data-vw-product_variant_id = \'+ variant_id +\']");
    //alert(wishbtn);
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
        } else {
          $.ajax({
            url : "'.base_url().'Home/addToCart?shop={{ shop.permanent_domain }}",
            method : "POST",
            dataType: "json",
            data: {
              product_variant_id : variant_id,
              cust_id : cust_id,
            },
            success: function(data) {
          	  console.log(data);
              {% if template contains "product" %}
              wishbtn1.removeClass("vw-wish-active");
              {% endif %}
              {% if template contains "collection"  or template == \'index\' %}
              wishbtn1.removeClass("active");
              {% endif %}

            //   Shopify.onItemAdded(line_item);
            alert(line_item.title + " was added to your shopping cart.");
              checklist();
		    },
            error: function(error) {
              alert("Error Updated Status");
            }
          });
        }  //else ends here
      }, //success
      error: function(XMLHttpRequest, textStatus) {
        Shopify.onError = function(XMLHttpRequest, textStatus) {
          // Shopify returns a description of the error in XMLHttpRequest.responseText.
          // It is JSON.
          // Example: {"description":"The product "Amelia - Small" is already sold out.","status":500,"message":"Cart Error"}
          var data = eval("(" + XMLHttpRequest.responseText + ")");
          alert(data.message + "(" + data.status  + "): " + data.description);
        };
        Shopify.onError(XMLHttpRequest, textStatus);
      }
    };
  jQuery.ajax(params);
  };
//===================
//remove from wishlist

  $(".my-wish-open").click(function(){
  	$("#vwmodal").show().fadeIn("slow");
  	$("body").addClass("vwmodal-open");
  });

  $("#close, #not_now").click(function(){
    $("#vwmodal").hide();
    $("body").removeClass("vwmodal-open");
  });
  {% endif %}
</script>';
}
  $content_html = array(
    "asset" => array(
      "key" => "snippets/wish-list-popup.liquid",
      "value" =>  $content_html_value
    )
  );

  $putForm = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/2020-01/themes/' . $themeId . '/assets.json', 'DATA' => $content_html], TRUE);

  $include_file = "{% include 'wish-list-popup' %}";

  $getThemeLiquid = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/2020-01/themes/' . $themeId . '/assets.json?asset[key]=layout/theme.liquid'], TRUE);

  $theme_liquid = $getThemeLiquid->asset->value;

  if (strpos($theme_liquid, $include_file) === false) {
    $bodytagpos = strpos($theme_liquid, '</body>');
    $new_theme_str = substr_replace($theme_liquid, $include_file, $bodytagpos, 0);

    // Taking backup of original theme.liquid file
    $backup_html = array(
      "asset" => array(
        "key" => "layout/VW_WISHLIST_BKP_theme.liquid",
        "value" =>  $theme_liquid
      )
    );

    $backup_theme_liquid = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/2020-01/themes/' . $themeId . '/assets.json', 'DATA' => $backup_html], TRUE);

    // Including our snippet in theme.liquid file
    $new_html = array(
      "asset" => array(
        "key" => "layout/theme.liquid",
        "value" => $new_theme_str
      )
    );

    $include_snippet_liquid = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/2020-01/themes/' . $themeId . '/assets.json', 'DATA' => $new_html], TRUE);

  }

}
public function getShopId($shop)
{
  if($shop!=''){
    $shop_id = $this->Home_model->GetShopId($shop);
    if (!empty($shop_id)) {
      return $shop_id;
    }else{
      $this->load->view('errors/html/error_404');
    }
  }else{
    $this->load->view('errors/html/error_404');
  }
}
    public function dashboard()
    {
        if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
        $data = array();
        $data['wishlistCounts'] = $this->Home_model->wishlistcounts($shop_id);
        $data['topCustomers'] = $this->Home_model->topCustomers($shop_id);
        $data['topProducts'] = $this->Home_model->topProducts($shop_id);
        $this->load->load_admin('dashboard', $data);
        }else{
          echo json_encode(array('code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id));
        }
      }else{
          $this->load->view('errors/html/error_404');
      }
    }

    public function wishgraph()
    {
      if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      $isValid = IsValidRequest();
      if($isValid['code'] == 200){
          if($shop_id != ''){
            $data = array();
            $data = $this->Home_model->wishgraph($shop_id);
            json_send($data);
          }else{
            json_send(['code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id]);
          }
        }else{
          json_send($isValid);
        }
      }else{
        $this->load->view('errors/html/error_404');
      }
    }

    public function getListInRange()
    {
      if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $isValid = IsValidRequest();
        if($isValid['code'] == 200){
          $shop_id = $this->Home_model->GetShopId($shop);
          if($shop_id != ''){
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            if(isset($start_date) && isset($end_date)){
              $data = $this->Home_model->getListInRange($start_date,$end_date,$shop_id);
            }
            if(isset($data)){
              json_send($data);
            }else {
              json_send(["code"=> 403, 'msg'=>'Server Error']);
            }
          }else{
            json_send(['code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id]);
          }
        }else{
          json_send($isValid);
        }
      }else{
        $this->load->view('errors/html/error_404');
      }
    }

    public function activity()
    {
        $this->load->load_admin('activity');
    }
    public function settings()
    {
      if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
        $data = array();
        $data['getsetting'] = $this->Home_model->getsetting($shop_id);
        $this->load->load_admin('setting',$data);
      }else{
        echo json_encode(array('code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id));
      }
      }else{
        $this->load->view('errors/html/error_404');
      }
    }
    public function savesetting() {
      if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){

        $plan_id =  $this->db->select('plan_id')->where('id', $shop_id)->get('shopify_stores')->row()->plan_id;
        if($plan_id == 1){
          $isValid = IsValidRequest();
          if($isValid['code'] == 200){
          $data = array(
            'shop'                    => $shop,
            'callwish'                => $this->input->post('callwish'),
            'btntxtcolr'              => $this->input->post('btntxtcolr'),
            'btnbckcolr'              => $this->input->post('btnbckcolr'),
            'btnTxtBeforeAdding'      => $this->input->post('btnTxtBeforeAdding'),
            'btnTxtAfterAdding'       => $this->input->post('btnTxtAfterAdding'),
            'btnIcon'                 => $this->input->post('btnIcon'),
            'removeItemFromWishlist'  => $this->input->post('removeItemFromWishlist'),
            'btnTxtForCart'           => $this->input->post('btnTxtForCart'),
            'textWhenNoItem'          => $this->input->post('textWhenNoItem'),
            'mgsForRemoveItem'        => $this->input->post('mgsForRemoveItem'),
            'email_subscr'            => $this->input->post('email_subscr'),
            'enable_email'            => $this->input->post('enable_email'),
            'host_name'               => $this->input->post('host_name'),
            'port_number'             => $this->input->post('port_number'),
            'sender_name'             => $this->input->post('sender_name'),
            'sender_email'            => $this->input->post('sender_email'),
            'password'                => $this->input->post('password'),
            'logoUrl'                 => $this->input->post('logoUrl'),
            'since_date'              => $this->input->post('since_date'),
            'email_template_remainder'=> $this->input->post('email_template_remainder'),
            'sub_remainder'           => $this->input->post('sub_remainder'),
            'greetingText_remainder'  => $this->input->post('greetingText_remainder'),
            'emailText_remainder'     => $this->input->post('emailText_remainder'),
            'sub_sale'                => $this->input->post('sub_sale'),
            'email_template_sale'     => $this->input->post('email_template_sale'),
            'sub_sale'                => $this->input->post('sub_sale'),
            'greetingText_sale'       => $this->input->post('greetingText_sale'),
            'emailText_sale'          => $this->input->post('emailText_sale'),
          );

          //setting up email
          if($data['enable_email'] !== '' || $data['enable_email'] !== NULL){
             if($data['email_subscr'] == '1 DAY'){
                $data['nextMailSendOn'] = date("Y-m-d", strtotime('+1 day'));
             }elseif($data['email_subscr'] == '7 DAY'){
                $data['nextMailSendOn'] = date("Y-m-d", strtotime('+7 day'));
             }elseif($data['email_subscr'] == '30 DAY'){
                $data['nextMailSendOn'] = date("Y-m-d", strtotime('+30 day'));
             }
          }else{
             $data['nextMailSendOn'] = NULL;
          }
          $ok = $this->Home_model->savesetting($data,$shop_id);
          $this->getThemes($shop);
          if(isset($ok)){
            $this->settings();
          }
          echo json_encode($ok);
          exit;
        }else{
            echo json_encode($isValid);
            exit;
          }

        }elseif ($plan_id == 0) {

          $isValid = IsValidRequest();
          if($isValid['code'] == 200){
              $data = array(
                'shop'                    => $shop,
                'callwish'                => $this->input->post('callwish'),
                'btntxtcolr'              => $this->input->post('btntxtcolr'),
                'btnbckcolr'              => $this->input->post('btnbckcolr'),
                'btnTxtBeforeAdding'      => $this->input->post('btnTxtBeforeAdding'),
                'btnTxtAfterAdding'       => $this->input->post('btnTxtAfterAdding'),
                'btnIcon'                 => $this->input->post('btnIcon'),
                'btnTxtForCart'           => $this->input->post('btnTxtForCart'),
                'textWhenNoItem'          => $this->input->post('textWhenNoItem'),
                'mgsForRemoveItem'        => $this->input->post('mgsForRemoveItem'),
              );
              $ok = $this->Home_model->savesetting($data,$shop_id);
              $this->getThemes($shop);
              if(isset($ok)){
                $this->settings();
              }
              echo json_encode($ok);
              exit;
            }else{
              echo json_encode($isValid);
              exit;
            }
        }else{
          echo json_encode(array('code' =>100 , 'msg'=> 'Unauthorise access!'));
        }
      }else{
        echo json_encode(array('code'=>100,'msg'=>'Shop does not exists!'));
      }
    }else{
      $this->load->view('errors/html/error_404');
    }
  }

  public function enableWishlist()
  {
    if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
        $isValid = IsValidRequest();
        if($isValid['code'] == 200){
          $data = ['shop_id'=> $shop_id,'enable_wishlist' => $this->input->post('enable')];
          $setting = $this->Home_model->enableWishlist($data);
          if(isset($setting)){
            $this->getThemes($shop);
          }
          echo json_encode(['code'=>200,'msg'=>'Status Updated']);
          exit;
        }else{
          echo json_encode($isValid);
          exit;
        }
      }else{
        echo json_encode(['code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id]);
      }
    }else{
      $this->load->view('errors/html/error_404');
    }
  }

  public function getWishlistSetting()
  {
    if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
      $setting = $this->Home_model->getWishlistSetting($shop_id);
      if(isset($setting))
      {
        $data = (int)$setting->enable_wishlist;
      }
      echo json_encode($data);
      exit;
    }else{
      echo json_encode(array('code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id));
    }
    }else{
      $this->load->view('errors/html/error_404');
    }
  }
    // fetch data for activity data
    public function fetchactivitydata()
    {
      if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){

      $isValid = IsValidRequest();
      if($isValid['code'] == 200){

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $is_date_search = $this->input->post("is_date_search");
        $col = 0;
        $dir = "";
          if(!empty($order)){
              foreach($order as $o){
                  $col = $o['column'];
                  $dir= $o['dir'];
              }
          }
            if($dir != "asc" && $dir != "desc"){
                $dir = "desc";
            }
            $valid_columns = ['product_id','product_name','cust_name','cust_id'];
            if(!isset($valid_columns[$col])){
                $order = null;
            }else{
                $order = $valid_columns[$col];
            }
            if($order !=null){
                $this->db->order_by($order, $dir);
            }

            if($is_date_search =='yes'){
              $start_date = $this->input->post('start_date');
              $end_date = $this->input->post('end_date');
              $added_to_list = $this->input->post('added_to_list');
              $removed_from_list = $this->input->post('removed_from_list');
              $new_list = $this->input->post('new_list');
              $added_to_cart = $this->input->post('added_to_cart');
              if(!empty($search)){
                $query = "SELECT * FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' ";
                if($start_date !== '' && $end_date !== ''){
                  $query .="AND `updated_at` >= '".$start_date."' AND `updated_at` <= '".$end_date."'   ";
                }
                if($added_to_list !== '' && $added_to_list == 1){
                  $query .="and action_type='".$added_to_list."' ";
                }
                if($removed_from_list !== '' && $removed_from_list == 2){
                  $query .="and action_type='".$removed_from_list."' ";
                }
                if($new_list !== '' && $new_list == 3){
                  $query .="and action_type='".$new_list."' ";
                }
                if($added_to_cart !== '' && $added_to_cart == 4){
                  $query .="and action_type='".$added_to_cart."' ";
                }
              }else{
                $query = "SELECT * FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND ";
                if($start_date !== '' && $end_date !== ''){
                  $query .=" `updated_at` >= '".$start_date."' AND `updated_at` <= '".$end_date."'   ";
                }
                if($start_date !== '' && $end_date !== '' && ($added_to_list !== '' || $removed_from_list !== '' || $new_list !== '' || $added_to_cart !== '')){
                  $query .="AND ";
                }

                if($added_to_list !== '' && $added_to_list == 1){
                  $query .=" `action_type` = '".$added_to_list."' ";
                }

                if(($removed_from_list !== '' && $added_to_list !== '')){
                  $query .="or ";
                }

                if($removed_from_list !== '' && $removed_from_list == 2){
                  $query .=" `action_type` = ' ".$removed_from_list."' ";
                }

                if( ($new_list !== '' && $added_to_list !== '')  || ($new_list !== '' && $removed_from_list !== '')){
                  $query .="or ";
                }

                if($new_list !== '' && $new_list == 3){
                  $query .="action_type='".$new_list."' ";
                }

                if(($added_to_cart !== '' && $new_list !== '') || ($added_to_cart !== '' && $added_to_list !== '') ||($added_to_cart !== '' && $removed_from_list !== '')){
                  $query .="or ";
                }

                if($added_to_cart !== '' && $added_to_cart == 4){
                  $query .="action_type='".$added_to_cart."' ";
                }
              }
              $query .= 'order by updated_at desc';
              $query .= ' '.'LIMIT '.$start.','.$length.' ';
              $activities = $this->Home_model->fetchActivity($query);
            }elseif($is_date_search =='no') {
              if(!empty($search)){
                $query = "SELECT * FROM `wishlist_tbl` WHERE `shop_id` = '".$shop_id."' AND product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' ";
              }else{
                $query ='SELECT * FROM `wishlist_tbl` WHERE `shop_id` = "'.$shop_id.'" ';
              }
              $query .= 'order by updated_at desc';
              $query .= ' '.'LIMIT '.$length.' OFFSET '.$start.' ';
              $activities = $this->Home_model->fetchActivity($query);
            }

      $data = array();
      foreach($activities as $row){
            $sub_array = array();
            $sub_array[] = ucfirst($row->cust_name);
            $sub_array[] = '<a href="https://'.$shop.''.$row->product_url.'">'.$row->product_name.'</a>';
            $sub_array[] = $this->timeElapsed($row->updated_at);
            $sub_array[] = $this->action_type($row->action_type);
            $sub_array[] = '<a href="'.base_url().'Home/getWishlist?shop='.$_GET['shop'].'&cust_id='.$row->cust_id.'" target="_blank">View wishlist <img src="https://static.thenounproject.com/png/1381335-200.png" style="width: 20px; height: 20px"></a>';
            $data[] = $sub_array;
      }
      $totalCount = $this->totalCount($shop_id);
      $output = ["draw" => $draw,"recordsTotal" => $totalCount,"recordsFiltered" => $totalCount,"data" => $data];
    }else{
      $output = ["draw" =>0,"recordsTotal" =>0,"recordsFiltered" =>0,"data" =>[],"code"=> $isValid['code'],"msg"=>$isValid['msg']];
    }
    echo json_encode($output);
    exit();
    }else{
      echo json_encode(array('code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id));
        exit();
    }
    }else{
      $this->load->view('errors/html/error_404');
    }
}


// ------------------------------------------------------------------------------------------------------

// fetch data for customers data
public function fetchProductsdata()
{
  if ($_GET['shop']!='') {
  $shop = $_GET['shop'];
  $shop_id = $this->Home_model->GetShopId($shop);
    if($shop_id != ''){
      $isValid = IsValidRequest();
      if($isValid['code'] == 200){

    $draw = intval($this->input->post("draw"));
    $start = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $order = $this->input->post("order");
    $search= $this->input->post("search");
    $search = $search['value'];
    $is_date_search = $this->input->post("is_date_search");
    $col = 0;
    $dir = "";
    if(!empty($order)){
        foreach($order as $o){
            $col = $o['column'];
            $dir= $o['dir'];
        }
    }

    if($dir != "asc" && $dir != "desc"){
        $dir = "desc";
    }

    $valid_columns = ['product_id','product_name','cust_name','cust_id'];

    if(!isset($valid_columns[$col])){
        $order = null;
    }else{
        $order = $valid_columns[$col];
    }

    if($order !=null){
        $this->db->order_by($order, $dir);
    }

    if($is_date_search =='yes'){
      $start_date = $this->input->post('start_date');
      $end_date = $this->input->post('end_date');
      if(!empty($search)){
        $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND  product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' AND `updated_at` >= '".$start_date."' AND `updated_at` <= '".$end_date."' GROUP BY product_name,product_id,product_image ";
      }else{
        $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND  `updated_at` >= '".$start_date." 00:00:00' AND `updated_at` <= '".$end_date." 23:59:59' GROUP BY product_name,product_id,product_image ";
      }
      $query .= ' '.'LIMIT '.$length.' OFFSET '.$start.' ';
      $products = $this->Home_model->fetchUniqueProducts($query);
    }elseif($is_date_search =='no') {
      if(!empty($search)){
        $query = "SELECT product_id,product_name,product_image, count(product_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND   product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' GROUP BY product_name,product_id,product_image ";
      }else{
        $query ='SELECT product_id,product_name,product_image, count(product_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl  WHERE `shop_id` = "'.$shop_id.'" GROUP BY product_name,product_id,product_image';
      }
      $query .= ' '.'LIMIT '.$length.' OFFSET '.$start.' ';
      $products = $this->Home_model->fetchUniqueProducts($query);
    }

    $data = array();
    foreach($products as $row){
          $product_img= $row->product_image == ''  ? base_url('assets/img/no-image.gif'):$row->product_image ;
          $sub_array = array();
          $sub_array[] = '<img src="'.$product_img .'" alt="'.$row->product_name.'" style="width:51px;height:60px"/>'." ".ucfirst($row->product_name);
          $sub_array[] = $row->items;
          $sub_array[] = $this->timeElapsed($row->mindate);
          $sub_array[] = $this->timeElapsed($row->maxdate);
          $sub_array[] = '<a href="https://'.$shop.'/admin/products/'.$row->product_id.'" target="_blank">View on store</a>';
          $data[] = $sub_array;
    }
    $totalCount = $this->totalCount($shop_id);
    $output = ["draw" => $draw,"recordsTotal" => $totalCount,"recordsFiltered" => $totalCount,"data" => $data];
  }else{
    $output = ["draw" =>0,"recordsTotal" =>0,"recordsFiltered" =>0,"data" =>[],"code"=> $isValid['code'],"msg"=>$isValid['msg']];
  }
    echo json_encode($output);
    exit();
  }else{
      echo json_encode(array('code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id));
    }
  }else{
    $this->load->view('errors/html/error_404');
  }
}


// ==========================================================================================================================
public function fetchCustomersdata()
{
  if ($_GET['shop']!='') {
  $shop = $_GET['shop'];
  $shop_id = $this->Home_model->GetShopId($shop);
    if($shop_id != ''){

      $isValid = IsValidRequest();
      if($isValid['code'] == 200){
          $draw = intval($this->input->post("draw"));
          $start = intval($this->input->post("start"));
          $length = intval($this->input->post("length"));
          $order = $this->input->post("order");
          $search= $this->input->post("search");
          $search = $search['value'];
          $is_date_search = $this->input->post("is_date_search");
          $col = 0;
          $dir = "";
          if(!empty($order)){
              foreach($order as $o){
                  $col = $o['column'];
                  $dir= $o['dir'];
              }
          }

          if($dir != "asc" && $dir != "desc"){
              $dir = "desc";
          }

          $valid_columns =['cust_id','cust_name','product_name','product_id'];

          if(!isset($valid_columns[$col])){
              $order = null;
          }else{
              $order = $valid_columns[$col];
          }

          if($order !=null){
              $this->db->order_by($order, $dir);
          }


          if($is_date_search =='yes'){
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            if(!empty($search)){
              $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl WHERE  `shop_id` = '".$shop_id."' AND    product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' AND `updated_at` >= '".$start_date."' AND `updated_at` >= '".$start_date."' GROUP BY cust_id,cust_name ";
            }else{
              $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND   `updated_at` >= '".$start_date." 00:00:00' AND `updated_at` <= '".$end_date." 23:59:59' GROUP BY cust_id,cust_name ";
            }
          }elseif($is_date_search =='no') {
            if(!empty($search)){
              $query = "SELECT cust_id,cust_name, count(cust_id) AS items,max(updated_at) as maxdate,min(updated_at) as mindate FROM wishlist_tbl WHERE `shop_id` = '".$shop_id."' AND    product_name LIKE '%".$search."%' or product_id LIKE '%".$search."%' or cust_id LIKE '%".$search."%' or  cust_name LIKE '%".$search."%' GROUP BY cust_id,cust_name ";
            }else{
              $query ='SELECT cust_id,cust_name, count(cust_id) AS items,max(updated_at) as maxdate, min(updated_at) as mindate FROM wishlist_tbl WHERE `shop_id` = "'.$shop_id.'" GROUP BY cust_id,cust_name';
            }
          }

          $query .= ' '.'LIMIT '.$length.' OFFSET '.$start.' ';
          $customers = $this->Home_model->fetchUniqueCustomers($query);
          $data = array();
          foreach($customers as $row){
                $sub_array = array();
                $sub_array[] = ucfirst($row->cust_name);
                $sub_array[] = $row->items;
                $sub_array[] = $this->timeElapsed($row->mindate);
                $sub_array[] = $this->timeElapsed($row->maxdate);
                $sub_array[] = '<a href="'.base_url().'Home/getWishlist?shop='.$_GET['shop'].'&cust_id='.$row->cust_id.'" target="_blank">View wishlist <img src="https://static.thenounproject.com/png/1381335-200.png" style="width: 20px; height: 20px"></a>';
                $data[] = $sub_array;
          }
          $totalCount = $this->totalCount($shop_id);
          $output = ["draw" => $draw,"recordsTotal" => $totalCount,"recordsFiltered" => $totalCount,"data" => $data];
        }else{
          $output = ["draw" =>0,"recordsTotal" =>0,"recordsFiltered" =>0,"data" =>[],"code"=> $isValid['code'],"msg"=>$isValid['msg']];
        }
        echo json_encode($output);
        exit();
  }else{
    echo json_encode(['code'=>100,'msg'=>'Shop does not exists!','shop'=>$shop,'shop_id'=>$shop_id]);
  }
}else{
  $this->load->view('errors/html/error_404');
}
}

    public function totalCount($shop_id)
    {
        $query = $this->db->where('shop_id', $shop_id)->select("COUNT(*) as num")->get("wishlist_tbl");
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
      if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
      $cust_id = $_GET['cust_id'];
      $data['actionCounts'] = $this->Home_model->getActionTypeCount($cust_id,$shop_id);
      $data['wishlists'] = $this->Home_model->getWishlist($cust_id,$shop_id);
      foreach ($data['wishlists'] as $item) {
        $action_type = $this->action_type($item->action_type);
        $item->action_type = $action_type;

        $timeago = $this->timeElapsed($item->updated_at);
        $item->created_at = $timeago;
      }
      // echo "<pre>";
      // print_r($data['wishlists']);
      // exit;
      $this->load->load_admin('wishlist', $data);
    }else{
      $this->load->view('errors/html/error_404');
        }
      }else{
        $this->load->view('errors/html/error_404');
      }
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
    public function changePlan()
    {
        // $this->load->view('layouts/header');
        $this->load->load_admin('changePlan');
        // $this->load->view('layouts/footer');
    }
    public function checklist()
    {
      if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
      $product_id = $this->input->post('product_id');
      $cust_id = $this->input->post('cust_id', true);
      $data = $this->Home_model->checklist($shop_id, $product_id, $cust_id);
      foreach ($data as $k) {
        $updated_at = $k->updated_at;
        $k->updated_at = date("jS \of F Y", strtotime($updated_at));
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
    }else{
      $error = array( 'code' => 403, 'mgs' => 'Shop id is not set');
      echo json_encode($error);
        }
      }else{
        $error = array( 'code' => 403, 'mgs' => 'Page not found');
        echo json_encode($error);
      }
    }

    public function addToWishlist() {
      if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
          $cust_id = $this->input->post('cust_id', true);
          $cust_name = $this->input->post('cust_name', true);
          $product_id = $this->input->post('product_id', true);
          //check the recored is already exixtsed in recordsTotal
          $isExisted = $this->Home_model->checkExisingRecord($shop_id,$cust_id,$product_id);
          // print_r($isExisted);
          // exit;
          if($isExisted>0) {
            $data = $this->Home_model->UpdateToWishlist($shop_id,$cust_id,$product_id);
            echo json_encode($data);
            exit();
          } else {
            $formdata =array(
              'shop' => $this->input->post('shop', true),
              'shop_id' => $shop_id,
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
              'updated_at' => date("Y-m-d H:i:s"),
            );
            if (empty($formdata)) {
              $error = array("code" => 403,"msg" => "Form data is empty");
            } else {

              $data = $this->Home_model->addToWishlist($formdata);
              if($data != false)
              {
              echo json_encode($data);
              exit();
            }else{
              $data =  array('status' => '0', 'mgs'=>'You have exceed limit for wishlist for this month.');
              echo json_encode($data);
              exit();
            }

            }
          } //end else
        } else {
          $error = array( 'code' => 403, 'mgs' => 'Shop id is not set');
          echo json_encode($error);
        }
      } else {
        $error = array( 'code' => 403, 'mgs' => 'Page not found');
        echo json_encode($error);
      }
    } //end function



    public function removeFromWishlist() {
      if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
           $cust_id = $this->input->post('cust_id', true);
           $product_id = $this->input->post('product_id', true);
           $data = $this->Home_model->removeFromWishlist($shop_id,$cust_id,$product_id);
           if (!isset($data)) {
               $error = array("code" => 403,"msg" => "Error");
               echo json_encode($error);
               exit;
           }else{
               echo json_encode($data);
               exit();
           }
         }else{
         $error = array( 'code' => 403, 'mgs' => 'Shop id is not set');
         echo json_encode($error);
           }
         }else{
           $error = array( 'code' => 403, 'mgs' => 'Page not found');
           echo json_encode($error);
         }
    }

    public function removeAll() {
      if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
           $cust_id = $this->input->post('cust_id', true);
           $data = $this->Home_model->removeAll($shop_id,$cust_id);
           if (!isset($data)) {
               $error = array("code" => 403,"msg" => "Error");
               echo json_encode($error);
               exit;
           }else{
               echo json_encode($data);
               exit();
           }
         }else{
         $error = array( 'code' => 403, 'mgs' => 'Shop id is not set');
         echo json_encode($error);
           }
         }else{
           $error = array( 'code' => 403, 'mgs' => 'Page not found');
           echo json_encode($error);
         }
    }

    public function addToCart()
    {
      if ($_GET['shop']!='') {
      $shop = $_GET['shop'];
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
     $cust_id = $this->input->post('cust_id', true);
     $product_variant_id = $this->input->post('product_variant_id', true);
     $data = $this->Home_model->addToCart($shop_id,$cust_id,$product_variant_id);
     if (!isset($data)) {
         $error = array("code" => 403,"msg" => "Error");
         echo json_encode($error);
         exit;
     }else{
         echo json_encode($data);
         exit();
     }
   }else{
   $error = array( 'code' => 403, 'mgs' => 'Shop id is not set');
   echo json_encode($error);
     }
   }else{
     $error = array( 'code' => 403, 'mgs' => 'Page not found');
     echo json_encode($error);
   }
    }

    //get all product list to whole item to cart
      public function getAllProducts()
      {
        if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
       $cust_id = $this->input->post('cust_id', true);
       $data = $this->Home_model->getAllProducts($shop_id,$cust_id);
       if (!isset($data)) {
           $error = array("code" => 403,"msg" => "Error");
           echo json_encode($error);
           exit;
       }else{
           echo json_encode($data);
           exit();
       }
      }else{
      $error = array( 'code' => 403, 'mgs' => 'Shop id is not set');
      echo json_encode($error);
       }
      }else{
       $error = array( 'code' => 403, 'mgs' => 'Page not found');
       echo json_encode($error);
      }
      }
      public function countProductAdded()
      {
        if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
       // $cust_id = $this->input->post('cust_id', true);
       $product_id = $this->input->post('product_id', true);
       $data = $this->Home_model->countProductAdded($shop_id,$product_id);
       if (!isset($data)) {
           $data = array("code" => 403,"msg" => "Sorry, Couldn't read data!");
           echo json_encode($data);
           exit;
       }else{
           echo json_encode($data);
           exit();
       }
     }else{
     $data = array( 'code' => 403, 'mgs' => 'Shop id is not set');
     echo json_encode($data);
       }
     }else{
       $data = array( 'code' => 403, 'mgs' => 'Page not found');
       echo json_encode($data);
     }
      }


      public function feedback()
      {
        $this->load->load_admin('feedback');
      }

      public function saveFeedback()
      {
        if ($_GET['shop']!='') {
        $shop = $_GET['shop'];
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
          $isValid = IsValidRequest();
            if($isValid['code'] == 200){
             $data = array(
               'shop_id' => $shop_id,
               'name'    => $this->input->post('name', true),
               'email'   => $this->input->post('email', true),
               'mgs'     => $this->input->post('mgs', true)
             );
             $data = $this->Home_model->saveFeedback($data);
             echo json_encode($data);
           }else{
               echo json_encode($isValid);
           }
         }else{
           $data = array( 'code' => 403, 'mgs' => 'Shop id is not set');
           echo json_encode($data);
           }
         }else{
           $data = array( 'code' => 403, 'mgs' => 'Page not found');
           echo json_encode($data);
         }
      }

      public function messageBody($shop_id,$cust_id)
      {
        $setting = $this->Home_model->getallSetting($shop_id);
        // echo "<pre>";print_r($setting);exit;
        $data = $this->Home_model->getProductsRemainder($cust_id,$shop_id);
        //extracting since date
         // echo "<pre>";print_r($data);exit;
        $i = 0;
        $date_arr = array();
        foreach ($data->products as $product) {
          // echo $i;
          $date_arr[$i] = $product->created_at;
          $i++;
        }
        // /get date min and max
        usort($date_arr, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
        $mindate = strtotime($date_arr[0]);
        $since_date =  date("F d, Y",$mindate);
        // echo "<pre>";print_r($since_date);exit;
        //getting first name
        $name       = $data->name;
        $nametemp   = explode(" ", $name);
        $firstname  = $nametemp[0];
        $firstname  = ucfirst($firstname);
        //greeting shortcode
        $greeting    = $setting->greetingText_remainder;
        $replacedata['Name'] = $firstname;
        $content_greet = $this->replacement($greeting, $replacedata);
        //getting shortcode for since date
        $emailText = $setting->emailText_remainder;
        $replacedata['since_date'] = $since_date;
        $content_body = $this->replacement($emailText, $replacedata);

        //short code for product
        $bodyEmail ='<div style="display: flex;flex-wrap:wrap;margin-top:10px;border-bottom: 2px solid #f2f3f3;">';
          foreach ($data->products as $product) {
        $bodyEmail .='<div style="display: inline-block; width: 30%; ">
                <img style="width: 100%;height: 40%;" src="'.$product->product_image.'" alt="relatedproductsimg1" border="0">
                <a  href="https://'.$data->shop.$product->product_url.'" target="_blank" style="text-decoration:none"><h4 style="font-size: 18px; line-height: 18px; color: #464646; text-align: center;text-transform:uppercase">'.$product->product_name.'</h4></a>
                <h5 style="font-size: 18px; line-height: 18px; color: #464646; text-align: center;">RS. '.$product->product_price.'</h5>
                <div style="text-align: center;"><a style="background-color: #5c2a9d; font-size: 16px; letter-spacing: 1px;color: #ffffff; font-family: \'Montserrat\'; font-weight: bold; text-align: center; border: none; border-radius: 4px; padding: 7px 20px; text-decoration: none;" href="https://'.$data->shop.$product->product_url.'" target="_blank">Visit item</a></div>
              </div>';
        }
        $bodyEmail .=  '</div>';
        $replacedata['products'] = $bodyEmail;
        $content_body = $this->replacement($emailText, $replacedata);
       // echo "<pre>";print_r($date_arr);exit;
        if($setting->email_template_remainder == 1)
        {
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                      <header><a href="https://'.$data->shop.'" ><img style="width: 124px" src="'.$setting->logoUrl.'" alt="logo" border="0"></a>
                      </header>
                      <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                        <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
                        <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                      </section>
                      <footer>
                      <div style="background: #ececec; padding: 10px; ">
                        <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                        <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                      </div>
                      </footer>
                      &nbsp;</div>';
                      // echo $body;exit;
        }
        elseif ($setting->email_template_remainder == 2) {
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                      	<section class="email_box" style="background-color: #fff; margin-top: 30px;">
                      		<header>
                      			<div style="text-align: center;">
                      				<a href="https://'.$data->shop.'">
                      					<img src="'.$setting->logoUrl.'" alt="logo" style="width: 200px;" border="0">
                      				</a>
                      			</div>
                      		</header>
                      		<hr style="border-top: 1px solid #ccc; margin-top: 15px;">
                      		<h3 style="padding: 10px 10px; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
                      		<p style="padding-left: 10px ;font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                        </section>
                        <footer>
                          <div style="background: #ececec; padding: 10px; ">
                            <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                            <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                          </div>
                        </footer>
                      </div>';
                    // echo $body;exit;
        }
        elseif($setting->email_template_remainder == 3) {
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                    <header><div style="text-align: center;"><a href="https://'.$data->shop.'"><img src="'.$setting->logoUrl.'" alt="logo" style="width: 153px;" border="0"></a></div></header>
                    <hr style="border-top: dashed #ccc; margin-top: 15px;">
                    <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                    <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
                    <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                    </div>
                    </section>
                    <footer style="width: 80%; margin: 0 auto;">
                    <div style="background: #ececec; padding: 10px; ">
                      <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                      <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                    </div>
                    </footer>
                    &nbsp;</div>';
                    // echo $body;exit;
        }

        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                  <html xmlns="http://www.w3.org/1999/xhtml">
                  <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                      <title>Title</title>
                      <style type="text/css">
                          body {
                              font-family: Arial, Verdana, Helvetica, sans-serif;
                              font-size: 16px;
                          }
                      </style>
                  </head>
                  <body>
                  ' . $body . '
                  </body>
                  </html>';
                  // print_r($message);exit;
        return $message;


      }
      //check is request cli
      //check shop
      //get shop id
      //get customers and its products which created_at > 30 days
      //send mail to that customer with products
    public function emailRemainder()
    {
      $emailSubscription = array('daily' => '1 DAY', 'weekly' => '7 DAY', 'monthly' => '30 DAY');
      foreach($emailSubscription as $subscri => $day)
        {
            switch($subscri)
            {
                case 'daily':
                // echo $subscri."<br>";
                    $premiumShop = $this->Home_model->premiumShop($day);
                    if(count($premiumShop) > 0)
                    {
                      foreach ($premiumShop as $key ) {
                        $shop_id = $key->shop_id;
                        $shop = $key->domain;
                        $customer_ids = $this->Home_model->getCustomersList($shop_id);
                        //get email id
                        $shopAccess = getShop_accessToken_byShop($shop);
                        $this->load->library('Shopify', $shopAccess);
                        $year = getYear();
                        // print_r($customer_ids);
                        // exit;
                          if(count($customer_ids)>0)
                          {
                          foreach ($customer_ids as $customer_id) {
                            $customer_info = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.$year.'/customers/'.$customer_id['cust_id'].'.json'], true);
                            $customer_email = $customer_info->customer->email;
                            // print_r($customer_email);exit;
                                $to           = $customer_email ; //recipient
                                $data = array(
                                  'shop_id'     => $shop_id,
                                  'cust_id'     => $customer_id['cust_id'],
                                  'email_type'  => 1,
                                  'send_on'     => date('Y-m-d h:i:s'),
                                );
                                // print_r($data);exit;
                                //call email send function
                                $this->send($shop_id, $to, $data, $customer_id['cust_id']);
                                // print_r($customer_email);exit;
                              } //foreach
                        }else{
                          echo 'No customer is not avaliable to send the emails'.'<br>';
                        }
                      } //foreach ($premiumShop as $key )
                    } //if(count($premiumShop) > 0)
                    else {
                       echo "Shops for ".$subscri." email subscription are not available"."<br>";
                    }
                    break;
// -------------------------------------case monthly-------------------------------------------------------------------
                case 'weekly':
                // echo $subscri."<br>";
                $premiumShop = $this->Home_model->premiumShop($day);
                if(count($premiumShop) > 0)
                {
                  foreach ($premiumShop as $key ) {
                    $shop_id = $key->shop_id;
                    $shop = $key->domain;
                    $customer_ids = $this->Home_model->getCustomersList($shop_id);
                    //get email id
                    $shopAccess = getShop_accessToken_byShop($shop);
                    $this->load->library('Shopify', $shopAccess);
                    $year = getYear();
                    // print_r($customer_ids);
                    // exit;
                      if(count($customer_ids)>0)
                      {
                      foreach ($customer_ids as $customer_id) {
                        $customer_info = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.$year.'/customers/'.$customer_id['cust_id'].'.json'], true);
                        $customer_email = $customer_info->customer->email;
                        // print_r($customer_email);exit;
                            $to           = $customer_email ; //recipient
                            $data = array(
                              'shop_id'     => $shop_id,
                              'cust_id'     => $customer_id['cust_id'],
                              'email_type'  => 1,
                              'send_on'     => date('Y-m-d h:i:s'),
                            );
                            // print_r($data);exit;
                            //call email send function
                            $this->send($shop_id, $to, $data, $customer_id['cust_id']);
                            // print_r($customer_email);exit;
                          } //foreach
                    }else{
                      echo 'No customer is not avaliable to send the emails'.'<br>';
                    }
                  } //foreach ($premiumShop as $key )
                } //if(count($premiumShop) > 0)
                else {
                   echo "Shops for ".$subscri." email subscription are not available"."<br>";
                }
                    break;
// -------------------------------------case monthly-------------------------------------------------------------------
                case 'monthly':
                // echo $subscri."<br>";
                $premiumShop = $this->Home_model->premiumShop($day);
                if(count($premiumShop) > 0)
                {
                  foreach ($premiumShop as $key ) {
                    $shop_id = $key->shop_id;
                    $shop = $key->domain;
                    $customer_ids = $this->Home_model->getCustomersList($shop_id);
                    //get email id
                    $shopAccess = getShop_accessToken_byShop($shop);
                    $this->load->library('Shopify', $shopAccess);
                    $year = getYear();
                    // print_r($customer_ids);
                    // exit;
                      if(count($customer_ids)>0)
                      {
                      foreach ($customer_ids as $customer_id) {
                        $customer_info = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.$year.'/customers/'.$customer_id['cust_id'].'.json'], true);
                        $customer_email = $customer_info->customer->email;
                        // print_r($customer_email);exit;
                            $to           = $customer_email ; //recipient
                            $data = array(
                              'shop_id'     => $shop_id,
                              'cust_id'     => $customer_id['cust_id'],
                              'email_type'  => 1,
                              'send_on'     => date('Y-m-d h:i:s'),
                            );
                            // print_r($data);exit;
                            //call email send function
                            $this->send($shop_id, $to, $data, $customer_id['cust_id']);
                            // print_r($customer_email);exit;
                          } //foreach
                    }else{
                      echo 'No customer is not avaliable to send the emails'.'<br>';
                    }
                  } //foreach ($premiumShop as $key )
                } //if(count($premiumShop) > 0)
                else {
                   echo "Shops for ".$subscri." email subscription are not available"."<br>";
                }
                    break;
// -------------------------------------case default -------------------------------------
                default:
                    echo 'Shop with premium plan is not available'."<br>";
            } //switch key
        } //foreach switch
        $data = array(
          'name'          => 'yahya',
          'json'              => 'email',
        );
        $this->db->insert('test', $data);

        return 200;
    }

    public function send($shop_id, $to, $data,$customer_id){
      if($shop_id !== '' && $to !== '')
      {
        $setting = $this->Home_model->getallSetting($shop_id);
        // print_r($setting); exit;
        $subject      = $setting->sub_remainder; //subject of email
        $to           = $to ; //recipient
        $senderName   = $setting->sender_name;       //sender name
        $senderMail   = $setting->sender_email;       //sender mail
        $message      = $this->messageBody($shop_id,$customer_id);          //body of email
        // echo "<pre>";print_r($setting);exit;
        // Load PHPMailer library
        $this->load->library('phpmailer_library');
        // PHPMailer object
        $mail = $this->phpmailer_library->load();
        // SMTP configuration
       $mail->isSMTP();
       $mail->Host = $setting->host_name;
       $mail->SMTPAuth = true;
       $mail->Username = $setting->sender_email;//enter your gmail address
       $mail->Password = $setting->password; //password of your gmail account
      // echo "<pre>";print_r(md5($setting->password));exit;
       $mail->SMTPSecure = $setting->email_service;
       $mail->Port = $setting->port_number;

       $mail->setFrom($senderMail,$senderName);
       $mail->addReplyTo($senderMail,$senderName); //optional email has expected reply

       // Add a recipient
       $mail->addAddress($to); //email address of recipient

       // Email subject
       $mail->Subject = $subject;

       //If sending HTML email set to true else set to false
       $mail->isHTML(true);
       $mail->Body = $message;
       if($mail->send())
        {
          $set_marked = $this->Home_model->sendMailsData($data);
           echo 'Mail Send';

        }

       else
        { echo 'Mail not send'.'<br> Error:'. $mail->ErrorInfo; }
      }

  	}


    public function createWebhook()
    {
      // print_r('in funct');exit;
      $shop       = $_GET['shop'];
      $accessData=getShop_accessToken_byShop($shop);
      $payload  = array(
          "webhook" => array(
              "topic" => "products/update",
              "address" => base_url()."Home/notifyOnProductUpdate",
              "format" => "json"
        )
      );
        // print_r($payload['webhook']['address']);exit;
      $payload_address = $payload['webhook']['address'];
      if ($accessData['ACCESS_TOKEN']!='') {
          $this->load->library('Shopify', $accessData);
          $webhookCount = $this->shopify->call(['METHOD' => 'GET', 'URL' =>'/admin/api/2020-04/webhooks/count.json'], true);
          $webhook = $this->shopify->call(['METHOD' => 'GET', 'URL' =>'/admin/api/2020-04/webhooks.json'], true);
          // print_r($webhook);exit;
          if ($webhookCount->count > 0) {
            foreach ($webhook as $value) {
              $existing_address = $value[0]->address;
              if ($payload_address == $existing_address){
                echo "Webhook already created for this address";
              }else {
                $this->shopify->call(['METHOD' => 'POST', 'URL' =>'/admin/api/2020-04/webhooks.json', 'DATA'=>$payload], true);
              }
            }
          }else{
            $this->shopify->call(['METHOD' => 'POST', 'URL' =>'/admin/api/2020-04/webhooks.json', 'DATA'=>$payload], true);
          }
      }
    }

    public function notifyOnProductUpdate()
    {
      $json                 = file_get_contents('php://input');
      $json_decode          = json_decode($json, true);
      $shop                 = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
      // $shop                 = $_GET['shop'];

      if ($shop !='') {
        $shop_id = $this->Home_model->GetShopId($shop);
        if($shop_id != ''){
          $email_facility = $this->Home_model->checkEmailFacilty($shop_id);
          // print_r($email_facility);exit;
          if($email_facility == '1')
          {
          $updated_price        = $json_decode['variants'][0]['price'];
          // $updated_price        = 510;
          $product_variant_id   = $json_decode['variants'][0]['id'];
          // $product_variant_id   = 33877413625995;
          // $product_id           = $json_decode['id'];  //product id
          // $product_id           = 850;  //product id
          if(isset($product_variant_id) && isset($updated_price))
          {
            $shopAccess = getShop_accessToken_byShop($shop);
            $this->load->library('Shopify', $shopAccess);
            $year = getYear();

            $customer_ids = $this->Home_model->notifyOnProductUpdate($shop_id,$product_variant_id,$updated_price);
            if(count($customer_ids) > 0)
            {
              foreach ($customer_ids as $customer_id) {
                $customer_info = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.$year.'/customers/'.$customer_id['cust_id'].'.json'], true);
                $customer_email = $customer_info->customer->email;
                $to = $customer_email;
                $data = array(
                      'shop_id'     => $shop_id,
                      'cust_id'     => $customer_id['cust_id'],
                      'email_type'  => 2,
                      'send_on'     => date('Y-m-d h:i:s'),
                    );
                    //call email send function
                    $this->sendSaleMail($shop_id, $to, $data, $customer_id['cust_id'],$product_variant_id,$updated_price);
                    // print_r($customer_email);exit;
                  } //foreach
            }else{
              echo 'No customer is not avaliable to send the emails';
            }
          }else{
          echo 'can\'t get required data';
        }
      }else {
        echo 'Email facility id disabled for this shop';
      }
      }else{
       echo 'Shop id is not set';
     } // shop id is not set
    }else{
     echo "shop name is not set";
   }  //if shop name is empty
} //end of function

    public function messageBodyForDiscount($shop_id,$cust_id,$product_variant_id,$updated_price)
    {
      if($shop_id !== '' )
      {
        $replacedata  = array();
        //get setting by shop id
        $setting = $this->Home_model->getallSetting($shop_id);
        // echo "<pre> ";print_r($setting);exit;v
        //get product detail
        $products = $this->Home_model->getProductsList($cust_id,$shop_id,$product_variant_id);
        // echo "<pre> ";print_r($products);exit;

        //getting first name
        $name       = $products->cust_name;
        $nametemp   = explode(" ", $name);
        $firstname  = $nametemp[0];
        $firstname  = ucfirst($firstname);
        // echo "<pre> ";print_r($firstname);exit;
        //gretting shortcode
        $greeting    = $setting->greetingText_sale;
        $replacedata['Name'] = $firstname;
        $content_greet = $this->replacement($greeting, $replacedata);
        // echo "<pre> ";print_r($content_greet);exit;

        //email body
        $content_body= $setting->emailText_sale;
        $productBody = '<div class="cart_img" style="padding: 30px 0"><img src="'.$products->product_image.'" alt="product-img" style="border: 2px solid #ccc; width:40%;height:50%" /></div>
                            <p><a href="https://'.$products->shop.$products->product_url.'" style="text-decoration:none;font-weight:bold;text-transform:uppercase" target="_blank">'.$products->product_name.'</a><p>
                            <p style="text-decoration:none;font-weight:bold;text-transform:uppercase">Rs. '.$updated_price.'</p>
                            <div  style="padding: 35px 0;"><a style="font-size: 16px; letter-spacing: 1px; line-height: 38px; color: #ffffff; font-family: \'Montserrat\'; font-weight: bold; text-align: center; background: #5ec7f0; border: none; border-radius: 4px; padding: 15px 168px; text-decoration: none;" target="_blank" href="https://'.$products->shop.$products->product_url.'">GRAB IT NOW</a></div>';
        $replacedata['product'] = $productBody;
        $content_body = $this->replacement($content_body, $replacedata);
        // echo "<pre> ";print_r($content_body);exit;

      if($setting->email_template_sale == 1)
      {
          $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
          <header style="text-align: center;"><a href="https://'.$products->shop.'" target="_blank"><img src="'.$setting->logoUrl.'" alt="logo" border="0" style="width: 100px;height:100px" /></a><hr style="color: #777777;"></header>
          <section class="email_box" style="background-color: #fff;">
          <h3 style="padding: 10px 0; font-size: 34px; line-height: 38px; color: #777777; font-family: \'Montserrat\'; font-weight: 800; text-align: center; margin: 0;">'.$content_greet.'</h3>
          <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; color: #7e8791; font-family: \'Montserrat\'; font-weight: 600; text-align: center; max-width: 100%; margin: 0 auto;">'.$content_body.' </p>
          </section>';
          // echo $body;exit;
      }elseif ($setting->email_template_sale == 2) {
          $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
          <header><a href="https://'.$products->shop.'" target="_blank"><img src="'.$setting->logoUrl.'" alt="logo" border="0"  style="width: 100px;height:100px"/></a></header>
          <section class="email_box" style="background-color: #fff; margin-top: 30px;">
          <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
          <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
          </section>';
          // echo $body;exit;
      }
      elseif($setting->email_template_sale == 3) {
          $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                  <header><a href="https://'.$products->shop.'" target="_blank"><img src="'.$setting->logoUrl.'" alt="logo" border="0"  style="width: 100px;height:100px"/></a></header>
                  <div style="border-style: dotted;border-width: 5px;">
                  <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                  <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\';  margin: 0;">'.$content_greet.'</h3>
                  <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                  </section>
                  </div>';
                  // echo $body;exit;
      }

      $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                    <title>Title</title>
                    <style type="text/css">
                        body {
                            font-family: Arial, Verdana, Helvetica, sans-serif;
                            font-size: 16px;
                        }
                    </style>
                </head>
                <body>
                ' . $body . '

                <footer>
                <div style="background: #ececec; padding: 10px; margin: 0 auto; width: 80%">
                  <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                  <p style="font-size: 12px; font-weight: 500; color: #79797  9;">If you have any question or concers ,please contact us.</p>
                </div>
                </footer>
                </body>
                </html>';
                // print_r($message);exit;
      return $message;
    }
    else{
      echo "failed to create message body";
    }
}

public function sendSaleMail($shop_id, $to, $data,$customer_id,$product_variant_id,$updated_price){
  if($shop_id !== '' && $to !== '')
  {
    $setting = $this->Home_model->getallSetting($shop_id);

    $subject      = $setting->sub_sale; //subject of email
    $to           = $to ; //recipient
    $senderName   = $setting->sender_name;       //sender name
    $senderMail   = $setting->sender_email;       //sender mail
    $message      = $this->messageBodyForDiscount($shop_id,$customer_id,$product_variant_id,$updated_price);          //body of email
    // echo "<pre>";print_r($setting);exit;
    // Load PHPMailer library
    $this->load->library('phpmailer_library');
    // PHPMailer object
    $mail = $this->phpmailer_library->load();
    // SMTP configuration
   $mail->isSMTP();
   $mail->Host = $setting->host_name;
   $mail->SMTPAuth = true;
   $mail->Username = $setting->sender_email;//enter your gmail address
   $mail->Password = $setting->password; //password of your gmail account
  // echo "<pre>";print_r(md5($setting->password));exit;
   $mail->SMTPSecure = $setting->email_service;
   $mail->Port = $setting->port_number;

   $mail->setFrom($senderMail,$senderName);
   $mail->addReplyTo($senderMail,$senderName); //optional email has expected reply

   // Add a recipient
   $mail->addAddress($to); //email address of recipient

   // Email subject
   $mail->Subject = $subject;

   //If sending HTML email set to true else set to false
   $mail->isHTML(true);
   $mail->Body = $message;
   if($mail->send())
    {
      $set_marked = $this->Home_model->sendMailsData($data);

      //update product price price
      $this->Home_model->updateProductPrice($shop_id,$product_variant_id,$updated_price);
       echo 'Mail Send';

    }
   else
    { echo 'Mail not send'.'<br> Error:'. $mail->ErrorInfo; }
  }
  else{ echo "require data is available";  }
}//end of function

public function testEmail()
{
  $shop = $_GET['shop'];
  if ($shop !='') {
    $shop_id = $this->Home_model->GetShopId($shop);
    if($shop_id != ''){
      $to = $this->input->post('emailTo');
      $emailTestFor = $this->input->post('emailTestFor');

      $isValid = IsValidRequest();
      if($isValid['code'] == 200){

      if($to != '' && $emailTestFor != '') {
        $setting = $this->Home_model->getallSetting($shop_id);
        $template     = $emailTestFor == 1 ? $setting->email_template_remainder : $setting->email_template_sale;
        $subject      = $emailTestFor == 1 ? $setting->sub_remainder            : $setting->sub_sale; //subject of email

        $senderName   = $setting->sender_name;       //sender name
        $senderMail   = $setting->sender_email;       //sender mail
        $body         = '';          //body of email
        //gretting shortcode
        $greetting    = $emailTestFor == 1 ? $setting->greetingText_remainder : $setting->greetingText_sale;
        $replacedata  = array();
        $replacedata['Name'] = 'User';
        $content_greet = $this->replacement($greetting, $replacedata);

        //since date
        $since_date = $emailTestFor == 1 ? $setting->emailText_remainder : $setting->emailText_sale; //string to replaced
        $replacedata['since_date'] = date("F d, Y"); //string to replace with
        $content_body = $this->replacement($since_date, $replacedata);

        $product = '<div style="display: flex;flex-wrap:wrap;margin-top:10px">
                  <div style="display: inline-block; width: 30%;padding:5px ">
                    <img style="width: 100%;height: 40%;" src="'.base_url().'assets/img/dummyProduct.png'.'" alt="relatedproductsimg1" border="0">
                    <a  href="#" target="_blank" style="text-decoration:none"><h4 style="font-size: 18px; line-height: 18px; color: #464646; text-align: center;text-transform:uppercase">Product Name 1</h4></a>
                    <h5 style="font-size: 18px; line-height: 18px; color: #464646; text-align: center;">RS. 250.00</h5>
                    <div style="text-align: center;"><a style="background-color: #37b6ce; font-size: 16px; letter-spacing: 1px;color: #ffffff; font-family: \'Montserrat\'; font-weight: bold; text-align: center; border: none; border-radius: 4px; padding: 7px 20px; text-decoration: none;" href="#" target="_blank">Buy</a></div>
                  </div>
                  <div style="display: inline-block; width: 30%;padding:5px ">
                      <img style="width: 100%;height: 40%;" src="'.base_url().'assets/img/dummyProduct.png'.'" alt="relatedproductsimg1" border="0">
                      <a  href="#" target="_blank" style="text-decoration:none"><h4 style="font-size: 18px; line-height: 18px; color: #464646; text-align: center;text-transform:uppercase">Product Name 2</h4></a>
                      <h5 style="font-size: 18px; line-height: 18px; color: #464646; text-align: center;">RS. 250.00</h5>
                      <div style="text-align: center;"><a style="background-color: #37b6ce; font-size: 16px; letter-spacing: 1px;color: #ffffff; font-family: \'Montserrat\'; font-weight: bold; text-align: center; border: none; border-radius: 4px; padding: 7px 20px; text-decoration: none;" href="#" target="_blank">Buy</a></div>
                    </div>
                  </div>';
        $replacedata['products'] = $product;
        $content_body = $this->replacement($content_body, $replacedata);

        if($emailTestFor == 1){
          if ($template == 1){
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                    <header><a href="https://'.$shop.'" ><img style="width: 124px" src="'.$setting->logoUrl.'" alt="logo" border="0"></a>
                    </header>
                    <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                      <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
                      <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                    </section>
                    <footer>
                    <div style="background: #ececec; padding: 10px; ">
                      <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                      <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                    </div>
                    </footer>
                    &nbsp;</div>';
                    // echo $body;exit;
      }elseif ($template == 2) {
          $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                      <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                        <header>
                          <div style="text-align: center;">
                            <a href="https://'.$shop.'">
                              <img src="'.$setting->logoUrl.'" alt="logo" style="width: 200px;" border="0">
                            </a>
                          </div>
                        </header>
                        <hr style="border-top: 1px solid #ccc; margin-top: 15px;">
                        <h3 style="padding: 10px 10px; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'!</h3>
                        <p style="padding-left: 10px ;font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                      </section>
                      <footer>
                        <div style="background: #ececec; padding: 10px; ">
                          <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                          <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                        </div>
                      </footer>
                    </div>';
                  // echo $body;exit;
      }elseif($template == 3) {
          $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                  <header><div style="text-align: center;"><a href="https://'.$shop.'"><img src="'.$setting->logoUrl.'" alt="logo" style="width: 153px;" border="0"></a></div></header>
                  <hr style="border-top: dashed #ccc; margin-top: 15px;">
                  <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                  <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
                  <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                  </div>
                  </section>
                  <footer style="width: 80%; margin: 0 auto;">
                  <div style="background: #ececec; padding: 10px; ">
                    <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                    <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                  </div>
                  </footer>
                  &nbsp;</div>';
                  // echo $body;exit;
               }
      }elseif($emailTestFor == 2) {
        if($template == 1)
        {
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
            <header style="text-align: center;"><a href="https://'.$shop.'" target="_blank"><img src="'.$setting->logoUrl.'" alt="logo" border="0" style="width: 100px;height:100px" /></a><hr style="color: #777777;"></header>
            <section class="email_box" style="background-color: #fff;">
            <h3 style="padding: 10px 0; font-size: 34px; line-height: 38px; color: #777777; font-family: \'Montserrat\'; font-weight: 800; text-align: center; margin: 0;">'.$content_greet.', User</h3>
            <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; color: #7e8791; font-family: \'Montserrat\'; font-weight: 600; text-align: center; max-width: 100%; margin: 0 auto;">'.$content_body.' </p>
            <div class="cart_img" style="padding: 30px 0"><img src="https://lh3.googleusercontent.com/proxy/Ghn0xZIeuSV2B39gHOWcXNrnpjTW_mxtHXc-XLu95qSFCdHdhpdpd7bsV4bigUMkRiVgD_xbGJvZ_f5cF0X8zU6Hud70pHlzQR3ENcMYruvjfgOmAlQ4H3cKBcOxFaqZ57OpFGFpXpV1fYCC6YU-avoVB4ELyBWL" alt="product-img" style="border: 2px solid #ccc; width:40%;height:50%" /></div>
            <p><a href="#" style="text-decoration:none;font-weight:bold;text-transform:uppercase" target="_blank">Product name</a><p>
            <p style="text-decoration:none;font-weight:bold;text-transform:uppercase">Rs. 250.00</p>
            <div  style="text-align: center; padding: 35px 0;"><a style="font-size: 16px; letter-spacing: 1px; line-height: 38px; color: #ffffff; font-family: \'Montserrat\'; font-weight: bold; text-align: center; background: #5ec7f0; border: none; border-radius: 4px; padding: 15px 168px; text-decoration: none;" target="_blank" href="#">View product </a></div>
            </section>
            <footer>
            <div style="background: #ececec; padding: 10px; margin: 0 auto; width: 80%">
              <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
              <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
            </div>
            </footer>';
            // echo $body;exit;
        }
        elseif ($template == 2) {
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
            <header><a href="https://'.$shop.'" target="_blank"><img src="'.$setting->logoUrl.'" alt="logo" border="0"  style="width: 100px;height:100px"/></a></header>
            <section class="email_box" style="background-color: #fff; margin-top: 30px;">
            <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\'; font-weight: 800; margin: 0;">'.$content_greet.'</h3>
            <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
            <div class="cart_img" style="padding: 30px 0 0 0"><img src="https://lh3.googleusercontent.com/proxy/Ghn0xZIeuSV2B39gHOWcXNrnpjTW_mxtHXc-XLu95qSFCdHdhpdpd7bsV4bigUMkRiVgD_xbGJvZ_f5cF0X8zU6Hud70pHlzQR3ENcMYruvjfgOmAlQ4H3cKBcOxFaqZ57OpFGFpXpV1fYCC6YU-avoVB4ELyBWL" alt="product-img" style="border: 2px solid #ccc; width:40%;height:50%"/>
            <p><a href="#" style="text-decoration:none;font-weight:bold;text-transform:uppercase">Product 1</a><p>
            <p>Rs. 250.00</p>
            <a style="font-size: 16px; letter-spacing: 1px; color: #ffffff; font-family: \'Montserrat\'; font-weight: bold; background: #5ec7f0; border: none; border-radius: 4px; padding: 15px 50px; text-decoration: none;" href="#">GRAB IT NOW</a></div>
            </div>
            </section>
            <footer>
            <div style="background: #ececec; padding: 10px; margin: 0 auto; width: 80%">
              <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
              <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
            </div>
            </footer>';
            // echo $body;exit;
        }
        elseif($template == 3) {
            $body = '<div id="email_template" style="width: 80%; margin: 0 auto;">
                    <header><a href="https://'.$shop.'" target="_blank"><img src="'.$setting->logoUrl.'" alt="logo" border="0"  style="width: 100px;height:100px"/></a></header>
                    <section class="email_box" style="background-color: #fff; margin-top: 30px;">
                    <h3 style="padding: 10px 0; font-size: 24px; line-height: 38px; color: #28282b; font-family: \'Montserrat\';  margin: 0;">'.$content_greet.'</h3>
                    <p style="font-size: 17px; letter-spacing: 0px; line-height: 29px; font-family: \'Montserrat\'; font-weight: 600; max-width: 100%; margin: 0;">'.$content_body.'</p>
                    <div class="cart_img" style="padding: 30px 0 0 0"><img src="https://lh3.googleusercontent.com/proxy/Ghn0xZIeuSV2B39gHOWcXNrnpjTW_mxtHXc-XLu95qSFCdHdhpdpd7bsV4bigUMkRiVgD_xbGJvZ_f5cF0X8zU6Hud70pHlzQR3ENcMYruvjfgOmAlQ4H3cKBcOxFaqZ57OpFGFpXpV1fYCC6YU-avoVB4ELyBWL" alt="product-img" style="border: 2px solid #ccc; width:40%;height:50%" />
                    <p><a href="#" style="text-decoration:none;font-weight:bold;text-transform:uppercase" target="_blank">Product Name</a><p>
                    <p style="text-decoration:none;font-weight:bold;text-transform:uppercase">Rs. 250.00</p>
                    </div>
                    </section>
                    </div>
                    <footer>
                    <div style="background: #ececec; padding: 10px; margin: 0 auto; width: 80%">
                      <p style="font-size: 12px; font-weight: 500; color: #797979;">This email was sent from a notification-only address that cannot accept incoming emails. please do not reply to this message.</p>
                      <p style="font-size: 12px; font-weight: 500; color: #797979;">If you have any question or concers ,please contact us.</p>
                    </div>
                    </footer>';
                    // echo $body;exit;
        }
      }
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                  <html xmlns="http://www.w3.org/1999/xhtml">
                  <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                      <title>Title</title>
                      <style type="text/css">
                          body {
                              font-family: Arial, Verdana, Helvetica, sans-serif;
                              font-size: 16px;
                          }
                      </style>
                  </head>
                  <body>
                  ' . $body . '
                  </body>
                  </html>';
                  // echo $message;exit;

        // Load PHPMailer library
        $this->load->library('phpmailer_library');
        // PHPMailer object
        $mail = $this->phpmailer_library->load();
        // SMTP configuration
       $mail->isSMTP();
       $mail->Host = $setting->host_name;
       $mail->SMTPAuth = true;
       $mail->Username = $setting->sender_email;//enter your gmail address
       $mail->Password = $setting->password; //password of your gmail account
      // echo "<pre>";print_r(md5($setting->password));exit;
       $mail->SMTPSecure = $setting->email_service;
       $mail->Port = $setting->port_number;

       $mail->setFrom($senderMail,$senderName);
       $mail->addReplyTo($senderMail,$senderName); //optional email has expected reply

       // Add a recipient
       $mail->addAddress($to); //email address of recipient

       // Email subject
       $mail->Subject = $subject;

       //If sending HTML email set to true else set to false
       $mail->isHTML(true);
       $mail->Body = $message;
       if($mail->send()){
          $data = array( 'code' => 200, 'mgs' => 'Mail send ');
          echo json_encode($data);
        }else{
          $data = array( 'code' => 100, 'mgs' => 'Mail not send  Error:'. $mail->ErrorInfo);
          echo json_encode($data);
        }
      }else{
        $data = array( 'code' => 100, 'mgs' => 'Invalid inputs!');
        echo json_encode($data);
      }
    }else{
      echo json_encode($isValid);
      exit;
    }
    }
    else {
      $data = array( 'code' => 100, 'mgs' => 'Shop id not found');
      echo json_encode($data);
    }
  }else {
    $data = array( 'code' => 100, 'mgs' => 'Shop name not found');
    echo json_encode($data);
  }
}

public function replacement($string, array $placeholders)
{
  //dd($placeholders);
  $resultString = $string;
  foreach($placeholders as $key => $value) {
      $resultString = str_replace('[' . $key . ']' , trim($value), $resultString, $i);
  }
  return $resultString;
}




















}
