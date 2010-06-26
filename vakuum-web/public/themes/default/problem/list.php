<?php
/**
 *
 * @var array(MDL_Problem)
 */
$problem_list = $this->list;
?>

<?php $this->title='题目列表' ?>
<?php $this->display('header.php') ?>
<?php  ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>Name</td>
		<td>Title</td>
	</tr>
<?php foreach($problem_list as $problem): ?>
	<?php $prob_id = $problem->getID() ?>
	<?php $prob_name = $problem->getName() ?>
	<?php $prob_path = $this->locator->getURL('problem').'/'.$prob_name ?>
	<?php $prob_title = $problem->getTitle() ?>
	<tr>
		<td><?php echo $prob_id?></td>
		<td><a href="<?php echo $prob_path?>"><?php echo $prob_name?></a></td>
		<td><?php echo $prob_title ?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>