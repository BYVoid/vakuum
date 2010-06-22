<?php
class NicEdit
{
	private static $plugin_file,$plugin_path;
	private static $plugin_name = 'NicEdit';
	private static $plugin_request;
	
	public static function initialize()
	{
		self::$plugin_file=BFL_Register::getVar('plugin_file');
		self::$plugin_path= MDL_Config::getInstance()->getVar('root_path'). self::$plugin_file;
		self::$plugin_request=MDL_Locator::getInstance()->getURL('plugin_request').'/'.self::$plugin_name;
	}
	
	public static function prepare()
	{
		$view = MDL_View::getInstance();
		$view->header['javascript']['NicEdit'] = self::$plugin_request .'?action=get_header_js';
	}
	
	public static function request()
	{
		$action = $_GET['action'];
		switch ($action)
		{
			case 'get_header_js':
				self::getHeaderJs();
				break;
			case 'upload':
				self::upload();
				break;
			default:
				
		}
	}
	
	private static function getHeaderJs()
	{
		header("Content-Type: application/x-javascript; charset=utf-8");
		echo 'var NicEdit_icon_path="'. self::$plugin_path .'";'."\n";
		echo 'var NicEdit_upload="'. self::$plugin_request .'?action=upload";'."\n";
		readfile(self::$plugin_file.'NicEdit.js');
	}
	
	private static function upload()
	{
		if (MDL_Config::getInstance()->getVar('plugin_NicEdit_upload_allow') == 0)
			exit;//TODO forbidden upload
		
		$upload_path=MDL_Config::getInstance()->getVar('plugin_NicEdit_upload_path');
		$upload_uri=MDL_Config::getInstance()->getVar('root_path').$upload_path;
		require(self::$plugin_file.'nicUpload.php');
	}
	
	public static function show($id,$allow_html=false,$button_wysiwyg='WYSIWYG',$button_html='HTML',$defalut_html=false)
	{
		$handle="NicEdit_handle_".$id;
		if ($allow_html)
		{
			echo <<< HTML
<input id="{$handle}_button_addarea" type="button" onclick="{$handle}_addarea();" value="{$button_wysiwyg}" />
<input id="{$handle}_button_removearea" disabled="disabled" type="button" onclick="{$handle}_removearea();" value="{$button_html}" />
<br />
HTML;
		}
		echo <<<HTML
<script type="text/javascript">//<![CDATA[
	function {$handle}_addarea()
	{
		{$handle} = new nicEditor({fullPanel : true}).panelInstance('{$id}');
		document.getElementById("{$handle}_button_addarea").disabled="disabled";
		document.getElementById("{$handle}_button_removearea").disabled="";
	}
	function {$handle}_removearea()
	{
		{$handle}.removeInstance('{$id}');
		document.getElementById("{$handle}_button_addarea").disabled="";
		document.getElementById("{$handle}_button_removearea").disabled="disabled";
	}
//]]></script>
HTML;
		if (!$defalut_html)
		{
			echo <<<HTML
<script type="text/javascript">//<![CDATA[
	bkLib.onDomLoaded(function() { {$handle}_addarea() });
//]]></script>
HTML;
		}
	}
}

NicEdit::initialize();