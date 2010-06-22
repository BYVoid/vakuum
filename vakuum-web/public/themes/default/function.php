<?php
function showStatus($status,$result_text)
{
	if ($status == MDL_Judge_Record::STATUS_WAITING)
		return 'Waiting';
	if ($status == MDL_Judge_Record::STATUS_PENDING)
		return 'Pending';  
	if ($status == MDL_Judge_Record::STATUS_RUNNING)
	{
		return 'Running #'. $result_text;
	}
	if ($status == MDL_Judge_Record::STATUS_STOPPED)
	{
		$complete = array
		(
			MDL_Judge_Record::RESULT_ACCEPTED => 'Accepted',
			MDL_Judge_Record::RESULT_COMILATION_ERROR => 'Compilation Error',
			MDL_Judge_Record::RESULT_WRONG_ANSWER => 'Wrong Answer',
			MDL_Judge_Record::RESULT_RUNTIME_ERROR => 'Runtime Error',
			MDL_Judge_Record::RESULT_TIME_LIMIT_EXCEED => 'Time Limit Exceed',
			MDL_Judge_Record::RESULT_MEMORY_LIMIT_EXCEED => 'Memory Limit Exceed',
			MDL_Judge_Record::RESULT_OUTPUT_LIMIT_EXCEED => 'Output Limit Exceed',
			MDL_Judge_Record::RESULT_SYSCALL_RESTRICTED => 'Syscall Restricted',
			MDL_Judge_Record::RESULT_EXECUTOR_ERROR => 'Executer Error',
		);
		return $complete[$result_text];
	}
	return 'Unknown';
}