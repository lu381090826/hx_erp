﻿<?phpdefined('BASEPATH') OR exit('No direct script access allowed');include_once(dirname(BASEPATH).'/inherit/BaseController.php');class Order extends BaseController {    /**     * constructor.     */    function __construct()    {        //父类        parent::__construct();        //环境变量设置        $this->_controller->api = "sell/Api";        $this->_controller->views = "sell/order/Order";        $this->_controller->controller = "sell/order/Order";        $this->_controller->layout = "layout/amaze/hx";        //类库        $this->load->library('evon/ApiResult','','apiresult');        //加载模型        $this->load->model('sell/order/Order_model',"model",true);        $this->load->model('sell/client/Client_model',"m_client",true);        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);        $this->load->model('sell/order/OrderSku_model',"m_sku",true);        $this->load->model('goods/Goods_model',"m_goods",true);        $this->load->model('goods/Sku_model',"m_good_sku",true);        $this->load->model('admin/User_model',"m_user",true);        $this->load->model('goods/Shop_model',"m_shop",true);    }    /**     * index     */    public function index()    {        //设置模型        $model = $this->model;        //获取状态列表        $statusMap = $model->getStatusMap();        //获取店铺信息        $uid = $this->session->uid;        $user = $this->m_user->get_user_info($uid);        $shop = isset($user["shop_info"][0])?$user["shop_info"][0]:null;        //调用视图        $this->show("list",[            "statusMap"=>$statusMap,            "shop" => $shop        ]);    }    /**     * index     */    public function index2()    {        $model = $this->model;        $param = $_REQUEST;        $page = isset($param["page"])?$param["page"]:1;        $size = isset($param["size"])?$param["size"]:20;        $condition = isset($param["condition"])?(array)json_decode($param["condition"]):[];        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"ASC"];        $result = $model->search($page,$size,$condition,$sort);        $this->show("index",[            "searched"=>$result,            "page"=>$page,            "size"=>$size,        ]);    }    /**     * create     */    public function create(){        $model = $this->model;        $param = $_REQUEST;        if (!empty($param) && $model->load($param) && $model->save()) {            redirect($this->_controller->views."/index");        } else {            $this->show("create",[                "model"=>$model,            ]);        }    }    /**     * update     */    public function update2($id){        $model = $this->model->get($id);        $param = $_REQUEST;        if (!empty($param) && $model->load($param) && $model->save()) {            redirect($this->_controller->views."/index");        } else {            $this->show("update",[                "model"=>$model,            ]);        }    }    /**     * delete     */    public function delete($id){        $model = $this->model->get($id);        $bool = $model->delete();        if($bool)            redirect($this->_controller->views."/index");    }    /**     * view     */    public function view($id){        $model = $this->model->get($id);        var_dump($model);    }    /**     * 添加订单     */    public function add(){        $model = $this->model;        $model->order_num = $this->model->createOrderNum();        $model->payment = 0;        $model->client = null;        $model->goods = [];        $model->allocate_mode = 0;        $paymentMap = $this->model->getPaymentMap();        $deliveryTypeMap = $this->m_client->getDeliveryTypeMap();        $allocateModeMap = $this->model->getAllocateModeMap();        //获取店铺信息        $uid = $this->session->uid;        $user = $this->m_user->get_user_info($uid);        $shop = isset($user["shop_info"][0])?$user["shop_info"][0]:null;        //调用视图        $this->show("order",[            "model"=>$model,            "isNew"=>1,            "spuAllowChange"=>1,            "paymentMap"=>$paymentMap,            "deliveryTypeMap"=>$deliveryTypeMap,            "allocateModeMap"=>$allocateModeMap,            "shop"=>$shop        ]);    }    /**     * 修改订单     */    public function modify($id){        //获取模型信息        $model = $this->model->get($id);        $paymentMap = $this->model->getPaymentMap();        $deliveryTypeMap = $this->m_client->getDeliveryTypeMap();        $allocateModeMap = $this->model->getAllocateModeMap();        //判断是否存在在，并且能更新        if(empty($model->id) || (string)$model->status != "0") {            redirect($this->_controller->views . "/index");        }        //获取客户        $client = empty($model->client_id)?null:$this->m_client->get($model->client_id);        $model->client = $client;        //获取店铺信息(直接调用获取店铺)        $shop = null;        if($model->shop_id){            $search = $this->m_shop->shop_detail_by_id($model->shop_id);            if($search["result_rows"])                $shop = $search["result_rows"];        }        //获取列表        $model->goods = $model->getGoods();        //判断是否允许更改spu        $spuAllowChange = 1;        $create_date = date("Y-m-d",$model->create_at);        $next_time = strtotime($create_date)+86400;        if(time()>$next_time) $spuAllowChange = 0;        //是否为新单(复用spuAllowChange，可以完全替代)        $isNew = $spuAllowChange;        //跳转到视图        $this->show("order",[            "model"=>$model,            "isNew"=>$isNew,            "spuAllowChange"=>$spuAllowChange,            "paymentMap"=>$paymentMap,            "deliveryTypeMap"=>$deliveryTypeMap,            "allocateModeMap"=>$allocateModeMap,            "shop"=>$shop        ]);    }    /**     * 添加/修改（异步）     */    public function update_asyn(){        //参数检测        $this->apiresult->checkApiParameter(['user_id','client_id',"total_num","total_price","payment","client"],-1);        $param = $_REQUEST;        $param["type"] = 0;        $param["status"] = 0;        $param_client = $_REQUEST["client"];        unset($_REQUEST["client"]);        unset($_REQUEST["num_allocat"]);        //保存客户信息        if($param_client){            $client = $this->m_client->get($param_client["id"]);            $client->load($param_client);            $client->save();        }        //设置模型        if(isset($param["id"]))            $model = $this->model->get($param["id"]);        else            $model = $this->model;        //事务方式处理表单        $bool = $model->updateOrder($param);        //输出结果        if($bool)            $this->apiresult->sentApiSuccess();        else            $this->apiresult->sentApiError(-1,"fail");    }    /**     * 作废（异步）     */    public function scrap_asyn(){        //参数检测        $this->apiresult->checkApiParameter(['id'],-1);        $id = $_REQUEST["id"];        //获取模型        $model = $this->model->get($id);        //判断并修改状态        if($model->status == 0)        {            $model->status = 3;            if($model->save())                $this->apiresult->sentApiSuccess();            else                $this->apiresult->sentApiError(-1,"fail");        }        else        {            $this->apiresult->sentApiError(-1,"status error");        }    }    /**     * 打印     * @param $id     */private  function toStr($bytes) {        $str = '';        foreach($bytes as $ch) {            $str .= chr($ch);        }           return $str;    }  private function createimg($qrcode_weixin,$qrcode_zhifubao){			 $this->load->helper("phpqrcode");	//创建一张图片 宽度 576PX  高度为288PX 	//生成两个微信和支付宝 288大小的二维码 插入到图片中		$errorCorrectionLevel ="H" ;//容错级别               $matrixPointSize = 11.52;//生成图片大小              //生成二维码图片               $object = new \QRcode();			  				$object->png($qrcode_weixin, "weixin.png", $errorCorrectionLevel, $matrixPointSize, 2); 		   				$object->png($qrcode_zhifubao, "zhifubao.png", $errorCorrectionLevel, $matrixPointSize, 2); 				$image = imagecreatetruecolor(576, 288);				$weixin = imagecreatefrompng('weixin.png');				$zhifubao = imagecreatefrompng('zhifubao.png');				imagecopyresampled($image,$weixin,0, 0,0, 0,288,288, imagesx($weixin), imagesy($weixin)); 				imagecopyresampled($image,$zhifubao,288, 0,0, 0,288,288, imagesx($zhifubao), imagesy($zhifubao)); 			     //  Header("Content-type: image/png");			   			 //  imagepng($image);			   	 			 		return   $image;		 }private  function CmdLightcensoring($img){	//生成图片未做,先用一个现成的图片	//  $img = imagecreatefromjpeg("1233.jpg"); 	  	  	 // 把图片缩放到576宽度 			$data=array();			$offset = 0;            $w =imagesx($img);            $rw = $w / 8;            $rh = imagesy($img);            $h = (($rh + 23) / 24) * 24;            $img_seg_height = $h;            $img_seg_count = $h / $img_seg_height;            for ($si = 0; $si < $img_seg_count; $si++)            {		$data= array_merge($data,array( 0x1d, 0x76,0x30,0x00 & 0x01, $rw % 256,floor($rw / 256),floor($img_seg_height % 256 - 1),floor(($img_seg_height / 256))));    for ($ph = 0; $ph < $img_seg_height; $ph++)                 {				for ($rwi = 0; $rwi < $rw; $rwi++)                    {						$t =  0x00;                        for ($j = 0; $j < 8; ++$j)                        {                            $image_x = $rwi * 8 + $j;                            $image_y = $ph + $si * $img_seg_height;                           $v = 0;                            if ($image_y >= $rh)                            {                                $v = 0;                            }                            else                            {							  $rgb = imagecolorat($img, $image_x, $image_y);							 							    $r   = ($rgb >> 16) & 0xFF;							    $g   = ($rgb >> 8) & 0xFF;							   $b   = $rgb & 0xFF;							  $Gray = $r*0.299 + $g*0.587 + $b*0.114;			                                 if ($Gray <127)                                {									                                     $v =  0x01;                                }                             }                             $t |=  ($v << (7 - $j));                        }                  array_push($data , ($t));                    }                }            }         return $data;			 } 	 private function PrintDemo($data)	 {		 		 $handle = fopen( md5( getenv($_SERVER['REMOTE_ADDR'])."9100"),"a+");//用域名IP和端口生成打印机MD5文件	if (flock($handle,LOCK_EX))  //文件锁防止并发打印,一个打印机一次只能有一个连接.必须上一个连接断开其它才能接受新的连接否则其它连接等持 . 	{				 $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);    		if(socket_connect($socket,$_SERVER['REMOTE_ADDR'],9100) == false){			echo '....:'.socket_strerror(socket_last_error());exit();		}else		{						for($printqty=0 ;$printqty < $this->input->post('printqty');$printqty++) 				{						$str= $this->toStr (array(0x1b,0x40,0x1b,0x64,0));///初始化打印指令						$str.=  $this->toStr (array( 27, 69, 0x00, 27, 77, 0x02, 29, 33, 0x00))."第".($printqty+1)."页\r\n". $this->toStr (array( 0x1b,0x40));///小字模式						$str.= $this->toStr (array( 27, 69, 0x01, 27, 77, 0x02, 29, 33, 0x33))."    T·G\r\n". $this->toStr (array( 0x1b,0x40));///大字倍高倍宽模式						$str.= $this->toStr (array(  27, 69, 0x01, 27, 77, 0x02, 29, 33, 0x11))."       THX GIVING\r\n". $this->toStr (array( 0x1b,0x40));///中字模式						$str.= $this->toStr (array(  27, 69, 0x01, 27, 77, 0x02, 29, 33, 0x00))." \r\n". $this->toStr (array( 0x1b,0x40));///小字模式						$str.= "单号 ". $data["order"]->order_num  ."   日期 " .date("Y-m-d H:i:s",  $data["order"]->create_at). "\r\n";						$str.= "地址：".$data["shop"]["address"] . "\r\n";						$str.= "档口座机：".$data["shop"]["phone"]. "\r\n";						$str.= "手机/微信：".$data["shop"]["owner_mobile"]. "\r\n\r\n";						$str.= "客户：".$data["client"]->name. "(".$data["client"]->phone.")\r\n\r\n";						$str.= "备注： ".$data["order"]->remark."\r\n";						$str.= "款号     颜色   尺码  数量    单价     小计 \r\n";											  foreach ($data["order"]->goods as $spu) 					  					  {						foreach ($spu->skus as $sku)						{											if($sku->num > 0) 							{							 								$str.=sprintf('%-10s', $spu->spu_id); 								$str.=sprintf('%-8s', $sku->color) ;								$str.=sprintf('%-6s', $sku->size);  								$str.=sprintf('%-7s', $sku->num ); 								$str.=sprintf('%-7s', $spu->snap_price);								$str.=sprintf('%-7s', $sku->num * $spu->snap_price )."\r\n";							}						}    						 											  }					  						$str.= "\r\n总数量:    ".$this->toStr (array(  27, 69, 0x01, 27, 77, 0x02, 29, 33, 0x11)).sprintf('%-6s',$data["order"]->total_num).$this->toStr (array(  27, 69, 0x00, 27, 77, 0x02, 29, 33, 0x00))." 总金额: ".$this->toStr (array(  27, 69, 0x01, 27, 77, 0x02, 29, 33, 0x11)).sprintf('%-6s',$data["order"]->total_price)."\r\n".$this->toStr (array(  27, 69, 0x01, 27, 77, 0x02, 29, 33, 0x00,0x1b,0x40));						$str.= "\r\n支付方式:".$data["order"]->getPaymentName()." 开单人:".$data["seller"]["name"]."\r\n";						$str.="工行ICBC：".$data["shop"]["bank_account"] ."\r\n";						$str.="农行 ABC：".$data["shop"]["bank_account_2"] ."\r\n";						$str.="户名：".$data["shop"]["bank_name"] ."           开户行：".$data["shop"]["bank_deposit"]."\r\n";						$str.="支付宝：".$data["shop"]["alipay_account"] ."      户名：".$data["shop"]["alipay_name"]."\r\n";						$str.="注意：产品如有质量问题，请七天内返还给档口，我们将及时为您代修或换货处理，逾期不受理，钱款请当面点清,离店概不负责\r\n";											if( socket_write($socket,mb_convert_encoding($str,"GBK","UTF-8"))==false)//打印图片前先换行移动打印头位置					   {							  echo '向打印机写入错误'.socket_strerror(socket_last_error());					   }					 					   					$img= $this->createimg($data["shop"]["web_home"],$data["shop"]['email']); //生成支付宝和微信的二维码													$img=$this->CmdLightcensoring($img);//把图片转化为打印机指令		  					$msg = $this->toStr ($img);				   		  					if( socket_write($socket,$msg,strlen($msg))==false)					   {							  echo '向打印机写入错误'.socket_strerror(socket_last_error());					   }						if( socket_write($socket,"\r\n")==false)//打印完图片以后要换行改变切刀位置理论上图片有多高就要插入多少换行 					   {							  echo '向打印机写入错误'.socket_strerror(socket_last_error());					   }						$msg = $this->toStr (array( 0x1b,0x40,27, 69, 0x00, 27, 77, 0x02, 29, 33, 0x11 ))."    微信        支付宝\r\n". $this->toStr (array( 0x1b,0x40));///小字模式						if( socket_write($socket,mb_convert_encoding($msg,"GBK","UTF-8"))==false)					   {							  echo '向打印机写入错误'.socket_strerror(socket_last_error());					   }						$msg ="\r\n\r\n\r\n\r\n". $this->toStr (array( 27, 69, 0x00, 27, 77, 0x02, 29, 33, 0x11,0x1b,0x64,0,27,64,27,109 ));///切刀命令 //切刀以前先改变切刀位置	   		   						if( socket_write($socket,$msg,strlen($msg))==false)					   {							  echo 'fail to write'.socket_strerror(socket_last_error());					   }	     				}  				// echo "打印完成了";	 }	socket_close($socket);//工作完毕，关闭套接流 			   				 flock($handle,LOCK_UN); 	 	 	}		}    public function printout($id){        //获取配置         $this->config->load('lodop');       $lodop = $this->config->item('lodop');        //获取订单，及商品信息        $model = $this->model->get($id);        $model->goods = $model->getGoods();        //获取客户信息        $client = empty($model->client_id)?null:$this->m_client->get($model->client_id);        //获取销售员信息        $seller = empty($model->user_id)?null:$this->m_user->get_user_info($model->user_id);        //获取店铺信息(直接调用获取店铺)        $shop = null;        if($model->shop_id){            $search = $this->m_shop->shop_detail_by_id($model->shop_id);            if($search["result_rows"])                $shop = $search["result_rows"];        }if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post'){  // var_dump( $shop);	 // exit("");	$this->PrintDemo( array("seller"=>$seller,            "client"=>$client,            "order"=>$model,            "lodop"=>$lodop,            "shop"=>$shop));	 }else{        //跳转到视图        $this->show("printout",[            "seller"=>$seller,            "client"=>$client,            "order"=>$model,            "lodop"=>$lodop,            "shop"=>$shop,        ]);}    }    /** 直接报货列表 */    public function deliverys(){        $model = $this->model;        $param = $_REQUEST;        $page = isset($param["page"])?$param["page"]:1;        $size = isset($param["size"])?$param["size"]:20;        $condition = isset($param["condition"])?(array)json_decode($param["condition"]):[];        $sort = isset($param["sort"])?(array)json_decode($param["sort"]):[$model->getPk()=>"ASC"];        $condition["type"] = 1;        $result = $model->search($page,$size,$condition,$sort);        $this->show("delivery_list",[            "searched"=>$result,            "page"=>$page,            "size"=>$size,        ]);    }    /** 添加直接报货 */    public function delivery_create(){        $model = $this->model;        $model->order_num = $this->model->createOrderNum();        $model->payment = 0;        $model->client = null;        $model->goods = [];        $model->allocate_mode = 0;        $paymentMap = $this->model->getPaymentMap();        $deliveryTypeMap = $this->m_client->getDeliveryTypeMap();        $allocateModeMap = $this->model->getAllocateModeMap();        //获取店铺信息        $uid = $this->session->uid;        $user = $this->m_user->get_user_info($uid);        $shop = isset($user["shop_info"][0])?$user["shop_info"][0]:null;        //调用视图        $this->show("delivery_order",[            "model"=>$model,            "isNew"=>1,            "spuAllowChange"=>1,            "paymentMap"=>$paymentMap,            "deliveryTypeMap"=>$deliveryTypeMap,            "allocateModeMap"=>$allocateModeMap,            "shop"=>$shop        ]);    }    /** 添加直接报货提交 */    public function delivery_create_submit(){        //参数检测        $this->apiresult->checkApiParameter(['user_id',"total_num","total_price","payment","client"],-1);        $param = $_REQUEST;        $param["type"] = 1;        $param["status"] = 0;        unset($_REQUEST["client"]);        unset($_REQUEST["num_allocat"]);        //设置模型        if(isset($param["id"]))            $model = $this->model->get($param["id"]);        else            $model = $this->model;        //事务方式处理表单        $bool = $model->updateDelivery($param);        //输出结果        if($bool)            $this->apiresult->sentApiSuccess();        else            $this->apiresult->sentApiError(-1,"fail");    }}