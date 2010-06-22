<?php
/**
 * List all judgers
 *
 * @author BYVoid
 */
class MDL_Judger_Detail
{
	/**
	 * getJudgers
	 * @return array
	 */
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
	
	private static function getJudgerAbstract($where)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_JUDGER . $where);
		$stmt->execute();
		$judgers = $stmt->fetchAll();
		foreach($judgers as $key => $item)
		{
			$judgers[$key]['judger_config'] = BFL_XML::XML2Array($judgers[$key]['judger_config']);
		}
		
		return $judgers;
	}
}