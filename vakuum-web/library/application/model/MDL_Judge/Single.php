<?php
/**
 * Submit a single record
 *
 * @author BYVoid
 */
class MDL_Judge_Single
{
	public static function submit($user_id,$prob_id,$lang,$source)
	{
		//TODO verify problem allowence
		$problem = MDL_Problem_Show::getProblem($prob_id);

		if ($problem['verified'] != 1)
			throw new MDL_Exception_Problem(MDL_Exception_Problem::UNVALIDATED_PROBLEM);

		//check length
		$config = MDL_Config::getInstance();
		$smaxlen = $config->getVar('judge_source_length_max');
		if (strlen($source) > $smaxlen)
			throw new MDL_Exception_Judge(MDL_Exception_Judge::INVALID_SOURCE_LENGTH);
		//encode source
		$source = self::convertEncode($source);

		//create new record
		$record_id = MDL_Judge_Record::createRecord($user_id,$prob_id,$lang,$source);

		return $record_id;
	}

	private static function convertEncode($source)
	{
		$original_encode = mb_detect_encoding($source,"UTF-8,CP936,EUC-CN,BIG5");
		if ($original_encode == "")
			throw new MDL_Exception_Judge(MDL_Exception_Judge::INVALID_SOURCE_ENCODIND);
		if ($original_encode != 'UTF-8')
			$source = mb_convert_encoding($source, 'UTF-8', $original_encode);
		return $source;
	}

	public static function rejudgeSingle($record_id)
	{
		MDL_Judge_Record::resetRecord($record_id);

		MDL_Judger_Process::processTaskQueue();
	}

	public static function rejudgeProblem($prob_id)
	{
		$records = MDL_Problem_List::getRecords($prob_id);
		foreach($records as $record)
		{
			self::rejudgeSingle($record['record_id']);
		}
	}

	public static function stop($record_id)
	{
		$record = new MDL_Record($record_id);
		$judger_id = $record->getJudgerID();

		if ($judger_id != 0)
		{
			$judger = MDL_Judger::getJudger($judger_id);
		}

		$record_meta = $record->getInfo()->getRecordMeta();
		$status = $record_meta->getVar('status');
		if ($status == MDL_Judge_Record::STATUS_STOPPED)
			return;

		$record_meta->setVar('status',MDL_Judge_Record::STATUS_STOPPED);
		$record_meta->setVar('result_text',MDL_Judge_Record::RESULT_EXECUTOR_ERROR);
		$task_name = $record->getTaskName();

		if ($judger_id != 0)
		{
			MDL_Judger_Access::stopJudge($task_name,$judger['judger_config']);
			if ($status != MDL_Judge_Record::STATUS_WAITING)
			{
				MDL_Judger::unlock($judger_id);
			}
		}
	}
}