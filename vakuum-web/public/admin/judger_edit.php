<?php $this->title = '修改评测机' ?>
<?php $this->display('header.php') ?>
<?php $judger = $this->judger?>

<form action="<?php echo $this->locator->getURL('admin_judger_doedit') ?>" method="post" >
<table border="1">
<?php if ($judger['judger_id'] != 0): ?>
<tr>
	<td>ID</td>
	<td><?php echo $judger['judger_id'] ?></td>
</tr>
<tr>
	<td>永久删除</td>
	<td><input name="remove" type="checkbox" value="1" /></td>
</tr>
<?php endif ?>
<tr>
	<td>名称</td>
	<td><input name="judger_name" type="text" value="<?php echo $judger['judger_name'] ?>" maxlength="32" /></td>
</tr>
<tr>
	<td>优先级</td>
	<td><input name="judger_priority" type="text" value="<?php echo $judger['judger_priority'] ?>" /></td>
</tr>
<tr>
	<td>可用</td>
	<td><input name="judger_enabled" type="checkbox" value="1" <?php if ($judger['judger_enabled']): ?>checked="checked" <?php endif; ?> /></td>
</tr>
<tr>
	<td>URL</td>
	<td><input name="url" type="text" value="<?php echo $judger['judger_config']['url'] ?>" /></td>
</tr>
<tr>
	<td>密钥</td>
	<td><input name="public_key" type="text" value="<?php echo $judger['judger_config']['public_key'] ?>" /></td>
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
<tr id="config_ftp">
	<td>FTP设置</td>
	<td>
		<?php $ftp = $judger['judger_config']['ftp']?>
		<table>
			<tr>
				<td>地址</td>
				<td><input name="ftp[address]" type="text" value="<?php echo $ftp['address'] ?>" /></td>
			</tr>
			<tr>
				<td>用户名</td>
				<td><input name="ftp[user]" type="text" value="<?php echo $ftp['user'] ?>" /></td>
			</tr>
			<tr>
				<td>密码</td>
				<td><input name="ftp[password]" type="text" value="<?php echo $ftp['password'] ?>" /></td>
			</tr>
			<tr>
				<td>任务路径</td>
				<td><input name="ftp[path][task]" type="text" value="<?php echo $ftp['path']['task'] ?>" /></td>
			</tr>
			<tr>
				<td>测试数据路径</td>
				<td><input name="ftp[path][testdata]" type="text" value="<?php echo $ftp['path']['testdata'] ?>" /></td>
			</tr>
		</table>
	</td>
</tr>
<tr id="config_share">
	<td>本地设置</td>
	<td>
		<?php $share = $judger['judger_config']['share']?>
		<table>
			<tr>
				<td>任务路径</td>
				<td><input name="share[path][task]" type="text" value="<?php echo $share['path']['task'] ?>" /></td>
			</tr>
			<tr>
				<td>测试数据路径</td>
				<td><input name="share[path][testdata]" type="text" value="<?php echo $share['path']['testdata'] ?>" /></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="<?php echo $judger['action'] ?>" />
<input type="hidden" name="judger_id" value="<?php echo $judger['judger_id'] ?>" />
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
	def=$('select[name="upload"] option[value="<?php echo $judger['judger_config']['upload'] ?>"]');
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