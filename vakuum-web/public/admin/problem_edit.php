<?php $this->title="修改题目" ?>
<?php NicEdit::prepare() ?>
<?php $this->display('header.php') ?>

<p><a href="<?php echo $this->locator->getURL('admin_problem_list')?>">返回题目管理</a></p>

<form action="<?php echo $this->locator->getURL('admin_problem_doedit') ?>" method="post">
<table border="1">
	<tr>
		<td>标题</td>
		<td><input type="text" name="prob_title" size="30" value="<?php echo $this->escape($this->problem['prob_title']) ?>" /></td>
	</tr>
	<tr>
		<td>ID</td>
		<td><input type="text" name="prob_id" size="30" value="<?php echo $this->escape($this->problem['prob_id']) ?>" <?php if ($this->problem['action']=='edit'): ?> readonly="readonly" <?php endif ?> /></td>
	</tr>
	<tr>
		<td>名称</td>
		<td><input type="text" name="prob_name" size="30" value="<?php echo $this->escape($this->problem['prob_name']) ?>" /></td>
	</tr>
	<tr>
		<td>显示</td>
		<td><?php echo $this->showCheckbox($this->problem['display'],'display') ?></td>
	</tr>
<?php if ($this->problem['action']=='edit'): ?>
	<tr>
		<td>永久删除</td>
		<td><input type="checkbox" name="remove" value="1"/></td>
	</tr>
<?php endif; ?>
	<tr>
		<td>正文</td>
		<td>
		<?php NicEdit::show("text_content",true,"可视化模式","HTML模式",true)?>
		<textarea name="prob_content" id="text_content" cols="80" rows="20" ><?php echo $this->escape($this->problem['prob_content']) ?></textarea>
		</td>
	</tr>
</table>
<input type="hidden" name="action" value="<?php echo $this->problem['action'] ?>" />
<input type="hidden" name="data" value="problem" />
<input type="submit" value="提交" />
</form>
<?php $this->display('footer.php') ?>