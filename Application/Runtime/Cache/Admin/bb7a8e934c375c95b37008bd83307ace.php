<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
<form id="pagerForm" data-toggle="ajaxsearch" action="<?php echo U('index');?>" method="post">
	
	<input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ($currentPage); ?>">
    <input type="hidden" name="orderField" value="id">
    <input type="hidden" name="orderDirection" value="asc">	 
    <div class="bjui-searchBar">
        <label>关键词：</label><input type="text" value="<?php echo ($_REQUEST['keys']); ?>" name="keys" class="form-control" size="15" />
         <button type="submit"  class="btn-default" data-icon="search">查询</button>
          <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
		  <!--<span style="float:right;" ><a href="/mcwcms/index.php/Admin/Menu/del/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red" data-toggle="doajax" data-confirm-msg="确定要清理吗？" data-icon="remove">清理</a></span>--> 
		  <span style="float:right;" ><a href="<?php echo U('delselect');?>" type="button" class="btn btn-red" data-toggle="doajaxchecked" data-group="delids" data-icon="remove" data-confirm-msg="确定要删除选中项吗？">批量删除</a></span>
<!--		  <span style="float:right;margin-right:20px;"><a href="/mcwcms/index.php/Admin/Menu/outxls" class="btn btn-blue" data-toggle="doexport" data-confirm-msg="确定要导出吗？" data-icon="arrow-up">导出</a></span>-->
		  <span style="float:right;margin-right:20px;"><a href="<?php echo U('add');?>" class="btn btn-green" data-toggle="dialog" data-width="900" data-height="500" data-id="dialog-mask" data-mask="true" data-icon="plus">新增菜单</a></span>
	</div> 
</form>    
</div>

<div class="bjui-pageContent">
	<form>
     <table class="table table-bordered table-hover" data-width="100%" data-layout-h="0" data-nowrap="true">
        <thead>
            <tr>
	            <th width="10" height="30"></th>
	            <th data-order-direction='asc' data-order-field='id'>ID</th>
				<th>标题</th>
				<th>URL</th>
				<th>状态</th>
				<th>排序</th>
				<th>是不折叠</th>
			    <th>隐藏</th>
			    <th>仅开发可见</th>
			    <th>操作</th>
            </tr>
        </thead>
        <tbody>
			<?php if(is_array($cate)): $k = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr id="<?php echo ($vo["id"]); ?>" pid="<?php echo ($vo["pid"]); ?>" >
			    <td></td>
			    <td><?php echo ($vo["id"]); ?></td>
				<td class="fold_sub" style="cursor: pointer;"><?php echo ($vo["fullname"]); ?></td>
				<td><?php echo ($vo["url"]); ?></td>
				<td><?php if($vo[status] == 1): ?><img src="/mcwcms/Public/images/ok.gif" border="0"/><?php else: ?><img src="/mcwcms/Public/images/locked.gif" border="0"/><?php endif; ?></a></td>				
				<td><?php echo ($vo["sort"]); ?></td>
				<td><?php if($vo["open"]): ?>展开<?php else: ?>折叠<?php endif; ?></td>
				<td><?php if($vo["hide"]): ?>隐藏<?php else: ?>可见<?php endif; ?></td>
				<td><?php if($vo["is_dev"]): ?>是<?php else: ?>否<?php endif; ?></td>
				<td>
					<a href="<?php echo U('edit',array('id'=>$vo['id']));?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="900" data-height="500" data-id="dialog-mask" data-mask="true" data-icon="edit"></a>
					&nbsp;&nbsp;<a href="<?php echo U('del',array('id'=>$vo['id']));?>" type="button" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？" data-icon="remove"></a>
				</td>
	        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</form>
</div>
<script type="text/javascript" charset="utf-8">
	$(function(){
	    var chn=function(cid,op){
	        if(op=="show"){
	            $("tr[pid='"+cid+"']").each(function(){
	                $(this).removeAttr("status").show();
	                chn($(this).attr("id"),"show");
	            });
	        }else{
	            $("tr[pid='"+cid+"']").each(function(){
	                $(this).attr("status",1).hide();
	                chn($(this).attr("id"),"hide");
	            });
	        }
	    }
	    $(".fold_sub").click(function(){
	        if($(this).attr("status")!=1){
	            chn($(this).parent().attr("id"),"hide");
	            $(this).attr("status",1);
	        }else{
	            chn($(this).parent().attr("id"),"show");
	            $(this).removeAttr("status");
	        }
	    });	
	});    
</script>