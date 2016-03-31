<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Controller;

class MenuController extends CommonController
{
	public function _initialize() {
		parent::_initialize();
		$this->_tbname='Menu';
		$this->model = D('Menu');
	}
	public function index(){
		$cat = new \Com\Category($this->_tbname, array('id', 'pid', 'title', 'fullname'));
		$this->cate = $cat->getList(NULL,0,'sort');
		$this->display();
	}
	public function add(){
		if (IS_POST) {
			$data = $this->model->create();
			if($data){
				$this->ajaxReturn($this->model->add($data));
			} else {
				$this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
			}
		}else{
			$this->assign("info", $this->getMenu());
			$this->display();
		}
	}

	public function edit(){
		if(IS_POST){
            $id=I('id',0,'intval');
            $condition=array('id'=>$id);
            $data = $this->model->create();
            if($data){
                $this->ajaxReturn($this->model->edit($data,$condition));
            } else {
                $this->ajaxReturn(array('statusCode' => 300, 'message' => $this->model->getError()));
            }
		}else{
			$id=I('id',0,'intval');
			if($id>0){
                $condition=array('id'=>$id);
				$info = $this->model->detail($condition);
				$this->assign("info", $this->getMenu($info));
			}else{
                $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),CONTROLLER_NAME,true));
			}

			$this->display();
		}
	}

	public function del(){
		$id=I('get.id',0,'intval');
		if($id>0){
            $pcondition=array('pid'=>$id);
            $condition=array('id'=>$id);
            if($this->model->gcount($pcondition)){
                $this->ajaxReturn(jsonArray(300,C('ALERT_MSG.DELETE_SUBMENU'),CONTROLLER_NAME,false));
            }else{
                $this->ajaxReturn($this->model->remove($condition));
            }
        }
	}

	private function getMenu($info = array()) {
		$cat=new \Com\Category($this->_tbname, array('id', 'pid', 'title', 'fullname'));
		$list = $cat->getList(NULL,0,'sort');               //获取分类结构
		$option ='<option value="0">根节点</option>';
		foreach ($list as $k => $v) {
			$disabled = (($v['id'] == $info['id']) || ($v['pid'] == $info['id']) || (in_array($v['pid'],$this->getChildsId($list,$info['id'])))) ? ' disabled="disabled"' : "";
			$selected = $v['id'] == $info['pid'] ? ' selected="selected"' : "";
			$option.='<option value="' . $v['id'] . '"' . $selected . $disabled . '>' . $v['fullname'] . '</option>';
		}
		$info['pidOption'] = $option;
		return $info;
	}
	private function getChildsId($cate,$pid){
		$arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$arr[]=$v['id'];
				$arr=array_merge($arr,$this->getChildsId($cate, $v['id']));
			}
		}
		return $arr;
	}

}