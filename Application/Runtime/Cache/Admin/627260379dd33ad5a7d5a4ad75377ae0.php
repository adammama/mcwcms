<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0" />
<title>管理员登录</title>
<link rel="stylesheet" type="text/css" href="/mcwcms/Public/BJUI/themes/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/mcwcms/Public/css/login.css" />
</head>

<body>
<div class="box">
		<div class="login-box">
			<div class="login-title text-center">
				<h1><small>管理员登录</small></h1>
			</div>
			<div class="login-content ">
			<div class="form">
			<form action="<?php echo U('checkLogin');?>" method="post">
				<div class="form-group">
					<div class="col-xs-12  ">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
							<input type="text" id="username" name="username" class="form-control" placeholder="用户名">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12  ">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
							<input type="text" id="password" name="password" class="form-control" placeholder="密码">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12  ">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
							<input type="text" id="verify" name="verify" class="form-control" placeholder="验证码">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12  ">
						<img class="img-rounded img" src="/mcwcms/index.php/Admin/Public/verify" alt="" onclick="this.src='/mcwcms/index.php/Admin/Public/verify/'+Math.random()" />
					</div>
				</div>
				<div class="form-group form-actions">
					<div class="col-xs-4 col-xs-offset-4">
						<button type="submit" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-off"></span> 登录</button>
					</div>
				</div>
<!--				<div class="form-group">
					<div class="col-xs-6 link">
						<p class="text-center remove-margin"><small>忘记密码？</small> <a href="javascript:void(0)" ><small>找回</small></a>
						</p>
					</div>
					<div class="col-xs-6 link">
						<p class="text-center remove-margin"><small>还没注册?</small> <a href="javascript:void(0)" ><small>注册</small></a>
						</p>
					</div>
				</div>-->
			</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>