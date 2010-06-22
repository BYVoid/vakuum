<?php
require_once('function.php');
$this->header['stylesheet']['global'] = $this->getViewURL(). 'style/screen.css';
$this->header['javascript']['jquery'] = $this->getCommonURL(). 'script/jquery-1.4.2.js';
$this->header['javascript']['jquery-ajaxqueue'] = $this->getCommonURL(). 'script/jquery-ajaxqueue.js';
$this->header['javascript']['admin-global'] = $this->getViewURL(). 'script/global.js';

$current_user = BFL_Register::getVar('personal');