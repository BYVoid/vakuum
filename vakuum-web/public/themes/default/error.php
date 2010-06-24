<?php $this->title='错误' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>错误</td>
		<td>描述</td>
	</tr>
<?php foreach($this->error as $field => $desc): ?>
	<tr>
		<td><?php echo $field ?></td>
		<td><?php echo $desc ?></td>
	</tr>
<?php endforeach ?>
</table>

<a id="link_goback" href="#">5秒后自动返回</a>

<script type="text/javascript">
$(document).ready(function(){
	function goback()
	{
		history.go(-1);
	}
	$("#link_goback").click(goback);
	window.setTimeout(goback, 5000);
});
</script>

<?php $this->display('footer.php') ?>