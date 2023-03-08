<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
class Auth extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model('AuthModel');
    }

    //App Install View Page
    public function Install_login() {
      $this->load->view('auth/appinstall');
    }

    public function access() {
      $shop = $this->input->get('shop');
      if ($shop != '') {
        if ($this->db->table_exists('shopify_stores') === TRUE) {
          $this->auth($shop);
        } else {
          $this->CreateTable($shop);
        }
      } else {
        echo 'Unauthorized Access!';
  			exit;
      }
    }

    public function CreateTable($shop='')
    {
      $query = "CREATE TABLE `shopify_stores` (
        `id` int(11) NOT NULL,
        `token` varchar(100) DEFAULT NULL,
        `shop` varchar(100) DEFAULT NULL,
        `store_id` int(11) DEFAULT NULL,
        `domain` varchar(100) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `active` tinyint(1) NOT NULL DEFAULT '1',
        `code` text,
        `hmac` text,
        `charge_id` varchar(100) DEFAULT NULL
      )";
      $query .= " " ."ALTER TABLE `shopify_stores`
                  ADD PRIMARY KEY (`id`)";
      $query .= " " .'ALTER TABLE `shopify_stores`
                              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT';
      $ok2 = $this->db->query($query);
      $this->auth($shop);
    }

        public function createTableSetting($shop)
        {
          if ($shop!='') {
          $this->load->model('Home_model');
          $shop_id = $this->Home_model->GetShopId($shop);
          if($shop_id != ''){
    // --
    // -- Table structure for table `setting`
    // --

       $query = "CREATE TABLE `setting` (
                `id` int(11) NOT NULL,
                `shop_id` int(11) NOT NULL,
                `enable_wishlist` int(11) NOT NULL DEFAULT '1' COMMENT '1=enable',
                `callwish` varchar(255) NOT NULL DEFAULT 'My Wishlist',
                `btntxtcolr` varchar(255) NOT NULL DEFAULT 'FFFFFF',
                `btnbckcolr` varchar(255) NOT NULL DEFAULT 'D238B6',
                `btnTxtBeforeAdding` varchar(255) NOT NULL DEFAULT 'ADD TO WISHLIST',
                `btnTxtAfterAdding` varchar(255) NOT NULL DEFAULT 'ADDED TO WISHLIST',
                `btnIcon` varchar(256) NOT NULL DEFAULT 'fa fa-bookmark',
                `removeItemFromWishlist` int(11) NOT NULL DEFAULT '1',
                `btnTxtForCart` varchar(255) NOT NULL DEFAULT 'ADD TO CART',
                `textWhenNoItem` varchar(255) NOT NULL DEFAULT 'No items in wishlist',
                `mgsForRemoveItem` varchar(255) NOT NULL DEFAULT 'Are you sure you want to remove wishlist item?',
                `shop` varchar(255) NOT NULL,
                `enable_email` int(11) NOT NULL DEFAULT '0',
                `host_name` varchar(255) DEFAULT NULL,
                `port_number` varchar(255) DEFAULT NULL,
                `email_service` varchar(255) DEFAULT NULL,
                `sender_name` varchar(255) DEFAULT NULL,
                `sender_email` varchar(255) DEFAULT NULL,
                `password` varchar(255) DEFAULT NULL,
                `email_subscr` varchar(255) NOT NULL DEFAULT '1 DAY',
                `logoUrl` varchar(255) DEFAULT NULL,
                `since_date` varchar(255) DEFAULT '1 DAY',
                `email_template_remainder` int(11) NOT NULL DEFAULT '1',
                `sub_remainder` varchar(255) DEFAULT 'We have saved your loved one!',
                `greetingText_remainder` varchar(255) DEFAULT 'Hello',
                `emailText_remainder`  VARCHAR(6000) DEFAULT '<p>You saved these item(s) in our wishlist since [since_date]. Please feel free to shop them.</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>',
                `sub_sale` varchar(255) DEFAULT 'Your wistlisted item is on sale!',
                `email_template_sale` int(11) NOT NULL DEFAULT '1',
                `greetingText_sale` varchar(255) NOT NULL DEFAULT 'Hello',
                `emailText_sale` longtext NOT NULL,
                `mailSendOn` DATE NULL DEFAULT NULL,
                `nextMailSendOn` DATE NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
          $ok = $this->db->query($query);
          if(isset($ok) && $ok == TRUE)
          {
            $query1 = "ALTER TABLE `setting`
                        ADD PRIMARY KEY (`id`)";
            $ok1 = $this->db->query($query1);
            if(isset($ok1) && $ok1 == TRUE)
            {
              $query2 = 'ALTER TABLE `setting`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4';
              $ok2 = $this->db->query($query2);
              if(isset($ok2) && $ok2 == true){
                   if ($this->db->table_exists('setting')) {
                     $this->insertSettingValue($shop);
                           $this->auth($shop);
                           return;
                   }
            }
          }else{
            print_r('Error in setting table query');
            exit;
          }
        }else{
              redirect('Auth/Install_login');
          }
          }else{
              redirect('Auth/Install_login');
          }
        }
    }
    public function insertSettingValue($shop)
    {
      if ($shop!='') {
      $this->load->model('Home_model');
      $shop_id = $this->Home_model->GetShopId($shop);
      if($shop_id != ''){
        $data = array(
          'shop_id'          => $shop_id,
          'shop'              => $shop,
        );
        $this->db->where('shop_id',$shop_id);
        $q = $this->db->get('setting');
        if ( $q->num_rows() > 0 )
        {
          $this->db->where('shop_id',$shop_id);
          $data = $this->db->update('setting', $data);
          return;
        }
        else{
          $this->db->where('shop_id',$shop_id);
          $data = $this->db->insert('setting', $data);
          return;
        }

     }
     return;
    }
  }
    public function auth($shop)
    {

        $data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $shop,
            'ACCESS_TOKEN' => ''
        );

        $this->load->library('Shopify', $data); //load shopify library and pass values in constructor

        $scopes = array(
            'read_content', 'write_content', 'write_products',
            'read_products',  'read_product_listings', 'read_orders',
            'write_orders', 'read_themes', 'write_themes','read_customers', 'write_customers'
        );
        $redirect_url = $this->config->item('redirect_url'); //redirect url specified in app setting at shopify
        $paramsforInstallURL = array(
            'scopes' => $scopes,
            'redirect' => $redirect_url
        );

        $permission_url = $this->shopify->installURL($paramsforInstallURL);

        $this->load->view('auth/escapeIframe', ['installUrl' => $permission_url]);
    }

    public function authCallback()
    {
        $code = $this->input->get('code');
        $shop = $this->input->get('shop');

        if (isset($code)) {
            $data = array(
                'API_KEY' => $this->config->item('shopify_api_key'),
                'API_SECRET' => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN' => $shop,
                'ACCESS_TOKEN' => ''
            );
            $this->load->library('Shopify', $data); //load shopify library and pass values in constructor
        }
        $accessToken = $this->shopify->getAccessToken($code);
        $this->updateAccess_Token($accessToken);
        if ($accessToken != '') {
          $this->charge_exist($shop);
          redirect('Auth/home?shop=' . $shop);
        } else {
          redirect('Auth/Install_login');
        }
        //redirect('https://' . $shop . '/admin/apps');
    }
    public function charge_exist($shop = '') {
  		if (!empty($shop)) {
  			$shop_details = $this->AuthModel->get_shop_details($shop);
  			if ($shop_details) {
  				if (empty($shop_details->charge_id)) {
  					redirect('Auth/CreateCharge?shop=' . $shop);
  				} else {
  					redirect('Auth/GetCharge?shop=' . $shop . '&charge_id=' . $shop_details->charge_id);
  				}
  			} else {
  				redirect('Auth/Install_login');
  			}
  		} else {
  			redirect('Auth/Install_login');
  		}
  	}

    public function CreateCharge() {
      if (isset($_GET['shop']) && !empty($_GET['shop'])) {
        $shop = $_GET['shop'];

        $shopAccess = getShop_accessToken_byShop($shop);
        $this->load->library('Shopify', $shopAccess);
        if (!isset($_GET['price'])) {
          $price = 4.99;
          $plan_id = 0;
          $name = "Basic Plan";
        }else {
          $price = 9.99;
          $plan_id = 1;
          $name = "Premium Plan";
        }
        $data = array(
          "recurring_application_charge" => array(
            "name" => $name,
            "price" => $price,
            "test" => true,
            "return_url" => base_url('Auth/Charge_return_url?shop=' . $shop.'&plan_id='.$plan_id),
            "trial_days" =>0,
            "trial_ends_on" =>date('Y-m-d', strtotime('+7 days'))
          ),
        );
        $year = getYear();
        $charge = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/recurring_application_charges.json', 'DATA' => $data], true);
        if ($charge->recurring_application_charge) {
          $charge = $charge->recurring_application_charge;
          $this->load->view('auth/escapeIframe', ['installUrl'=>$charge->confirmation_url]);
          // redirect($charge->confirmation_url);
        } else {
          redirect('Auth/Install_login');
        }
      } else {
        redirect('Auth/Install_login');
      }
    }
    public function GetCharge() {
      $shop = $_GET['shop'];
      $charge_id = $_GET['charge_id'];


      if (!empty($shop)) {
        $shopAccess = getShop_accessToken_byShop($shop);
        $this->load->library('Shopify', $shopAccess);
        $year = getYear();
        $charge = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.$year.'/recurring_application_charges/' . $charge_id . '.json'], true);
        if ($charge) {
          $charge = $charge->recurring_application_charge;
          if ($charge->status != 'active') {
            redirect('Auth/CreateCharge?shop=' . $shop);
          } else {
            redirect('Auth/AppLoader?shop=' . $shop);
          }
        }
      }
    }

    public function Charge_return_url() {
      $plan_id = $_GET['plan_id'];
      $shop = $_GET['shop'];
      $charge_id = isset($_GET['charge_id'])? $_GET['charge_id'] : 0 ;
      if (!empty($shop)) {

        $shopAccess = getShop_accessToken_byShop($shop);
        $this->load->library('Shopify', $shopAccess);

        $data = array(
          "recurring_application_charge" => array(
            "id" => $charge_id,
            "status " => "accepted"
          ),
        );
        $year = getYear();
        $charge = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/recurring_application_charges/' . $charge_id . '/activate.json', 'DATA' => $data], true);
        if ($charge) {
          $where = array('shop' => $shop);
          $data1 = array('charge_id' => $charge_id,'plan_id'=>$plan_id);
          $update = $this->AuthModel->UpdateShopDetails($where,$data1);
          if ($update) {
            redirect('Auth/AppLoader?shop=' . $shop);
          } else {
            $charge = $charge->recurring_application_charge;
            $data['installUrl'] = $charge->confirmation_url;
            $this->load->view('auth/escapeIframe', $data);
          }
        } else {
          redirect('Auth/Install_login');
        }
      }
    }
    public function updateAccess_Token($accessToken) {
      if ($_GET['shop'] != '' && $_GET['code'] != '' && $_GET['hmac'] != '') {
        $shopdata = array('shop' => $_GET['shop'], 'code' => $_GET['code'], 'hmac' => $_GET['hmac']);
        $check_shop_exist = $this->AuthModel->check_ShopExist($_GET['shop']);
        if ($check_shop_exist) {
          $this->AuthModel->update_Shop($shopdata, $accessToken);
        } else {
          $this->AuthModel->add_newShop($shopdata, $accessToken);
        }
      }
    }

    public function home() {
      $code = $this->input->get('code');
      $shop = $this->input->get('shop');
      if (empty($shop)) {
        echo 'Unauthorized Access!';
        exit;
      }
      $this->AppLoader($shop);
    }

    public function AppLoader($shop='') {
      $shop = $this->input->get('shop');
      if (empty($shop)) {
        echo 'Unauthorized Access!';
        exit;
      }
      if (isset($shop)) {
        if ($shop != '') {
          $accessData = getShop_accessToken_byShop($shop);
          if (count($accessData) > 0) {
            if ($accessData['ACCESS_TOKEN'] != '') {
              $data['access_token'] = $accessData['ACCESS_TOKEN'];
              if ($this->db->table_exists('setting') === TRUE) {
                $this->insertSettingValue($shop);
              }
              else {
                $this->createTableSetting($shop);
              }
              $this->addScript();
              redirect('Home/dashboard?shop='.$shop);
            } else {
              redirect('Auth/Install_login');
            }
          } else {
            redirect('Auth/Install_login');
          }
        }
      } else {
        redirect('Auth/Install_login');
      }
    }
    public function addScript() {
    $shop       = $_GET['shop'];
    // echo $_GET['shop'];
    // print_r($shop);
    $shopAccess = getShop_accessToken_byShop($shop);

    $this->load->library('Shopify', $shopAccess);

    // Run themes api in order to get all the themes info installed in the store
    $themes = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/2020-04/themes.json']);

    $themes_array = $themes->themes;

    // Get published theme id
    $theme_id;
    foreach ($themes_array as $theme) {
      if ($theme->role == "main") {
        $theme_id = $theme->id;
      }
    }

    // Get contents of theme.liquid
    $theme_template_contents = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/2020-04/themes/'.$theme_id.'/assets.json?asset[key]=layout/theme.liquid'], true);
    $theme_template_contents = $theme_template_contents->asset->value;

    // Custom script
    $custom_script = "{% include 'wish-list-popup' %}";
    if (strpos($theme_template_contents, $custom_script) === false) {
      $bodytagpos = strpos($theme_template_contents, '</body>');
      $new_theme_str = substr_replace($theme_template_contents, $custom_script, $bodytagpos, 0);

      $params = array(
        'asset' => array(
          "key" => "layout/VW_WISHLIST_BKP_theme.liquid",
          "source_key" => "layout/theme.liquid"
        )
      );

      // Run backup api
      $theme_backup = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/2020-04/themes/'.$theme_id.'/assets.json', 'DATA' => $params], true);

      // Including our snippet in theme.liquid file
      $new_html = array(
        "asset" => array(
          "key" => "layout/theme.liquid",
          "value" => $new_theme_str
        )
      );

      // Finally create a button
      $theme_template = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/2020-04/themes/'.$theme_id.'/assets.json', 'DATA' => $params], true);

    }

}

}
