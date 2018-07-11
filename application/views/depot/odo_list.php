<!doctype html>
 <html lang="zh-CN">
 <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="/style/css/common.css">
   <link rel="stylesheet" href="/style/css/main.css">
   <link rel="stylesheet" href="/style/css/page.css">
   <script type="text/javascript" src="/style/js/jquery.min.js"></script>
   <script type="text/javascript" src="/style/js/colResizable-1.3.min.js"></script>
   <script type="text/javascript" src="/style/js/common.js"></script>
   <script type="text/javascript" src="/style/js/Calendar.js"></script>
   <script language="javascript" src="/style/js/LodopFuncs.js"></script>
   <SCRIPT type="text/javascript">
  
		    function delete_pos(id){
		    	if(window.confirm('你确定要删除这个库位吗？')){
		    		 $.ajax({     
		                 type:"POST",       
		                 url:"/depot/depot/delete_pos",
		                 dataType:"json",
		                 data :{"id":id},
		                 cache:false,
		                 beforeSend: function() {
		                 },     
		                 success:function(data){ 
		                	 $("#d"+id).remove(); 
		                	 alert(data.msg);		                	 
		                 },   
		        
		              });
		          }else{
		             //alert("取消");
		             return false;
		         }
            }
		    window.onload = function () { 
			    
	    		 $.ajax({     
	                 type:"POST",       
	                 url:"/depot/offer/get_print",
	                 dataType:"json",
	                 cache:false,
	                 beforeSend: function() {
	                 },     
	                 success:function(data){ 
		   		    	  var print_count = LODOP.GET_PRINTER_COUNT();
			   		 
				    	  for(i=0;i<print_count;i++){	
				    		  $("#odo").append("<OPTION value='"+i+"'>"+LODOP.GET_PRINTER_NAME(i)+"</OPTION>");
				    		  $("#package").append("<OPTION value='"+i+"'>"+LODOP.GET_PRINTER_NAME(i)+"</OPTION>");
					    	  if(data.data.odo==LODOP.GET_PRINTER_NAME(i)){
					    		  $("#odo").val(i);
						      }
					    	  if(data.data.package==LODOP.GET_PRINTER_NAME(i)){
					    		  $("#package").val(i);
						      }
					      }

	                 },   
	        
	              });
			}

		    function change_print(type,value){

		    	if(value==''){
		    		return;
			     }
			     
			    var value = LODOP.GET_PRINTER_NAME(value);
	    		 $.ajax({     
	                 type:"POST",       
	                 url:"/depot/offer/change_print",
	                 dataType:"json",
	                 data :{"type":type,"value":value},
	                 cache:false,
	                 beforeSend: function() {
	                 },     
	                 success:function(data){ 

	                 },   
	        
	              });
			}
   </SCRIPT>
   <title>库存列表</title>
 </head>
 <body>
  <div id="forms" class="mt10" style="margin-top:20px;">
 
 <form id="print"  action="/depot/offer/odo_list_where_view" method="post">

<input type="text" name="search" id="search" value="<?php echo @$search_data['search'];?>"  placeholder="出库单号/客户姓名/电话/sku" class="input-text lh25" size="20">
<input type="text" name="start_date" id="start_date" value="<?php echo @$search_data['start_date'];?>"  placeholder="出库开始日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="12">
<input type="text" name="end_date" id="end_date" value="<?php echo @$search_data['end_date'];?>"  placeholder="出库结束日期" onClick="SelectDate(this,'yyyy-MM-dd')" readonly="true" class="input-text lh25" size="12">
<SELECT class="select" id="status" name="status" style="width:120px;">
                    <OPTION value="">全部状态</OPTION>
                    <OPTION value="4">等待打单</OPTION>
                    <OPTION value="1">打单完成</OPTION>
</SELECT>
<input type="submit" name="button" class="btn btn82 btn_search"  value="查询"> <span style='color:red;'>*支持模糊搜索</span>

出库单打印机：<SELECT class="select" id="odo" name="odo" onchange="change_print('Fodo',this.value)" style="width:200px;">
                    <OPTION value="">请选择打印机</OPTION>
</SELECT>

包裹单打印机：<SELECT class="select" id="package" name="package" onchange="change_print('Fpackage',this.value)" style="width:200px;">
                    <OPTION value="">请选择打印机</OPTION>
