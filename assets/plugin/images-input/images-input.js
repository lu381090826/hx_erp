/*
 @Author: Evon
 @GitHub: https://github.com/EvonDu/jquery-plugins
 @Date: 2017/12/2
 @Last Modified by: Evon
 @Last Modified time: 2017/12/2
 */

$.fn.imagesInput = function(options) {
    //环境变量
    var setting = {inited:false};

    //执行
    main(this,options);

    //主调度方法
    function main(_this,options){
        //调度
        if(options["action"]=="init") {
        }
        else {
            init(_this);
        }
    }

    //选择图片触发
    function change(e){
        //获取图片的Base64后，
        getBase64(e,function(data){
            //获取插件
            var plugin = $(e.target).closest("*.images-input");

            //获取上传url
            var path = $(e.target).attr("path") || "";

            //显示正在上传
            loading_show(plugin)

            //上传二进制
            $.ajax({
                type: 'post',//提交的方式
                url:path,
                data:{
                    base64:data
                },
                dataType: "JSON",
                success:function(result){
                    if(result.state.return_code == 0){
                        var path = result.data;
                        //添加图片
                        add(plugin,path);
                    }
                    else{
                        alert("上传失败");
                    }
                    //隐藏正在上传
                    loading_hide(plugin);
                },
                error:function(){
                    //DEBUG
                    alert("上传失败");
                    //隐藏正在上传
                    loading_hide(plugin);
                }
            })
        });
    }

    //根据input生成Base64码
    function getBase64(e,recall) {
        var file = e.target.files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file); // 读出 base64
        reader.onloadend = function () {
            // 图片的 base64 格式, 可以直接当成 img 的 src 属性值
            var dataURL = reader.result;
            // 获取后返回
            if(typeof(recall) == "function")
                recall(dataURL);
        };
    }

    //初始化方法
    function init(_this){
        //清空
        $(_this).empty();

        //获取属性
        var name = $(_this).attr("name") || "";
        var path = $(_this).attr("path") || "";
        var value = $(_this).attr("value") || "";

        //添加插件
        var plugin = document.createElement('div');
        plugin.setAttribute("class", "images-input");
        $(_this).append(plugin);

        //添加显示列表
        var elements = document.createElement('div');
        elements.setAttribute("class", "images-input-elements");
        $(plugin).append(elements);

        //添加值相关
        $(plugin).append('<input class="images-input-value" type="hidden" name="'+name+'"/>');

        //添加上传按钮
        var div = document.createElement('div');
        div.setAttribute("class", "images-input-button");
        var span = document.createElement('span');
        span.innerHTML = "上传图片";
        var input = document.createElement('input');
        input.setAttribute("type", "file");
        input.setAttribute("path", path);
        $(input).on("change",change);
        div.appendChild(span);
        div.appendChild(input);
        $(plugin).append(div);

        //添加加载层
        var loading = document.createElement('div');
        loading.setAttribute("class", "images-input-loading");
        $(loading).append('<div class="images-input-shade"></div><div class="images-input-icon"></div>');
        $(elements).append(loading);

        //设置初始值
        var value_init = [];
        try{value_init = JSON.parse(value);}catch(e){}
        for(var key in value_init){
            var path = value_init[key];
            add(plugin,path);
        }

        //设置初始化完成
        setting.inited = true;
    }

    //添加图片
    function add(plugin,path){
        console.log(0);
        //添加元素
        var element = document.createElement('div');
        element.setAttribute("class", "images-input-element");
        $(plugin).find(".images-input-elements").append(element);

        console.log(1);
        //设置元素内容
        var html = "<div class='images-input-shade'>" +
            "<div class='backdrop'></div>" +
            "<div class='action'><span>移除</span></div>" +
            "</div>" +
            "<img src='"+path+"'>";
        $(element).append(html);

        console.log(2);
        //添加移除按钮
        $(element).find(".action span").on("click",function(){remove(plugin,path)});

        //获取旧值
        var input = $(plugin).find(".images-input-value");
        var value_json = input.val();
        var value = null;
        try{
            value = JSON.parse(value_json);
        }
        catch(e){
            value = [];
        }

        console.log(3);
        //设置新值
        value.push(path);
        var value_new = JSON.stringify(value);
        input.val(value_new);

        console.log(4);
        //触发事件
        event_changed(value_new);
    }

    //移除图片
    function remove(plugin,path){
        //移除相关结点
        $(plugin).find(".images-input-elements .images-input-element img[src='"+path+"']").each(function () {
            $(this).closest(".images-input-element").remove();
        });

        //获取input取值
        var value = null;
        var input = $(plugin).find(".images-input-value");
        try{ value = JSON.parse(input.val()); } catch (e){ value = [] }

        //移除值并JSON化回input
        var index = value.indexOf(path);
        if (index > -1) value.splice(index, 1);
        var value_json = JSON.stringify(value);
        input.val(value_json);

        //触发事件
        event_changed(value_json);
    }

    //显示加载中
    function loading_show(plugin){
        var loading = $(plugin).find(".images-input-loading");
        var btn = $(plugin).find(".images-input-button");

        $(loading).css("visibility","visible");                //显示加载中框
        $(btn).attr("disabled","disabled");                    //设置按钮不可点击
        $(btn).css('pointer-events', "none");                  //设置按钮内部元素点击无效
    }

    //隐藏加载中
    function loading_hide(plugin){
        var loading = $(plugin).find(".images-input-loading");
        var btn = $(plugin).find(".images-input-button");

        $(loading).css("visibility","hidden");                //隐藏加载中框
        $(btn).removeAttr("disabled");                        //设置按钮可点击
        $(btn).css('pointer-events', "auto");                 //设置按钮内部元素点击有效
    }

    //事件：值改变
    function event_changed(value){
        //判断初始化是否完成
        if(setting.inited== false)
            return;

        if(options.binding != undefined){
            options.binding = value;
        }

        //触发配置事件
        if(options.change && typeof(options.change) == "function"){
            options.change(value);
        }
    }
}