<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
<form id="pagerForm" data-toggle="ajaxsearch" action="<?php echo U('index');?>" method="post">
	
	<input type="hidden" name="pageSize" value="<?php echo (session('pageSize')); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo (session('pageCurrent')); ?>">
    <input type="hidden" name="orderField" value="<?php echo (session('orderField')); ?>">
    <input type="hidden" name="orderDirection" value="<?php echo (session('orderDirection')); ?>">
    <div class="bjui-searchBar">
        <!--<label>关键词：</label><input type="text" name="search[username]" value="<?php echo ($search["username"]); ?>" class="form-control" size="15" />-->
        <label>关键词：</label><input type="text" name="search" value="<?php echo ($search); ?>" class="form-control" size="15" />
         <button type="submit"  class="btn-default" data-icon="search">查询</button>
          <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
		  <!--<span style="float:right;" ><a href="/mcwcms/index.php/Admin/User/del/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red" data-toggle="doajax" data-confirm-msg="确定要清理吗？" data-icon="remove">清理</a></span>--> 
		  <span style="float:right;" ><a href="<?php echo U('delselect');?>" type="button" class="btn btn-red" data-toggle="doajaxchecked" data-group="ids" data-icon="remove" data-confirm-msg="确定要删除选中项吗？">批量删除</a></span>
<!--		  <span style="float:right;margin-right:20px;"><a href="/mcwcms/index.php/Admin/User/outxls" class="btn btn-blue" data-toggle="doexport" data-confirm-msg="确定要导出吗？" data-icon="arrow-up">导出</a></span>-->
		  <span style="float:right;margin-right:20px;"><a href="<?php echo U('add');?>" class="btn btn-green" data-toggle="dialog" data-width="900" data-height="500" data-id="dialog-mask" data-mask="true" data-icon="plus">新增用户</a></span>
	</div> 
</form>    
</div>

<div class="bjui-pageContent">
     <table data-toggle="tablefixed" data-width="100%" data-layout-h="0" data-nowrap="true">
        <thead>
            <tr>
                <th width="26"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
                <?php if(is_array($tableFields)): foreach($tableFields as $key=>$tv): ?><th <?php if($tv["order"] == 1): ?>data-order-field="<?php echo ($key); ?>"<?php endif; ?>><?php echo ($tv["name"]); ?></th><?php endforeach; endif; ?>
                <th>操作</th>
<!--	            <th width="10" height="30"></th>
	            <th width="26"><input type="checkbox" class="checkboxCtrl" data-group="delids" data-toggle="icheck"></th>
	            <th data-order-direction='asc' data-order-field='id'>ID</th>
				<th data-order-direction='desc' data-order-field='username'>用户名</th>
				<th data-order-direction='desc' data-order-field='status'>状态</th>
				<th>IP</th>
				<th data-order-direction='desc' data-order-field='last_login_time'>时间</th>
			    <th>编辑</th>-->
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
                    <td><input type="checkbox" name="ids" data-toggle="icheck" value="<?php echo ($v["id"]); ?>"></td>
                    <?php if(is_array($tableFields)): foreach($tableFields as $key=>$tvo): ?><td><?php echo ($v["$key"]); ?></td><?php endforeach; endif; ?>
                    <td>
                        <a href="<?php echo U('edit',array('id'=>$v['id']));?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="900" data-height="500" data-id="dialog-mask" data-mask="true" data-icon="edit"></a>
                        &nbsp;&nbsp;<a href="<?php echo U('del',array('id'=>$v['id']));?>" type="button" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？" data-icon="remove"></a>
                    </td>
                </tr><?php endforeach; endif; ?>
<!--			 <?php if(is_array($user)): foreach($user as $key=>$v): ?><tr>
			    <td></td>
			    <td><input type="checkbox" name="delids" data-toggle="icheck" value="<?php echo ($v["id"]); ?>"></td>
			    <td><?php echo ($v["id"]); ?></td>
				<td><?php echo ($v["username"]); ?></td>
				<td><a href="/mcwcms/index.php/Admin/User/del/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" data-toggle="doajax" data-confirm-msg="确定要操作吗？"><?php if($v["status"] == 1 ): ?><img src="/mcwcms/Public/images/ok.gif" border="0"/><?php else: ?><img src="/mcwcms/Public/images/locked.gif" border="0"/><?php endif; ?></a></td>
				<td><?php echo ($v["last_login_ip"]); ?></td>
				<td><?php echo (date("Y-m-d",$v["last_login_time"])); ?></td>
				<td>
					<a href="<?php echo U('useredit',array('id'=>$v['id']));?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="900" data-height="500" data-id="dialog-mask" data-mask="true" data-icon="edit">编辑</a>
					&nbsp;&nbsp;<a href="<?php echo U('userdel',array('id'=>$v['id']));?>" type="button" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？" data-icon="remove">删除</a>
				</td>
	        </tr><?php endforeach; endif; ?>-->
        </tbody>
    </table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="20" <?php if($_SESSION['pageSize']== 20): ?>selected="selected"<?php endif; ?>>20</option>
                <option value="40" <?php if($_SESSION['pageSize']== 40): ?>selected="selected"<?php endif; ?>>40</option>
                <option value="60" <?php if($_SESSION['pageSize']== 60): ?>selected="selected"<?php endif; ?>>60</option>
                <option value="120" <?php if($_SESSION['pageSize']== 120): ?>selected="selected"<?php endif; ?>>120</option>
                <option value="150" <?php if($_SESSION['pageSize']== 150): ?>selected="selected"<?php endif; ?>>150</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo ($total); ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo ($total); ?>" data-page-size="<?php echo (session('pageSize')); ?>" data-page-current="<?php echo (session('pageCurrent')); ?>">
    </div>
</div>