<?php
require_once('function.php');
$this->header['stylesheet']['global'] = $this->getViewURL(). 'style/screen.css';
$this->header['javascript']['jquery'] = $this->getCommonURL(). 'script/jquery-1.4.2.js';