<?php $this->title='用户列表' ?>
<?php $this->display('header.php') ?>
<?php $user_list = $this->list ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>用户名</td>
		<td>用户昵称</td>
		<td>注册时间</td>
	</tr>
<?php foreach($user_list as $item): ?>
	<?php $user_id = $item['user_id']?>
	<?php $user_name = $item['user_name'] ?>
	<?php $user_nickname = $item['user_nickname'] ?>
	<?php $user_path = $this->locator->getURL('user_detail').'/'. $user_name ?>
	<?php $user_register_time = $item['register_time']?>
	<?php $user_register_time_text = $this->formatTime($user_register_time)?>
	<tr>
		<td><?php echo $user_id?></td>
		<td><a href="<?php echo $user_path?>"><?php echo $user_name ?></a></td>
		<td><?php echo $user_nickname ?></td>
		<td><?php echo $user_register_time_text?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>