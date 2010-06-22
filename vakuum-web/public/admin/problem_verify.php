<?php $this->title="数据验证" ?>
<?php $this->display('header.php') ?>
<?php $case_id = 0 ?>
<p><a href="<?php echo $this->locator->getURL('admin_problem_list')?>">返回题目管理</a></p>

<table>
	<tr>
		<td>
			<table border="1">
				<caption>基本信息</caption>
				<tr>
					<td>标题</td>
					<td><input type="text" size="30" value="<?php echo $this->escape($this->data_config['title']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>ID</td>
					<td><input type="text" name="id" size="30" value="<?php echo $this->data_config['id'] ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>名称</td>
					<td><input type="text" name="name" size="30" value="<?php echo $this->data_config['name'] ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>输入</td>
					<td><input type="text" name="input" size="30" value="<?php echo $this->escape($this->data_config['input']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>输出</td>
					<td><input type="text" name="output" size="30" value="<?php echo $this->escape($this->data_config['output']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>比较程序</td>
					<td>
						<table>
							<tr>
								<td>文件名</td>
								<td><input type="text" name="checker" size="20" value="<?php echo $this->escape($this->data_config['checker']['name']) ?>" readonly="readonly" /></td>
							</tr>
							<tr>
								<td>类型</td>
								<td><input type="text" name="checker" size="20" value="<?php echo $this->escape($this->data_config['checker']['type']) ?>" readonly="readonly" /></td>
							</tr>
							<?php if($this->data_config['checker']['type']=='custom'):?>
							<tr>
								<td>源文件</td>
								<td><input type="text" name="checker" size="20" value="<?php echo $this->escape($this->data_config['checker']['custom']['source']) ?>" readonly="readonly" /></td>
							</tr>
							<tr>
								<td>语言</td>
								<td><input type="text" name="checker" size="20" value="<?php echo $this->escape($this->data_config['checker']['custom']['language']) ?>" readonly="readonly" /></td>
							</tr>
							<?php endif ?>
						</table>
						
					</td>
				</tr>
				<tr>
					<td>附加访问文件</td>
					<td>
						<?php if (isset($this->data_config['additional_file'])): ?>
						<table>
							<?php foreach($this->data_config['additional_file'] as $item): ?>
							<tr><td><input type="text" name="additional_file[]" size="30" value="<?php echo $this->escape($item) ?>" readonly="readonly" /></td></tr>
							<?php endforeach; ?>
						</table>
						<?php endif ?>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table border="1">
				<caption>全局限制</caption>
				<tr>
					<td>时间限制(ms)</td>
					<td><input type="text" name="time_limit" size="30" value="<?php echo $this->escape($this->data_config['time_limit']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>内存限制(KB)</td>
					<td><input type="text" name="memory_limit" size="30" value="<?php echo $this->escape($this->data_config['memory_limit']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>输出限制(KB)</td>
					<td><input type="text" name="output_limit" size="30" value="<?php echo $this->escape($this->data_config['output_limit']) ?>" readonly="readonly" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<?php if ($this->verify_result['overall'] != 'testdata_path'): ?>
			<table border="1" id="testcases">
				<caption>测试数据</caption>
				<tr>
					<td>编号</td>
					<td>输入文件</td>
					<td>输出文件</td>
					<td>时间限制(ms)</td>
					<td>内存限制(KB)</td>
					<td>输出限制(KB)</td>
				</tr>
				<?php foreach($this->data_config['case'] as $item): ?>
				<?php if($item['time_limit']=='') $item['time_limit']=$this->data_config['time_limit'] ?>
				<?php if($item['memory_limit']=='') $item['memory_limit']=$this->data_config['memory_limit'] ?>
				<?php if($item['output_limit']=='') $item['output_limit']=$this->data_config['output_limit'] ?>
				<?php $input_verify = $this->verify_result['case'][$case_id]['input']?"[Yes]":"[No]" ?>
				<?php $output_verify = $this->verify_result['case'][$case_id]['output']?"[Yes]":"[No]" ?>
				<tr>
					<td><?php echo $case_id++ ?></td>
					<td><?php echo $this->escape($item['input']).$input_verify ?></td>
					<td><?php echo $this->escape($item['output']).$output_verify ?></td>
					<td><?php echo $this->escape($item['time_limit']) ?></td>
					<td><?php echo $this->escape($item['memory_limit']) ?></td>
					<td><?php echo $this->escape($item['output_limit']) ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		<?php endif?>
		</td>
	</tr>
</table>

<?php if($this->verify_result['overall'] == 'testdata_path'): ?>
<p>请将测试数据和自定义比较程序(如果有)放在本服务器<?php echo $this->escape($this->verify_result['testdata_path'])?>目录下，然后重新验证。路径和文件名大小写敏感。</p>
<?php elseif ($this->verify_result['overall'] == 'case'): ?>
<p>请检查测试数据文件是否与设置一致，文件名大小写敏感。</p>
<?php elseif ($this->verify_result['overall'] == 'checker'): ?>
<p>请检查自定义检查器<?php echo $this->escape($this->data_config['checker']['custom']['source']) ?>是否存在，文件名大小写敏感。</p>
<?php else:?>
<p>已通过验证，请进入下一步。</p>
<form action="<?php echo $this->locator->getURL('admin_problem_dispatch') ?>/<?php echo $this->data_config['id'] ?>" method="get">
<input type="submit" value="下一步" /> 
</form>
<?php endif ?>

<?php $this->display('footer.php') ?>