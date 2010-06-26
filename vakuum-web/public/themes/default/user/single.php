<?php
/**
 *
 * @var MDL_USER
 */
$user = $this->user;
unset($this->user);
$user_info = $user->getInfo();
$this->title = '用户 - ' . $user->getNickname();
?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td><?php echo $user->getID() ?></td>
	</tr>
	<tr>
		<td>用户名</td>
		<td><?php echo $this->escape($user->getName()) ?></td>
	</tr>
	<tr>
		<td>昵称</td>
		<td><?php echo $this->escape($user->getNickname()) ?></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><?php echo reCaptcha::showEmail($user_info['email']) ?></td>
	</tr>
	<tr>
		<td>个人网站</td>
		<td><a href="<?php echo $this->escape($user_info['website']) ?>" target="_blank" rel="nofollow"><?php echo $this->escape($user_info['website']) ?></a></td>
	</tr>
	<tr>
		<td>注册时间</td>
		<td><?php echo $this->formatTime($user_info['register_time']) ?></td>
	</tr>
	<tr>
		<td>身份</td>
		<td><?php echo $this->escape($user_info['identity']) ?></td>
	</tr>
	<tr>
		<td>备注</td>
		<td><?php echo $this->escape($user_info['memo']) ?></td>
	</tr>
</table>

<?php echo gravatar::showImage($user_info['email']) ?>

<?php $this->display('footer.php') ?>