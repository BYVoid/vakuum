<?php
$user = $this->user;
$user_info = $user->getInfo();
$this->title = '用户 - ' . $user->getNickname();
?>
<?php $this->display('header.php') ?>

<table border="1">
	<tr>
		<td>ID</td>
		<td><?php echo $user->getID() ?></td>
	</tr>
	<tr>
		<td>用户名</td>
		<td><?php echo $this->escape($user->getName()) ?></td>
	</tr>
	<tr>
		<td>昵称</td>
		<td><?php echo $this->escape($user->getNickname()) ?></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><?php echo reCaptcha::showEmail($user_info['email']) ?></td>
	</tr>
	<tr>
		<td>个人网站</td>
		<td><a href="<?php echo $this->escape($user_info['website']) ?>" target="_blank" rel="nofollow"><?php echo $this->escape($user_info['website']) ?></a></td>
	</tr>
	<tr>
		<td>身份</td>
		<td><?php echo $this->escape($user_info['identity']) ?></td>
	</tr>
	<tr>
		<td>备注</td>
		<td><?php echo $this->escape($user_info['memo']) ?></td>
	</tr>
</table>

<?php echo gravatar::showImage($user_info['email']) ?>

<?php $user_record = $user->getRecord() ?>
<p>该用户从<?php echo $this->formatTime($user_info['register_time']) ?>起，提交了 <?php echo $user_record->getRecordsCount() ?> 次，通过了 <?php echo $user_record->getAcceptedProblemsCount() ?> 道题，通过率为 <?php printf('%.2lf', $user_record->getAcceptedRate()*100) ?>%。</p>
<p>通过的题目</p>
<ul>
<?php foreach($user_record->getAcceptedLastRecords() as $record): ?>
	<li>
		<a href="<?php echo $record->getURL() ?>"><?php echo $record->getProblem()->getTitle() ?></a>(<a href="<?php echo $record->getProblem()->getURL() ?>">#</a>)
	</li>
<?php endforeach ?>
</ul>
<?php $this->display('footer.php') ?>