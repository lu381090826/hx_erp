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
method:get_sku_list_info_by_goods_id
request:
goods_id  #商品编号 spu_id
response:
Array
(
    [result] => 0
    [res_info] => ok
    [total_num] => 0
    [pages] => 0
    [result_rows] => 
    [
          {
            "goods_id": "201710290735",
            "sku_id": "2017102907351106",
            "name": "",   
            "brand": "1111",   品牌
            "category_id": "1",  分类id
            "cost": "8",   成本
            "price": "100",  售价
            "memo": "test",   备注
            "op_uid": "0",
            "create_time": "2017-11-04 13:46:57",
            "color_info": {
              "id": "11",    颜色id
              "name": "111",   
              "color_num": "19",
              "color_code": "1f1fde"
            },
            "size_info": {
              "id": "6",    尺寸id
              "size_info": "大1码1",
              "size_num": "1111"
            },
            "category_info": "服装"   分类名
          }
        ]
```
###商品搜索
```php
model:goods/goods_model
method:search_goods
request:

goods_id  货号
price_max  最大价格
price_min  最小价格
record_number  备案号
brand  品牌
category_id  分类id
category    分类
begin_time   开始时间
end_time     结束时间

```