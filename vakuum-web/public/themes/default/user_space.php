<?php $this->title = '个人中心' ?>
<?php $this->display('header.php') ?>

<a href="<?php echo $this->locator->getURL('user_edit') ?>">修改个人信息</a>

<?php if(MDL_ACL::getInstance()->check('unvalidated')):?>
<p><a href="<?php echo $this->locator->getURL('passport_sendvalidation') ?>">重新发送验证邮件</a></p>
<?php endif?>

<?php $this->display('footer.php') ?>