<?php $this->title = '修改评测机' ?>
<?php $this->display('header.php') ?>
<?php $state = $this->state?>

<?php if ($state == 'ready'):?>
连接成功
<?php else: ?>
连接失败: <?php echo $state?>
<?php endif ?>

<?php $this->display('footer.php') ?>