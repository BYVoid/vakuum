<?php $this->title='题目列表' ?>
<?php $this->display('header.php') ?>
<?php $problem_list = $this->list ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>Name</td>
		<td>Title</td>
		<td>Time</td>
	</tr>
<?php foreach($problem_list as $item): ?>
	<?php $prob_id = $item['prob_id']?>
	<?php $prob_name = $item['prob_name']?>
	<?php $prob_path = $this->locator->getURL('problem_single').'/'.$prob_name ?>
	<?php $prob_title = $item['prob_title']?>
	<?php $prob_adding_time = $item['adding_time']?>
	<?php $prob_adding_time_text = $this->formatTime($prob_adding_time)?>
	<tr>
		<td><?php echo $prob_id?></td>
		<td><a href="<?php echo $prob_path?>"><?php echo $prob_name?></a></td>
		<td><?php echo $prob_title ?></td>
		<td><?php echo $prob_adding_time_text?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>