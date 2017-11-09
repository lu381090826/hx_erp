<?phpinclude_once(dirname(BASEPATH).'/inherit/BaseModel.php');class Allocate_model extends BaseModel{    /**     * @var table     */    protected $table = "t_sell_allocate";    protected $pk = "id";    /**     * @fields     */    public $id,$order_num,$form_id,$create_at,$create_user_id,$status,$remark;    /**     * Allocate_model constructor.     */    function __construct(){        $this->load->model('sell/allocate/AllocateItem_model',"m_item",true);    }    /**     * @return object     */    static function describe(){        $data = (object)array();        $data->desc = "报货单";        $data->name = "allocate";        return $data;    }    /**     * @return array     */    static function attributeLabels()    {        return [            "id"=>"ID",            "form_id"=>"销售单ID",            "order_num"=>"报货单号",            "create_at"=>"报货时间",            "create_user_id"=>"报货用户ID",            "status"=>"状态",            "remark"=>"备注",        ];    }    /**     * @return string     */    public function getStatusName(){        switch($this->status){            case 0:                return "未完成";            case 1:                return "已完成";            default:                return "其他";        }    }    /**     * 生成销售单号     */    public function createOrderNum(){        list($t1, $t2) = explode(' ', microtime());        $time = (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);        return "HXA".$time;    }    /**     * 添加订单(事务)     */    public function createForm($data){        //开始事务        $this->db->trans_strict(FALSE);        $this->db->trans_begin();        //保存订单        $this->load($data);        $this->save();        //遍历列表        if(!empty($data["list"]) && is_array($data["list"])) {            foreach ($data["list"] as $item) {                $item_model = $this->m_item->_new();                $item_model->load($item);                $item_model->allocate_id=$this->id;                $item_model->save();            }        }        //事务结束处理        if ($this->db->trans_status() === FALSE)        {            $this->db->trans_rollback();            return false;        }        else        {            $this->db->trans_commit();            return true;        }    }    /**     * 保存前执行     */    protected function beforeSave()    {        //设置时区        date_default_timezone_set('Asia/Shanghai');        //如果是新增        if(empty($this->id)){            $this->status = 0;            $this->create_at = time();            $this->create_user_id = $this->session->uid;        }        //父类方法        parent::beforeSave(); // TODO: Change the autogenerated stub    }}?>    