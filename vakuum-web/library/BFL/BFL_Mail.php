<?php
class BFL_Mail
{
	private $receiver,$subject,$message,$from,$content_type,$encoding;
	
	public function __construct($receiver,$subject,$message)
	{
		$this->setRecerver($receiver);
		$this->subject = $subject;
		$this->message = $message;
		$this->content_type = 'text/html';
		$this->encoding = 'utf-8';
		
		$server_name = BFL_General::getServerName();
		$this->from = "{$server_name} <noreply@{$server_name}>";
	}
	
	public function setRecerver($receiver)
	{
		if (is_array($receiver))
		{
			$this->receiver = implode(',',$receiver);
		}
		else
		{
			$this->receiver = $receiver;
		}
	}
	
	public function setFrom($from)
	{
		$this->from = $from;
	}
	
	public function setContentType($content_type)
	{
		$this->content_type = $content_type;
	}
	
	public function setEncoding($encoding)
	{
		$this->encoding = $encoding;
	}
	
	public function send()
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: '.$this->content_type.'; charset='. $this->encoding . "\r\n";
		$headers .= 'To: '. $this->receiver . "\r\n";
		$headers .= 'From: '. $this->from . "\r\n";
		return mail(null,$this->subject,$this->message,$headers);
	}
}