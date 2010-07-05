<?php $this->title='用户列表' ?>
<?php $this->display('header.php') ?>
<?php $path_option = BFL_PathOption::getInstance() ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>用户名</td>
		<td>用户昵称</td>
		<td><a href="<?php echo $path_option->getURL(array('by'=>'ac','order'=>getOrderOp())) ?>">通过数量</a></td>
		<td>通过率</td>
	</tr>
<?php foreach($this->list->getList() as $user ): ?>
	<?php $user_id = $user->getID() ?>
	<?php $user_name = $user->getName() ?>
	<?php $user_nickname = $user->getNickname() ?>
	<tr>
		<td><?php echo $user_id?></td>
		<td><a href="<?php echo $user->getURL()?>"><?php echo $user_name ?></a></td>
		<td><?php echo $user_nickname ?></td>
		<td><?php echo $user->getRecord()->getAcceptedProblemsCount() ?></td>
		<td><?php printf('%.2lf', $user->getRecord()->getAcceptedRate() * 100) ?>%</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->list->getPageCount(),$this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>