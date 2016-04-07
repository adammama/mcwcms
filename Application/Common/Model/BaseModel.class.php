<?php

/**
 * @author 小策一喋 <xvpindex@qq.com>
 * @link http://www.topstack.cn
 * @copyright Copyright (C) 2014 EWSD
 * @datetime 2014-10-15 12:40
 * @version 1.0
 * @description 公用数据库基础操作模型
 */

namespace Common\Model;

use Think\Model;

class BaseModel extends Model {

    /**
      +----------------------------------------------------------
     * 获取系统当前用户信息
     * @return   string  返回当前用户信息
      +----------------------------------------------------------
     */
    public static function getMyInfo($field) {
        return $_SESSION['myInfo'][$field];
    }

    public static function getModel($param){
        if(isset($param['modelType'])){
            if($param['modelType'] == 'viewModel' || $param['modelType'] == 'relationModel'){
                $M = D($param['modelName']);
            }
        }else{
              $M = isset($param['connection']) ? M($param['modelName'], $param['tablePrefix'], $param['connection']) : M($param['modelName']);
        }
        return $M;
    }

    /**
      +----------------------------------------------------------
     * 获取列表
     * @param  array  $param  一维数组数据
     * @param  array  $condition  一维数组数据
     * @return  array  二维数组数据
      +----------------------------------------------------------
     */
    public static function getList($param = array('modelName' => '', 'field' => '*', 'order' => '', 'limit' => '5', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $condition = '') {
        $M = self::getModel($param);
        $list = $M->field($param['field'])->where($condition)->order($param['order'])->limit($param['limit'])->select();
        
        //echo $sqlStr = $M->getlastsql();
        return $list;
    }

    /**
      +----------------------------------------------------------
     * 获取分页列表
     * @return  array  返回列表及分页数据
      +----------------------------------------------------------
     */
    public static function getPageList($param = array('modelName' => '', 'field' => '*', 'order' => 'id DESC', 'listRows' => '20', 'tablePrefix' => '', 'connection' => '', 'modelType' => 'commonModel', 'relationModel' => ''), $condition = '') {

        $data = $_POST;

        // 排序数据
        if($data['orderField'] != ""){
            $param['order'] = $data['orderField'] . " " . $data['orderDirection'];
            session('orderField', $data['orderField']);
            session('orderDirection', $data['orderDirection']);
        }

        // 生成分页session数据
        session('pageCurrent', $data['pageCurrent']);

        if($data['pageSize'] != ""){
            $pageSize = $data['pageSize'];
        } else {
            if(session('pageSize') != "" and $data['pageSize'] == session('pageSize'))
                $pageSize = session('pageSize');
            else
                $pageSize = 20;
        }
        session('pageSize', $pageSize);


        $M = self::getModel($param);

        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $list['total'] = $M->relation($relationModel)->field($param['field'])->where($condition)->count();
        }else{
            $list['total'] = $M->field($param['field'])->where($condition)->count();
        }

        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $list['info'] = $M->relation($relationModel)->field($param['field'])->where($condition)->order($param['order'])->page($data['pageCurrent'],$pageSize)->select();
        }else{
            $list['info'] = $M->field($param['field'])->where($condition)->order($param['order'])->page($data['pageCurrent'],$pageSize)->select();
        }

