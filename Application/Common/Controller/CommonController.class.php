<?php
namespace Common\Controller;
use Think\Controller;

class CommonController extends Controller {
    public function _initialize () {
        $this->checklogin();
    }
    public function checklogin(){
        if(!isset($_SESSION[C('USER_AUTH_KEY')])){ //判断是否有uid
            $this->redirect("Public/Index");
        }
        $Auth = new \Think\Auth();
        $module_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
        if($_SESSION['uname']!==C('ADMIN_AUTH_KEY')){    //以用户名来判断是否是超级管理员，绕过验证，不用用户组来判断的原因是用户组有时候是中文    ，而且常删除或更改。
            if(!$Auth->check($module_name,$_SESSION[C('USER_AUTH_KEY')],array('in','1,2'))){
                $this->ajaxReturn(array('statusCode' => 300, 'message' => C('ALERT_MSG.NO_AUTH')));
            }
        }
    }

    /**
     * 查询关键词
     */
    public function searchKeywords() {
        //$searchArr = $_POST['search'];
        $searchArr = I('post.search','','trim');
        //dump($searchArr);die;
        return $searchArr;
    }

    /**
     * 生成查询条件
     */
    public function searchCondition($searchFields) {
        $searchkeys=$this->searchKeywords();
        if(empty($searchkeys)){
            $map['status']= array('gt',-1);
            return $map;
        }else{
            foreach($searchFields as $val){
                $map [$val] = array('like','%'.$searchkeys.'%');
            }
            $map['_logic'] = 'or';
            $where['_complex'] = $map;
            $where['status']  = array('gt',-1);
            return $where;
        }

/*        if(!empty($keys)) {
            foreach ($model->getDbFields() as $key => $val) {
                if(in_array($val, C('SEARCH_KEY'))){
                    $map [$val] = array('like','%'.$keys.'%');
                }
            }
            $map['_logic'] = 'or';
            $where['_complex'] = $map;
            $where['status']  = array('gt',-1);
            return $where;
        }else{
            $map['status']= array('gt',-1);
            return $map;
        }*/
        /*foreach ($this->searchKeywords() as $k => $v) {
            $condition[$k] = array('like', '%' . $v . '%');
        }
        return $condition;*/
    }
}