<?php $this->title='题目列表' ?>
<?php $this->display('header.php') ?>
<?php $problem_list = $this->list ?>

<p><a href="<?php echo $this->locator->getURL('admin_problem_edit') ?>">添加</a></p>

<table border="1">
	<tr>
		<td>ID</td>
		<td>名称</td>
		<td>标题</td>
		<td>操作</td>
	</tr>
<?php foreach($problem_list as $item): ?>
	<?php $prob_id = $item['prob_id']?>
	<?php $prob_name = $item['prob_name']?>
	<?php $prob_edit = $this->locator->getURL('admin_problem_edit').'/'.$prob_id ?>
	<?php $prob_data = $this->locator->getURL('admin_problem_data').'/'.$prob_id ?>
	<?php $prob_verify = $this->locator->getURL('admin_problem_verify').'/'.$prob_id ?>
	<?php $prob_dispatch = $this->locator->getURL('admin_problem_dispatch').'/'.$prob_id ?>
	<?php $prob_rejudge = $this->locator->getURL('admin_record_rejudge').'/prob_id/'. $prob_id ?>
	<?php $prob_title = $item['prob_title']?>
	<?php $prob_adding_time = $item['adding_time']?>
	<?php $prob_adding_time_text = $this->formatTime($prob_adding_time)?>
	<tr>
		<td><?php echo $prob_id?></td>
		<td><?php echo $prob_name?></td>
		<td><?php echo $prob_title ?></td>
		<td>
			<a href="<?php echo $prob_edit?>">编辑题目</a>
			<a href="<?php echo $prob_data?>">数据设置</a>
			<a href="<?php echo $prob_verify?>">数据验证</a>
			<a href="<?php echo $prob_dispatch?>">数据分发</a>
			<a href="<?php echo $prob_rejudge?>">重新评测所有记录</a>
		</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>