<?php

    function jsonArray($status,$info,$navTabId="",$closeCurrent=true) {
        $result = array();
        $result['statusCode'] = $status;
        $result['message'] = $info;
        $result['tabid'] = strtolower($navTabId).'_index';
        $result['forward'] = '';
        $result['forwardConfirm']='';
        $result['closeCurrent'] =$closeCurrent;
        return $result;
    }

    function p($arr){
        echo '<pre>' . print_r($arr,true) . '</pre>';
    }

    function pp($arr){
        //dump为ThinkPHP函数
        dump($arr,1,'<pre>',0);
    }

    function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }