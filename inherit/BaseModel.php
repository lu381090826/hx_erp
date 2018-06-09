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
     * @param $param
     * @return bool|void
     */
    public function load_safe($param){
        //判断是否为数组
        if(!is_array($param))
            return;

        //载入参数
        foreach($param as $key=>$value){
            if(property_exists($this, $key))//是否拥有这个属性
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
        //按照加官，使用sql前所有字段加F
        $this->addPrefixGet();

        //获取查询结果
        $result = $this->db->get_where($this->table,array($this->pk => $id))->first_row();

        //加载到参数
        if(!empty($result))
            $this->load((array)$result);

        //还原F前缀
        $this->delPrefixGet();

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
        //按照加官，使用sql前所有字段加F
        $condition = $this->addPrefixKeyValue($condition);
        $sort = $this->addPrefixKeyValue($sort);

        //查找数量
        //$count = $this->db->where($condition)->count_all_results($this->table);
        $count = $this->getCount($condition);

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
        //按照加官，使用sql前所有字段加F
        $condition = $this->addPrefixKeyValue($condition);
        $sort = $this->addPrefixKeyValue($sort);

        //查找数量
        //$count = $this->db->where($condition)->count_all_results($this->table);
        $count = $this->getCount($condition);

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
     * @return bool
     * 插入一行数据
     */
    public function insert($param)
    {
        //生命周期：保存前
        $this->beforeSave();

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

        //生命周期：保存后
        $this->afterSave();

        return true;
    }

    /**
     * @return mixed
     * 更新数据
     */
    public function update()
    {
        //生命周期：保存前
        $this->beforeSave();

        //判断是否存在对应模型
        if(empty($this->{$pk}))
            return false;

        //更改到数据库
        $bool = $this->db->update($this->table, $this, array($this->pk => $this->{$pk}));


        //生命周期：保存后
        $this->afterSave();

        //返回
        return $bool;
    }

    /**
     * @return mixed
     * 删除数据
     */
    public function delete()
    {
        //生命周期：保存前
        $this->beforeSave();

        //判断是否存在对应模型
        if(empty($this->{$this->pk}))
            return false;

        //更改到数据库
        $bool = $this->db->delete($this->table, array($this->pk => $this->{$this->pk}));

        //生命周期：保存后
        $this->afterSave();

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
        if(empty($this->{$this->pk})) {
            $this->{$this->pk} = $this->db->insert_id();
        }

        //生命周期：保存后
        $this->afterSave();

        //返回
        return $bool;
    }

    /**
     * @param array $condition
     * @return bool
     * 按照条件删除
     */
    public function deleteAll($condition=[]){
        //按照加官，使用sql前所有字段加F
        $condition = $this->addPrefixKeyValue($condition);

        //删除
        $this->db->where($condition)->delete($this->table);

        //返回
        return true;
    }

    //endregion

    #region 统计查询

    public function select_sum($field, $condition=[]){
        //使用sql前所有字段加F
        $field = $this->addPrefixField($field);
        $condition = $this->addPrefixKeyValue($condition);

        //查询
        $this->db->select_sum($field);
        $this->db->where($condition);
        $query = $this->db->get($this->table);
        $result = $query->result();
        return $result;
    }

    public function select_avg($field, $condition=[]){
        //使用sql前所有字段加F
        $field = $this->addPrefixField($field);
        $condition = $this->addPrefixKeyValue($condition);

        //查询
        $this->db->select_avg($field);
        $this->db->where($condition);
        $query = $this->db->get($this->table);
        $result = $query->result();
        return $result;
    }

    #endregion

    //region 生命周期

    /**
     * 保全前执行
     */
    protected function beforeSave(){
        //按照加官，使用sql前所有字段加F
        $this->addPrefix();
    }

    /**
     * 保全后执行
     */
    protected function afterSave(){
        //按照加官，使用sql后全部字段削去F
        $this->delPrefix();
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

    //region 内核应对

    //获取数量
    protected function getCount($condition){
        $query = $this->db->select('COUNT(*) AS `Fnums`')->where($condition)->get($this->table);;
        $result = $query->row();
        $count = $result->nums;
        return $count;
    }

    //添加前置
    protected function addPrefix(){
        //按照加官，使用sql前所有字段加F
        foreach($this as $key=>$value){
            //跳过表名
            if($key == "table") continue;
            //跳过已经加过的
            if($key[0] == "F") continue;

            //主键特殊处理
            if($key == "pk"){
                $this->{$key} = "F".$value;
                continue;
            }

            //为字段属性添加F
            $this->{"F".$key} = $value;
            //删除原属性
            unset($this->{$key});
        }
    }

    //删除前缀
    protected function delPrefix(){
        //按照加官，使用sql后全部字段削去F
        foreach($this as $key=>$value){
            //跳过表名
            if($key == "table") continue;

            //主键特殊处理
            if($key == "pk"){
                $this->{$key} = substr($value,1);
                continue;
            }

            //跳过非F开头的
            if($key[0] != "F") continue;
            //还原原属性
            $this->{substr($key,1)} = $value;
            //删除F属性
            unset($this->{$key});
        }
    }

    //添加前缀，GET时使用
    protected function addPrefixGet(){
        $this->pk = "F".$this->pk;
    }

    //删除前缀，GET时使用
    protected function delPrefixGet(){
        $this->pk = substr($this->pk,1);
    }

    //排序和条件使用
    protected function addPrefixKeyValue($array){
        $result = array();
        foreach($array as $key=>$value){
            $result["F".$key] = $value;
        }
        return $result;
    }

    //字段使用
    protected function addPrefixField($field){
        return "F".$field;
    }

    //字段使用
    protected function addPrefixFields($fields){
        $result = array();
        foreach($fields as $value){
            $result[] = "F".$value;
        }
        return $result;
    }

    //endregion;
}