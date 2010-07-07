<?php $this->title='评测机列表' ?>
<?php $this->display('header.php') ?>

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
<?php foreach($this->list->getList() as $judger): ?>
	<?php $judger_id = $judger->getID() ?>

	<?php $judger_edit = $this->locator->getURL('admin/judger/edit').'/'.$judger_id ?>
	<?php $judger_connect = $this->locator->getURL('admin/judger/connect').'/'.$judger_id ?>
	<?php $judger_force_available = $this->locator->getURL('admin/judger/force').'/'.$judger_id ?>

	<tr>
		<td><?php echo $judger->getID() ?></td>
		<td><?php echo $judger->getName() ?></td>
		<td><?php echo $judger->getPriority() ?></td>
		<td><?php echo $judger->isEnabled() ?></td>
		<td><?php echo $judger->isAvailable() ?></td>
		<td><?php echo $judger->getJudgeCount() ?></td>
		<td><?php echo $judger->getConfig()->getRemoteURL() ?></td>
		<td><?php echo $judger->getConfig()->getUploadMethod() ?></td>
		<td>
			<a href="<?php echo $judger_edit?>">修改配置</a>
			<a href="<?php echo $judger_connect?>">测试连接</a>
			<a href="<?php echo $judger_force_available?>">强制启动</a>
		</td>
	</tr>
<?php endforeach?>
</table>
<div style="padding-top: 1em">
<?php echo list_navigation::show($this->list->getPageCount(), $this->list->getCurrentPage()) ?>
</div>

<?php $this->display('footer.php') ?>