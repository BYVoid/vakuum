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
<?php $rank = 0 ?>
<?php foreach($contest->getSignUpUsers() as $user): ?>
	<tr>
		<td><?php echo ++$rank ?></td>
		<td><?php echo $user->getNickname() ?></td>
		<td><?php echo $contest->getUserScore($user->getID()) ?></td>
		<td><?php echo $contest->getPenaltyTime($user->getID()) ?></td>
	<?php foreach($problems as $problem): ?>
		<?php $record = $contest->getUserLastRecordWithProblem($user->getID(), $problem->getID()) ?>
		<td><?php echo $record->getSubmitTime() ?></td>
	<?php endforeach ?>
	</tr>
<?php endforeach ?>
</table>
<?php $this->display('footer.php') ?>