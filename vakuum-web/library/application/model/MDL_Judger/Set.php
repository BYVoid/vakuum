<?php
class MDL_Judger_Set
{
	public static function getAllJudgers()
	{
		$db = BFL_Database::getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_JUDGER);
		$stmt->execute();
		$judgers = $stmt->fetchAll();

		foreach($judgers as $i => $judger)
		{
			$judgers[$i] = new MDL_Judger($judger['judger_id']);
		}

		return $judgers;
	}

	/**
	 * @return MDL_Judger
	 */
	public static function getAvailableJudger()
	{
		$db = BFL_Database::getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_JUDGER.' where `judger_enabled` = 1
				and `judger_available` = 1 order by judger_priority asc');
		$stmt->execute();
		$judger = $stmt->fetch();

		if (empty($judger))
			return NULL;

		return new MDL_Judger($judger['judger_id']);
	}
}