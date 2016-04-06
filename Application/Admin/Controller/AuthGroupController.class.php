<?php
/**
 * Created by PhpStorm.
 * User: mcw
 * Date: 2016/3/23
 * Time: 14:54
 */

namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Controller;
use Admin\Model\AuthGroupModel;
class AuthGroupController extends CommonController
{
    public function _initialize() {
        parent::_initialize();
        $this->model = D('AuthGroup');
    }

    public function index() {
        $list = $this->model->index($this->searchCondition($this->model->searchFields));
        //p($this->searchKeywords());
        $this->assign('search', $this->searchKeywords());
        $this->assign('tableFields', $this->model->tableFields);
        $this->assign('list', $list['info']);
        $this->assign('total', $list['total']);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            $_POST['type']=AuthGroupModel::TYPE_ADMIN;
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
                $gid = I('id',0,'intval');
                $condition=array('id'=>$gid);
                //p($data);die;
                $this->ajaxReturn($this->model->edit($data,$condition));
            }else{
                $this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
            }
        } else {
            $id=I('get.id',0,'intval');
            if($id>0){
                $condition=array('id'=>$id);
                $this->dataone=$this->model->detail($condition);
                //$this->dataone=$dataone;
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
                $gcondition['group_id']=$id;
                //pp($gcondition);die;
                if($this->model->groupcheck($gcondition)){
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
                $gcondition['group_id']=array('in',$ids);
                if($this->model->groupcheck($gcondition)){
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

    public function access(){
        $this->updateRules();
        $auth_group = M('AuthGroup')->where( array('status'=>array('egt','0'),'module'=>'admin','type'=>AuthGroupModel::TYPE_ADMIN) )
            ->getfield('id,title,rules');
        $node_list   = $this->returnNodes();
        $map         = array('module'=>'admin','type'=>AuthGroupModel::RULE_MAIN,'status'=>1);
        $main_rules  = M('AuthRule')->where($map)->getField('name,id');
        $map         = array('module'=>'admin','type'=>AuthGroupModel::RULE_URL,'status'=>1);
        $child_rules = M('AuthRule')->where($map)->getField('name,id');
        //pp($node_list);die;
        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list',  $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$_GET['group_id']]);
        $this->meta_title = '访问授权';
        $this->display('managergroup');
    }

    public function writeGroup(){
        if(isset($_POST['rules'])){
            sort($_POST['rules']);
            $_POST['rules']  = implode( ',' , array_unique($_POST['rules']));
        }else{
            $_POST['rules'] =  '';
        }
        $_POST['module'] =  'admin';
        $_POST['type']   =  AuthGroupModel::TYPE_ADMIN;
        $AuthGroup       =  D('AuthGroup');
        $data = $AuthGroup->create();
        //dump($data);die;
        if ( $data ) {
            if ( empty($data['id']) ) {
                $r = $AuthGroup->add();
            }else{
                $r = $AuthGroup->save();
            }
            if($r===false){
                $this->ajaxReturn(jsonArray(300,'操作失败!',CONTROLLER_NAME,true));
            } else{
                $this->ajaxReturn(jsonArray(200,'操作成功!',CONTROLLER_NAME,true));
            }
        }else{
            $this->ajaxReturn(jsonArray(300,'操作失败!',CONTROLLER_NAME,true));
        }
    }

    public function addToGroup(){
        $uid = I('uid');
        $gid = I('group_id');
        if( empty($uid) ){
            //$this->error('参数有误');
            $this->ajaxReturn(jsonArray(300,'参数有误!',CONTROLLER_NAME,true));
        }
        $AuthGroup = D('AuthGroup');
        if(is_numeric($uid)){
            if( !M('User')->where(array('uid'=>$uid))->find() ){
                //$this->error('用户不存在');
                $this->ajaxReturn(jsonArray(300,'用户不存在!',CONTROLLER_NAME,true));
            }
        }

        if( $gid && !$AuthGroup->checkGroupId($gid)){
            //$this->error($AuthGroup->error);
            $this->ajaxReturn(jsonArray(300,$AuthGroup->error,CONTROLLER_NAME,true));
        }
        if ( $AuthGroup->addToGroup($uid,$gid) ){
            $this->ajaxReturn(jsonArray(200,'操作成功!',CONTROLLER_NAME,false));
        }else{
            //$this->error($AuthGroup->getError());
            $this->ajaxReturn(jsonArray(300,$AuthGroup->error,CONTROLLER_NAME,false));
        }
    }

    public function groupmember(){
        $id=I('get.id',0,'intval');
        if($id>0){
            //pp(AuthGroupModel::memberInGroup($id));die;
            $this->groupmember=AuthGroupModel::memberInGroup($id);

        }
        $this->display();
    }

    public function groupmemberlookup(){
        $groupid=I('get.groupid',0,'intval');
        if($groupid>0){
            $uids=AuthGroupModel::memberInGroup($groupid);
        }
        //pp($uids);die;
        $ontInIds=array();
        foreach ($uids as $val){
            array_push($ontInIds,$val['id']);
        }

        $model = D('User');
        $map = $this->_search('User');
        if(!empty($ontInIds)){
            $map['id']  = array('not in',$ontInIds);
        }
//			pp($ontInIds);
//			pp($map);die;
        if (!empty($model)) {
            $this->_rtlist($model, $map);
        }
        $this->display();
    }


    public function removeFromGroup(){
        $uid = I('get.uid');
        $gid = I('get.group_id');
        if( $uid==UID ){
            //$this->error('不允许解除自身授权');
            $this->ajaxReturn(jsonArray(300,'不允许解除自身授权!',CONTROLLER_NAME,true));
        }
        if( empty($uid) || empty($gid) ){
            //$this->error('参数有误');
            $this->ajaxReturn(jsonArray(300,'参数有误!',CONTROLLER_NAME,true));
        }
        $AuthGroup = D('AuthGroup');
        if( !$AuthGroup->find($gid)){
            //$this->error('用户组不存在');
            $this->ajaxReturn(jsonArray(300,'用户组不存在!',CONTROLLER_NAME,true));
        }
        if ( $AuthGroup->delFromGroup($uid,$gid) ){
            //$this->success('操作成功');
            $this->ajaxReturn(jsonArray(200,'操作成功!',CONTROLLER_NAME,false));
        }else{
            //$this->error('操作失败');
            $this->ajaxReturn(jsonArray(300,'操作失败!',CONTROLLER_NAME,true));
        }
    }

    public function updateRules(){
        //需要新增的节点必然位于$nodes 从表Menu中取出
        $nodes    = $this->returnNodes(false);

        $AuthRule = M('AuthRule');
        $map      = array('module'=>'admin','type'=>array('in','1,2'));//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules 从表AuthRule表中取出
        $rules    = $AuthRule->where($map)->order('name')->select();

        //构建insert数据
        $data     = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value){
            $temp['name']   = $value['url'];
            $temp['title']  = $value['title'];
            $temp['module'] = 'admin';
            if($value['pid'] >0){
                $temp['type'] = AuthGroupModel::RULE_URL;
            }else{
                $temp['type'] = AuthGroupModel::RULE_MAIN;
            }
            $temp['status']   = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        foreach ($rules as $index=>$rule){
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if ( isset($data[$key]) ) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']]=$rule;
            }elseif($rule['status']==1){
                $ids[] = $rule['id'];
            }
        }
        if ( count($update) ) {
            foreach ($update as $k=>$row){
                if ( $row!=$diff[$row['id']] ) {
                    $AuthRule->where(array('id'=>$row['id']))->save($row);
                }
            }
        }
        if ( count($ids) ) {
            $AuthRule->where( array( 'id'=>array('IN',implode(',',$ids)) ) )->save(array('status'=>-1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if( count($data) ){
            $AuthRule->addAll(array_values($data));
        }
        if ( $AuthRule->getDbError() ) {
            trace('['.__METHOD__.']:'.$AuthRule->getDbError());
            return false;
        }else{
            return true;
        }
    }

    final protected function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }
        if((int)$tree){
            $list = M('Menu')->field('id,pid,title,url,remark,hide')->order('sort asc')->select();
            foreach ($list as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $list[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
            $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
            foreach ($nodes as $key => $value) {
                if(!empty($value['operator'])){
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        }else{
            $nodes = M('Menu')->field('title,url,remark,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $nodes[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
            //stripos(string,find) 查找字符串在另一字符串中第一次出现的位置（不区分大小写）
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }

}