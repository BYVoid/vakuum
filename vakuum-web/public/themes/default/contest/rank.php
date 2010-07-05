<?php $contest = $this->contest ?>
<?php $contest_id = $contest->getID() ?>
<?php $contest_config = $contest->getConfig() ?>
<?php $display = $contest->getRankDisplay() ?>

<?php $this->title="比赛排名 - ".$contest_config->getName() ?>
<?php $this->display('contest/header.php') ?>

<?php if ($contest->canView('list')): ?>

<table border="1">
	<tr>
		<td>名次</td>
	<?php if ($contest->canView('user')): ?>
		<td>用户昵称</td>
	<?php endif ?>
	<?php if ($contest->canView('score')): ?>
		<td>得分</td>
	<?php endif ?>
	<?php if ($contest->canView('penalty')): ?>
		<td>罚时</td>
	<?php endif ?>
	<?php if ($contest->canView('problem')): ?>
		<?php $problems = $contest_config->getProblems() ?>
		<?php foreach($problems as $problem): ?>
			<?php $problem_url = $this->locator->getURL('contest/entry').'/'. $contest_id .'/' . $problem->alias ?>
			<td><a href="<?php echo $problem_url ?>"><?php echo $problem->alias ?>(<?php echo $problem->getTitle() ?>)</a></td>
		<?php endforeach ?>
	<?php endif ?>
	</tr>
<?php $total = $contest->getContestUsersCount() ?>
<?php for ($rank = 0; $rank < $total; ++$rank): ?>
	<?php $contest_user = $contest->getRank($rank) ?>
	<tr>
		<td><?php echo $rank + 1 ?></td>
	<?php if ($contest->canView('user')): ?>
		<td><a href="<?php echo $contest_user->getUser()->getURL() ?>"><?php echo $contest_user->getUser()->getNickname() ?></a></td>
	<?php endif ?>
	<?php if ($contest->canView('score')): ?>
		<td><?php echo (int) $contest_user->getScore() ?></td>
	<?php endif ?>
	<?php if ($contest->canView('penalty')): ?>
		<td><?php echo $this->formatTimeSection($contest_user->getPenaltyTime()) ?></td>
	<?php endif ?>
	<?php if ($contest->canView('problem')): ?>
		<?php $contest_start_time = $contest_config->getContestTimeStart() ?>
		<?php foreach($problems as $problem): ?>
			<?php $record = $contest_user->getLastRecordWithProblem($problem) ?>
			<td>
			<?php if ($record != NULL): ?>
				<?php echo $this->formatTimeSection ($record->getSubmitTime() - $contest_start_time) ?>
				<?php $record_url = MDL_Locator::makeURL('contest/record',array('contest'=>$contest->getID(),'user'=>$contest_user->getUser()->getID(),'problem' => $problem->getID())) ?>
				<br /><?php echo (int) $contest_user->getScoreWithProblem($problem) ?>
				<a href="<?php echo $record_url?>">(<?php echo count($contest_user->getRecordsWithProblem($problem)) ?>)</a>
			<?php endif ?>
			</td>
		<?php endforeach ?>
	<?php endif ?>
	</tr>
<?php endfor ?>
</table>
<?php else:?>
对不起，您没有权限。
<?php endif ?>
<?php $this->display('footer.php') ?>