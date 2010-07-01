<?php $this->title='首页' ?>
<?php $this->display('header.php') ?>

<div style="padding:10px; margin:10px;">
<?php
$cache = MDL_Cache::getInstance();
if (isset($cache->index_page))
	$contents = $cache->index_page;
else
{
	$contents = BFL_HTTP::fetch("http://code.google.com/p/vakuum-oj/");
	preg_match('/<div id="wikicontent" style="padding:0 3em 1.2em 0">(.+?)<\/div>/ies', $contents, $match);
	$contents = str_replace('&para;','',$match[0]);
	$cache->set('index_page', $contents, 3600);
}
echo $contents;
?>
</div>

<?php $this->display('footer.php') ?>