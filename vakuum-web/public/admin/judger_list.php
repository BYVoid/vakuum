<?php $this->title='评测机列表' ?>
<?php $this->display('header.php') ?>
<?php $judger_list = $this->list ?>

<p><a href="<?php echo $this->locator->getURL('admin_judger_edit') ?>">添加</a></p>

<table border="1">
	<tr>
		<td>ID</td>
		<td>名称</td>
		<td>优先级</td>
		<td>可用性</td>
		<td>空闲</td>
		<td>计数</td>
		<td>地址</td>
		<td>传输模式</td>
		<td>操作</td>
	</tr>
<?php foreach($judger_list as $item): ?>
	<?php $judger_id = $item['judger_id']?>
	<?php $judger_name = $item['judger_name']?>
	<?php $judger_priority = $item['judger_priority']?>
	<?php $judger_enabled = $item['judger_enabled']?>
	<?php $judger_available = $item['judger_available']?>
	<?php $judger_count = $item['judger_count']?>
	<?php $judger_url = $item['judger_config']['url']?>
	<?php $judger_upload = $item['judger_config']['upload']?>
	<?php $judger_edit = $this->locator->getURL('admin_judger_edit').'/'.$judger_id ?>
	<?php $judger_connect = $this->locator->getURL('admin_judger_connect').'/'.$judger_id ?>
	<?php $judger_force_available = $this->locator->getURL('admin_judger_force').'/'.$judger_id ?>
	<tr>
		<td><?php echo $judger_id?></td>
		<td><?php echo $judger_name?></td>
		<td><?php echo $judger_priority ?></td>
		<td><?php echo $judger_enabled ?></td>
		<td><?php echo $judger_available ?></td>
		<td><?php echo $judger_count ?></td>
		<td><?php echo $judger_url ?></td>
		<td><?php echo $judger_upload ?></td>
		<td>
			<a href="<?php echo $judger_edit?>">修改配置</a>
			<a href="<?php echo $judger_connect?>">测试连接</a>
			<a href="<?php echo $judger_force_available?>">强制启动</a>
		</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->info['page_count'],$this->info['current_page']) ?>
</div>

<?php $this->display('footer.php') ?>