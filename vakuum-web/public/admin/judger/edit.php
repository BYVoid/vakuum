<?php $this->title = '修改评测机' ?>
<?php $this->display('header.php') ?>
<?php $judger = $this->judger?>

<form action="<?php echo $this->locator->getURL('admin_judger_doedit') ?>" method="post" >
<table border="1">
<?php if ($judger->getID() != 0): ?>
<tr>
	<td>ID</td>
	<td><?php echo $judger->getID() ?></td>
</tr>
<tr>
	<td>永久删除</td>
	<td><input name="remove" type="checkbox" value="1" /></td>
</tr>
<?php endif ?>
<tr>
	<td>名称</td>
	<td><input name="judger_name" type="text" value="<?php echo $judger->getName() ?>" maxlength="32" /></td>
</tr>
<tr>
	<td>优先级</td>
	<td><input name="judger_priority" type="text" value="<?php echo $judger->getPriority() ?>" /></td>
</tr>
<tr>
	<td>可用</td>
	<td><input name="judger_enabled" type="checkbox" value="1" <?php if ($judger->isEnabled()): ?>checked="checked" <?php endif; ?> /></td>
</tr>
<tr>
	<td>URL</td>
	<td><input name="url" type="text" value="<?php echo $judger->getConfig()->getRemoteURL() ?>" /></td>
</tr>
<tr>
	<td>密钥</td>
	<td><input name="public_key" type="text" value="<?php echo $judger->getConfig()->getRemoteKey() ?>" /></td>
</tr>
<tr>
	<td>传输模式</td>
	<td>
		<select name="upload">
			<option value="ftp">FTP</option>
			<option value="share">本地</option>
		</select>

	</td>
</tr>
<?php $judger_config = $judger->getConfig() ?>
<tr id="config_ftp">
	<td>FTP设置</td>
	<td>

		<table>
			<tr>
				<td>地址</td>
				<td><input name="ftp[address]" type="text" value="<?php echo $judger_config->getFTPAddress() ?>" /></td>
			</tr>
			<tr>
				<td>用户名</td>
				<td><input name="ftp[user]" type="text" value="<?php echo $judger_config->getFTPUser() ?>" /></td>
			</tr>
			<tr>
				<td>密码</td>
				<td><input name="ftp[password]" type="text" value="<?php echo $judger_config->getFTPPassword() ?>" /></td>
			</tr>
			<tr>
				<td>任务路径</td>
				<td><input name="ftp[path][task]" type="text" value="<?php echo $judger_config->getTaskPath('ftp') ?>" /></td>
			</tr>
			<tr>
				<td>测试数据路径</td>
				<td><input name="ftp[path][testdata]" type="text" value="<?php echo $judger_config->getTestdataPath('ftp') ?>" /></td>
			</tr>
		</table>
	</td>
</tr>
<tr id="config_share">
	<td>本地设置</td>
	<td>
		<table>
			<tr>
				<td>任务路径</td>
				<td><input name="share[path][task]" type="text" value="<?php echo $judger_config->getTaskPath('share') ?>" /></td>
			</tr>
			<tr>
				<td>测试数据路径</td>
				<td><input name="share[path][testdata]" type="text" value="<?php echo $judger_config->getTaskPath('share') ?>" /></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="<?php echo $this->action ?>" />
<input type="hidden" name="judger_id" value="<?php echo $judger->getID() ?>" />
<input type="submit" value="修改" />
</form>
<script type="text/javascript">//<![CDATA[
$(document).ready(function()
{
	hideAllUploadConfig();
	$('select[name="upload"]').change( function()
	{
		var upload = $(this).find('option:selected').val();
		showUploadConfig(upload);
	});
	def=$('select[name="upload"] option[value="<?php echo $judger_config->getUploadMethod() ?>"]');
	def.attr('selected','selected');
	showUploadConfig(def.val());
});

function hideAllUploadConfig()
{
	$('#config_ftp').hide(0);
	$('#config_share').hide(0);
}

function showUploadConfig(id)
{
	hideAllUploadConfig();
	$('#config_'+id).show(0);
}
//]]></script>

<?php $this->display('footer.php') ?>