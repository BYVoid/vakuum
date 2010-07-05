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
	protected $sql_prefix = '';

	public function getItemCount()
	{
		if ($this->list == NULL)
			$this->updateList();
		return $this->item_count;
	}

	public function getPageCount()
	{
		$page_count = (int) ceil($this->item_count/ $this->getPageSize());
		if ($page_count == 0)
			$page_count = 1;
		return $page_count;
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
		if ($this->page_size <= 0)
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE_SIZE);
		$this->list = NULL;
	}

	public function setCurrentPage($page)
	{
		$this->current_page = (int) $page;
		if ($this->current_page <= 0)
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE);
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
		$sql = $this->sql_prefix;
		$stmt = BFL_Database::getInstance()->factory($sql);
		$stmt->execute();

		$item_count = $stmt->rowCount();
		$this->item_count = $item_count;

		$current_page = $this->getCurrentPage();
		if ($current_page > $this->getPageCount())
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE);
		$page_size = $this->getPageSize();
		$offset = $page_size * ($current_page - 1);

		//Fetch list with LIMIT
		$sql.= " LIMIT {$offset},{$page_size}";
		$stmt = BFL_Database::getInstance()->factory($sql);
		$stmt->execute();
		$this->list = $stmt->fetchAll();
	}

	protected function separatePage($list)
	{
		$this->item_count = $item_count = count($list);

		$current_page = $this->getCurrentPage();
		$page_size = $this->getPageSize();
		$page_count = $this->getPageCount();
		if ($current_page > $page_count)
			throw new MDL_Exception_List(MDL_Exception_List::INVALID_PAGE);

		$offset = $page_size * ($current_page - 1);

		$newlist = array();
		for ($i = $offset; $i < $offset + $page_size && $i < $item_count; ++$i)
			$newlist[] = $list[$i];

		return $newlist;
	}
}