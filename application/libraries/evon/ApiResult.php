<?php
class ApiResult
{
    //检测参数
    public function checkApiParameter($list,$code){
        foreach($list as $item){
            if(!isset($_REQUEST[$item])){
                $this->sentApiError($code,"parameter missing [$item]");
            }
        }
    }

    //返回成功信息
    public function sentApiSuccess($data = null){
        header('Content-type: application/json');
        $result = $this->getResultObject();
        $result->data = $data;
        exit(json_encode($result));
    }

    //返回失败信息
    public function sentApiError($code, $msg){
        header('Content-type: application/json');
        $result = $this->getResultObject();
        $result->state->return_code = $code;
        $result->state->return_msg = $msg;
        exit(json_encode($result));
    }

    //获取API返回值结构
    public function getResultObject(){
        $result = (object)array();

        //状态
        $result->state = (object)array();
        $result->state->return_code = 0;
        $result->state->return_msg = "OK";

        //数据
        $result->data = null;

        return $result;
    }

    //读取参数到模型
    public function loadRequest(&$model,$className){
        $parameter = array($className=>Yii::$app->request->queryParams);
        $model->load($parameter);
    }
}