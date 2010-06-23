<?php
/**
 * judger
 *
 * @author BYVoid
 */
class MDL_Judger
{
	protected static function decodeConfig($str)
	{
		return BFL_XML::XML2Array($str);
	}
	
	protected static function encodeConfig($config)
	{
		return BFL_XML::Array2XML($config);
	}
	
	public static function lock($judger_id)
	{
		self::turn($judger_id,0);
	}
	
	public static function unlock($judger_id)
	{
		self::turn($judger_id,1);
	}
	
	private static function turn($judger_id,$judger_available)
	{
		$db = BFL_Database :: getInstance();
		$meta = array('`judger_available` = :judger_available');
		if ($judger_available == 0)
		{
			$meta[] = '`judger_count` = `judger_count` + 1';
		}
		$stmt = $db->update(DB_TABLE_JUDGER , $meta ,'where `judger_id`=:judger_id');
		$stmt->bindParam(':judger_available', $judger_available);
		$stmt->bindParam(':judger_id', $judger_id);
		$stmt->execute();
	}
	
	public static function getJudgers()
	{
		return self::getJudgerAbstract('');
	}

	public static function getJudger($judger_id)
	{
		$judgers = self::getJudgerAbstract("where `judger_id` = '{$judger_id}'");
		if (empty($judgers))
			throw new MDL_Exception('judger_id');
		return $judgers[0];
	}
	
	public static function getAvailableJudgers()
	{
		$judgers = self::getJudgerAbstract(' where `judger_enabled` = 1 and 
				`judger_available` = 1 order by judger_priority asc');
		
		return $judgers;
	}
	
	private static function getJudgerAbstract($where)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_JUDGER . $where);
		$stmt->execute();
		$judgers = $stmt->fetchAll();
		foreach($judgers as $key => $item)
		{
			$judgers[$key]['judger_config'] = self::decodeConfig($judgers[$key]['judger_config']);
		}
		
		return $judgers;
	}
}