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
    public $id,$name,$phone,$addr,$delivery_type,$delivery_addr;

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
            'delivery_type' => '收货方式',
            'delivery_addr' => '收货地址',
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

    /**
     * 收货方式列表
     * @return array
     */
    static function getDeliveryTypeMap(){
        return (object)[
            0=> "快递",
            1=> "现场取货",
        ];
    }

    /**
     * 收货方式
     * @return string
     */
    public function deliveryTypeName(){
        switch($this->delivery_type){
            case 0:
                return "快递";
            case 1:
                return "上门取货";
        }
    }

    /**
     * 模糊查询（全部）
     * @param $key
     * @return object
     */
    public function searchLikeAll($key){
        //构建条件
        $select = $this->db
            ->or_like('Fid', $key)
            ->or_like('Fname', $key)
            ->or_like('Fphone', $key)
            ->or_like('Fdelivery_type', $key)
            ->or_like('Fdelivery_addr', $key);

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

    /**
     * 模糊查询
     * @param int $page
     * @param int $size
     * @param string $key
     * @param array $sort
     * @return object
     */
    public function searchLike($page=1,$size=20,$key="",$sort=[]){
        //按照加官，使用sql前所有字段加F
        $sort = $this->addPrefixKeyValue($sort);

        //查找数量
        $count = $this->getLikeCount($key);

        //条件筛选
        $start = ($page-1)*$size;
        $select = $this->db
            ->or_like('Fname', $key)
            ->or_like('Fphone', $key)
            ->limit($size,$start);
        //->get($this->table);

        //排序
        foreach($sort as $key=>$value)
            $select = $select->order_by($key,$value);

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
        $result->count = $count;
        $result->model = $this;
        return $result;
    }

    /**
     * 获取数量
     * @param $condition
     * @return mixed
     */
    protected function getLikeCount($key){
        $query = $this->db->select('COUNT(*) AS `Fnums`')
            ->or_like('Fname', $key)
            ->or_like('Fphone', $key)
            ->get($this->table);
        $result = $query->row();
        $count = $result->nums;
        return $count;
    }
}
?>    