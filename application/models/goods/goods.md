#商品模块Model
###获取spu列表
```php
model:goods/goods_model
method:get_goods_list
request:
Array
(
    page  #默认1
    limit #默认10
)
response:
Array
(
    [result] => 0
    [res_info] => ok
    [total_num] => 20
    [pages] => 2
    [result_rows] => Array
        (
            [0] => Array
                (
                    [name] => 1   #商品名
                    [goods_id] => 3   #spu_id
                    [price] => 1  #价格
                    [pic] =>      #小图url
                    [pic_normal] =>   #大图url
                    [record_number] =>   #
                    [brand] =>           #品牌   
                    [category_id] => 1   #分类id
                    [category] =>        #分类名
                    [memo] =>           #备注
                    [version] => 0
                    [status] => 1       #状态 1-启用
                    [op_uid] => 0
                    [create_time] => 2017-08-13 14:16:41
                    [modify_time] => 2017-10-14 10:34:04
                )

        )

)
