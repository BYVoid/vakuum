<?php $this->title='提交记录列表' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>结果</td>
		<td>题目</td>
		<td>用户</td>
		<td>评测机</td>
		<td>提交时间</td>
	</tr>
<?php foreach($this->list->getList() as $record): ?>
	<?php $prob_id = $record->getProblem()->getID() ?>
	<?php $prob_name = $record->getProblem()->getName() ?>
	<?php $prob_title = $record->getProblem()->getTitle() ?>
	<?php $prob_path = $record->getProblem()->getURL() ?>

	<?php $user_id = $record->getUser()->getID() ?>
	<?php $user_name = $record->getUser()->getName() ?>
	<?php $user_nickname = $record->getUser()->getNickName() ?>
	<?php $user_path = $record->getUser()->getURL() ?>

	<?php $judger_name = $record->getJudger()->getName() ?>
	<?php $submit_time_text = $this->formatTime($record->getSubmitTime())?>

	<?php
	$status_text = '隱藏';
	if ($record->canView('result'))
		$status_text = showStatus($record->getStatus(),$record->getResultText());
	?>
	<tr>
		<td><?php echo $record->getID() ?></td>
		<td><a href='<?php echo $record->getURL() ?>'><?php echo $status_text ?></a></td>
		<td><a href='<?php echo $prob_path ?>'><?php echo $prob_title ?></a></td>
		<td><a href='<?php echo $user_path ?>'><?php echo $user_nickname ?></a></td>
		<td><?php echo $judger_name ?></td>
		<td><?php echo $submit_time_text ?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->list->getPageCount(),$this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>