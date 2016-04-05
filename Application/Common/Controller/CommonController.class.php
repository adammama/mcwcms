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
    //带查询条件
    protected function _rtlist($model, $map, $asc = true) {
        $order=I('orderField','id','trim');
        $sort=I('orderDirection','','trim');
        if(empty($sort)) {
            $sort = $asc ? 'asc' : 'desc';
        }
        $pageCurrent=I('pageCurrent',1,'intval');
        $count = $model->where($map)->count();
        $numPerPage=C('PERPAGE');
        if ($count > 0) {
            $voList = $model->where($map)->order("`" . $order . "` " . $sort)->page($pageCurrent.','.$numPerPage.'')->select();
            $this->assign('user', $voList);
        }
        $this->assign('totalCount', $count);//数据总数
        $this->assign('currentPage', $pageCurrent);//当前的页数，默认为1
        $this->assign('numPerPage', $numPerPage); //每页显示多少条
        //cookie('_currentUrl_', __SELF__);
        return;
    }

    //不带查询条件
    protected function _list($model, $asc = true) {
        $order=I('orderField','id','trim');
        $sort=I('orderDirection','','trim');
        if(empty($sort)) {
            $sort = $asc ? 'asc' : 'desc';
        }

        $pageCurrent=I('pageCurrent',1,'intval');

        $count = $model->count();
        if ($count > 0) {
            $numPerPage=C('PERPAGE');
            $voList = $model->order("`" . $order . "` " . $sort)->page($pageCurrent.','.$numPerPage.'')->field('password',true)->relation(true)->select();
            $this->assign('user', $voList);
        }

        $this->assign('totalCount', $count);//数据总数
        $this->assign('currentPage', $pageCurrent);//当前的页数，默认为1
        $this->assign('numPerPage', $numPerPage); //每页显示多少条
        //cookie('_currentUrl_', __SELF__);
        return;
    }

    //生成查询条件
    protected function _search($tbname='') {
        $tbname = $tbname ? $tbname : $this->_tbname;
        $model = D($tbname);
        $map = array();
        $keys=I('keys','','trim');
        if(!empty($keys)) {
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
        }
    }
}