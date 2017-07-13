<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
    <input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);"
           value="返回上一页">
操作成功～！
<?php $this->load->view('footer'); ?>