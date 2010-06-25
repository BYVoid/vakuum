<?php

function showBool($value)
{
	return $value ? '是' : '否';
}

function showStatus($status,$result_text)
{
	if ($status == MDL_Judge_Record::STATUS_WAITING)
		return '正在等待';
	if ($status == MDL_Judge_Record::STATUS_PENDING)
		return '正在分配';  
	if ($status == MDL_Judge_Record::STATUS_RUNNING)
	{
		return '正在运行 #'. $result_text;
	}
	if ($status == MDL_Judge_Record::STATUS_STOPPED)
	{
		$complete = array
		(
			MDL_Judge_Record::RESULT_ACCEPTED => '通过',
			MDL_Judge_Record::RESULT_COMILATION_ERROR => '编译失败',
			MDL_Judge_Record::RESULT_WRONG_ANSWER => '答案错误',
			MDL_Judge_Record::RESULT_RUNTIME_ERROR => '运行时错误',
			MDL_Judge_Record::RESULT_TIME_LIMIT_EXCEED => '超过时间限制',
			MDL_Judge_Record::RESULT_MEMORY_LIMIT_EXCEED => '超过内存空间限制',
			MDL_Judge_Record::RESULT_OUTPUT_LIMIT_EXCEED => '超过输出空间限制',
			MDL_Judge_Record::RESULT_SYSCALL_RESTRICTED => '禁止系统调用',
			MDL_Judge_Record::RESULT_EXECUTOR_ERROR => '执行失败',
		);
		return $complete[$result_text];
	}
	return 'Unknown';
}