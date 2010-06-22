<?php
$language = $this->language;
$source_file = $this->source_file;
$binary_file = $this->binary_file;

writelog('Compile...');
//获得编译器路径
$path = Config::getInstance()->getVar('path');
$compiler_path = $path['compiler'];

//生成编译命令
$command = "{$compiler_path}compiler {$language} {$source_file} {$binary_file}";

//执行编译命令
$this->execute($command,$stdout,$stderr);

//生成返回结果
list($result,$option) = explode("\n",$stdout);
$compiler_message_file = 'compile_message.txt';
$compiler_message = file_get_contents($compiler_message_file);
$post_result = array
(
	'type' => 'compile',
	'info' => array
	(
		'result' => $result,
		'option' => $option,
		'compiler_message' => $compiler_message,
	)
);

//发送返回结果
$this->sendBack($post_result);
