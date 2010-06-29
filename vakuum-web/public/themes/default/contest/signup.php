<?php $contest = $this->contest ?>
<?php $contest_id = $contest->getID() ?>
<?php $contest_config = $contest->getConfig() ?>

<?php $this->title="报名参赛" ?>
<?php $this->display('contest/header.php') ?>

<table border="1">
	<tr>
		<td>名称</td>
		<td><?php echo $this->escape($contest_config->getName()) ?></td>
	</tr>
	<tr>
		<td>描述</td>
		<td><?php echo $this->escape($contest_config->getDesc()) ?></td>
	</tr>
	<tr>
		<td>报名截止时间</td>
		<td><?php echo $this->formatTime($contest_config->getSignUpTimeEnd()) ?></td>
	</tr>
	<tr>
		<td>比赛开始时间</td>
		<td><?php echo $this->formatTime($contest_config->getContestTimeStart()) ?></td>
	</tr>
	<tr>
		<td>比赛结束时间</td>
		<td><?php echo $this->formatTime($contest_config->getContestTimeEnd()) ?></td>
	</tr>
	<tr>
		<td>比赛时间限制</td>
		<td><?php echo $contest_config->getContestTimeLimit() ?></td>
	</tr>
	<tr>
		<td>按测试点记分</td>
		<td><?php echo showBool($contest_config->countCaseScore()) ?></td>
	</tr>
	<tr>
		<td>计算罚时</td>
		<td><?php echo $this->escape($contest_config->getPenaltyTime()) ?></td>
	</tr>
</table>

<a href="<?php echo $this->locator->getURL('contest/dosignup').'/'. $contest_id ?>">立即报名</a>

<?php $this->display('footer.php') ?>