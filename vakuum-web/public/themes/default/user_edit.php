<?php $this->title = '修改个人信息' ?>
<?php $this->display('header.php') ?>

<form action="<?php echo $this->locator->getURL('user_doedit') ?>" method="post" >
<table border="1">
<tr>
	<td>用户名</td>
	<td><?php echo $this->escape($this->user['user_name']) ?></td>
</tr>
<tr>
	<td>昵称</td>
	<td><input name="user_nickname" type="text" value="<?php echo $this->escape($this->user['user_nickname']) ?>" maxlength="16" /></td>
</tr>
<tr>
	<td>原密码(不修改请留空)</td>
	<td><input name="user_password_original" type="password" value="" maxlength="64" /></td>
</tr>
<tr>
	<td>新密码(不修改请留空)</td>
	<td><input name="user_password" type="password" value="" maxlength="64" /></td>
</tr>
<tr>
	<td>重复输入密码</td>
	<td><input name="user_password_repeat" type="password" value="" maxlength="64" /></td>
</tr>
<tr>
	<td>邮箱</td>
	<td><input name="email" type="text" value="<?php echo $this->escape($this->user['email']) ?>" maxlength="64" /></td>
</tr>
<tr>
	<td>个人网站</td>
	<td><input name="website" type="text" value="<?php echo $this->escape($this->user['website']) ?>" maxlength="256" /></td>
</tr>
<tr>
	<td>备注</td>
	<td><textarea name="memo" cols="60" rows="6" id="memo"><?php echo $this->escape($this->user['memo']) ?></textarea></td>
</tr>
</table>
<input type="submit" value="修改" />
</form>

<?php $this->display('footer.php') ?>