<?php $this->title=$this->user['user_nickname'] ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>用户ID</td>
		<td><?php echo $this->user['user_id'] ?></td>
	</tr>
	<tr>
		<td>用户名</td>
		<td><?php echo $this->escape($this->user['user_name']) ?></td>
	</tr>
	<tr>
		<td>昵称</td>
		<td><?php echo $this->escape($this->user['user_nickname']) ?></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><?php echo reCaptcha::showEmail($this->user['email']) ?></td>
	</tr>
	<tr>
		<td>个人网站</td>
		<td><a href="<?php echo $this->escape($this->user['website']) ?>" target="_blank" rel="nofollow"><?php echo $this->escape($this->user['website']) ?></a></td>
	</tr>
	<tr>
		<td>注册时间</td>
		<td><?php echo $this->formatTime($this->user['register_time']) ?></td>
	</tr>
	<tr>
		<td>身份</td>
		<td><?php echo $this->escape($this->user['identity']) ?></td>
	</tr>
	<tr>
		<td>备注</td>
		<td><?php echo $this->escape($this->user['memo']) ?></td>
	</tr>
</table>

<?php echo gravatar::showImage($this->user['email']) ?>

<?php $this->display('footer.php') ?>