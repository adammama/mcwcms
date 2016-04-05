<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="<?php echo U('writeGroup');?>" class="pageForm" data-toggle="validate">
        <div class="pageFormContent" data-layout-h="0">
          <table class="table table-condensed table-hover" width="100%">
           <tbody>
           <?php if(is_array($node_list)): $i = 0; $__LIST__ = $node_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$node): $mod = ($i % 2 );++$i;?><tr style="background-color: #ebebeb;">
					<td><label><input obj="node_<?php echo ($node["id"]); ?>" class="auth_rules rules_all" type="checkbox" name="rules[]" value="<?php echo $main_rules[$node['url']] ?>"> <?php echo ($node["title"]); ?>管理</label></td>
				</tr>
				<?php if(isset($node['child'])): if(is_array($node['child'])): $i = 0; $__LIST__ = $node['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><tr>
						<td style="padding-left: 50px;">
							<label <?php if(!empty($child['tip'])): ?>title='<?php echo ($child["tip"]); ?>'<?php endif; ?>>
							<input obj="node_<?php echo ($node["id"]); ?>_<?php echo ($child["id"]); ?>" class="auth_rules rules_row" type="checkbox" name="rules[]" value="<?php echo $auth_rules[$child['url']] ?>"/> <?php echo ($child["title"]); ?></label>
						</td>
					</tr>
                    <?php if(!empty($child['operator'])): ?><tr>
                    		<td style="padding-left: 100px;">
	                           <?php if(is_array($child['operator'])): $i = 0; $__LIST__ = $child['operator'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$op): $mod = ($i % 2 );++$i;?><label style=" float: left; padding-right: 20px;" <?php if(!empty($op['tip'])): ?>title='<?php echo ($op["tip"]); ?>'<?php endif; ?>>
	                                   <input obj="node_<?php echo ($node["id"]); ?>_<?php echo ($child["id"]); ?>_<?php echo ($op["id"]); ?>" class="auth_rules rules_item" type="checkbox" name="rules[]"
	                                   value="<?php echo $auth_rules[$op['url']] ?>"/> <?php echo ($op["title"]); ?>
	                               </label><?php endforeach; endif; else: echo "" ;endif; ?>
                           </td>
                        </tr><?php endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
			<tr><td></td></tr>
           </tbody>
          </table>
        </div>
        <input type="hidden" name="id" value="<?php echo ($this_group["id"]); ?>" />
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button type="submit" class="btn-default">保存</button></li>
    </ul>
</div>
<script type="text/javascript" charset="utf-8">
    +function($){
        var rules = [<?php echo ($this_group["rules"]); ?>];
        $('.auth_rules').each(function(){
            if( $.inArray( parseInt(this.value,10),rules )>-1 ){
                $(this).prop('checked',true);
            }
        });

        //全选节点
        $('.rules_all').on('change',function(){
        	//console.info('abc');
            var obj=$(this).attr("obj")+"_";
            $("input[obj^='"+obj+"']").prop("checked",$(this).prop("checked"));
        });
        $('.rules_row').on('change',function(){
            var obj=$(this).attr("obj")+"_";
            $("input[obj^='"+obj+"']").prop("checked",$(this).prop("checked"));
            var tem=obj.split("_");
            //将当前模块父级选中
            if($(this).prop('checked')){
                $("input[obj='node_"+tem[1]+"']").prop("checked","checked");
            }
        });
        $('.rules_item').on('change',function(){
            var tem=$(this).attr("obj").split("_");
            if($(this).prop('checked')){
                //所属项目
                $("input[obj='node_"+tem[1]+"']").prop("checked","checked");
                //所属模块
                $("input[obj='node_"+tem[1]+"_"+tem[2]+"']").prop("checked","checked");
            }
        });
    }(jQuery);
</script>