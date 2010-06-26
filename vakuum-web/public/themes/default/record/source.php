<?php $record_id = $this->record->getID() ?>
<?php $this->title='提交记录代码 #'.$record_id ?>
<?php $this->display('header.php') ?>

<?php $source = $this->escape($this->record->getSource()) ?>

<a href="<?php echo $this->locator->getURL('record/detail').'/'. $record_id?>">查看提交记录</a>

<textarea class="codearea" readonly="readonly">
<?php echo $source ?>
</textarea>

<?php $this->display('footer.php') ?>