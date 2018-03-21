<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="/style/css/common.css">
  <link rel="stylesheet" href="/style/css/style.css">
  <script type="text/javascript" src="/style/js/jquery.min.js"></script>
  <script type="text/javascript" src="/style/js/jquery.SuperSlide.js"></script>
  <style>
  .set{
  font-weight:bold;
  }
  </style>
  <script type="text/javascript">
  $(function(){
      $(".sideMenu").slide({
         titCell:"h3", 
         targetCell:"ul",
         defaultIndex:0, 
         effect:'slideDown', 
         delayTime:'500' , 
         trigger:'click', 
         triggerTime:'150', 
         defaultPlay:true, 
         returnDefault:false,
         easing:'easeInQuint',
         endFun:function(){
              scrollWW();
         }
       });
      $(window).resize(function() {
          scrollWW();
      });
  });
  function scrollWW(){
    if($(".side").height()<$(".sideMenu").height()){
       $(".scroll").show();
       var pos = $(".sideMenu ul:visible").position().top-38;
       $('.sideMenu').animate({top:-pos});
    }else{
       $(".scroll").hide();
       $('.sideMenu').animate({top:0});
       n=1;
    }
  } 

var n=1;
function menuScroll(num){
  var Scroll = $('.sideMenu');
  var ScrollP = $('.sideMenu').position();
  /*alert(n);
  alert(ScrollP.top);*/
  if(num==1){
     Scroll.animate({top:ScrollP.top-38});
     n = n+1;
  }else{
    if (ScrollP.top > -38 && ScrollP.top != 0) { ScrollP.top = -38; }
    if (ScrollP.top<0) {
      Scroll.animate({top:38+ScrollP.top});
    }else{
      n=1;
    }
    if(n>1){
      n = n-1;
    }
  }
}

  </script>

  <title>仓库管理系统</title>
</head>
<body>

    <div class="top">
      <div id="top_t">
        <div id="photo_info" class="fr" style="float: left;">
          <div id="photo" class="fl">
            <a href="#"><img src="/style/images/a.png" alt="" width="60" height="60"></a>
          </div>
          <div id="base_info" class="fr">
            <div class="help_info">
              <a href="#" id="hp">&nbsp;</a>
              <a href="#" id="gy">&nbsp;</a>
              <a href="javascript:void" onclick="login_out()">&nbsp;</a>
            </div>
            <div class="info_center"><?php print_r(@$_SESSION['name']);?>
            </div>
          </div>
        </div>
      </div>
      <div id="side_here">
        <a href="/index.php"><div id="side_here_l" class="fl"></div></a>
        <div id="here_area" class="fl" >当前位置：<a href="/index.php" class='set'>系统主页</a>─>仓库管理后台主页 </div>
      </div>
    </div>
    <div class="side">
        <div class="sideMenu" style="margin:0 auto">
 
     <?php 
     $zuheid = '';
     foreach ($data as $k=>$v):
     ?>
     <h3><?php echo $data[$k]['group_name']?></h3>
     <ul>  
     <?php foreach($data[$k]['list'] as $a=>$b):
     ?>
     <li  id='t<?php echo $data[$k]['id'].$data[$k]['list'][$a]['id']?>'><a href='#'  id='abc' onclick='jump("t<?php echo $data[$k]['id'].$data[$k]['list'][$a]['id']?>","<?php echo $data[$k]['list'][$a]['url']?>","<?php echo $data[$k]['list'][$a]['list_name']?>")'><?php echo $data[$k]['list'][$a]['list_name']?></a></li>  
     <?php 
     if(!$zuheid){
     	$zuheid = '#t'.$data[$k]['id'].$data[$k]['list'][$a]['id'];
     }
     else{
     	$zuheid = $zuheid.',#t'.$data[$k]['id'].$data[$k]['list'][$a]['id'];
     }
     endforeach; ?>
     </ul>
     <?php endforeach;?>
       </div>
    </div>
    <div class="main">
       <iframe name="right" id="rightMain" src="https://www.baidu.com" frameborder="no" scrolling="auto" width="100%" height="auto" allowtransparency="true"></iframe>
    </div>
    <div class="bottom">
      <div id="bottom_bg">版权</div>
    </div>
    <div class="scroll">
          <a href="javascript:;" class="per" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(1);"></a>
          <a href="javascript:;" class="next" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(2);"></a>
    </div>
  
</body>
  <script>
    function jump(id,url,list_name){
        var zuheid = '<?php echo $zuheid?>';
         var newstr = zuheid.replace('#'+id+',',"");
         var newstr = newstr.replace(',#'+id,"");
    	document.getElementById("rightMain").src= url;
        $('#'+id).css("background","url('/style/images/side_li_on.png') 0px 0px no-repeat");
        $(newstr).css("background","url('') 0px 0px no-repeat");  
        $("#here_area").html("当前位置：<a href='/index.php' class='set'>系统主页</a>─><a href='/depot/index' class='set'>仓库管理后台主页</a>─>"+list_name);
    }
    </script>
</html>
   
 