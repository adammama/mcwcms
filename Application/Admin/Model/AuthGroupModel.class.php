<?php
/**
 * Created by PhpStorm.
 * User: mcw
 * Date: 2016/3/24
 * Time: 9:10
 */

namespace Admin\Model;
use Think\Model;
use Common\Model\CommonModel;

class AuthGroupModel extends CommonModel
{
    public $model = 'AuthGroup';
    public $tableFields = array(
        'id' => array('name'=>'ID', 'order'=>'1'),
        'title' => array('name'=>'角色名称', 'order'=>'1'),
        'status' => array('name'=>'状态', 'order'=>'1'),
    );
    public $searchFields=array('id','title','status');

    protected $_validate = array(
        array('username', 'require', '用户名必填！'),
        array('username', '', '用户名已存在！', 0, 'unique', self::MODEL_INSERT),
        array('password', 'require', '密码必填！',0,'',self::MODEL_INSERT),
        array('repassword', 'password', '确认密码不正确 ', 0, 'confirm'),
        array('status', 'require', '用户状态必填！'),
    );

    public function index($condition) {
        $list = $this->getPageList($param = array('modelName' => $this->model, 'field' => '*', 'order' => 'id ASC', 'listRows' => '20'), $condition);
        //dump($list);die;
        foreach ($list['info'] as $k => $v) {
            $list['info'][$k]['id']=$v['id'];
            $list['info'][$k]['title']=$v['title'];
            $list['info'][$k]['status'] = $v['status'] == 1 ? "启用" : "禁用";
        }
        return $list;
    }

    public function add($data) {
        return $this->insert($param = array('modelName' => $this->model,'returnUrl' => CONTROLLER_NAME), $data);
    }

    public function edit($data,$condition) {
        return $this->update($param = array('modelName' => $this->model,'returnUrl' => CONTROLLER_NAME), $data, $condition);
    }

    public function groupcheck($condition){
        return $this->exists($param = array('modelName' => 'AuthGroupAccess'), $condition);
    }

    public function remove($condition) {
        return $this->rdel($param = array('modelName' => $this->model), $condition);
    }

    public function removefromgroup($condition) {
        return $this->del($param = array('modelName' => 'AuthGroupAccess'), $condition);
    }

    public function detail($condition){
        return $this->getDetail($param = array('modelName' => $this->model), $condition);
    }

}