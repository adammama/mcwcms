<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="<?php echo U('edit');?>" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
        <div class="pageFormContent" data-layout-h="0">
          <table class="table table-condensed table-hover" width="100%">
           <tbody>
			<tr>
				<td><label for='userpidname' class='control-label x85'>父级菜单:</label>
					<select name='pid' id='pid' style="min-width: 80px;"><?php echo ($info["pidOption"]); ?></select>
				</td>
				<td><label for='sort' class='control-label x85'>排序:</label>
					<input type='text' id='sort' name='sort' data-rule='required;' size='5' value="<?php echo ($info["sort"]); ?>"></td>					
			</tr>
			<tr>
				<td><label for='title' class='control-label x85'>标题:</label>
					<input type='text' id='title' name='title' data-rule='required;' size='20' value="<?php echo ($info["title"]); ?>">
				</td>
				<td><label for='status' class='control-label x85'>状态:</label>
					<select name="status" data-toggle="selectpicker">
						<?php if($info["status"] > 0 ): ?><option value="1" selected>激活</option>
				            <option value="0">锁定</option>
				        <?php else: ?>
				            <option value="1">激活</option>
				            <option value="0" selected>锁定</option><?php endif; ?>

			        </select>
				</td>
			</tr>
			<tr>
				<td><label for='url' class='control-label x85'>链接:</label>
					<input type='text' id='url' name='url' data-rule='required;' size='20' value="<?php echo ($info["url"]); ?>">
				</td>
				<td><label for='open' class='control-label x85'>是否折叠:</label>
					<select name="open" data-toggle="selectpicker">
						<?php if($info["open"] > 0 ): ?><option value="1" selected>展开</option>
				            <option value="0">折叠</option>
				        <?php else: ?>
				            <option value="1">展开</option>
				            <option value="0" selected>折叠</option><?php endif; ?>
			        </select>
			        
			        
				</td>
			</tr>
			<tr>
				<td><label for='hide' class='control-label x85'>是否隐藏:</label>
						<?php if($info["hide"] < 1 ): ?><input type="radio" name="hide" id="hide1" data-toggle="icheck" value="1" data-rule="checked" data-label="是&nbsp;&nbsp;">
	                        <input type="radio" name="hide" id="hide2" data-toggle="icheck" value="0" data-label="否" checked>
                        <?php else: ?>
                        	<input type="radio" name="hide" id="hide1" data-toggle="icheck" value="1" data-rule="checked" data-label="是&nbsp;&nbsp;" checked>
	                        <input type="radio" name="hide" id="hide2" data-toggle="icheck" value="0" data-label="否"><?php endif; ?>
                        
				</td>
				<td><label for='is_dev' class='control-label x85'>仅开发者可见:</label>
					<?php if($info["is_dev"] < 1 ): ?><input type="radio" name="is_dev" id="is_dev1" data-toggle="icheck" value="1" data-label="是&nbsp;&nbsp;">
                        <input type="radio" name="is_dev" id="is_dev2" data-toggle="icheck" value="0" data-rule="checked" data-label="否" checked>
                   	<?php else: ?>
                        <input type="radio" name="is_dev" id="is_dev1" data-toggle="icheck" value="1" data-label="是&nbsp;&nbsp;" checked>
                        <input type="radio" name="is_dev" id="is_dev2" data-toggle="icheck" value="0" data-rule="checked" data-label="否"><?php endif; ?>
				</td></tr>
			<tr>
				<td colspan="2"><label for='group' class='control-label x85'>分组:</label>
					<input type='text' id='group' name='group' data-rule='密码:required;' size='65' value="<?php echo ($info["group"]); ?>">
				</td>
			</tr>
			<tr><td colspan=2><label for='remark' class='control-label x85'>说明:</label>
				<textarea name='remark' cols='65' rows='2' > <?php echo ($info["remark"]); ?></textarea></td></tr>
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