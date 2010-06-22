</div><!-- content -->

</div><!-- body -->

<div id="footer">
	<div id="pageinfo">
		<?php printf("%.0f",$this->getScriptExecutingTime() * 1000) ?> ms
		<?php echo $this->getDatabaseQueryCount() ?> queries
		<?php echo $this->formatTime(time()); ?>
	</div>
	<div id="copyright">
		Vakuum Online Judge
		by <a href="http://www.byvoid.com" target="_blank">BYVoid</a>
		hosted on <a href="http://vakuum-oj.googlecode.com/" target="_blank">Google Code</a>
	</div>
</div>

</div><!-- wrapper -->

</body>
</html>
