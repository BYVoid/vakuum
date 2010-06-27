<?php $contest = $this->contest ?>
<?php $contest_id = $contest->getID() ?>
<?php $contest_config = $contest->getConfig() ?>
<?php $contest_rank_path = $this->locator->getURL('contest/rank').'/'.$contest_id ?>

<?php $this->title="比赛 - ".$contest_config->getName() ?>
<?php $this->display('header.php') ?>

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
	<tr>
		<td></td>
		<td><a href="<?php echo $contest_rank_path ?>">查看排名</a></td>
	</tr>
</table>

<table border="1">
	<tr>
		<td>代号</td>
		<td>题目</td>
		<td>分值</td>
		<td>状态</td>
	</tr>
<?php foreach($contest_config->getProblems() as $problem): ?>
	<?php $problem_url = $this->locator->getURL('contest/entry').'/'. $contest_id .'/' . $problem->alias ?>
	<tr>
		<td><?php echo $problem->alias ?></td>
		<td><a href="<?php echo $problem_url ?>"><?php echo $problem->getTitle() ?></a></td>
		<td><?php echo $problem->score ?></td>
		<td>状态</td>
	</tr>
<?php endforeach ?>
</table>
<?php $this->display('footer.php') ?>