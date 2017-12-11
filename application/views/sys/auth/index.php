<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<link rel="stylesheet" type="text/css" href="/assets/css/amazeui.page.css">
<br>

<!--功能选项-->
<select data-am-selected id="method_select" title="选择功能">
    <option value="user">用户管理</option>
    <option value="role">角色管理</option>
    <option value="auth" selected>权限管理</option>
    <!--    <option value="get_auths">权限管理</option>-->
</select>
<br>
<hr>
<strong>修改/添加权限控制需联系开发</strong>
<ul class="am-list am-list-border am-list-striped">
    <?php foreach ($result_rows as $key => $vale): ?>
        <?php if ($vale['pid'] == 1): ?>
            <li>
                <div class="am-g">

                    <div class="am-u-sm-4">
                        <?= $vale['auth_name']; ?>
                    </div>
                    <div class="am-u-sm-8">
                        <label class="am-checkbox pauth">
                            <!--<input type="checkbox" name="auth_ids[]" value="<? /*= $vale['id']; */ ?>"
                                   data-am-ucheck>--> 所有权限
                        </label>
                        <?php foreach ($result_rows as $k => $r): ?>
                            <?php if ($r['pid'] == $vale['id']): ?>
                                <label class="am-checkbox">
                                    <!--<input type="checkbox" class="cauth" name="auth_ids[]" value="<? /*= $r['id']; */ ?>"
                                           data-am-ucheck> --><?= $r['auth_name']; ?>
                                </label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                </div>

            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<br>

<!--分页-->
<div id="page"></div>

<?php $this->load->view('footer'); ?>

<script type="text/javascript" src="/assets/js/amazeui.page.js"></script>
<script type="text/javascript" src="/assets/js/sys.js"></script>