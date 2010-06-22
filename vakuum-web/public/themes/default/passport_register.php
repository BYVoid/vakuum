<?php $this->title='用户注册' ?>
<?php $this->display('header.php') ?>

<form id="form_register" name="form_login" method="post" action="<?php echo $this->locator->getURL('doregister') ?>">
	<table>
		<tr>
			<td>用户名</td>
			<td><input name="user_name" id="user_name" type="text" maxlength="16" /></td>
		</tr>
		<tr>
			<td>昵称</td>
			<td><input name="user_nickname" type="text" id="nickname" value="匿名懦夫" maxlength="16" /></td>
		</tr>
		<tr>
			<td>密码</td>
			<td><input name="user_password" id="user_password" type="password" maxlength="64" /></td>
		</tr>
		<tr>
			<td>重复输入密码</td>
			<td><input name="user_password_repeat" id="user_password_repeat" type="password" maxlength="64" /></td>
		</tr>
		<tr>
			<td>邮箱</td>
			<td><input name="email" id="email" type="text" maxlength="64" /></td>
		</tr>
		<tr>
			<td>个人网站</td>
			<td><input name="website" type="text" maxlength="256" /></td>
		</tr>
		<tr>
			<td>备注</td>
			<td><textarea name="memo" cols="60" rows="6" id="memo">我很懒，什么也不写。</textarea></td>
		</tr>
<?php if ($this->captcha):?>
		<tr>
			<td>验证码</td>
			<td><?php echo reCaptcha::showValidate() ?></td>
		</tr>
<?php endif ?>
	</table>
	<input type="submit" value="注册" />
</form>

<?php $this->display('footer.php') ?>