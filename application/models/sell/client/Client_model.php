<?php
include_once(dirname(BASEPATH).'/inherit/BaseModel.php');
class Client_model extends BaseModel{
    /**
     * @var table
     */
    protected $table = "t_client";
    protected $pk = "id";


    /**
     * @fields
     */
    public $id,$name,$phone,$addr;

    /**
     * @return array
     */
    static function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'phone' => '电话',
            'addr' => '地址',
        ];
    }

    /**
     * @return object
     */
    static function describe()
    {
        $data = (object)array();
        $data->desc = "客户管理";
        $data->name = "Client";

        return $data;
    }

    public function likeSearch($key){
        //构建条件
        $select = $this->db
            ->or_like('Fid', $key)
            ->or_like('Fname', $key)
            ->or_like('Fphone', $key);

        //查询表
        $select = $select->get($this->table);

        //构成返回结果
        $list = array();
        foreach($select->result() as $data){
            $item = $this->_new();
            $item->load((array)$data);
            $list[] = $item;
        }

        //返回
        $result = (object)array();
        $result->list = $list;
        $result->model = $this;
        return $result;
    }
}
?>    