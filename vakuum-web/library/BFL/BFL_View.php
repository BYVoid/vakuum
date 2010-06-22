<?php
class BFL_View
{
	protected $_view_path;

	protected function setViewPath($view_path)
	{
		$this->_view_path = $view_path;
	}

	protected function getViewPath()
	{
		return $this->_view_path;
	}
	
	public function render($script)
	{
		$this->_filename = $this->getViewPath() . $script;
		unset($script);
		
		ob_start();
		require($this->_filename);
		return ob_get_clean();
	}
	
	public function display($script)
	{
		echo $this->render($script);
	}
}