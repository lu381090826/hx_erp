<?php
/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Sku_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database('old',true);
    }

    public function getFirstInfo()
    {
        $s="SELECT * FROM V_SKUINFO LIMIT 10;";

        $ret=$this->old->query($s);

        return $ret->row(0);
    }

}