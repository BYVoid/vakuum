<?php $this->title='提交记录代码 #'.$this->record['record_id'] ?>
<?php $this->display('header.php') ?>

<?php $record_id = $this->record['record_id'] ?>
<?php $source = $this->escape($this->record['detail']['source']) ?>

<a href="<?php echo $this->locator->getURL('record_detail').'/'. $record_id?>">提交记录查看</a>

<textarea class="codearea" readonly="readonly">
<?php echo $source ?>
</textarea>

<?php $this->display('footer.php') ?>