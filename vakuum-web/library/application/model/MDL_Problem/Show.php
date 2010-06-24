<?php
/**
 * Show a problem
 *
 * @author BYVoid
 */
class MDL_Problem_Show
{
	/**
	 * Get Porblem By ID
	 * @param int $prob_id Problem ID
	 * @return array Problem
	 */
	public static function getProblem($prob_id)
	{
		$sql_where = 'WHERE `prob_id` = :param';
		return self::getProblemAbstract($sql_where, $prob_id);
	}

	/**
	 * Get Porblem By Name
	 * @param int $prob_name Problem Name
	 * @return array Problem
	 */
	public static function getProblemByName($prob_name)
	{
		$sql_where = 'WHERE `prob_name` = :param';
		return self::getProblemAbstract($sql_where, $prob_name);
	}

	/**
	 * Ger Problem
	 * @param string $sql_where
	 * @param string $param
	 * @return array Problem
	 */
	private static function getProblemAbstract($sql_where,$param)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_PROB.' '.$sql_where);
		$stmt->bindParam(':param', $param);
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
		{
			throw new MDL_Exception_Problem(MDL_Exception_Problem::NOTFOUND);
		}

		$problem = $rs;
		$prob_id = $problem['prob_id'];

		$prob_meta = new MDL_Problem_Meta($prob_id);
		$rs = $prob_meta->getAll();
		$problem = array_merge($problem,$rs);
		$problem['data_config'] = BFL_XML::XML2Array($problem['data_config']);
		if (!isset($problem['data_config']['case'][0]))
			$problem['data_config']['case'] = array($problem['data_config']['case']);
		return $problem;
	}
	
	/**
	 * getProblemName
	 * @param int $prob_id
	 * @return array
	 */
	public static function getProblemName($prob_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `prob_name`,`prob_title` from '.DB_TABLE_PROB.' WHERE `prob_id` = :prob_id');
		$stmt->bindParam(':prob_id', $prob_id);
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
		{
			throw new MDL_Exception_Problem(MDL_Exception_Problem::NOTFOUND);
		}
		
		return $rs;
	}
}