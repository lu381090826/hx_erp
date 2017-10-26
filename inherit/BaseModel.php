<?php
class BaseModel extends CI_Model {
    /**
     * @var string
     * 模型属性
     */
    protected $table = "";             //表名
    protected $pk = "id";              //这个必须用自增id

    //region 模型方法

    /**
     * @return Evon_Model
     * 创建新模型(废弃)
     */
    public function _new(){
        return clone $this;
    }

    /**
     * @param $param
     * @return bool|void
     * 加载参数
     */
    public function load($param){
        //判断是否为数组
        if(!is_array($param))
            return;

        //载入参数
        foreach($param as $key=>$value){
            $this->{$key} = $value;
        }

        //返回
        return true;
    }

    /**
     * @param $id
     * @return $this
     * 获取模型
     */
    public function get($id){
        //获取查询结果
        $result = $this->db->get_where($this->table,array($this->pk => $id))->first_row();

        //加载到参数
        if(!empty($result))
            $this->load((array)$result);

        //返回
        return $this;
    }

    /**
     * @return string
     */
    public function getPk(){
        return $this->pk;
    }

    //endregion

    //region CURD方法

    /**
     * @param int $page
     * @param int $size
     * @param array $condition
     * @param array $sort
     * @return object
     */
    public function search($page=1,$size=20,$condition=[],$sort=[]){
        //查找数量
        $count = $this->db->where($condition)->count_all_results($this->table);
        //$count = $this->db->count_all_results($this->table);

        //条件筛选
        $start = ($page-1)*$size;
        $select = $this->db
            ->where($condition)
            ->limit($size,$start);
            //->get($this->table);

        //排序
        foreach($sort as $key=>$value)
            $select = $select->order_by($key,$value);

        //查询表
        $select = $select->get($this->table);

        //构成返回结果
        $list = array();
        foreach($select->result() as $data){
            $item = $this->_new();
            $item->load((array)$data);
            $list[] = $item;
        }

        //返回
        $result = (object)array();
        $result->list = $list;
        $result->count = $count;
        $result->model = $this;
        return $result;
    }

    /**
     * @param array $condition
     * @param array $sort
     * @return object
     */
    public function searchAll($condition=[],$sort=[]){
        //查找数量
        $count = $this->db->where($condition)->count_all_results($this->table);

        //条件筛选
        $select = $this->db
            ->where($condition);

        //排序
        foreach($sort as $key=>$value)
            $select = $select->order_by($key,$value);

        //查询表
        $select = $select->get($this->table);

        //构成返回结果
        $list = array();
        foreach($select->result() as $data){
            $item = $this->_new();
            $item->load((array)$data);
            $list[] = $item;
        }

        //返回
        $result = (object)array();
        $result->list = $list;
        $result->count = $count;
        $result->model = $this;
        return $result;
    }

    /**
     * @param $param
     * 插入一行数据
     */
    public function insert($param)
    {
        //载入参数
        $this->load($param);

        //事务开始
        $this->db->trans_start();

        //插入到数据库
        $this->db->insert($this->table, $this);
        //获取自增id
        $this->{$this->pk} = $this->db->insert_id();

        //事务提交
        $this->db->trans_complete();
    }

    /**
     * @return mixed
     * 更新数据
     */
    public function update()
    {
        //判断是否存在对应模型
        if(empty($this->{$pk}))
            return false;

        //更改到数据库
        $bool = $this->db->update($this->table, $this, array($this->pk => $this->{$pk}));

        //返回
        return $bool;
    }

    /**
     * @return mixed
     * 删除数据
     */
    public function delete()
    {
        //判断是否存在对应模型
        if(empty($this->{$this->pk}))
            return false;

        //更改到数据库
        $bool = $this->db->delete($this->table, array($this->pk => $this->{$this->pk}));

        //返回结果
        return $bool;
    }

    /**
     * @return mixed
     * 保存数据
     */
    public function save(){
        //生命周期：保存前
        $this->beforeSave();

        //修改
        $bool = $this->db->replace($this->table, $this);

        //修改自增id
        if(empty($this->{$this->pk}))
            $this->{$this->pk} = $this->db->insert_id();

        //生命周期：保存后
        $this->afterSave();

        //返回
        return $bool;
    }

    //endregion

    //region 生命周期

    /**
     * 保全前执行
     */
    protected function beforeSave(){

    }

    /**
     * 保全后执行
     */
    protected function afterSave(){

    }

    //endregion;

    //region 描述方法

    /**
     * @return object
     */
    static function describe(){
        $data = (object)array();
        $data->desc = "DEMO";
        $data->name = "demo";

        return $data;
    }

    /**
     * @return array
     */
    static function attributeLabels()
    {
        return [];
    }

    /**
     * @param $file
     * @return mixed
     */
    public function getLabel($file){
        $labels = $this->attributeLabels();
        $label = isset($labels[$file])?$labels[$file]:$file;
        return $label;
    }

    //endregion
}