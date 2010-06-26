<?php $this->title='提交记录列表' ?>
<?php $this->display('header.php') ?>
<?php $record_list = $this->list ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>题目</td>
		<td>用户</td>
		<td>评测机</td>
		<td>提交时间</td>
	</tr>
<?php foreach($record_list as $record): ?>
	<?php $record_id = $record->getID() ?>
	<?php $record_path = $this->locator->getURL('record/detail').'/'. $record_id ?>

	<?php $prob_id = $record->getProblemID() ?>
	<?php $prob_name = $record->getProblem()->getName() ?>
	<?php $prob_title = $record->getProblem()->getTitle() ?>
	<?php $prob_path = $this->locator->getURL('problem/single').'/'. $prob_name ?>

	<?php $user_id = $record->getUserID() ?>
	<?php $user_name = $record->getUser()->getName() ?>
	<?php $user_nickname = $record->getUser()->getNickName() ?>
	<?php $user_path = $this->locator->getURL('user/detail').'/'. $user_name ?>

	<?php $judger_id = $record->getJudgerID() ?>
	<?php $submit_time = $record->getInfo()->submit_time ?>
	<?php $submit_time_text = $this->formatTime($submit_time)?>

	<?php
	$display = $record->getInfo()->getDisplay();
	if ($display->showRunResult())
	{
		$status_text = showStatus($record->getInfo()->status,$record->getInfo()->result_text);
	}
	?>
	<tr>
		<td><a href='<?php echo $record_path ?>'><?php echo $record_id ?></a></td>
		<td><a href='<?php echo $prob_path ?>'><?php echo $prob_title ?></a></td>
		<td><a href='<?php echo $user_path ?>'><?php echo $user_nickname ?></a></td>
		<td><?php echo $judger_id ?></td>
		<td><?php echo $submit_time_text ?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>