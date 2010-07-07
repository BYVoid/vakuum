<?php $this->title="数据分发" ?>
<?php $this->display('header.php') ?>
<?php $current_version = $this->data_config['version'] ?>
<?php $current_hash_code = $this->data_config['hash_code'] ?>
<p><a href="<?php echo $this->locator->getURL('admin_problem_list')?>">返回题目管理</a></p>

<p>当前版本 <?php echo $current_version ?></p>
<p>当前Hash <?php echo $current_hash_code ?></p>

<form>
<table border="1">
	<tr>
		<td>ID</td>
		<td>地址</td>
		<td>传输方式</td>
		<td>验证成功</td>
		<td>分发</td>
	</tr>
	<?php foreach($this->judgers as $judger):?>
	<?php $verify_result = $current_version==$judger->testdataVersion['version']&&$current_hash_code==$judger->testdataVersion['hash_code'] ?>
	<tr>
		<td><?php echo $judger->getID() ?></td>
		<td><?php echo $this->escape($judger->getConfig()->getRemoteURL())?></td>
		<td><?php echo $this->escape($judger->getConfig()->getUploadMethod())?></td>
		<td id="verify_result_<?php echo $judger->getID() ?>"><?php echo $verify_result?"Yes":"No" ?></td>
		<td><input value="<?php echo $judger->getID() ?>" type="checkbox" <?php if(!$verify_result): ?>checked="checked"<?php endif ?> /></td>
	</tr>
	<?php endforeach ?>
</table>
</form>

<input id="btnExecute" type="button" value="执行" />
<div id="divResult">
</div>
<script>
$(document).ready(function()
{
	$('#btnExecute').click
	(
		function(event)
		{
			$('#btnExecute').attr('disabled','disabled');
			$("#divResult").html('');
			$(':checked').each(function()
			{
				judger_id = $(this).val();
				$.ajaxQueue.add
				({
					url: '<?php echo $this->locator->getURL('admin_problem_dodispatch')?>',
					type: 'post',
					judger_id: judger_id,
					data:
					{
						judger_id: judger_id,
						prob_id: '<?php echo $this->data_config['id'] ?>',
						ajax: 1
					},
					beforeSend: function()
					{
						$("#divResult").append('<p>正在向评测机 #' + this.judger_id + ' 传送数据...</p>');
					},
					success: function(xml)
					{
						result = $(xml).find('overall').html();
						if (result == '')
						{
							responseText = "成功";
							$("#verify_result_"+this.judger_id).html('Yes');
						}
						else if (result == 'verify')
							responseText = "无法通过验证";
						else if (result == 'checker_compile')
							responseText = "自定义检查器无法编译";
						else
							responseText = "未知错误 "+result;
						$("#divResult").append('<p>'+responseText+'</p>');
					}
				});
			});
			$.ajaxQueue.process
			(
				{
					complete: function (textStatus)
					{
						$('#btnExecute').removeAttr('disabled');
					}
				}
			);

		}
	);
});
</script>

<?php $this->display('footer.php') ?>