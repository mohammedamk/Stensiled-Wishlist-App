<!DOCTYPE html>
<html>
   <head>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css'>
      <link href="<?=base_url('assets/css/custome.css') ?>" type="text/css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css" />
      <link href="<?=site_url(); ?>assets/css/seaff.css" type="text/css" rel="stylesheet">
      <link rel='stylesheet' href='//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css'>
      <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css'>
      <link rel='stylesheet' href='https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css'>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <!-- <script src="https://cdn.shopify.com/s/assets/external/app.js"></script> -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
      <script src="<?=base_url('assets/js/jscolor.js') ?>"></script>
      <!-- toggle button-->
      <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
      <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
      <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
      <script src="https:////cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.min.js" type="text/javascript"></script>

      <?php
        $shop = $_GET['shop'];
      ?>
      <script type="text/javascript">
         var _configs = {
               apiKey: '<?=$this->config->item('shopify_api_key'); ?>',
               shop: '<?=$shop?>',
           }
       window.GenerateSessionToken = function(){
         var AppBridgeUtils = window['app-bridge-utils'];
         const sessionToken = AppBridgeUtils.getSessionToken(window.app);
         sessionToken.then(function(result) {
           $.ajaxSetup({
             headers: { "Authorization": "Bearer " + result }
           });
           window.sessionToken = result;
         }, function(err) {
             // console.log(err); // Error: "It broke"
         });
       }

       window.ShowErrorToast = function(msg){
         var Toast = window.ShopifyApp.Toast;
         const toastError = Toast.create(window.ShopifyApp.App, {message: msg,duration: 5000,isError: true});
         toastError.dispatch(Toast.Action.SHOW);
       }
       window.ShowSuccesToast = function(msg){
         var Toast = window.ShopifyApp.Toast;
         const toastError = Toast.create(window.ShopifyApp.App, {message: msg,duration: 5000});
         toastError.dispatch(Toast.Action.SHOW);
       }

       head.ready("shopifyAppBridgeUtils", function() {
           var shopName = _configs.shop;
           var token = '';
           function initializeApp() {
             var app = createApp({
               apiKey:_configs.apiKey,
               shopOrigin: shopName
             });
             window.app = app;
             window.GenerateSessionToken();
             return app;
           }

           var AppBridge = window['app-bridge'];
           var AppBridgeUtils = window['app-bridge-utils'];
           var createApp = AppBridge.default;
           var actions = AppBridge.actions;
           window.ShopifyApp = {
             App: initializeApp(),
             ShopOrigin: _configs.shop,
             ResourcePicker: actions.ResourcePicker,
             Toast: actions.Toast,
             Button: actions.Button,
             TitleBar: actions.TitleBar,
             Modal: actions.Modal,
             Redirect: actions.Redirect,
             Loading: actions.Loading,
           };
           var ShopifyApp = window.ShopifyApp;
         });
       </script>
       <script type="text/javascript">
         head.load({shopifyAppBridge: "https://unpkg.com/@shopify/app-bridge@1.30"},{shopifyAppBridgeUtils: "https:////unpkg.com/@shopify/app-bridge-utils@1.30"});
       </script>

      <style>
         .collapsible {
         display:inline-block;
         background-color: #777;
         color: white;
         cursor: pointer;
         padding: 18px;
         width: 100%;
         border: none;
         text-align: left;
         outline: none;
         font-size: 15px;
         }
         .active1, .collapsible:hover {
         background-color: #555;
         }
         .collapsible:after {
         content: '\002B';
         color: white;
         font-weight: bold;
         float: right;
         margin-left: 5px;
         }
         .active1:after {
         content: "\2212";
         }
         .content {
         padding: 0 18px;
         max-height: 0;
         overflow: hidden;
         transition: max-height 0.2s ease-out;
         background-color: #f1f1f1;
         }
         label{
         font-weight: 600;
         color: #605a56;
         }
         /* Chrome, Safari, Edge, Opera */
         input::-webkit-outer-spin-button,
         input::-webkit-inner-spin-button {
         -webkit-appearance: none;
         margin: 0;
         }
      </style>
   </head>
   </head>
   <body>
      <div class="custom--header border-bottom sticky-top">
         <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
               <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                     <a class="nav-item nav-link" href= "<?=base_url().'Home/dashboard?shop='.$_GET["shop"]; ?>">Dashboard <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-item nav-link" href="<?=base_url().'Home/activity?shop='.$_GET["shop"]; ?>">Activity</a>
                  </li>
                  <li class="nav-item ">
                     <a class="nav-item nav-link" href="<?=base_url().'Home/customers?shop='.$_GET["shop"]; ?>">Customers</a>
                  </li>
                  <li class="nav-item ">
                     <a class="nav-item nav-link" href="<?=base_url().'Home/products?shop='.$_GET["shop"]; ?>">Products</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-item nav-link" href="<?=base_url().'Home/settings?shop='.$_GET["shop"]; ?>">Settings</a>
                  </li>
               </ul>
               <div class="d-flex flex-row-reverse bd-highlight">
                  <?php
                     $shop = $_GET["shop"];
                     if($shop != '')
                     {
                       $data = $this->db->select('id, plan_id')->where('domain', $shop)->get('shopify_stores')->row();
                       $plan_id = $data->plan_id;
                       $shop_id = $data->id;
                       if($shop_id)
                       {
                         $enable_wishlist = $this->db->select('enable_wishlist')->where('shop_id', $shop_id)->get('setting')->row()->enable_wishlist;
                       }
                     }

                     ?>
                  <form class="form-inline">
                     <label><b>Enable or disable wishlist</b></label>&nbsp;&nbsp;
                     <input type="checkbox"  id="wishlistbtn" data-onstyle="primary" data-offstyle="light" <?= $enable_wishlist == 1 ? 'checked': ''?>>
                     <input type="hidden" name="enable_wishlist"  id="enable_wishlist">
                     <a href="<?=base_url().'Home/changePlan?shop='.$_GET["shop"]; ?>" class="btn btn-warning mx-3">
                     Change the Plan
                     </a>
                  </form>
               </div>
            </div>
         </nav>
      </div>
