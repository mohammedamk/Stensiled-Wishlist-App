<?php
class AuthModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function GetShopId($shop){
        $shop_id = $this->db->select('id')->where('domain', $shop)->get('shopify_stores')->row()->id;
        return $shop_id;
    }
    public function check_ShopExist($shop = NULL)
    {
        $query = $this->db->query("SELECT * FROM `shopify_stores` where  shop='" . $shop . "'");
        $rows  = $query->num_rows();
        if ($rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function get_shop_details($shop = NULL)
    {
        $shop_details = $this->db->select('charge_id')->where('shop', $shop)->get('shopify_stores');
        if ($shop_details->num_rows() > 0) {
            return $shop_details->row();
        } else {
            return false;
        }
    }
    public function UpdateShopDetails($where = array(), $data = array())
    {

        $state = $this->db->where($where)->update('shopify_stores', $data);
          return $state;

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


    public function save_token($getdata)
    {
        $this->db->select("*");
        $this->db->from("tokenTable");
        $this->db->where("access_token", $getdata["access_token"]); // will check row by column name in database
        // $this->db->where("shop", $getdata["shop"]); // will check row by column name in database
        $this->result = $this->db->get();

        if ($this->result->num_rows() > 0) {
            // you data exist
            return false;
        } else {
            // data not exist insert you information
            $this->db->insert('tokenTable', $getdata);
            return true;
        }
    }
    public function check($shop)
    {
        // $data = array();
        $this->db->select('*');
        $this->db->from('tokenTable');
        $this->db->where('shop', $shop);
        $query = $this->db->get();
        if ($query->num_rows() > 0)

            return $query->result();
    }

    public function update_Shop($data, $accessToken)
    {
        if ($accessToken) {
            $sql = "update  shopify_stores set code='" . $data['code'] . "', hmac='" . $data['code'] . "', token='" . $accessToken . "' where  shop='" . $data['shop'] . "' ";
            $this->db->query($sql);
        }
    }
    public function add_newShop($data, $accessToken)
    {
        $sql = "insert into shopify_stores set code='" . $data['code'] . "', hmac='" . $data['code'] . "', domain='" . $data['shop'] . "',shop='" . $data['shop'] . "', token='" . $accessToken . "' ";
        $this->db->query($sql);
    }
}
