<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="javascript:history.back(-1);" value="返回上一页">
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="" value="编辑">
<input type="button" name="Submit" class="am-btn am-btn-default" onclick="" value="删除">
<table class="am-table">
    <thead>
    </thead>

    <tbody>
    <tr>
        <td>uid</td>
        <td>{uid}</td>
    </tr>
    <tr>
        <td>用户名</td>
        <td>{name}</td>
    </tr>
    <tr>
        <td>手机号</td>
        <td>{mobile}</td>
    </tr>
    <tr>
        <td>邮箱</td>
        <td>{email}</td>
    </tr>
    <tr>
        <td>角色</td>
        <td>{role_id}</td>
    </tr>
    <tr>
        <td>创建时间</td>
        <td>{create_time}</td>
    </tr>
    <tr>
        <td>修改时间</td>
        <td>{modify_time}</td>
    </tr>

    </tbody>
</table>

<?php
$this->load->view('footer');
?>
