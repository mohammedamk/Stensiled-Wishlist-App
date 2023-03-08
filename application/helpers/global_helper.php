<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
function getShop_accessToken_byShop($shop = null)
{
    $ci      = &get_instance();
    $query   = $ci->db->query("SELECT * FROM `shopify_stores` where shop='" . $shop . "' limit  0,1");
    $rowdata = $query->row();
    if ($rowdata) {
        $data = array(
            'API_KEY'      => $ci->config->item('shopify_api_key'),
            'API_SECRET'   => $ci->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $rowdata->shop,
            'ACCESS_TOKEN' => $rowdata->token,
        );
        return $data;
    }
}

function json_send($data) {
  header('Content-Type: application/json');
  echo json_encode($data);
}

function getYear() {
  $curr_date = date('m/d/Y h:i:s a', time());
  $curr_month = date('m');
  $curr_year = date('Y');
  $api_arr = ['-01', '-04', '-07', '-10'];
  $api_end = '';

  if($curr_month == 1) {
    $api_end = ($curr_year - 1) . $api_arr[3];
  } else if($curr_month > 1 && $curr_month <= 4) {
    $api_end = $curr_year . $api_arr[0];
  } else if($curr_month > 4 && $curr_month <= 7) {
    $api_end = $curr_year . $api_arr[1];
  } else if($curr_month > 7 && $curr_month <= 10) {
    $api_end = $curr_year . $api_arr[2];
  } else if($curr_month > 10 && $curr_month <= 12) {
    $api_end = $curr_year . $api_arr[3];
  }
  return $api_end;
}
function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function checkSignature($token,$ci)
 {
     $parts = explode('.', $token);
     $signature = array_pop($parts);
     $check = implode('.', $parts);
     $shop = null;
     $body = json_decode(base64url_decode($parts[1]));
     if (isset($body->dest)) {
         $url = parse_url($body->dest);
         $shop = isset($url['host']) ? $url['host'] : null;
     }
     $secret = $ci->config->item('shopify_secret');
     $hmac = hash_hmac('sha256', $check, $secret, true);
     $encoded = base64url_encode($hmac);
     return $encoded === $signature;
 }

function IsValidRequest()
{
  $ci =& get_instance();
  $return = ['code'=>200,"msg"=>"Success Request"];
  $apiKey = $ci->config->item('shopify_api_key');
  $headers = apache_request_headers();
  if(isset($headers['Authorization'])){
      $token = str_replace('Bearer ', '', $headers['Authorization']);
      if (!checkSignature($token,$ci)) {
        $return = ['code'=>100,"msg"=>"Unable to verify signature"];
        return $return;
        exit;
      }
      $parts = explode('.', $token);
      $body = json_decode(base64url_decode($parts[1]));
      $now = time();
      if($now > $body->exp || $body->nbf > $now){
        $return = ['code'=>100,"msg"=>"Token Expired"];
        return $return;
        exit;
      }

      if(strpos($body->iss,'myshopify.com') == false || strpos($body->dest, 'myshopify.com') == false) {
        $return = ['code'=>100,"msg"=>"Requestor not Verified"];
        return $return;
        exit;
      }
      if ($apiKey != $body->aud) {
        $return = ['code'=>100,"msg"=>"Not Requested from the My App"];
        return $return;
        exit;
      }

      $return = $return;
      return $return;
  }else{
    $return = ['code'=>100,"msg"=>"Not Requested from Shopify admin"];
    return $return;
  }
}
