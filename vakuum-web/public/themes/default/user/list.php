<?php $this->title='用户列表' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>用户名</td>
		<td>用户昵称</td>
	</tr>
<?php foreach($this->list->getList() as $user ): ?>
	<?php $user_id = $user->getID() ?>
	<?php $user_name = $user->getName() ?>
	<?php $user_nickname = $user->getNickname() ?>
	<?php $user_path = $this->locator->getURL('user/detail').'/'. $user_name ?>
	<tr>
		<td><?php echo $user_id?></td>
		<td><a href="<?php echo $user_path?>"><?php echo $user_name ?></a></td>
		<td><?php echo $user_nickname ?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->list->getPageCount(),$this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>