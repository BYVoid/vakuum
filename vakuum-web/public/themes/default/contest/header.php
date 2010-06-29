<?php $this->display('header.php') ?>

<div id="contest_header">
<p>
	<a href="<?php echo $this->locator->getURL('contest/entry').'/'.$this->contest->getID() ?>">比赛入口</a>
	<a href="<?php echo $this->locator->getURL('contest/rank').'/'.$this->contest->getID() ?>">比赛排名</a>
	<a href="">我的状态</a>
	<a href="">提交记录</a>
</p>
</div>