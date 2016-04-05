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
    const RULE_URL = 1;
    const RULE_MAIN = 2;
    const TYPE_ADMIN = 1;
    const MEMBER                    = 'user';
    const AUTH_GROUP_ACCESS         = 'auth_group_access'; // 关系表表名
    const AUTH_GROUP                = 'auth_group';        // 用户组表名

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

    static public function memberInGroup($group_id){
        $prefix   = C('DB_PREFIX');
        $l_table  = $prefix.self::MEMBER;
        $r_table  = $prefix.self::AUTH_GROUP_ACCESS;
        $list     = M() ->field('m.id,m.username,m.last_login_time,m.last_login_ip,m.status')
            ->table($l_table.' m')
            ->join($r_table.' a ON m.id=a.uid')
            ->where(array('a.group_id'=>$group_id))
            ->where('m.status>-1')
            ->select();
        return $list;
    }
    public function delFromGroup($uid,$gid){
        return M(self::AUTH_GROUP_ACCESS)->where( array( 'uid'=>$uid,'group_id'=>$gid) )->delete();
    }

    public function addToGroup($uid,$gid){
        $uid = is_array($uid)?implode(',',$uid):trim($uid,',');
        $gid = is_array($gid)?$gid:explode( ',',trim($gid,',') );
        $Access = M(self::AUTH_GROUP_ACCESS);
        $uid_arr = explode(',',$uid);
        $add = array();
        foreach ($uid_arr as $u){
            //判断用户id是否合法
            if(M('User')->getFieldById($u,'id') == false){
                $this->error = "编号为{$u}的用户不存在！";
                return false;
            }
            foreach ($gid as $g){
                if( is_numeric($u) && is_numeric($g) ){
                    $add[] = array('group_id'=>$g,'uid'=>$u);
                }
            }
        }
        $Access->addAll($add);

        if ($Access->getDbError()) {
            if( count($uid_arr)==1 && count($gid)==1 ){
                //单个添加时定制错误提示
                $this->error = "不能重复添加";
            }
            return false;
        }else{
            return true;
        }
    }
    public function checkId($modelname,$mid,$msg = '以下id不存在:'){
        if(is_array($mid)){
            $count = count($mid);
            $ids   = implode(',',$mid);
        }else{
            $mid   = explode(',',$mid);
            $count = count($mid);
            $ids   = $mid;
        }

        $s = M($modelname)->where(array('id'=>array('IN',$ids)))->getField('id',true);
        if(count($s)===$count){
            return true;
        }else{
            $diff = implode(',',array_diff($mid,$s));
            $this->error = $msg.$diff;
            return false;
        }
    }

    /**
     * 检查用户组是否全部存在
     * @param array|string $gid  用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkGroupId($gid){
        return $this->checkId('AuthGroup',$gid, '以下用户组id不存在:');
    }

    /**
     * 检查分类是否全部存在
     * @param array|string $cid  栏目分类id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkCategoryId($cid){
        return $this->checkId('Category',$cid, '以下分类id不存在:');
    }


}