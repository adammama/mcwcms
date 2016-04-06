<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Controller;

class UserController extends CommonController
{
    public function _initialize() {
        parent::_initialize();
        $this->model = D('User');
    }

    public function mcwhome(){}

    public function index() {
        $list = $this->model->index($this->searchCondition($this->model->searchFields));
        //p($this->searchKeywords());
        $this->assign('search', $this->searchKeywords());
        $this->assign('tableFields', $this->model->tableFields);
        $this->assign('list', $list['info']);
        $this->assign('total', $list['total']);
        $this->display();
    }

    public function add() {
        if (IS_POST) {
            $data = $this->model->create();
            if($data){
                $this->ajaxReturn($this->model->add($data));
            } else{
                $this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
            }
        } else {
            $this->display();
        }
    }
    public function edit()
    {
        if(IS_POST) {
            $data = $this->model->create();
            if($data){
                $uid = I('id',0,'intval');
                $condition=array('id'=>$uid);
                $this->ajaxReturn($this->model->edit($data,$condition));
            }else{
                $this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
            }
        } else {
            $id=I('get.id',0,'intval');
            if($id>0){
                $dataone = M('User')->where(array('id'=>$id))->find();
                $this->dataone=$dataone;
                $this->display();
            }else{
                $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),CONTROLLER_NAME,true));
            }
        }
    }

    public function del()
    {
        $id=I('get.id', 0, 'intval');
        if($id>0){
            $condition['id']=$id;
            if($this->model->remove($condition)){
                $gcondition['uid']=$id;
                if ($this->model->checkID($gcondition)){
                    $this->ajaxReturn($this->model->removefromgroup($gcondition));
                }else{
                    $this->ajaxReturn(jsonArray(200,C('ALERT_MSG.DELETE_SUCCESS'),CONTROLLER_NAME,false));
                }
            } else {
                $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.DELETE_FAILED'),CONTROLLER_NAME,false));
            }
        } else {
            $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.DELETE_FAILED'),CONTROLLER_NAME,false));
        }

    }

    public function delselect()
    {
        $ids=I('ids','','trim');
        if(!empty($ids)){
            $condition['id']=array('in',$ids);
            if($this->model->remove($condition)){
                $gcondition['uid']=array('in',$ids);
                if ($this->model->checkID($gcondition)){
                    $this->ajaxReturn($this->model->removefromgroup($gcondition));
                }else{
                    $this->ajaxReturn(jsonArray(200,C('ALERT_MSG.DELETE_SUCCESS'),CONTROLLER_NAME,false));
                }
            }else{
                $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.DELETE_FAILED'),CONTROLLER_NAME,false));
            }
        } else {
            $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.SELECT_DATA'),CONTROLLER_NAME,false));
        }
    }
/*    public function add() {
        if (IS_POST) {
            $data = $this->model->create();
            if($data){
                if($lastinsertid=$this->model->add($data)){
                    $role=array(
                        'group_id'=>I('group_id',0,'intval'),
                        'uid'=>$lastinsertid
                    );
                    $this->ajaxReturn($this->model->addtogroup($role));
                }else{
                    $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),CONTROLLER_NAME,true));
                }
            } else{
                $this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
            }
        } else {
            $this->group=$this->model->getgrouplist();
            $this->display();
        }
    }

    public function edit()
    {
        if(IS_POST) {
            $data = $this->model->create();
            if($data){
                $uid = I('id',0,'intval');
                $group_id = I('group_id',0,'intval');
                $condition=array('id'=>$uid);
                if($this->model->edit($data,$condition)){
                    $gdata=array('group_id' => $group_id);
                    $gcondition=array('uid'=>$uid);
                    if($this->model->editgroup($gdata,$gcondition)){
                        $this->ajaxReturn(jsonArray(200,C('ALERT_MSG.EXECUTE_SUCCESS'),CONTROLLER_NAME,true));
                    } else {
                        $this->ajaxReturn(jsonArray(200,C('ALERT_MSG.USERS_GROUPF'),CONTROLLER_NAME,true));
                    }
                } else {
                    $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),CONTROLLER_NAME,true));
                }
            }else{
                $this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
            }
        } else {
            $id=I('get.id',0,'intval');
            if($id>0){
                $pre = C("DB_PREFIX");
                $dataone = M('User')->where(array('id'=>$id))->join($pre . "auth_group_access ON " . $pre . "user.id = " . $pre . "auth_group_access.uid")->find();
                $this->role=$this->model->getgrouplist();
                $this->dataone=$dataone;
                $this->display();
            }else{
                $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),CONTROLLER_NAME,true));
            }
        }
    }*/


}