<?php
/**
 *
 * @var array(MDL_USER)
 */
$user_list = $this->list;
unset($this->list);
?>
<?php $this->title='用户列表' ?>
<?php $this->display('header.php') ?>

<p><a href="<?php echo $this->locator->getURL('admin_user_edit') ?>">添加</a></p>

<table border="1">
	<tr>
		<td>用户ID</td>
		<td>用户名</td>
		<td>用户昵称</td>
		<td>操作</td>
	</tr>
<?php foreach($user_list as $user): ?>
	<?php $user_id = $user->getID() ?>
	<?php $user_name = $user->getName() ?>
	<?php $user_nickname = $user->getNickname() ?>
	<?php $user_edit = $this->locator->getURL('admin_user_edit').'/'.$user_id ?>
	<tr>
		<td><?php echo $user_id?></td>
		<td><?php echo $user_name?></td>
		<td><?php echo $user_nickname ?></td>
		<td>
			<a href="<?php echo $user_edit?>">编辑资料</a>
		</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>