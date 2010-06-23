<?php
/**
 * record
 *
 * @author BYVoid
 */
class MDL_Judge_Record
{
	const STATUS_WAITING = 0;
	const STATUS_PENDING = 1;
	const STATUS_RUNNING = 2;
	const STATUS_STOPPED = 3;
	
	const RESULT_ACCEPTED = 0;
	const RESULT_RUNTIME_ERROR = 1;
	const RESULT_TIME_LIMIT_EXCEED = 2;
	const RESULT_MEMORY_LIMIT_EXCEED = 3;
	const RESULT_OUTPUT_LIMIT_EXCEED = 4;
	const RESULT_SYSCALL_RESTRICTED = 5;
	const RESULT_EXECUTOR_ERROR = 6;
	const RESULT_WRONG_ANSWER = 7;
	const RESULT_COMILATION_ERROR = 8;
	
	public static function createRecord($user_id,$prob_id,$lang,$source)
	{
		//create a new record
		$db = BFL_Database :: getInstance();
		$meta = array
		(
			'record_prob_id' => ':prob_id' ,
			'record_user_id' => ':user_id' ,
			'record_judger_id' => '0',
		);
		$stmt = $db->insert(DB_TABLE_RECORD , $meta);
		$stmt->bindParam(':prob_id', $prob_id);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		$record_id = $db->getLastInsertID();

		// TODO: config default
		$display = new MDL_Record_DisplayConfig();
		$display->setShowCaseResult();
		$display->setShowCodeToOwner();
		$display->setShowCompileResult();
		$display->setShowInRecordList();
		$display->setShowRunResult();
		$display = $display->getValue();
		
		//record metas
		$meta = array
		(
			'lang' => $lang,
			'status' => self::STATUS_WAITING,
			'result_text' => '',
			'submit_time' => time(),
			'memory' => '0',
			'time' => '0',
			'source' => $source,
			'score' => 0.00,
			'display' => $display,
		);
		$record_meta = new MDL_Record_Meta($record_id);
		$record_meta->setMetas($meta);

		return $record_id;
	}
	
	public static function pend($record_id,$judger_id)
	{
		$record_meta = new MDL_Record_Meta($record_id);
		if ($record_meta->getVar('status') == self::STATUS_STOPPED)
			return;
		$record_meta->setVar('status',self::STATUS_PENDING);
		
		$db = BFL_Database :: getInstance();
		$meta = array('`record_judger_id` = :judger_id');
		$stmt = $db->update(DB_TABLE_RECORD , $meta ,'where `record_id`=:record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->bindParam(':judger_id', $judger_id);
		$stmt->execute();

	}
	
	public static function recordCompile($info)
	{
		$record_id = $info['record_id'];
		unset($info['record_id']);
		$record_meta = new MDL_Record_Meta($record_id);
		if ($record_meta->getVar('status') == self::STATUS_STOPPED)
			return;
		
		$result = array
		(
			'compile' => $info
		);
		$result = BFL_XML::Array2XML($result);
		
		$record_meta->setVar('result',$result);
		$record_meta->setVar('result_text',0);
		$record_meta->setVar('status',self::STATUS_RUNNING);
	}

	public static function recordExecute($info)
	{
		$newCase = $info;
		unset($newCase['record_id']);

		$record_meta = new MDL_Record_Meta($info['record_id']);
		if ($record_meta->getVar('status') == self::STATUS_STOPPED)
			return;
		
		$result = $record_meta->getVar('result');

		$result = BFL_XML::XML2Array($result);
		if (isset($result['execute']['case']))
		{
			if (!isset($result['execute']['case'][0]))
				$result['execute']['case'] = array($result['execute']['case']);
			$result['execute']['case'][]=$newCase;
		}
		else
		{
			$result['execute']['case']=$newCase;
		}
		$result = BFL_XML::Array2XML($result);
		

		$record_meta->setVar('result',$result);
		$record_meta->setVar('result_text',$info['case_id']);
		$record_meta->setVar('status', self::STATUS_RUNNING);
	}

	public static function recordComplete($info)
	{
		$record_meta = new MDL_Record_Meta($info['record_id']);
		if ($record_meta->getVar('status') == self::STATUS_STOPPED)
			return;
		
		$meta = array
		(
			'result_text' => $info['fatal'],
			'status' => self::STATUS_STOPPED,
			'time' =>  $info['time'],
			'memory' =>  $info['memory'],
			'score' => $info['score'],
		);
		$record_meta->setMetas($meta);
		
		//get judger_id via record_id
		$judger_id = MDL_Record::getJudgerID($info['record_id']);
		
		//free judger
		MDL_Judger::unlock($judger_id);
		
		//process task queue
		MDL_Judger_Process::processTaskQueue();
	}
	
	public static function resetRecord($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt=$db->update(DB_TABLE_RECORD,'`record_judger_id` = 0','where `record_id`=:record_id');
		$stmt->bindParam(':record_id',$record_id);
		$stmt->execute();

		$record_meta = new MDL_Record_Meta($record_id);
		$meta = array
		(
			'status' => self::STATUS_WAITING,
			'result' => '',
			'memory' => '0',
			'time' => '0',
			'score' => 0.00,
		);
		$record_meta->setMetas($meta);
	}
}