</SELECT>
</form>
  <div class="container"> 
     <div id="table" class="mt10">
        <div class="box span10 oh">
              <table  border="0" cellpadding="0" cellspacing="0" class="list_table">
              <tr>
               <th width="10%">出库单号</th>
               <th width="5%">制单人</th>
               <th width="8%">出库类型</th>
               <th width="8%">出库仓库</th>
               <th width="8%">出库数量</th>
               <th width="8%">出库日期</th>  
               <th width="10%">创建时间</th>
               <th width="15%">备注</th>                          
               <th width="10%">订单状态</th>
               <th width="15%">操作</th>
              </tr>
 
           <?php 
           foreach($odo_data as $k=>$v){
           ?>
              <tr class='tr' align='center' id=""> 
                <td ><?php echo $odo_data[$k]['odo_sn']?></td>
                <td ><?php echo $odo_data[$k]['name']?></td>               
                <td ><?php echo $odo_data[$k]['enter_depot']?></td>
                <td ><?php echo $odo_data[$k]['source_depot']?></td>
                <td ><?php echo $odo_data[$k]['total_count']?></td>
                <td ><?php echo $odo_data[$k]['odo_date']?></td>
                <td ><?php echo $odo_data[$k]['create_time']?></td>
                <td ><?php echo $odo_data[$k]['beizhu']?></td>
                <td id="<?php echo $odo_data[$k]['odo_sn'];?>"><?php 
                   switch ($odo_data[$k]['status']){
                       case"4":
                             echo "<span>等待打单</span>";
                       break;
                               
                       case"1":
                           echo "<span style='color:red;'>已打单</span>";
                       break;
                }
                ?></td>
                <td class="pirnt"><a href="/depot/offer/odo_detail_view?odo_sn=<?php echo $odo_data[$k]['odo_sn']?>">详情</a>　　<a href="javascript:void(0);" onclick="print_odo('<?php echo $odo_data[$k]['odo_sn']?>')" >打单</a>　　</td>
              </tr>
         <?php 
           }
         ?>
              </table>
            <?php echo @$page_data;?>
        </div>
     </div>

   </div>
<script type="text/javascript">

function peihuo(){
   var chk_value =[]; 
	$('input[name="cblist"]:checked').each(function(){ 
	chk_value.push($(this).val()); 
	});
	 if(chk_value.length==0){
           alert('请选择需要配货的报货单！');return;
     }

	 window.location.href= "/depot/offer/add_odo_view?id_list="+chk_value;
}
var isCheckAll = false;  
function swapCheck() {  
	   //取消全选
    if (isCheckAll) {  
        $("input[type='checkbox']").each(function() {  
            this.checked = false;  
        }); 
        isCheckAll = false;  
    } 
    //全选
    else {  
        $("input[type='checkbox']").each(function() {  
            this.checked = true;  
        });  
        isCheckAll = true;  
    }  
}  

function change_status_beifen(status,odo_sn){
	if(status==3){
		var text  = '你确定要打印这张出库单吗？';
    }
	else if(status==2){
		var text  = '你确定拣货完成了吗？';
   }
	else if(status==1){
		var text  = '你确定要出库了吗？';
   }   
	if(window.confirm(text)){
		 $.ajax({     
           type:"POST",       
           url:"/depot/offer/update_odo_status",
           dataType:"json",
           data :{"status":status,"odo_sn":odo_sn},
           cache:false,
           beforeSend: function() {
           },     
           success:function(data){ 
               if(data.result=='1'){
                   if(status==3){
                	   window.open('/depot/offer/print_list?odo_sn='+odo_sn); 
                   }
                   else if(status==2){
                       alert('拣货完成');
                   }
                   else if(status==1){
                	   alert('出库完成');
                   }
                   $("#"+odo_sn).html(data.data);
                }
               else{
                   alert(data.msg);
               }
        	   
           },   
  
        });
    }else{
       //alert("取消");
       return false;
   }
}

function print_odo(odo_sn){
	var text  = '你确定要打印这张出库单吗？';
	
	if(window.confirm(text)){
		 $.ajax({     
           type:"POST",       
           url:"/depot/offer/print_odo",
           dataType:"json",
           data :{"odo_sn":odo_sn},
           cache:false,
           beforeSend: function() {
           },     
           success:function(data){ 
               if(data.result=='1'){
            	   if(to_print(data.data) ){
                	   alert('打印成功');
                  }  
               }       	       	   
           },   
  
        });
    }else{
       //alert("取消");
       return false;
   }
}


