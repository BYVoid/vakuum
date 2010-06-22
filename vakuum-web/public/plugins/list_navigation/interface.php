<?php
class list_navigation
{
	private static $default_size = 8;
	
	public static function initialize()
	{
		$view = MDL_View::getInstance();
		$view->header['stylesheet']['list_navigation'] = MDL_Locator::makePublicURL(BFL_Register::getVar('plugin_file').'style.css');
	}
	
	private static function list_navigation_make_url($page,$display)
	{
		$target_url = BFL_PathOption::getInstance()->getURL(array('page'=>$page));
		return "<span><a href=\"{$target_url}\" title=\"{$page}\">{$display}</a></span>";
	}
	
	public static function show($total,$current)
	{
		$size = self::$default_size;
		if ($total < $size)
			$size = $total;
	
		$html = '<div class="navigation">'."\n";
		$html .= '<span class="pages">Page '. $current .' of '. $total .'</span>';
		
		if ($current != 1)
		{
			$html .= self::list_navigation_make_url(1,'First') ."\n";
			$html .= self::list_navigation_make_url($current - 1,'&laquo;') ."\n";
		}
		
		
		$left_size = (int)$size / 2;
		if ($current - $left_size < 1)
			$left_size = $current - 1;
		$right_size = $size - $left_size - 1;
		if ($current + $right_size > $total)
		{
			$right_size = $total - $current;
			$left_size = $size - $right_size - 1;
		}
	
		$bound = $current - $left_size;
		if ($bound != 1)
			$html .= '<span class="extend">...</span>'."\n";
		
		for ($i=$bound;$i<$current;++$i)
		{
			$html .= self::list_navigation_make_url($i,$i) ."\n";
		}
		
		$html .= '<span class="current">'.$current.'</span>'."\n";
		
		$bound = $current + $right_size;
		
		for ($i=$current+1;$i<=$bound;++$i)
		{
			$html .= self::list_navigation_make_url($i,$i) ."\n";
		}
		
		if ($bound != $total)
			$html .= '<span class="extend">...</span>'."\n";
		
		if ($current != $total)
		{
			$html .= self::list_navigation_make_url($current + 1,'&raquo;') ."\n";
			$html .= self::list_navigation_make_url($total,'Last') ."\n";
		}
		
		$html .= '</div>'."\n";
		return $html;
	}
}

list_navigation::initialize();