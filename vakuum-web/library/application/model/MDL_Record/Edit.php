<?php
/**
 * Edit a record
 *
 * @author BYVoid
 */
class MDL_Record_Edit
{
	public static function delete($record_id)
	{
		$db = BFL_Database::getInstance();
		$stmt = $db->delete(DB_TABLE_RECORD,'where `record_id`=:record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		
		$stmt = $db->delete(DB_TABLE_RECORDMETA,'where `rmeta_record_id`=:record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
	}
}