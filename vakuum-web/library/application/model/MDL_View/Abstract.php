<?php
abstract class MDL_View_Abstract extends BFL_View
{
	public $header,$footer;

	protected function getViewURL()
	{
		return MDL_Locator::makePublicURL($this->getViewPath());
	}

	protected function getCommonURL()
	{
		return MDL_Locator::makePublicURL('public/common/');
	}

	protected function loadHeader()
	{
		$rs = "<!--Stylesheet Description Start-->\n";
		foreach($this->header['stylesheet'] as $key => $item)
		{
			$rs .= '<link type="text/css" rel="stylesheet" href="'. $item .'" />';
			$rs .= "<!-- {$key} -->\n";
		}
		$rs .= "<!--Stylesheet Description End-->\n";
		$rs .= "<!--Javascript Description Start-->\n";
		foreach($this->header['javascript'] as $key => $item)
		{
			$rs .= '<script src="'. $item .'" type="text/javascript"></script>';
			$rs .= "<!-- {$key} -->\n";
		}
		$rs .= "<!--Javascript Description End-->\n";

		return $rs;
	}

	protected function getScriptExecutingTime()
	{
		return BFL_Timer :: getScriptExecutingTime();
	}

	protected function getDatabaseQueryCount()
	{
		return BFL_Database :: getInstance()->getQueryCount();
	}

	protected function formatTime($time)
	{
		$config = MDL_Config :: getInstance();
		return date($config->getVar('time_format'),$time);
	}

	protected function formatTimeSection($time)
	{
		$hour = (int) $time / 3600;
		$time %= 3600;
		$minute = $time / 60;
		$second = $time % 60;
		return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
	}

	protected function escape($str)
	{
		return htmlspecialchars($str);
	}

	protected function showInputbox($value="",$name="",$id="",$size = -1,$readonly=false,$disabled=false,$password=false,$maxlen = -1,$addition = "")
	{
		$html = "<input";
		if ($value != "")
			$html.= " value=\"". $this->escape($value) ."\"";
		if ($name != "")
			$html.= " name=\"{$name}\"";
		if ($id != "")
			$html.= " id=\"{$id}\"";
		if ($size != -1)
			$html.= " size=\"{$size}\"";
		if ($readonly)
			$html.= " readonly=\"readonly\"";
		if ($disabled)
			$html.= " disabled=\"disabled\"";
		if ($password)
			$html.= " type=\"password\"";
		else
			$html.= " type=\"text\"";
		if ($maxlen != -1)
			$html.= " maxlength=\"{$maxlen}\"";
		if ($addition != '')
			$html.= " {$addition}";
		$html .= "/>";
		return $html;
	}

	protected function showCheckbox($checked = false,$name="",$id="",$disabled=false,$addition = "")
	{
		$html = "<input type=\"checkbox\" value=\"1\"";
		if ($checked)
			$html.= " checked=\"checked\"";
		if ($name != "")
			$html.= " name=\"{$name}\"";
		if ($id != "")
			$html.= " id=\"{$id}\"";
		if ($disabled)
			$html.= " disabled=\"disabled\"";
		if ($addition != '')
			$html.= " {$addition}";
		$html .= "/>";
		return $html;
	}

	protected function showRadio($value = 1,$checked = false,$name="",$id="",$disabled=false,$addition = "")
	{
		$html = "<input type=\"radio\" value=\"{$value}\"";
		if ($checked)
			$html.= " checked=\"checked\"";
		if ($name != "")
			$html.= " name=\"{$name}\"";
		if ($id != "")
			$html.= " id=\"{$id}\"";
		if ($disabled)
			$html.= " disabled=\"disabled\"";
		if ($addition != '')
			$html.= " {$addition}";
		$html .= "/>";
		return $html;
	}
}