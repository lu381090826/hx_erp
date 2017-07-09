<?php

/**
 * Created by PhpStorm.
 * User: lujagan
 * Date: 2017/6/25
 * Time: 下午10:17
 */
class Ra_model extends HX_Model
{

    private $table = "t_role_authority";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_by_role_id($id)
    {
        $s = "SELECT Frole_id,Fauth_id FROM {$this->table}  WHERE Fstatus = 1 AND Frole_id = ? LIMIT 1;";
        $ret = $this->db->query($s, [$id])->result('array');

        $auths = [];
        foreach ($ret as $row) {
            $auths[] = $row['auth_id'];
        }

        $auths_info = [];
        if (!empty($auths)) {
            $this->load->model('authority_model', 'm_authority_model');
            $auths_info = $this->m_authority_model->get_all_by_auth_ids($auths);
        }

        return $auths_info;
    }

}