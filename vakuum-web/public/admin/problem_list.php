<?php $this->title='题目列表' ?>
<?php $this->display('header.php') ?>

<p><a href="<?php echo $this->locator->getURL('admin_problem_edit') ?>">添加</a></p>

<table border="1">
	<tr>
		<td>ID</td>
		<td>名称</td>
		<td>标题</td>
		<td>操作</td>
	</tr>
<?php foreach($this->list->getList() as $problem): ?>
	<?php $prob_id = $problem->getID() ?>
	<?php $prob_name = $problem->getName() ?>
	<?php $prob_path = $this->locator->getURL('problem').'/'.$prob_name ?>
	<?php $prob_title = $problem->getTitle() ?>
	<?php $prob_edit = $this->locator->getURL('admin_problem_edit').'/'.$prob_id ?>
	<?php $prob_data = $this->locator->getURL('admin_problem_data').'/'.$prob_id ?>
	<?php $prob_verify = $this->locator->getURL('admin_problem_verify').'/'.$prob_id ?>
	<?php $prob_dispatch = $this->locator->getURL('admin_problem_dispatch').'/'.$prob_id ?>
	<?php $prob_rejudge = $this->locator->getURL('admin_record_rejudge').'/prob_id/'. $prob_id ?>
	<tr>
		<td><?php echo $prob_id?></td>
		<td><?php echo $prob_name?></td>
		<td><a href="<?php echo $prob_path ?>"><?php echo $prob_title ?></a></td>
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
<?php echo list_navigation::show($this->list->getPageCount(), $this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>
