<?php $this->title='比赛列表' ?>
<?php $this->display('header.php') ?>
<?php $contest_list = $this->list ?>

<?php foreach($contest_list as $contest): ?>
	<?php $contest_id = $contest->getID() ?>
	<?php $contest_config = $contest->getConfig() ?>
	<?php $contest_name = $contest_config->getName() ?>
	<?php $contest_desc = $contest_config->getDesc() ?>
	<?php $contest_path = $this->locator->getURL('contest/entry').'/'. $contest_id ?>
	<?php $contest_signup_path = $this->locator->getURL('contest/signup').'/'. $contest_id ?>
	<table border="1">
	<tr>
		<td>ID</td>
		<td><?php echo $contest_id?></td>
	</tr>
	<tr>
		<td>比赛名</td>
		<td><?php echo $this->escape($contest_name) ?></td>
	</tr>
	<tr>
		<td>比赛描述</td>
		<td><?php echo $this->escape($contest_desc) ?></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="<?php echo $contest_signup_path ?>">报名参赛</a></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="<?php echo $contest_path ?>">进入比赛</a></td>
	</tr>
	</table>
<?php endforeach?>

<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>