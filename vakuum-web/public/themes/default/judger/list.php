<?php $this->title='评测机列表' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>名称</td>
		<td>可用</td>
		<td>计数</td>
	</tr>
<?php foreach($this->list->getList() as $judger): ?>
	<tr>
		<td><?php echo $judger->getID() ?></td>
		<td><?php echo $judger->getName() ?></td>
		<td><?php echo showBool($judger->isEnabled()) ?></td>
		<td><?php echo $judger->getJudgeCount() ?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->list->getPageCount(), $this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>