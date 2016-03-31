<?php
namespace Com;
class Category2{
	//一维数组，子项在父项下
	static public function unlimitedForLevel($cate,$html='├┄┄┄┄┄┄',$pid=0,$lever=0){
		$arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$v['level']=$lever+1;
				$v['html']=str_repeat($html, $lever);
				$arr[]=$v;
				$arr=array_merge($arr,self::unlimitedForLevel($cate,$html,$v['id'],$lever+1));
			}
		}
		return $arr;
	}
	//多维数组
	static public function unlimitedForLayer($cate,$name='child',$pid=0){
		$arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$v[$name]=self::unlimitedForLayer($cate,$name,$v['id']);
				$arr[]=$v;
			}
		}
		return $arr;
	}
	//根据ID返回所有父级
	static public function getParents($cate,$id){
		$arr=array();
		foreach ($cate as $v){
			if($v['id']==$id){
				$arr[]=$v;
				$arr=array_merge(self::getParents($cate, $v['pid']),$arr);
			}
		}
		return $arr;
	}
	
	//传递一个父级分类ID返回所有子分类ID
	static public function getChildsId($cate,$pid){
		$arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$arr[]=$v['id'];
				$arr=array_merge($arr,self::getChildsId($cate, $v['id']));				
			}
		}
		return $arr;
	}

	//传递一个父级分类ID返回所有子级
	static public function getChilds($cate,$pid){
		$arr=array();
		foreach ($cate as $v){
			if($v['pid']==$pid){
				$arr[]=$v;
				$arr=array_merge($arr,self::getChilds($cate, $v['id']));				
			}
		}
		return $arr;
	}
}
?>