function to_print(data) {
	//获取目前选择的打印机序号
	var odo_print_name = $("#odo").val();
	var package_print_name = $("#package").val();

	if(odo_print_name==''){
		alert('请选择出库单打印机');return;
     }
	if(package_print_name==''){
		alert('请选择包裹单打印机');return;
     }
	
    //循环打印次数
        for(var i=0;i<data.length;i++){
        	//循环打印页数
            for(var jj=0;jj<data[i].total_page;jj++){

            var num=2;
            var fenyeSize=0;
           
            var printNum;//序号
            LODOP.PRINT_INITA(0,0,1800,1460,"韩讯出库单");
            

            LODOP.SET_PRINT_PAGESIZE('1','241mm','140mm',"A4");//一开始用的是像素，后来都改成用mm为单位
            fenyeSize=140;
       	    
            var lastheght=2;
            //上面的信息
            if(num!=1){
            var now_page  = jj+1;
            LODOP.ADD_PRINT_TEXT("3.39mm","13mm","77.55mm","9.6mm","第"+now_page+"张/共"+data[i].total_page+"张");
            LODOP.ADD_PRINT_TEXT("3.39mm","64.4mm","77.55mm","9.6mm","韩讯平台销售出库单");
                LODOP.SET_PRINT_STYLEA(0, "ItemType", 0);
                LODOP.SET_PRINT_STYLEA(0, "FontSize", 14);
                LODOP.SET_PRINT_STYLEA(0, "Bold", 1);
            }

            //数据条数
            var size = data[i].odo_data[jj].detail_data.length;     
            
            var extendSize=0;
            var table_hegth=(size+1)*25;
            var fonsize=4;
            //上面的信息

           
                
            LODOP.ADD_PRINT_TEXT("15mm","13mm","20.13mm","5.37mm","出库单号：");
            LODOP.ADD_PRINT_TEXT("15mm","27mm","45.56mm","5.37mm",data[i].odo_sn_data.odo_sn);
            
            
            LODOP.ADD_PRINT_TEXT("15mm","58mm","20.13mm","5.37mm","出库仓库：");
            LODOP.ADD_PRINT_TEXT("15mm","73mm","20.13mm","5.37mm",data[i].odo_sn_data.source_depot);
            
            LODOP.ADD_PRINT_TEXT("15mm","92mm","20.13mm","5.37mm","制单人：");
            LODOP.ADD_PRINT_TEXT("15mm","104mm","20.13mm","5.37mm",data[i].odo_sn_data.name);

            LODOP.ADD_PRINT_TEXT("15mm","115mm","20.13mm","5.37mm","出库日期：");
            LODOP.ADD_PRINT_TEXT("15mm","130mm","20.13mm","5.37mm",data[i].odo_sn_data.odo_date);
            
            LODOP.ADD_PRINT_TEXT("15mm","152mm","20.13mm","5.37mm","出库总数：");
            LODOP.ADD_PRINT_TEXT("15mm","167mm","20.13mm","5.37mm",data[i].shop_send_count);

            LODOP.ADD_PRINT_TEXT("15mm","182mm","20.13mm","5.37mm","收货档口：");
            LODOP.ADD_PRINT_TEXT("15mm","197mm","20mm","5.37mm",data[i].shop_name);
            
            LODOP.ADD_PRINT_TEXT("15mm","210mm","20.13mm","5.37mm","备注：");
            LODOP.ADD_PRINT_TEXT("15mm","220mm","80mm","5.37mm",data[i].odo_sn_data.beizhu);

    
            //标题等
            LODOP.ADD_PRINT_TEXT("23.02mm","22mm","18mm","5.37mm","销售单号");
            LODOP.ADD_PRINT_TEXT("23.02mm","52mm","18mm","5.37mm","报货单号");
            LODOP.ADD_PRINT_TEXT("23.02mm","74mm","9mm","5.37mm","库位");
            LODOP.ADD_PRINT_TEXT("23.02mm","90mm","18mm","5.37mm","skuid");
            LODOP.ADD_PRINT_TEXT("23.02mm","110mm","9mm","5.37mm","款号");
            LODOP.ADD_PRINT_TEXT("23.02mm","124mm","9mm","5.37mm","颜色");
            LODOP.ADD_PRINT_TEXT("23.02mm","135mm","9mm","5.37mm","尺码");
            LODOP.ADD_PRINT_TEXT("23.02mm","146mm","18mm","5.37mm","客户姓名");
            LODOP.ADD_PRINT_TEXT("23.02mm","163mm","18mm","5.37mm","客户电话");
            LODOP.ADD_PRINT_TEXT("23.02mm","181mm","18mm","5.37mm","报货数量");
            LODOP.ADD_PRINT_TEXT("23.02mm","198mm","18mm","5.37mm","配数");
            
            //表格线
            LODOP.ADD_PRINT_LINE("21.31mm","13mm","21.31mm","208.00mm",0,1);// 最上条标题横线            
            LODOP.ADD_PRINT_LINE("27.31mm","13mm","27.31mm","208.00mm",0,1);// 标题下横线        
            LODOP.ADD_PRINT_LINE("21.31mm","13mm", "27.11mm", "13mm", 0, 1);// 最左竖线
            //LODOP.ADD_PRINT_LINE("31.31mm","25mm", "37.11mm", "25mm", 0, 1);// 行号后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","43mm", "27.11mm", "43mm", 0, 1);// 销售单号后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","72mm", "27.11mm", "72mm", 0, 1);// 报货单号后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","83mm", "27.11mm", "83mm", 0, 1);// 库位后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","105mm", "27.11mm", "105mm", 0, 1);// skuid后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","121mm", "27.11mm", "121mm", 0, 1);// 款号后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","132mm", "27.11mm", "132mm", 0, 1);// 颜色后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","142mm", "27.11mm", "142mm", 0, 1);// 尺码后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","160mm", "27.11mm", "160mm", 0, 1);// 客户姓名后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","180mm", "27.11mm", "180mm", 0, 1);// 客户电话后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","195mm", "27.11mm", "195mm", 0, 1);// 报货数量后竖线
            LODOP.ADD_PRINT_LINE("21.31mm","208mm", "27.11mm", "208mm", 0, 1);// 配数后竖线
    
            //动态列表信息
            var trheight=27.31;//用于每个竖线距离上面的固定长度
            var thHeight=30.98;//用于每行商品距离上面的固定长度
            var newHeight=0;//用于动态增加一行的长度
            var lastSize=0;//分页前的那个下标
            var allProductNumber=0;

            
            for(var k=0;k<size;k++){
                var sell_order= data[i].odo_data[jj].detail_data[k].sell_order;
                var allocate_order= data[i].odo_data[jj].detail_data[k].allocate_order;
                var pos_name = data[i].odo_data[jj].detail_data[k].pos_name;
                var sku_id = data[i].odo_data[jj].detail_data[k].sku_id;
                var spu_id = data[i].odo_data[jj].detail_data[k].spu;
                var color = data[i].odo_data[jj].detail_data[k].color;
                var sku_size = data[i].odo_data[jj].detail_data[k].size;
                var name = data[i].odo_data[jj].detail_data[k].client_name;
                var mobile = data[i].odo_data[jj].detail_data[k].client_phone;
                var allocate_count = data[i].odo_data[jj].detail_data[k].allocate_count+'/'+data[i].odo_data[jj].detail_data[k].total_send_num;
                var send_count = data[i].odo_data[jj].detail_data[k].send_count;
            
                newHeight=(k-lastSize)*5.37+extendSize*fonsize;
                var SizeTmpt=parseInt(getByteLen(sell_order)/35);
           
                if(parseInt(getByteLen(allocate_order)/16)>SizeTmpt){
                     SizeTmpt=parseInt(getByteLen(allocate_order)/16);
                }

                    if(parseInt(getByteLen(pos_name)/17)>SizeTmpt){
                        SizeTmpt=parseInt(getByteLen(pos_name)/17);
                    }

                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","0.5mm","58mm",5.3+SizeTmpt*fonsize+"mm",sell_order);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","43mm","30mm",5.3+SizeTmpt*fonsize+"mm",allocate_order);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","62mm","30mm",5.3+SizeTmpt*fonsize+"mm",pos_name);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","81mm","25mm","5.37mm",sku_id);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","98mm","30mm","5.37mm",spu_id);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","116mm","23mm","5.37mm",color);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","126mm","23mm","5.37mm",sku_size);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","140mm","23mm","5.37mm",name);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","159mm","23mm","5.37mm",mobile);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","175mm","23mm","5.37mm",allocate_count);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                LODOP.ADD_PRINT_TEXT(thHeight+newHeight+"mm","190mm","23mm","5.37mm",send_count);
                LODOP.SET_PRINT_STYLEA(0, "Alignment", 2);
                extendSize+=SizeTmpt;
                newHeight=(k-lastSize)*5.37+extendSize*fonsize;
                
                if(k==lastSize+1&&k!=1){
                    LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","13mm",trheight+newHeight-SizeTmpt*fonsize+"mm","193mm",0,1);//每条数据后加一横线
                }
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","13mm",trheight+newHeight+5.37+"mm","13mm",0,1);//最左竖线
                //LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","25mm",trheight+newHeight+5.37+"mm","25mm",0,1);//行号后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","43mm",trheight+newHeight+5.37+"mm","43mm",0,1);//销售单号后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","72mm",trheight+newHeight+5.37+"mm",'72mm',0,1);//报货单号后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","83mm",trheight+newHeight+5.37+"mm","83mm",0,1);//库位后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","105mm",trheight+newHeight+5.37+"mm","105mm",0,1);//skuid后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","121mm",trheight+newHeight+5.37+"mm","121mm",0,1);//款号后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","132mm",trheight+newHeight+5.37+"mm","132mm",0,1);//颜色后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","142mm",trheight+newHeight+5.37+"mm","142mm",0,1);//尺码后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","160mm",trheight+newHeight+5.37+"mm","160mm",0,1);//客户姓名后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","180mm",trheight+newHeight+5.37+"mm","180mm",0,1);//客户电话竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","195mm",trheight+newHeight+5.37+"mm","195mm",0,1);//报货数量后竖线
                LODOP.ADD_PRINT_LINE(trheight+newHeight-SizeTmpt*fonsize+"mm","208mm",trheight+newHeight+5.37+"mm","208mm",0,1);//配数后竖线
                
                LODOP.ADD_PRINT_LINE(trheight+newHeight+5.57+"mm","13mm",trheight+newHeight+5.57+"mm","208mm",0,1);//每条数据后加一横线
                        
            }//内循环结束

            table_hegth=trheight+newHeight+5.57;
             LODOP.NewPage();
             //选择打印机
             LODOP.SET_PRINTER_INDEX(odo_print_name);
             LODOP.PRINT();

             //包裹单打印
             for(ii=0;ii<data[i].odo_data[jj].client_data.length;ii++){
                 LODOP=getLodop();  
                 LODOP.PRINT_INIT("韩讯包裹单");
                 LODOP.SET_PRINT_PAGESIZE('1','100mm','100mm',"A4");//一开始用的是像素，后来都改成用mm为单位
                 LODOP.SET_PRINT_STYLE("FontSize",8);
                 
                 LODOP.ADD_PRINT_TEXT("65.39mm","0.4mm","77.55mm","9.6mm",data[i].odo_data[jj].client_data[ii].print_time);       
                 LODOP.ADD_PRINT_TEXT("70.39mm","0.4mm","90.55mm","9.6mm","数量请当面点清一经离开概不负责！！！");
                 
                 var one = jj+1;
                 var two = ii+1;
                 LODOP.ADD_PRINT_TEXT("65.39mm","40mm","8mm","9.6mm",one+"/"+two);

//                  alert(data[i].odo_data[jj].client_data[ii].print_time);return;
                 //循环输出款号 颜色 尺码数据        
                 var height = 5.35;
                  for(iii=0;iii<data[i].odo_data[jj].client_data[ii].data.length;iii++){
                  newheight = height+iii*4;
                  LODOP.ADD_PRINT_TEXT(newheight+"mm","50mm","40mm","9.6mm",data[i].odo_data[jj].client_data[ii].data[iii].spu+'/'+data[i].odo_data[jj].client_data[ii].data[iii].color+'/'+data[i].odo_data[jj].client_data[ii].data[iii].size+'('+data[i].odo_data[jj].client_data[ii].data[iii].send_count+')');         
                 }
                 
                 //输出合计
                 newheight = newheight+5;
                 LODOP.ADD_PRINT_TEXT(newheight+"mm","50mm","40mm","9.6mm","合计：");      
                 LODOP.ADD_PRINT_TEXT(newheight+"mm","58mm","40mm","9.6mm",data[i].odo_data[jj].client_data[ii].total_send_count);  
                   
                 LODOP.SET_PRINT_STYLE("FontSize",18);
                 LODOP.SET_PRINT_STYLE("Bold",1);
                 LODOP.ADD_PRINT_TEXT("5.39mm","0.4mm","48.55mm","9.6mm",data[i].odo_data[jj].client_data[ii].client_name);       
                 LODOP.ADD_PRINT_BARCODE("35mm","0.1mm",110,110,"QRCode","1234567890");
                 LODOP.SET_PRINT_STYLE("FontSize",26);
                 LODOP.SET_PRINT_STYLE("Bold",1);
                 LODOP.ADD_PRINT_TEXT("40mm","25mm","40.55mm","9.6mm",data[i].shop_name);
                 
                 //选择打印机
                 LODOP.SET_PRINTER_INDEX(package_print_name);
                 LODOP.PRINT();
             }

         }
        }//外循环结束       
        return true;
};  

function getByteLen(val) {
    var len = 0;
    for (var i = 0; i < val.length; i++) {
        if (val[i].match(/[^\x00-\xff]/ig) != null) //全角
            len += 2;
        else
            len += 1;
    }
    return len;
}
$("#status").val('<?php echo @$search_data['status'];?>');
</script>
 </body>
 </html>
  
