<?php $this->title='评测机列表' ?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td>priority</td>
		<td>enabled</td>
		<td>available</td>
		<td>count</td>
		<td>config</td>
	</tr>
<?php foreach($this->list as $item): ?>
	<tr>
		<td><?php echo $item['judger_id']?></td>
		<td><?php echo $this->escape($item['judger_priority'])?></td>
		<td><?php echo $this->escape($item['judger_enabled'])?></td>
		<td><?php echo $this->escape($item['judger_available'])?></td>
		<td><?php echo $this->escape($item['judger_count'])?></td>
		<td><?php var_dump($item['judger_config'])?></td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>