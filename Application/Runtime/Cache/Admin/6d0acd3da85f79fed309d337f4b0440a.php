<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">

<form action="<?php echo U('addToGroup');?>" class="pageForm" data-toggle="validate">
        <div class="pageFormContent" data-layout-h="0">
	        <table class="table table-bordered table-condensed table-hover" width="100%">
	           <tbody>
				<tr>
					<td><label for='title' class='control-label'>成员列表:</label>
						<input type="hidden" name="group_id" value="<?php echo I('get.id');?>">
						<input type="text" name="uid" id="uid" size="30" data-toggle="lookup" data-url="<?php echo U('groupmemberlookup',array('groupid'=>I('get.id')));?>" data-width="600" data-height="300">
						&nbsp;&nbsp;<button type="submit" class="btn-default" data-icon="save">新增</button>
						<span style="float:right;" ><a href="<?php echo U('delUserFromGroup',array('group_id'=>I('get.id')));?>" type="button" class="btn btn-red" data-toggle="doajaxchecked" data-group="ids" data-icon="trash" data-confirm-msg="确定要解除吗？">批量解除授权</a></span>
					</td>
				</tr>
				<tr><td></td></tr>
	           </tbody>
	          </table>

			<table data-toggle="tablefixed" class="table table-bordered  table-condensed table-hover" data-width="100%">
		        <thead>
		            <tr>
			            <th width="10" height="30"></th>
			            <th data-order-direction='asc' data-order-field='id'>ID</th>
						<th data-order-direction='desc' data-order-field='username'>用户名</th>
						<th>状态</th>
						<th width="26"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
						<th>操作</th>
		            </tr>
		        </thead>
		        <tbody>
					 <?php if(is_array($groupmember)): foreach($groupmember as $key=>$v): ?><tr>
					    <td></td>
					    <td><?php echo ($v["id"]); ?></td>
						<td><?php echo ($v["username"]); ?></td>
						<td><?php if($v["status"] == 1 ): ?><img src="/mcwcms/Public/images/ok.gif" border="0"/><?php else: ?><img src="/mcwcms/Public/images/locked.gif" border="0"/><?php endif; ?></td>
						<td><input type="checkbox" name="ids" data-toggle="icheck" value="<?php echo ($v["id"]); ?>"></td>
						<td><a href="<?php echo U('removeFromGroup',array('uid'=>$v['id'],'group_id'=>I('get.id')));?>" type="button" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要解除吗？" data-icon="trash">解除授权</a></td>
			         </tr><?php endforeach; endif; ?>
		        </tbody>
	    	</table>
        </div>
</form>
</div>