<?php $this->title='用户登录' ?>
<?php $this->display('header.php') ?>

<a href="<?php echo $this->locator->getURL('passport_register')?>">用户注册</a>

<form id="form_login" name="form_login" method="post" action="<?php echo $this->locator->getURL('dologin') ?>">
	<table>
		<tr>
			<td>账号</td>
			<td><input name="user_name" type="text" maxlength="16" /></td>
		</tr>
		<tr>
			<td>密码</td>
			<td><input name="user_password" type="password" maxlength="16" /></td>
		</tr>
	</table>
	<input type="submit" value="登录" />
</form>

<?php $this->display('footer.php') ?>