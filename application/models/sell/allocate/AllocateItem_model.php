<?phpinclude_once(dirname(BASEPATH).'/inherit/BaseModel.php');class AllocateItem_model extends BaseModel{    /**     * @var table     */    protected $table = "t_sell_allocate_item";    protected $pk = "id";    /**     * @fields     */    public $id,$allocate_id,$order_id,$order_spu_id,$order_sku_id,$spu_id,$sku_id,$num,$status;    /**     * @return object     */    static function describe(){        $data = (object)array();        $data->desc = "AllocateItem";        $data->name = "报货单项";        return $data;    }    /**     * @return array     */    static function attributeLabels()    {        return [            "id"=>"ID",            "allocate_id"=>"配货单ID",            "order_id"=>"销售单ID",            "order_spu_id"=>"销售单SPU",//sku项            "order_sku_id"=>"销售单SKU",//sku项            "spu_id"=>"SPU",            "sku_id"=>"SKU",            "num"=>"报货数量",            "status"=>"状态",        ];    }    /**     * 获取配货状态,返回key-value     * @param $order_id     * @return array     */    public function getAllocateStatus($order_id,$filterAllocatId = null){        //定义条件        $select = "order_sku_id";        $select_sum = "num";        $condition = array("order_id"=>$order_id);        $groupby = array("order_sku_id");        //添加配货单号过滤        if($filterAllocatId)            $condition["allocate_id !="] = $filterAllocatId;        //转义        $select_esc = $this->addPrefixField($select);        $select_sum_esc = $this->addPrefixField($select_sum);        $condition = $this->addPrefixKeyValue($condition);        $groupby = $this->addPrefixFields($groupby);        //查询已经配数量        $query = $this->db            ->select($select_esc)            ->select_sum($select_sum_esc)            ->where($condition)            ->group_by($groupby)            ->get($this->table);        $list = $query->result();        //重整理成key/value形式        $result = array();        foreach($list as $item){            $result[$item->{$select}] = $item->{$select_sum};        }        //返回        return $result;    }    /**     * @return string     */    public function getStatusName(){        switch($this->status){            case 0:                return "未配货";            case 1:                return "完成配货";            default:                return "部分配货";        }    }}?>    