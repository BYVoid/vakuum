<?php $this->title='提交记录列表' ?>
<?php $this->display('header.php') ?>
<?php $record_list = $this->list ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>状态</td>
		<td>题目</td>
		<td>用户</td>
		<td>评测机</td>
		<td>提交时间</td>
	</tr>
<?php foreach($record_list as $item): ?>
	<?php $record_id = $item['record_id']?>
	<?php $record_path = $this->locator->getURL('record_detail').'/'. $record_id ?>
	
	<?php $prob_id = $item['prob_id']?>
	<?php $prob_name = $item['prob_name'] ?>
	<?php $prob_title = $item['prob_title'] ?>
	<?php $prob_path = $this->locator->getURL('problem_single').'/'. $prob_name ?>
	
	<?php $user_id = $item['user_id']?>
	<?php $user_name = $item['user_name'] ?>
	<?php $user_nickname = $item['user_nickname'] ?>
	<?php $user_path = $this->locator->getURL('user_detail').'/'. $user_name ?>
	
	<?php $judger_id = $item['judger_id']?>
	<?php $submit_time = $item['other']['submit_time'] ?>
	<?php $submit_time_text = $this->formatTime($submit_time)?>
	<?php $status_text = showStatus($item['other']['status'],$item['other']['result_text'])?>
	<tr>
		<td><?php echo $record_id?></td>
		<td><a href='<?php echo $record_path ?>'><?php echo $status_text ?></a></td>
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