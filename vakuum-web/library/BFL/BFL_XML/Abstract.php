<?php
/**
 * Abstract XML class
 *
 * @author BYVoid
 */
class BFL_XML_Abstract
{
	var $parser;   #a reference to the XML parser
	var $document; #the entire XML structure built up so far
	var $parent;   #a pointer to the current parent - the parent will be an array
	var $stack;	#a stack of the most recent parent at each nesting level
	var $last_opened_tag; #keeps track of the last tag opened.

	function __construct()
	{
		$this->parser = xml_parser_create();
		xml_parser_set_option(&$this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object(&$this->parser, &$this);
		xml_set_element_handler(&$this->parser, 'open','close');
		xml_set_character_data_handler(&$this->parser, 'data');
	}
	function __destruct()
	{
		xml_parser_free(&$this->parser);

	}
	function parse(&$data)
	{
		$this->document = array();
		$this->stack	= array();
		$this->parent   = &$this->document;
		return xml_parse($this->parser, $data, true) ? $this->document : NULL;
	}
	function open(&$parser, $tag, $attributes)
	{
		$this->data = ''; #stores temporary cdata
		$this->last_opened_tag = $tag;
		if(is_array($this->parent) and array_key_exists($tag,$this->parent))
		{ #if you've seen this tag before
			if(is_array($this->parent[$tag]) and array_key_exists(0,$this->parent[$tag]))
			{ #if the keys are numeric
			#this is the third or later instance of $tag we've come across
				$key = $this->count_numeric_items($this->parent[$tag]);
			}
			else
			{
			#this is the second instance of $tag that we've seen. shift around
				if(array_key_exists("$tag attr",$this->parent))
				{
					$arr = array('0 attr'=>&$this->parent["$tag attr"], &$this->parent[$tag]);
					unset($this->parent["$tag attr"]);
				}
				else
				{
					$arr = array(&$this->parent[$tag]);
				}
				$this->parent[$tag] = &$arr;
				$key = 1;
			}
			$this->parent = &$this->parent[$tag];
		}
		else
		{
			$key = $tag;
		}
		if($attributes)
		{
			$this->parent["$key attr"] = $attributes;
		}
		$this->parent  = &$this->parent[$key];
		$this->stack[] = &$this->parent;
	}
	function data(&$parser, $data)
	{
		if($this->last_opened_tag != NULL) #you don't need to store whitespace in between tags
			$this->data .= $data;
	}
	function close(&$parser, $tag)
	{
		if($this->last_opened_tag == $tag)
		{
			$this->parent = $this->data;
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack) $this->parent = &$this->stack[count($this->stack)-1];
	}
	function count_numeric_items(&$array)
	{
		return is_array($array) ? count(array_filter(array_keys($array), 'is_numeric')) : 0;
	}
}