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
            $this->load->model('admin/authority_model', 'authority_m');
            $auths_info = $this->authority_m->get_all_by_auth_ids($auths);
        }

        return $auths_info;
    }

    /*
     * request :
     * [
     *  role_id = '',
     *  auth_ids = []
     * ]
     * 插入新角色对应的权限*/
    public function insert_new_ra($request)
    {
        if (!isset($request['auth_ids']) || !isset($request['role_id'])) {
            return 0;
        }
        $insert_arr = [];
        foreach ((array)$request['auth_ids'] as $row) {
            $insert_arr[] = [
                'Frole_id' => $request['role_id'],
                'Fauth_id' => $row,
                'Fstatus' => 1,
            ];
        }
        $this->db->insert_batch($this->table, $insert_arr);
    }
}