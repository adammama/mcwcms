<?php

/**
 * @author 小策一喋 <xvpindex@qq.com>
 * @link http://www.topstack.cn
 * @copyright Copyright (C) 2014 EWSD
 * @datetime 2014-11-24 16:28
 * @version 1.0
 * @description 公用分类模型
 */

namespace Common\Model;

use Think\Model;
use Common\Model\CommonModel;

class CategoryModel extends CommonModel {


    /**
      +----------------------------------------------------------
     * 获取所有分类信息，用于在列表中根据分类id显示分类名称
     * @param  array  $condition  查询条件
     * @return  array  返回查询结果
      +----------------------------------------------------------
     */
    public static function getCategoryList($condition) {
        $categoryInfoArr = parent::getList($param = array('modelName' => 'Category', 'field' => '*', 'order' => 'cid ASC', 'tablePrefix' => 'ec_', 'connection' => 'DB_ADMIN'), $condition = '');
        foreach ($categoryInfoArr as $k => $v) {
            $categoryInfo[$v['cid']] = $v;
        }
        return $categoryInfo;
    }

    /**
      +----------------------------------------------------------
     * 获取分类信息，用于下拉选择框
     * @param  array  $condition  查询条件
     * @return  array  返回查询结果
      +----------------------------------------------------------
     */
    public static function getCategoryListByType($condition) {
        $condition['status'] = 1;
        $space = array(array('id' => '','name' => ''));
        $list = parent::getList($param = array('modelName' => 'Category', 'field' => '*', 'order' => 'oid ASC', 'tablePrefix' => 'ec_', 'connection' => 'DB_ADMIN'), $condition);
        return array_merge($space,$list);
    }

}

?>
