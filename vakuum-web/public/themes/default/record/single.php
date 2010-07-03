<?php
$compile_explaination = array
(
	0 => '编译指令错误',
	1 => '编译器被终止',
	2 => '源文件不存在',
	3 => '编译错误',
);
$result_explaination = array
(
	0 => '正常运行结束',
	1 => '运行时错误',
	2 => '超过时间限制',
	3 => '超过内存空间限制',
	4 => '超过输出空间限制',
	5 => '禁止系统调用',
	6 => '执行失败',
);

$record_id = $this->record->getID();

$problem = $this->record->getProblem();
$prob_id = $problem->getID();
$prob_name = $problem->getName();
$prob_title = $problem->getTitle();
$prob_path = $this->locator->getURL('problem').'/'.$prob_id;

$user = $this->record->getUser();
$user_id = $user->getID();
$user_name = $user->getName();
$user_nickname = $user->getNickname();
$user_path = $this->locator->getURL('user/detail').'/'.$user_name;

$record_source = $this->locator->getURL('record/source').'/'. $record_id;
$record_info = $this->record->getInfo();
$language = $record_info->language;
$source_length = $record_info->source_length;
$submit_time = $this->formatTime($record_info->submit_time);
?>

<?php $this->title='提交记录 #'.$record_id ?>
<?php $this->display('header.php') ?>

<?php
if ($this->record->canView('result'))
{
	$status_text = showStatus($record_info->status,$record_info->result_text);
	$score = $record_info->score;
	$time_used = $record_info->time;
	$memory_used = $record_info->memory;
}

$record_result = $record_info->getResult();

if ($this->record->canView('compile'))
{
	$compile_result = $record_result->getCompile();
	$compiled = $compile_result != false;
	if ($compile_result)
	{
		if (isset($compile_result['result']))
		{
			if ($compile_result['result'] == 0)
				$compile_result_message = '编译成功';
			else
				$compile_result_message = '编译失败 '.$compile_explaination[$compile_result['option']];
			$compiler_message = $compile_result['compiler_message'];
		}
	}
	else
		$compile_result_message ='等待编译';
}

if ($this->record->canView('case'))
{
	$execute_result = $record_result->getExecute();
	if (!$execute_result)
		$execute_result = array();
}
?>


<table border="1">
	<tr>
		<td>记录编号</td>
		<td><?php echo $record_id ?> <a href='<?php echo $record_source ?>'>查看代码</a></td>
	</tr>
<?php if ($this->record->canView('information')): ?>
	<tr>
		<td>题目</td>
		<td><a href='<?php echo $prob_path ?>'><?php echo $prob_title ?></a></td>
	</tr>
	<tr>
		<td>用户</td>
		<td><a href='<?php echo $user_path ?>'><?php echo $user_nickname ?></a></td>
	</tr>
	<tr>
		<td>提交时间</td>
		<td><?php echo $submit_time ?></td>
	</tr>
	<tr>
		<td>语言</td>
		<td><?php echo $language ?></td>
	</tr>
	<tr>
		<td>代码长度</td>
		<td><?php echo $source_length ?> 字节</td>
	</tr>
<?php endif ?>
<?php if ($this->record->canView('result')): ?>
	<tr>
		<td>状态</td>
		<td><?php echo $status_text ?></td>
	</tr>
	<tr>
		<td>通过比例</td>
		<td><?php echo (int) ($score * 100) ?>%</td>
	</tr>
	<tr>
		<td>时间使用</td>
		<td><?php echo $time_used ?> 毫秒</td>
	</tr>
	<tr>
		<td>内存使用</td>
		<td><?php echo $memory_used ?> 千字节</td>
	</tr>
<?php endif ?>
<?php if ($this->record->canView('compile')): ?>
	<tr>
		<td>编译结果</td>
		<td>
			<table border="0">
				<tr><td><?php echo $compile_result_message ?></td></tr>
				<?php if ($compiled) :?>
				<tr><td><textarea rows="6" cols="60"><?php echo $this->escape($compiler_message) ?></textarea></td></tr>
				<?php endif ?>
			</table>
		</td>
	</tr>
<?php endif?>
<?php if ($this->record->canView('case')): ?>
	<tr>
		<td>运行结果</td>
		<td>
			<table border="1">
				<tr>
					<td>ID</td>
					<td>结果</td>
					<td>备注</td>
					<td>时间(ms)</td>
					<td>空间(kB)</td>
					<td>得分</td>
					<td>信息</td>
				</tr>
			<?php foreach ($execute_result as $item): ?>
				<tr>
					<td><?php echo $item['case_id'] ?></td>
					<td><?php echo $result_explaination[$item['result']] ?></td>
					<td><?php echo $item['option'] ?></td>
					<td><?php echo $item['time_used'] ?></td>
					<td><?php echo $item['memory_used'] ?></td>
					<td><?php echo $item['score'] ?></td>
					<td><?php echo $item['check_message'] ?></td>
				</tr>
			<?php endforeach ?>
			</table>
		</td>
	</tr>
<?php endif ?>
</table>

<?php $this->display('footer.php') ?>