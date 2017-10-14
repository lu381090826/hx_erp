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
```
###根据spu查询sku列表
```php
model:goods/sku_model
method:get_sku_list_by_goods_id
request:
goods_id  #商品编号 spu_id
response:
Array
(
    [result] => 0
    [res_info] => ok
    [total_num] => 0
    [pages] => 0
    [result_rows] => Array
        (
            [0] => Array
                (
                    [id] => 35   
                    [goods_id] => TEST20170102
                    [sku_id] => TEST2017010298   
                    [name] =>          
                    [bar_code] =>       #条码（废弃，等同skuid）
                    [record_number] => 测试  （备案号）
                    [brand_id] => 0     
                    [brand] => 测试           （品牌）
                    [category_id] => 3     （分类id）
                    [property_id] => 0
                    [price] => 1111
                    [source_area] => 
                    [import] => 0
                    [unit] => 
                    [weight] => 
                    [pic] => 
                    [pic_normal] => 
                    [color_id] => 0   #颜色id
                    [size_id] => 0    #尺寸id
                    [memo] => 测试
                    [version] => 0
                    [status] => 1
                    [op_uid] => 0
                    [create_time] => 2017-10-14 11:00:44
                    [modify_time] => 2017-10-14 11:00:44
                )
        )
)
```