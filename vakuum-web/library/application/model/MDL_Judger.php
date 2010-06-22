<?php
/**
 * judger
 *
 * @author BYVoid
 */
class MDL_Judger
{
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
}