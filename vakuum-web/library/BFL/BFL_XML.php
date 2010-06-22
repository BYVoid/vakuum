<?php
/**
 * XML Reader & Writer
 *
 * @author BYVoid
 */
class BFL_XML
{
	public static $phpexit = false;

	public static function XML2Array($xml)
	{
		$rs = self::XML_unserialize($xml);
		return $rs['document'];
	}
	
	public static function Array2XML($data)
	{
		$data = array('document'=>$data);
		return self::XML_serialize($data);
	}
	
	private static function XML_unserialize($xml)
	{
		$xml_parser = new BFL_XML_Abstract();
		$data = $xml_parser->parse($xml);
		$xml_parser = NULL;
		return $data;
	}
	
	private static function XML_serialize(&$data, $level = 0, $prior_key = NULL)
	{
		if($level == 0)
		{
			ob_start();
			echo '<?xml version="1.0" encoding="utf-8"?>',"\n";
			if (self::$phpexit)
			{
				echo '<!--<?php exit; ?>-->',"\n";
			}
		}
		foreach($data as $key => $value)
		{
			if (!strpos($key, ' attr'))
			{
				if(is_array($value) && array_key_exists(0, $value))
				{
					self::XML_serialize($value, $level, $key);
				}
				else
				{
					$tag = $prior_key ? $prior_key : $key;
					echo str_repeat("\t", $level),'<',$tag;
					if (array_key_exists("$key attr", $data))
					{
						while(list($attr_name, $attr_value) = each($data["$key attr"]))
							echo ' ',$attr_name,'="',htmlspecialchars($attr_value),'"';
						reset($data["$key attr"]);
					}

					if(is_null($value))
					{
						echo " />\n";
					}
					else if (!is_array($value))
					{
						echo '>',htmlspecialchars($value),"</$tag>\n";
					}
					else
					{
						echo ">\n",self::XML_serialize($value, $level+1),str_repeat("\t", $level),"</$tag>\n";
					}
				}
			}
		}
		reset($data);
		if($level == 0)
		{
			$str = &ob_get_contents();
			ob_end_clean();
			return $str;
		}
	}
}