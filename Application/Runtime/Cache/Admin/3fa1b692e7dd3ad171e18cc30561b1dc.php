<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="<?php echo U('add');?>" class="pageForm" data-toggle="validate">
        <div class="pageFormContent" data-layout-h="0">
          <table class="table table-condensed table-hover" width="100%">
           <tbody>
			<tr>
				<td><label for='title' class='control-label x85'>角色名称:</label>
					<input type='text' id='title' name='title' data-rule='required;' size='20'></td>
			</tr>
			<tr>
				<td><label for='status' class='control-label x85'>状态:</label>
					<select name="status" data-toggle="selectpicker">
			            <option value="1" selected>激活</option>
			            <option value="0">锁定</option>
			        </select>
				</td>
			</tr>
			<tr><td colspan=2><label for='remark' class='control-label x85'>描述:</label>
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