<?php require_once('common.php') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $this->loadHeader()?>
<title><?php echo $this->title ?> - 后台管理 - Vakuum</title>
</head>
<body>

<div id="wrapper">

<div id="header">
	<div id="header_user">
		<?php echo $current_user['user_nickname'] ?> | 
		<a href="<?php echo $this->locator->getURL('passport_dologout')?>">退出登录</a>
	</div>
	<div id="header_site">
		Vakuum 后台管理
		<a href="<?php echo $this->locator->getURL('index')?>">查看站点首页</a>
	</div>

</div><!-- header -->

<div id="body">

<div id="adminmenu">
<ul>
	<li><a href="<?php echo $this->locator->getURL('admin_index')?>">后台管理</a></li>
	<li><a href="<?php echo $this->locator->getURL('admin_preference')?>">参数设置</a></li>
	<li><a href="<?php echo $this->locator->getURL('admin_problem_list')?>">题目管理</a></li>
	<li><a href="<?php echo $this->locator->getURL('admin_user_list')?>">用户管理</a></li>
	<li><a href="<?php echo $this->locator->getURL('admin_record_list')?>">记录管理</a></li>
	<li><a href="<?php echo $this->locator->getURL('admin_judger_list')?>">评测机管理</a></li>
</ul>
</div><!-- adminmenu -->

<div id="content">

<h1><?php echo $this->title ?></h1>