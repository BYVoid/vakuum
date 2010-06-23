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
		try
		{
			$problem = MDL_Problem_Show::getProblem($prob_id);
		}
		catch (MDL_Exception_Problem $e)
		{
			$desc = $e->getDescription();
			switch($desc[1])
			{
				case 'id':
					throw new MDL_Exception_Judge('problem_not_exist');
					break;
				default:
					throw $e;
			}
		}
		if ($problem['verified'] != 1)
			throw new MDL_Exception_Judge('problem_not_verified');
		
		//check length
		$config = MDL_Config::getInstance();
		$smaxlen = $config->getVar('judge_source_length_max');
		if (strlen($source) > $smaxlen)
			throw new MDL_Exception_Judge('source_length');
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
			throw new MDL_Exception_Judge('source_encode');
		if ($original_encode != 'UTF-8')
			$source = mb_convert_encoding($source, 'UTF-8', $original_encode);
		return $source;		
	}
	
	public static function rejudgeSingle($record_id)
	{
		MDL_Judge_Record::resetRecord($record_id);
		try
		{
			MDL_Judger_Process::processTaskQueue();
		}
		catch(MDL_Exception_Judge_Submit $e)
		{
			//No available judger
		}
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
		try
		{
			$judger_id = MDL_Record::getJudgerID($record_id);
			if ($judger_id != 0)
			{
				$judger = MDL_Judger::getJudger($judger_id);
			}
		}
		catch(MDL_Exception $e)
		{
			return;
		}
		
		$record_meta = new MDL_Record_Meta($record_id);
		$status = $record_meta->getVar('status');
		if ($status == MDL_Judge_Record::STATUS_STOPPED)
			return;
		
		$record_meta->setVar('status',MDL_Judge_Record::STATUS_STOPPED);
		$record_meta->setVar('result_text',MDL_Judge_Record::RESULT_EXECUTOR_ERROR);
		$task_name = MDL_Record::getTaskName($record_id);
		
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