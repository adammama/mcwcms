<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="<?php echo U('groupmemberlookup');?>" method="post">
		<input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
	    <input type="hidden" name="pageCurrent" value="<?php echo ($currentPage); ?>">
	    <input type="hidden" name="orderField" value="id">
	    <input type="hidden" name="orderDirection" value="asc">	 
        <div class="bjui-searchBar">
            <label>名称：</label><input type="text" value="<?php echo ($_REQUEST['keys']); ?>" name="keys" size="15">&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" onclick="$.CurrentDialog.dialog('refresh');" data-icon="undo">清空查询</a>
            <!--<a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>--></li>
            <div class="pull-right">
                <button type="button" class="btn-blue" data-toggle="lookupback" data-lookupid="ids" data-warn="请至少选择一个用户" data-icon="check-square-o">选择选中</button>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent">
    <table data-toggle="tablefixed" data-width="100%">
        <thead>
            <tr>
                <th data-order-field="id">ID</th>
                <th data-order-field="username">用户名</th>
                <th data-order-field="status">状态</th>
                <th width="28"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
                <th width="74">操作</th>
            </tr>
        </thead>
        <tbody>
			 <?php if(is_array($user)): foreach($user as $key=>$v): ?><tr>
                <td><?php echo ($v["id"]); ?></td>
                <td><?php echo ($v["username"]); ?></td>
                <td><?php if($v["status"] == 1 ): ?><img src="/mcwcms/Public/images/ok.gif" border="0"/><?php else: ?><img src="/mcwcms/Public/images/locked.gif" border="0"/><?php endif; ?></td>
                <td><input type="checkbox" name="ids" data-toggle="icheck" value="{uid:<?php echo ($v['id']); ?> }"></td>
                <td>
                    <a href="javascript:;" data-toggle="lookupback" data-args="{ uid:<?php echo ($v['id']); ?> }" class="btn btn-blue" title="选择本项" data-icon="check">选择</a>
                </td>
            </tr><?php endforeach; endif; ?>            
        </tbody>
    </table>
</div>
<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="30">20</option>
                <option value="60">40</option>
                <option value="120">60</option>
                <option value="150">80</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo ($totalCount); ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo ($totalCount); ?>" data-page-size="<?php echo ($numPerPage); ?>" data-page-current="<?php echo ($currentPage); ?>">
    </div>
</div>