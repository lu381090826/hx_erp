<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('head');
?>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a
  href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<!-- 页面内容 开发时删除 -->
<div class="am-g am-g-fixed am-margin-top">
  <div class="am-u-sm-12">
    <h1>你好世界！小可 Amaze UI 模板。</h1>

    <p>这是一个基础的页面，引入 Amaze UI 的 css 、js
      文件。如果你已经熟读文档，使用时删除内容开始开发即可。建议使用前先阅读文档，以提高开发效率。</p>
  </div>
</div>
<div class="am-g am-g-fixed am-margin-top">

</div>

<?php
$this->load->view('footer');
?>
