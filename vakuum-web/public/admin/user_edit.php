<?php $this->title = '修改用户' ?>
<?php $this->display('header.php') ?>
<?php $identity = explode(',',$this->user['identity']) ?>

<form action="<?php echo $this->locator->getURL('admin_user_doedit') ?>" method="post" >
<table border="1">
<?php if ($this->user['user_id'] != 0): ?>
<tr>
	<td>用户ID</td>
	<td><?php echo $this->user['user_id'] ?></td>
</tr>
<tr>
	<td>永久删除</td>
	<td><input name="remove" type="checkbox" value="1" /></td>
</tr>
<?php endif ?>
<tr>
	<td>用户名</td>
	<td><input name="user_name" type="text" value="<?php echo $this->escape($this->user['user_name']) ?>" maxlength="16" <?php if ($this->user['user_id'] != 0): ?>readonly="readonly"<?php endif?> /></td>
</tr>
<tr>
	<td>昵称</td>
	<td><input name="user_nickname" type="text" value="<?php echo $this->escape($this->user['user_nickname']) ?>" maxlength="16" /></td>
</tr>
<tr>
	<td><?php if ($this->user['user_id'] != 0): ?>新密码(不修改请留空)<?php else:?>密码<?php endif?></td>
	<td><input name="user_password" type="text" value="" maxlength="64" /></td>
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
<tr>
	<td>用户权限组</td>
	<td>
		<ul>
			<li><input name="identity[unvalidated]" type="checkbox" value="1" />等待验证</li>
			<li><input name="identity[general]" type="checkbox" value="1" />用户</li>
			<li><input name="identity[administrator]" type="checkbox" value="1" />管理员</li>
		</ul>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="<?php echo $this->user['action'] ?>" />
<input type="hidden" name="user_id" value="<?php echo $this->user['user_id'] ?>" />
<input type="submit" value="修改" />
</form>
<script type="text/javascript">//<![CDATA[
$(document).ready(function()
{
	<?php foreach($identity as $item): ?>
	$('form input:checkbox[name^="identity[<?php echo $item ?>]"]').attr('checked','checked');
	<?php endforeach; ?>
});
//]]></script>

<?php $this->display('footer.php') ?>