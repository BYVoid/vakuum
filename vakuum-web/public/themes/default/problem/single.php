<?php
/**
 *
 * @var MDL_Problem
 */
$problem = $this->problem;

$prob_id = $problem->getID();
$prob_name = $problem->getName();
$prob_title = $problem->getTitle();
$prob_contents = $problem->getContents();
$prob_info = $problem->getInfo();
$input = $prob_info['data_config']['input'];
$output = $prob_info['data_config']['output'];
$checker = $prob_info['data_config']['checker'];
$time_limit = $prob_info['data_config']['time_limit'];
$memory_limit = $prob_info['data_config']['memory_limit'];
$output_limit = $prob_info['data_config']['output_limit'];
$case_count = count($prob_info['data_config']['case']);
$judge_source_length_max = MDL_Config::getInstance()->getVar('judge_source_length_max');
if ($time_limit == 0) $time_limit = MDL_Config::getInstance()->getVar('judge_default_time_limit');
if ($memory_limit == 0) $memory_limit = MDL_Config::getInstance()->getVar('judge_default_memory_limit');
if ($output_limit == 0) $output_limit = MDL_Config::getInstance()->getVar('judge_default_output_limit');
$checker=($checker['type']=='standard')?"标准检查器":"特殊检查器";
?>

<?php $this->title = $prob_title ?>
<?php $this->display('header.php') ?>
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
			<form action="<?php echo $this->submit_url ?>" method="post" enctype="multipart/form-data" >
				<input type="file" name="source"/>
				<select name="lang">
					<option value="cpp">C++</option>
					<option value="c">C</option>
					<option value="pas">Pascal</option>
				</select>
				<input type="submit" value="提交" />
				<input name="prob_id" type="hidden" value="<?php echo $prob_id ?>" />
				<input name="prob_name" type="hidden" value="<?php echo $prob_name ?>" />
			<?php if (isset($this->contest)): ?>
				<input name="contest_id" type="hidden" value="<?php echo $this->contest->getID() ?>" />
			<?php endif ?>
				<input name="MAX_FILE_SIZE" type="hidden" value="<?php echo $judge_source_length_max?>" />
			</form>
		</td>
	</tr>
</table>

<div id = "problem_content">
<?php echo $prob_contents ?>
</div>

<?php $this->display('footer.php') ?>