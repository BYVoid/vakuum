</div>

<div id="footer">
Powered by Vakuum
<small>Processed in <?php printf("%.0f",$this->getScriptExecutingTime() * 1000) ?>ms,
<?php echo $this->getDatabaseQueryCount() ?> db queries</small>
<small><?php echo $this->formatTime(time()); ?></small>
</div>

</div>

</body>
</html>
