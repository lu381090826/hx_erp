<?php
$base_url = base_url();
include_once(dirname(__FILE__)."/class/UrlComponent.php");
include_once(dirname(__FILE__)."/class/ViewComponent.php");
?>

<!-- header -->
<?php $this->load->view("head")?>
<!-- bootstrap -->
<link rel="stylesheet" href="<?=$base_url?>assets/bootstrap/css/bootstrap.min.css">
<script src="<?=$base_url?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- vue -->
<script src="<?=$base_url?>assets/vue/vue.js"></script>

<!-- 内容 -->
<?php $this->load->view($page,$param);?>


<!-- footer -->
<?php $this->load->view("footer")?>

<!-- public -->
<script>
    $(function(){
        $("#subnav").on('change', function(e) {
            var value = e.target.value;
            if(value != "#")
                location.href = value;
        });
    })
</script>