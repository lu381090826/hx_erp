<?php

class XMG_Controller extends CI_Controller
{
	public  $addtime;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper('url');
        
        $this->addtime = date("Y-m-d H:i:s");
        
        $this->load->model("depot/index_model");
    }
    public function get_page($dbname,$sql){
    
    	$page = @isset($_REQUEST['page'])?$_REQUEST['page']:1;
    	$page_url = @$_SERVER["REQUEST_URI"];
    
    	$count = $this->index_model->count_db($dbname,$sql);
    
    	$params = array('count' => $count, 'page' => $page,'page_url'=>$page_url);
    
    	//加载分页类
    	$this->load->library('page',$params);
    
    	$this->pageStr = $this->page->GetPagerContent();
    
    	return array("page"=>$page,"pageStr"=>$this->pageStr);
    }
    function return_msg($array=NULL,$format='json'){
    	if ($format=='xml'){
    		echo "<data>";
    		foreach($array as $key=>$value){
    			echo "<$key>$value</$key>";
    		}
    		echo "</data>";
    	}else{
    		echo json_encode($array);
    	}
    	exit;
    }
}

?>
