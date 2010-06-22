<?php $this->title=$this->problem['prob_title'] ?>
<?php $this->display('header.php') ?>

<?php $prob_id = $this->problem['prob_id'] ?>
<?php $prob_name = $this->problem['prob_name'] ?>
<?php $prob_title = $this->problem['prob_title'] ?>
<?php $prob_content = $this->problem['prob_content'] ?>

<?php $input = $this->problem['data_config']['input'] ?>
<?php $output = $this->problem['data_config']['output'] ?>
<?php $checker = $this->problem['data_config']['checker'] ?>
<?php $time_limit = $this->problem['data_config']['time_limit'] ?>
<?php $memory_limit = $this->problem['data_config']['memory_limit'] ?>
<?php $output_limit = $this->problem['data_config']['output_limit'] ?>
<?php $case_count = count($this->problem['data_config']['case']) ?>

<?php $judge_source_length_max = MDL_Config::getInstance()->getVar('judge_source_length_max') ?>
<?php if ($time_limit == 0) $time_limit = MDL_Config::getInstance()->getVar('judge_default_time_limit') ?>
<?php if ($memory_limit == 0) $memory_limit = MDL_Config::getInstance()->getVar('judge_default_memory_limit') ?>
<?php if ($output_limit == 0) $output_limit = MDL_Config::getInstance()->getVar('judge_default_output_limit') ?>
<?php $checker=($checker['type']=='standard')?"标准检查器":"特殊检查器" ?>

<table>
	<tr>
		<td><?php echo $this->escape($prob_title) ?></td>
	</tr>
	<tr>
		<td>
			<table border="1">
				<tr>
					<td>ID</td>
					<td><?php echo $prob_id ?></td>
				</tr>
				<tr>
					<td>名称</td>
					<td><?php echo $prob_name ?></td>
				</tr>
				<tr>
					<td>输入文件</td>
					<td><?php echo $this->escape($input) ?></td>
				</tr>
				<tr>
					<td>输出文件</td>
					<td><?php echo $this->escape($output) ?></td>
				</tr>
				<tr>
					<td>检查器</td>
					<td><?php echo $this->escape($checker) ?></td>
				</tr>
				<tr>
					<td>时间限制(ms)</td>
					<td><?php echo $this->escape($time_limit) ?></td>
				</tr>
				<tr>
					<td>内存限制(KB)</td>
					<td><?php echo $this->escape($memory_limit) ?></td>
				</tr>
				<tr>
					<td>输出限制(KB)</td>
					<td><?php echo $this->escape($output_limit) ?></td>
				</tr>
				<tr>
					<td>测试点数</td>
					<td><?php echo $this->escape($case_count) ?></td>
				</tr>

			</table>
		</td>
	</tr>
	<tr>
		<td>
			<form action="<?php echo $this->locator->getURL('dosubmit') ?>" method="post" enctype="multipart/form-data" >
				<input type="file" name="source"/>
				<select name="lang">
					<option value="cpp">C++</option>
					<option value="c">C</option>
					<option value="pas">Pascal</option>
				</select>
				<input type="submit" value="提交" />
				<input name="prob_id" type="hidden" value="<?php echo $prob_id ?>" />
				<input name="prob_name" type="hidden" value="<?php echo $prob_name ?>" />
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $judge_source_length_max?>" />
			</form>
		</td>
	</tr>
</table>

<div id = "problem_content">
<?php echo $prob_content ?>
</div>

<?php $this->display('footer.php') ?>