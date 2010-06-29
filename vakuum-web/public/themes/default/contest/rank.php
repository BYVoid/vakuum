<?php $contest = $this->contest ?>
<?php $contest_id = $contest->getID() ?>
<?php $contest_config = $contest->getConfig() ?>
<?php $display = $contest->getRankDisplay() ?>

<?php $this->title="比赛排名 - ".$contest_config->getName() ?>
<?php $this->display('header.php') ?>

<?php if ($display->list): ?>

<table border="1">
	<tr>
		<td>名次</td>
	<?php if ($display->user): ?>
		<td>用户昵称</td>
	<?php endif ?>
	<?php if ($display->score): ?>
		<td>得分</td>
	<?php endif ?>
	<?php if ($display->penalty): ?>
		<td>罚时</td>
	<?php endif ?>
	<?php if ($display->problem): ?>
		<?php $problems = $contest_config->getProblems() ?>
		<?php foreach($problems as $problem): ?>
			<?php $problem_url = $this->locator->getURL('contest/entry').'/'. $contest_id .'/' . $problem->alias ?>
			<td><a href="<?php echo $problem_url ?>" title="<?php echo $problem->getTitle() ?>"><?php echo $problem->alias ?></a></td>
		<?php endforeach ?>
	<?php endif ?>
	</tr>
<?php $total = $contest->getContestUsersCount() ?>
<?php for ($rank = 0; $rank < $total; ++$rank): ?>
	<?php $contest_user = $contest->getRank($rank) ?>
	<tr>
		<td><?php echo $rank + 1 ?></td>
	<?php if ($display->user): ?>
		<td><?php echo $contest_user->getUser()->getNickname() ?></td>
	<?php endif ?>
	<?php if ($display->score): ?>
		<td><?php echo (int) $contest_user->getScore() ?></td>
	<?php endif ?>
	<?php if ($display->penalty): ?>
		<td><?php echo $this->formatTimeSection($contest_user->getPenaltyTime()) ?></td>
	<?php endif ?>
	<?php if ($display->problem): ?>
		<?php foreach($problems as $problem): ?>
			<td><?php echo (int) $contest_user->getScoreWithProblem($problem) ?></td>
		<?php endforeach ?>
	<?php endif ?>
	</tr>
<?php endfor ?>
</table>

<?php endif ?>
<?php $this->display('footer.php') ?>