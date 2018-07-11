<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once(dirname(BASEPATH).'/inherit/BaseController.php');
class Report extends BaseController {
    /**
     * constructor.
     */
    function __construct()
    {
        //父类
        parent::__construct();

        //环境变量设置
        $this->_controller->api = "sell/Api";
        $this->_controller->views = "sell/report/Report";
        $this->_controller->controller = "sell/report/Report";
        $this->_controller->layout = "layout/amaze/hx";

        //类库
        $this->load->library('evon/ApiResult','','apiresult');

        //加载模型
        $this->load->model('sell/order/Order_model',"m_order",true);
        $this->load->model('sell/client/Client_model',"m_client",true);
        $this->load->model('sell/order/OrderSpu_model',"m_spu",true);
        $this->load->model('sell/order/OrderSku_model',"m_sku",true);
        $this->load->model('goods/Goods_model',"m_goods",true);
        $this->load->model('goods/Sku_model',"m_good_sku",true);
    }

    /**
     * index
     */
    public function index(){
        $this->show("index",[]);
    }

    //导出到Excel
    public function export(){
        $param = (array)json_decode($_POST["data"]);
        if($param["export_type"] == "sell"){
            $this->export_sell($param);
        }
        else{
            $this->export_client($param);
        }
    }

    //导出客户精选
    private function export_client($param){
        //获取数据
        $list = $this->m_order->getReportClient($param);
        //var_dump($list[1]);
        //die;

        //先清除一下
        ob_end_clean();
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $excel = $this->phpexcel;

        //设置标题
        $excel->getActiveSheet()->setCellValue("A1", "客户");
        $excel->getActiveSheet()->setCellValue("B1", "数量");
        $excel->getActiveSheet()->setCellValue("C1", "金额");
        $excel->getActiveSheet()->setCellValue("D1", "款号");
        $excel->getActiveSheet()->setCellValue("E1", "颜色");
        $excel->getActiveSheet()->setCellValue("F1", "数量");

        //为单元格赋值
        $index = 2;
        foreach ($list as $value){
            //主项
            $excel->getActiveSheet()->setCellValue("A".($index), $value->client_name);
            $excel->getActiveSheet()->setCellValue("B".($index), $value->num);
            $excel->getActiveSheet()->setCellValue("C".($index), $value->amount);
            //合并单元格
            $excel->getActiveSheet()->mergeCells("A$index:A".($index+$value->sku_count-1));
            $excel->getActiveSheet()->mergeCells("B$index:B".($index+$value->sku_count-1));
            $excel->getActiveSheet()->mergeCells("C$index:C".($index+$value->sku_count-1));
            //拆分项
            foreach ($value->sku as $key=>$sku){
                $excel->getActiveSheet()->setCellValue("D".($index), $sku->spu_id);
                $excel->getActiveSheet()->setCellValue("E".($index), $sku->color);
                $excel->getActiveSheet()->setCellValue("F".($index), $sku->num);
                $index++;
            }
        }

        //输出到浏览器
        $this->export_excel($excel);
    }

    //导出销售报表
    private function export_sell($param){
        //获取数据
        $list = $this->m_order->getReportSell($param);
        //var_dump($list["A1245"]);
        //die;

        //加载PHPExcel的类
        ob_end_clean();
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $excel = $this->phpexcel;

        //设置标题
        $excel->getActiveSheet()->setCellValue("A1", "款号");
        $excel->getActiveSheet()->setCellValue("B1", "数量");
        $excel->getActiveSheet()->setCellValue("C1", "金额");
        $excel->getActiveSheet()->setCellValue("D1", "颜色");
        $excel->getActiveSheet()->setCellValue("E1", "码数");
        $excel->getActiveSheet()->setCellValue("F1", "数量");
        $excel->getActiveSheet()->setCellValue("G1", "金额");

        //为单元格赋值
        $index = 2;
        foreach ($list as $value){
            //主项
            $excel->getActiveSheet()->setCellValue("A".($index), $value->spu_id);
            $excel->getActiveSheet()->setCellValue("B".($index), $value->num);
            $excel->getActiveSheet()->setCellValue("C".($index), $value->amount);
            //合并单元格
            $excel->getActiveSheet()->mergeCells("A$index:A".($index+$value->sku_count-1));
            $excel->getActiveSheet()->mergeCells("B$index:B".($index+$value->sku_count-1));
            $excel->getActiveSheet()->mergeCells("C$index:C".($index+$value->sku_count-1));
            //拆分项
            foreach ($value->sku as $key=>$sku){
                $excel->getActiveSheet()->setCellValue("D".($index), $sku->color);
                $excel->getActiveSheet()->setCellValue("E".($index), $sku->size);
                $excel->getActiveSheet()->setCellValue("F".($index), $sku->num);
                $excel->getActiveSheet()->setCellValue("G".($index), $sku->amount);
                $index++;
            }
        }

        $this->export_excel($excel);
    }

    //导出文件到浏览器
    private function export_excel($excel,$name="")
    {
        //输出到浏览器
        $write = new PHPExcel_Writer_Excel2007($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . date('YmdHi') . $name.'.xlsx"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }
}