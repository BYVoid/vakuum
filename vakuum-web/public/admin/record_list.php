<?php $this->title='提交记录列表' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>状态</td>
		<td>题目</td>
		<td>用户</td>
		<td>评测机</td>
		<td>提交时间</td>
		<td>操作</td>
	</tr>
<?php foreach($this->list->getList() as $record): ?>
	<?php $record_id = $record->getID() ?>
	<?php $record_path = $this->locator->getURL('record/detail').'/'. $record_id ?>

	<?php $prob_id = $record->getProblem()->getID() ?>
	<?php $prob_name = $record->getProblem()->getName() ?>
	<?php $prob_title = $record->getProblem()->getTitle() ?>
	<?php $prob_path = $record->getProblem()->getURL() ?>

	<?php $user_id = $record->getUser()->getID() ?>
	<?php $user_name = $record->getUser()->getName() ?>
	<?php $user_nickname = $record->getUser()->getNickName() ?>
	<?php $user_path = $record->getUser()->getURL() ?>

	<?php $judger_name = $record->getJudger()->getName() ?>
	<?php $submit_time = $record->getSubmitTime() ?>
	<?php $submit_time_text = $this->formatTime($submit_time)?>

	<?php $status_text = showStatus($record->getInfo()->status,$record->getInfo()->result_text)?>

	<?php $record_delete = $this->locator->getURL('admin_record_delete').'/'. $record_id ?>
	<?php $record_rejudge = $this->locator->getURL('admin_record_rejudge').'/record_id/'. $record_id ?>
	<?php $record_stopjudge = $this->locator->getURL('admin_record_stopjudge').'/'. $record_id ?>

	<tr>
		<td><?php echo $record_id?></td>
		<td><a href='<?php echo $record_path ?>' target="_blank"><?php echo $status_text ?></a></td>
		<td><a href='<?php echo $prob_path ?>' target="_blank"><?php echo $prob_title ?></a></td>
		<td><a href='<?php echo $user_path ?>' target="_blank"><?php echo $user_nickname ?></a></td>
		<td><?php echo $judger_name ?></td>
		<td><?php echo $submit_time_text ?></td>
		<td>
			<a href="<?php echo $record_delete ?>">永久删除</a>
			<a href="<?php echo $record_rejudge ?>" target="_blank">重新评测</a>
			<a href="<?php echo $record_stopjudge ?>" >停止评测</a>
		</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->list->getPageCount(), $this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>
