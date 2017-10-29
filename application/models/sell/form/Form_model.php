<?php
include_once(dirname(BASEPATH).'/inherit/BaseModel.php');
class Form_model extends BaseModel{
	/**
	 * @var table
	 */
	protected $table = "t_sell_form";
	protected $pk = "id";

	/**
	 * @fields
	 */
	public $id,$user_id,$client_id,$total_num,$total_price,$payment,$status,$remark,$create_at,$update_at,$create_user_id,$update_user_id;

	function __construct(){
		$this->load->model('sell/form/FormSpu_model',"MSpu",true);
		$this->load->model('sell/form/FormSku_model',"MSku",true);
	}

	/**
	 * @return array
	 */
	static function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => '销售员ID',
			'client_id' => '客户ID',
			'tatol_num' => '合计数量',
			'total_price' => '合计金额',
			'total_price' => '合计金额',
			'payment' => '支付方法',
			'remark' => '备注',
			'status' => '状态',
			'create_at' => '创建时间',
			'update_at' => '更新时间',
			'create_user_id' => '创建人ID',
			'update_user_id' => '更新人ID',
		];
	}

	/**
	 * @return object
	 */
	static function describe()
	{
		$data = (object)array();
		$data->desc = "销售表";
		$data->name = "From";

		return $data;
	}

	/**
	 * @return string
	 */
	public function getStatusName(){
		switch($this->status){
			case 0:
				return "新单";
			default:
				return "其他";
		}
	}

	/**
	 * 保存前执行
	 */
	protected function beforeSave()
	{
		//设置时区
		date_default_timezone_set('Asia/Shanghai');

		//保存当前时间戳
		$this->user_id = 0;
		$this->update_at = time();
		$this->update_user_id = 0;

		//如果是新增
		if(empty($this->id)){
			$this->create_at = time();
			$this->create_user_id = 0;
		}

		//父类方法
		parent::beforeSave(); // TODO: Change the autogenerated stub
	}

	/**
	 * 支付方式映射
	 */
	public function getPaymentMap(){
		return (object)[
			0=> "默认付款方式",
			1=>"现金",
			2=>"工行汇款",
			3=> "农行汇款",
			4=> "建行汇款",
			5=> "交行汇款",
			6=> "POS机刷卡",
			7=> "其他",
			8=> "微信",
			9=> "未付",
		];
	}

	/**
	 * 添加订单
	 */
	public function add($data){
		//开始事务
		$this->db->trans_strict(FALSE);
		$this->db->trans_begin();

		//保存订单
		$this->load($data);
		$this->save();

		//遍历spu
		foreach($data["selectList"] as $spu_data){
			$spu = $this->MSpu->_new();
			$spu->form_id = $this->id;
			$spu->spu_id = $spu_data["spu_id"];
			$spu->snap_price = $spu_data["price"];
			$spu->snap_pic = $spu_data["pic"];
			$spu->snap_pic_normal = $spu_data["pic_normal"];
			$spu->save();
			//遍历sku
			foreach($spu_data["skus"] as $sku_data){
				$sku = $this->MSku->_new();
				$sku->fspu_id = $spu->id;
				$sku->sku_id = $sku_data["sku_id"];
				$sku->color = $sku_data["color"];
				$sku->size = $sku_data["size"];
				$sku->num = $sku_data["num"];
				$sku->save();
			}
		}

		//事务结束处理
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
}
?>    