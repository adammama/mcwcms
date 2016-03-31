<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="<?php echo U('useradd');?>" class="pageForm" data-toggle="validate">
        <div class="pageFormContent" data-layout-h="0">
          <table class="table table-condensed table-hover" width="100%">
           <tbody>
			<tr>
				<td><label for='username' class='control-label x85'>用户:</label>
					<input type='text' id='username' name='username' data-rule='用户名:required;' size='20'></td>
			</tr>
			<tr>
				<td><label for='password' class='control-label x85'>密码:</label>
					<input type='password' id='password' name='password' data-rule='密码:required;' size='20'>
				</td>
			</tr>
			<tr>
				<td>
					<label for='repassword' class='control-label x85'>确认密码:</label>
					<input type='password' id='repassword' name='repassword' data-rule="确认密码:required;match(password)"  size='20'>
				</td>
			</tr>
			<tr>
				<td><label for='status' class='control-label x85'>状态:</label>
					<select name="status" data-toggle="selectpicker">
			            <option value="1" selected>启用</option>
			            <option value="0">禁用</option>
			        </select>
				</td>
			</tr>
			<tr>
				<td><label for='group_id' class='control-label x85'>所属角色:</label>
					<select name="group_id" data-toggle="selectpicker" data-rule='所属角色:required;'>
			            <option value="">--请选择--</option>
			            <?php if(is_array($group)): foreach($group as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["title"]); ?></option><?php endforeach; endif; ?>
			        </select>
				</td>
			</tr>
			<tr><td colspan=2><label for='remark' class='control-label x85'>备注:</label>
				<textarea name='remark' cols='65' rows='5' ></textarea></td></tr>
			<tr><td></td></tr>
           </tbody>
          </table>
        </div>
        <div class="bjui-footBar">
            <ul>
            	&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" class="btn-default" data-icon="save">保存</button>&nbsp;&nbsp;
                <button type="button" class="btn-close" data-icon="close">取消</button>
                
            </ul>
        </div>
    </form>
</div>
<!--<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button type="submit" class="btn-default">保存</button></li>
    </ul>
</div>-->