<?php
namespace Admin\Model;
use Think\Model;
use Common\Model\CommonModel;

class UserModel extends CommonModel
{
    public $model = 'User';
    public $tableFields = array(
        'id' => array('name'=>'ID', 'order'=>'1'),
        'username' => array('name'=>'账号', 'order'=>'1'),
        'status' => array('name'=>'状态', 'order'=>'1'),
//        'last_login_time' => array('name'=>'时间', 'order'=>'1'),
//        'last_loin_ip' => array('name'=>'IP', 'order'=>'1'),
//        'remark' => array('name'=>'备注', 'order'=>'0'),
    );
    public $searchFields=array('id','username','status');

    protected $_validate = array(
        array('username', 'require', '用户名必填！'),
        array('username', '', '用户名已存在！', 0, 'unique', self::MODEL_INSERT),
        array('password', 'require', '密码必填！',0,'',self::MODEL_INSERT),
        array('repassword', 'password', '确认密码不正确 ', 0, 'confirm'),
        array('status', 'require', '用户状态必填！'),
    );

    protected $_auto = array(
        array('password', 'md5', 3, 'function'),
        array('last_loin_ip', 'get_client_ip', 3, 'function'),
        array('last_login_time', 'time', 3, 'function'),
    );

    public function index($condition) {
        $list = $this->getPageList($param = array('modelName' => $this->model, 'field' => '*', 'order' => 'id ASC', 'listRows' => '20'), $condition);
        //dump($list);die;
        foreach ($list['info'] as $k => $v) {
            $list['info'][$k]['id']=$v['id'];
            $list['info'][$k]['username']=$v['username'];
            $list['info'][$k]['status'] = $v['status'] == 1 ? "启用" : "禁用";
            $list['info'][$k]['last_login_time'] = $v['last_login_time'];
            $list['info'][$k]['last_loin_ip'] = $v['last_loin_ip'];
            $list['info'][$k]['remark'] = $v['remark'];
        }
        return $list;
    }

    public function add($data) {
        return $this->insert($param = array('modelName' => $this->model,'returnUrl' => CONTROLLER_NAME), $data);
    }
    public function edit($data,$condition) {
        return $this->update($param = array('modelName' => $this->model,'returnUrl' => CONTROLLER_NAME), $data, $condition);
    }
/*    public function add($data) {
        return $this->lastinsertid($param = array('modelName' => $this->model), $data);
    }
    public function edit($data,$condition) {
        return $this->rupdate($param = array('modelName' => $this->model), $data, $condition);
    }
    public function getgrouplist(){
        return $this->getList($param = array('modelName' => 'AuthGroup','field' => 'id,title','limit' => ''));
    }

*/
    public function addtogroup($data) {
        return $this->insert($param = array('modelName' => 'AuthGroupAccess','returnUrl' => CONTROLLER_NAME), $data);
    }

    public function remove($condition) {
        return $this->rdel($param = array('modelName' => $this->model), $condition);
    }

    public function removefromgroup($condition) {
        return $this->del($param = array('modelName' => 'AuthGroupAccess'), $condition);
    }
    public function editgroup($data,$condition) {
        return $this->rupdate($param = array('modelName' => 'AuthGroupAccess'), $data, $condition);
    }
}