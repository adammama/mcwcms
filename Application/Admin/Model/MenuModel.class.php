<?php
namespace Admin\Model;
use Think\Model;
use Common\Model\CommonModel;

class MenuModel extends CommonModel
{
    public $model = 'Menu';
/*    public $tableFields = array(
        'id' => array('name'=>'ID', 'order'=>'1'),
        'username' => array('name'=>'账号', 'order'=>'1'),
        'status' => array('name'=>'状态', 'order'=>'1'),
    );*/
    public $searchFields=array('id','username','status');

    protected $_validate = array(
        array('title', 'require', '标题必填！'),
        array('url', 'require', '链接地址必填！'),
        array('sort', 'require', '排序必填！'),
        array('group', 'require', '分组必填！'),
        array('status', 'require', '状态必填！'),
    );

/*    protected $_auto = array(
        array('password', 'md5', 3, 'function'),
        array('last_loin_ip', 'get_client_ip', 3, 'function'),
        array('last_login_time', 'time', 3, 'function'),
    );*/
    public function add($data) {
        //return $this->lastinsertid($param = array('modelName' => $this->model), $data);
        return $this->insert($param = array('modelName' => $this->model,'returnUrl' => CONTROLLER_NAME), $data);
    }
    public function edit($data,$condition) {
        return $this->update($param = array('modelName' => $this->model,'returnUrl' => CONTROLLER_NAME), $data, $condition);
    }
    public function detail($condition){
        return $this->getDetail($param = array('modelName' => $this->model), $condition);
    }
    public function remove($condition) {
        return $this->del($param = array('modelName' => $this->model), $condition);
    }
    public function gcount($condition){
        return $this->getCount($param = array('modelName' => $this->model), $condition);
    }
}