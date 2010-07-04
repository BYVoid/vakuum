<?php
/**
 * List Viewer
 *
 * @author BYVoid
 */
abstract class MDL_List
{
	protected $current_page = NULL;
	protected $page_size = NULL;
	protected $list = NULL;
	protected $item_count = NULL;
	protected $page_count = NULL;
	protected $sql_prefix = '';

	public function getItemCount()
	{
		if ($this->list == NULL)
			$this->updateList();
		return $this->item_count;
	}

	public function getPageCount()
	{
		if ($this->list == NULL)
			$this->updateList();
		return $this->page_count;
	}

	public function getCurrentPage()
	{
		return $this->current_page;
	}

	public function getPageSize()
	{
		return $this->page_size;
	}

	public function getList()
	{
		if ($this->list == NULL)
			$this->updateList();
		return $this->list;
	}

	public function setPageSize($page_size)
	{
		$this->page_size = (int) $page_size;
		$this->list = NULL;
	}

	public function setCurrentPage($page)
	{
		$this->current_page = (int) $page;
		$this->list = NULL;
	}

	protected function setSQLPrefix($sql)
	{
		$this->sql_prefix = $sql;
		$this->list = NULL;
	}

	protected function __construct()
	{

	}

	protected function updateList()
	{
		$current_page = $this->getCurrentPage();
		if ($current_page <= 0)
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE);

		$page_size = $this->getPageSize();
		if ($page_size <= 0)
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE_SIZE);

		$sql = $this->sql_prefix;
		$stmt = BFL_Database::getInstance()->factory($sql);
		$stmt->execute();

		$item_count = $stmt->rowCount();
		$page_count = (int) ceil($item_count / $page_size);
		if ($page_count == 0)
			$page_count = 1;

		$this->item_count = $item_count;
		$this->page_count = $page_count;

		if ($current_page > $page_count)
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE);

		$offset = $page_size * ($current_page - 1);

		//Fetch list with LIMIT
		$sql.= " LIMIT {$offset},{$page_size}";
		$stmt = BFL_Database::getInstance()->factory($sql);
		$stmt->execute();
		$this->list = $stmt->fetchAll();
	}
}