        //echo $sqlStr = $M->getlastsql();
        return $list;
    }

    /**
      +----------------------------------------------------------
     * 获取分页列表
     * @return  array  返回列表及分页数据
      +----------------------------------------------------------
     */
    public static function getCommonPageList($param = array('modelName' => '', 'field' => '*', 'order' => 'id DESC', 'listRows' => '20', 'tablePrefix' => '', 'connection' => '', 'modelType' => 'commonModel', 'relationModel' => ''), $condition = '') {
        $M = self::getModel($param);
        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $count = $M->relation($relationModel)->field($param['field'])->where($condition)->count();
        }else{
            $count = $M->field($param['field'])->where($condition)->count();
        }
        $Page = new \Think\Page($count, $param['listRows']);
        $Page->setConfig('theme', "<ul class='pagination'><li><a>%totalRow% %header% %nowPage%/%totalPage% 页</a></li><li>%upPage%</li><li>%first%</li><li>%prePage%</li><li>%linkPage%</li><li>%nextPage%</li><li>%end%</li><li>%downPage%</li></ul>");
        $list['page'] = $Page->show();
        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $list['info'] = $M->relation($relationModel)->field($param['field'])->where($condition)->order($param['order'])->limit($Page->firstRow, $Page->listRows)->select();
        }else{

            $cacheCode = md5($param['modelName'] . $_SERVER['REQUEST_URI']);
            $startTime=microtime(true);
            $cacheList = S($cacheCode);
            $endTime=microtime(true);
            $totalTime=round(($endTime-$startTime)*1000, 3);
            
            // 启用缓存
            if(C('DATA_CACHE_TYPE') == 'redis') {
                S(array('host' => C('DATA_CACHE_HOST'), 'port' => '6379'));
            }

            if(empty($cacheList)){
                $startTime=microtime(true);
                $list['info'] = $M->field($param['field'])->where($condition)->order($param['order'])->limit($Page->firstRow, $Page->listRows)->select();
                $endTime=microtime(true);
                $totalTime=round(($endTime-$startTime)*1000, 3);
                $list['cacheInfo'] = '<i class="fa fa-clock-o fw"></i> <em>耗时 ' . $totalTime . ' 毫秒</em> / <i class="fa fa-database fw"></i> <em>从 MySQL 数据库读取</em>';
            } else {
                $list['info'] = $cacheList['info'];
                $list['cacheInfo'] = '<i class="fa fa-clock-o fw"></i> <em>耗时 ' . $totalTime . ' 毫秒</em> / <i class="fa fa-database fw"></i> <em>从 ' . C('DATA_CACHE_TYPE') . ' 缓存读取</em> / <i class="fa fa-coffee fw"></i> <em>缓存间隔 ' . C('DATA_CACHE_TIME') . ' 秒</em>';
            }

        }
        S($cacheCode,$list);
        //echo $sqlStr = $M->getlastsql();
        return $list;
    }

    /**
      +----------------------------------------------------------
     * 获取记录数
     * @return  int  返回记录条数
      +----------------------------------------------------------
     */
    public static function getCount($param = array('modelName' => '', 'field' => '*', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $condition) {
        $M = self::getModel($param);
        $count = $M->where($condition)->count($param['field']);
        //echo $sqlStr = $M->getlastsql();
        return $count;
    }

    /**
      +----------------------------------------------------------
     * 求和
     * @return  sum  返回求和值
      +----------------------------------------------------------
     */
    public static function getSum($param = array('modelName' => '', 'field' => '*', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $condition) {
        $M = self::getModel($param);
        return $sum = $M->where($condition)->sum($param['field']);
    }

    /**
      +----------------------------------------------------------
     * 获取详细数据
     * @return  array  返回详细数据
      +----------------------------------------------------------
     */
    public static function getDetail($param = array('modelName' => '', 'field' => '*', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $condition) {
        $M = self::getModel($param);
        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $info = $M->relation($relationModel)->where($condition)->find();
        }else{
            $info = $M->field($param['field'])->where($condition)->find();
        }
        return $info;
    }

    public static function rgetDetail($param = array('modelName' => '', 'field' => '*', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $condition) {
        $M = self::getModel($param);
        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $info = $M->relation($relationModel)->where($condition)->find();
        }else{

            $cacheCode = md5($param['modelName'] . $_SERVER['REQUEST_URI']);
            $startTime=microtime(true);
            $info = S($cacheCode);
            $endTime=microtime(true);
            $totalTime=round(($endTime-$startTime)*1000, 3);
            
            // 启用缓存
            if(C('DATA_CACHE_TYPE') == 'redis') {
                S(array('host' => C('DATA_CACHE_HOST'), 'port' => '6379'));
            }

            if(empty($info)){
                $startTime=microtime(true);
                $info = $M->field($param['field'])->where($condition)->find();
                $endTime=microtime(true);
                $totalTime=round(($endTime-$startTime)*1000, 3);
                $info['cacheInfo'] = '<i class="fa fa-clock-o fw"></i> <em>耗时 ' . $totalTime . ' 毫秒</em> / <i class="fa fa-database fw"></i> <em>从 MySQL 数据库读取</em>';
                S($cacheCode, $info);
            } else {
              $info['cacheInfo'] = '<i class="fa fa-clock-o fw"></i> <em>耗时 ' . $totalTime . ' 毫秒</em> / <i class="fa fa-database fw"></i> <em>从 ' . C('DATA_CACHE_TYPE') . ' 缓存读取</em> / <i class="fa fa-coffee fw"></i> <em>缓存间隔 ' . C('DATA_CACHE_TIME') . ' 秒</em>';
            }
            
        }
        //$sqlStr = $M->getlastsql();
        if (!is_array($info)) {
            //exit(C('ALERT_MSG.RECORD_NOT_EXIST'));
        }
        //echo $sqlStr = $M->getlastsql();
        return $info;
    }

    /**
      +----------------------------------------------------------
     * 写入数据表操作
     * @return  array  返回写入状态
      +----------------------------------------------------------
     */
    public static function insert($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $data) {
        $M = self::getModel($param);
        //$data['cUid'] = self::getMyInfo('uid');
        //$data['cTime'] = $data['uTime'] = time();

        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $result = $M->relation($relationModel)->add($data);
        }else{
            $result = $M->add($data);
        }
        //$sqlStr = $M->getlastsql();
        if ($result) {
           // self::OperationLogInsert($sqlStr);
            return jsonArray(200,C('ALERT_MSG.EXECUTE_SUCCESS'),$param['returnUrl']);
            //return array('statusCode' => 200, 'message' => C('ALERT_MSG.EXECUTE_SUCCESS'), 'closeCurrent' => true, 'url' => $param['returnUrl']);
        } else {
            return jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),$param['returnUrl']);
            //return array('statusCode' => 300, 'message' => C('ALERT_MSG.EXECUTE_FAILED'));
        }
    }

    /**
    +----------------------------------------------------------
     * 写入数据表操作并返回当前ID
     * @return  result  返回写入状态
    +----------------------------------------------------------
     */
    public static function lastinsertid($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $data) {
        $M = self::getModel($param);
        if($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $result = $M->relation($relationModel)->add($data);
        }else{
            $result = $M->add($data);
        }
        return $result;
    }
    /**
      +----------------------------------------------------------
     * 更新数据操作
     * @return  array  返回更新状态
      +----------------------------------------------------------
     */
    public static function update($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $data, $condition) {
        $M = self::getModel($param);
        //$data['uUid'] = self::getMyInfo('uid');
        //$data['uTime'] = time();

        if ($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $result = $M->relation($relationModel)->where($condition)->save($data);
        } else {
            $result = $M->where($condition)->save($data);
        }
        //$sqlStr = $M->getlastsql();
        if ($result) {
            //self::OperationLogInsert($sqlStr);
            //return jsonArray(300,C('ALERT_MSG.EXECUTE_SUCCESS'),$param['returnUrl']);
            return jsonArray(200,C('ALERT_MSG.EXECUTE_SUCCESS'),$param['returnUrl']);
            //return array('statusCode' => 200, 'message' => C('ALERT_MSG.EXECUTE_SUCCESS'), 'closeCurrent' => true, 'url' => $param['returnUrl']);
        } else {
            return jsonArray(300,C('ALERT_MSG.EXECUTE_FAILED'),$param['returnUrl']);
            //return array('statusCode' => 300, 'message' => C('ALERT_MSG.EXECUTE_FAILED'));
        }
    }

    /**
    +----------------------------------------------------------
     * 更新数据操作
     * @return  result  返回值是影响的记录数 或 false
    +----------------------------------------------------------
     */
    public static function rupdate($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => '', 'modelType' => '', 'relationModel' => ''), $data, $condition) {
        $M = self::getModel($param);
        if ($param['modelType'] == 'relationModel'){
            $relationModel = isset($param['relationModel']) ? $param['relationModel'] : true;
            $result = $M->relation($relationModel)->where($condition)->save($data);
        } else {
            $result = $M->where($condition)->save($data);
        }
        return $result;
    }

    /**
      +----------------------------------------------------------
     * 真实删除操作
     * @return  array  返回删除状态
      +----------------------------------------------------------
     */
    public static function del($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => ''), $condition) {
        $M = $param['connection'] ? M($param['modelName'], $param['tablePrefix'], $param['connection']) : M($param['modelName']);
        if ($M->where($condition)->delete()) {
            //$sqlStr = $M->getlastsql();
            //self::OperationLogInsert($sqlStr);
            return jsonArray(200,C('ALERT_MSG.DELETE_SUCCESS'),CONTROLLER_NAME,false);
            //return array('statusCode' => 200, 'message' => C('ALERT_MSG.DELETE_SUCCESS'), 'closeCurrent' => false);
        } else {
            return jsonArray(300,C('ALERT_MSG.DELETE_FAILED'),CONTROLLER_NAME,false);
            //return array('statusCode' => 300, 'message' => C('ALERT_MSG.DELETE_FAILED'));
        }
    }

    /**
    +----------------------------------------------------------
     * 真实删除操作
     * @return  array  返回删除状态
    +----------------------------------------------------------
     */
    public static function rdel($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => ''), $condition) {
        $M = $param['connection'] ? M($param['modelName'], $param['tablePrefix'], $param['connection']) : M($param['modelName']);
        $result=$M->where($condition)->delete();
        return $result;
    }
    /**
    +----------------------------------------------------------
     * 检查是否存在
     * @return  array  返回存在状态
    +----------------------------------------------------------
     */
    public static function exists($param = array('modelName' => '', 'returnUrl' => '', 'tablePrefix' => '', 'connection' => ''), $condition) {
        $M = $param['connection'] ? M($param['modelName'], $param['tablePrefix'], $param['connection']) : M($param['modelName']);
        $result=$M->where($condition)->find();
        return $result;
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
      +----------------------------------------------------------
     * 给$field字段值增加指定的值$value
      +----------------------------------------------------------
     */
    public static function toSetInc($param = array('modelName' => '', 'tablePrefix' => '', 'connection' => ''), $field, $value, $condition) {
        $M = $param['connection'] ? M($param['modelName'], $param['tablePrefix'], $param['connection']) : M($param['modelName']);
        if($M->where($condition)->setInc($field, $value)){
            $sqlStr = $M->getlastsql();
            self::OperationLogInsert($sqlStr);
        }
    }

    /**
      +----------------------------------------------------------
     * 记录操作日志
      +----------------------------------------------------------
     */
    public static function OperationLogInsert($content) {
        $data['content'] = $content;
        $data['cUid'] = self::getMyInfo('uid');
        $data['cTime'] = time();
        $M = M('Operationlog');
        $M->add($data);
    }

}

?>
