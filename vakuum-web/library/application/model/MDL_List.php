<?php
/**
 * List Viewer
 *
 * @author BYVoid
 */
class MDL_List
{
	/**
	 * Get Data Set
	 * @param string $sql
	 * @return array DataSet
	 */
	public static function getList($sql,$current_page,$page_size)
	{
		//Restrict $current_page to positive integer
		$current_page = (int)$current_page;
		if ($current_page <= 0)
			throw new MDL_Exception_List('page');
		
		$page_size = (int)$page_size;
		if ($page_size <= 0)
			throw new MDL_Exception_Config('page_size');
		
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory($sql);
		$stmt->execute();

		//Compute $row_count and $page_count
		$item_count = $rs = $stmt->rowCount();
		$stmt = NULL;
		$page_count = (int)ceil($item_count / $page_size);
		if ($page_count == 0)
			$page_count = 1;
		$info = array
		(
			'item_count' => $item_count,
			'page_count' => $page_count,
			'page_size' => $page_size,
			'current_page' => $current_page,
		);
		
		//Restrict $current_page not to be more than $page_count
		if ($current_page > $page_count)
			throw new MDL_Exception_List('page');
		$offset = $page_size * ($current_page - 1);
		
		//Fetch list with LIMIT
		$sql.= " LIMIT {$offset},{$page_size}";
		$stmt = $db->factory($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		return array
		(
			'list' => $result,
			'info' => $info,
		);
	}
}