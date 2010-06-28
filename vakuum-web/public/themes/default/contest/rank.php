<?php $contest = $this->contest ?>
<?php $contest_id = $contest->getID() ?>
<?php $contest_config = $contest->getConfig() ?>

<?php $this->title="比赛排名 - ".$contest_config->getName() ?>
<?php $this->display('header.php') ?>

<?php $problems = $contest_config->getProblems() ?>

<table border="1">
	<tr>
		<td>名次</td>
		<td>昵称</td>
		<td>得分</td>
		<td>罚时</td>
	<?php foreach($problems as $problem): ?>
		<?php $problem_url = $this->locator->getURL('contest/entry').'/'. $contest_id .'/' . $problem->alias ?>
		<td><a href="<?php echo $problem_url ?>" title="<?php echo $problem->getTitle() ?>"><?php echo $problem->alias ?></a></td>
	<?php endforeach ?>
	</tr>
<?php $total = $contest->getContestUsersCount() ?>
<?php for ($rank = 0; $rank < $total; ++$rank): ?>
	<?php $score = $contest->getRank($rank) ?>
	<tr>
		<td><?php echo $rank + 1 ?></td>
		<td><?php echo $score->getUser()->getNickname() ?></td>
		<td><?php echo $score->getScore() ?></td>
		<td><?php echo $score->getPenaltyTime() ?></td>
	<?php foreach($problems as $problem): ?>
		<td><?php echo $score->getScoreWithProblem($problem) ?></td>
	<?php endforeach ?>
	</tr>
<?php endfor ?>
</table>
<?php $this->display('footer.php') ?>