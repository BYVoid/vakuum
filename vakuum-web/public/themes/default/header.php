<?php require_once('common.php') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $this->loadHeader()?>
<title><?php echo $this->title ?> - Vakuum</title>
</head>
<body>

<div id="wrapper">

<div id="header">
<h1>Vakuum</h1>
<p>
	<a href="<?php echo $this->locator->getURL('index')?>">首页</a>
	<a href="<?php echo $this->locator->getURL('problem_list')?>">题目列表</a>
	<a href="<?php echo $this->locator->getURL('user_list')?>">用户列表</a>
	<a href="<?php echo $this->locator->getURL('record_list')?>">记录列表</a>
	<a href="<?php echo $this->locator->getURL('judger_list')?>">评测机列表</a>
<?php if(BFL_ACL::getInstance()->check(array('general','unvalidated'))):?>
	<a href="<?php echo $this->locator->getURL('user_space')?>">个人中心</a>
	<a href="<?php echo $this->locator->getURL('passport_dologout')?>">用户登出</a>
<?php else:?>
	<a href="<?php echo $this->locator->getURL('passport')?>">用户登录</a>
<?php endif ?>
<?php if(BFL_ACL::getInstance()->check('administrator')):?>
	<a href="<?php echo $this->locator->getURL('admin_index')?>">后台管理</a>
<?php endif ?>
</p>
</div>

<div id="content">