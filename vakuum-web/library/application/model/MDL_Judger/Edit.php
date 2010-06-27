<?php
class MDL_Judger_Edit extends MDL_Judger
{
	public static function add($judger)
	{
		if ($judger['judger_name'] == '')
			throw new MDL_Exception('judger_name');

		$judger['judger_config'] = self::encodeConfig($judger['judger_config']);

		$db = BFL_Database :: getInstance();
		$meta = array
		(
			'judger_name' => ':judger_name' ,
			'judger_priority' => ':judger_priority' ,
			'judger_enabled' => ':judger_enabled' ,
			'judger_available' => '0' ,
			'judger_count' => '0' ,
			'judger_config' => ':judger_config',
		);

		$stmt = $db->insert(DB_TABLE_JUDGER , $meta);
		$stmt->bindParam(':judger_name', $judger['judger_name']);
		$stmt->bindParam(':judger_priority', $judger['judger_priority']);
		$stmt->bindParam(':judger_enabled', $judger['judger_enabled']);
		$stmt->bindParam(':judger_config', $judger['judger_config']);
		$stmt->execute();
	}

	public static function edit($judger)
	{
		$judger['judger_config'] = self::encodeConfig($judger['judger_config']);
		$judger_id = $judger['judger_id'];

		$meta = array
		(
			'`judger_name` = :judger_name ',
			'`judger_priority` =:judger_priority ',
			'`judger_enabled` =:judger_enabled ' ,
			'`judger_config` =:judger_config ',
		);

		$db = BFL_Database :: getInstance();
		$stmt = $db->update(DB_TABLE_JUDGER,$meta,'where `judger_id`=:judger_id');
		$stmt->bindParam(':judger_id', $judger_id);
		$stmt->bindParam(':judger_name', $judger['judger_name']);
		$stmt->bindParam(':judger_priority', $judger['judger_priority']);
		$stmt->bindParam(':judger_enabled', $judger['judger_enabled']);
		$stmt->bindParam(':judger_config', $judger['judger_config']);
		$stmt->execute();
	}

	public static function remove($judger_id)
	{
		$db = BFL_Database::getInstance();

		$stmt = $db->delete(DB_TABLE_JUDGER,'where `judger_id`=:judger_id');
		$stmt->bindParam(':judger_id', $judger_id);
		$stmt->execute();

	}

	public static function forceAvailable($judger_id)
	{
		$meta = array
		(
			'`judger_available` = 1 ',
		);

		$db = BFL_Database :: getInstance();
		$stmt = $db->update(DB_TABLE_JUDGER,$meta,'where `judger_id`=:judger_id');
		$stmt->bindParam(':judger_id', $judger_id);
		$stmt->execute();
	}

	public static function getDefault()
	{
		$judger = array
		(
			'judger_id' => 0,
			'judger_name' => '',
			'judger_priority' => 0,
			'judger_enabled' => 1,
			'judger_config' => array
			(
				'url' => '',
				'public_key' => '',
				'upload' => 'ftp',
				'ftp' => array
				(
					'address' => '',
					'user' => '',
					'password' => '',
					'path' => array
					(
						'task' => '',
						'testdata' => '',
					),
				),
				'share' => array
				(
					'path' => array
					(
						'task' => '',
						'testdata' => '',
					),
				),
			),
		);
		return $judger;
	}
}