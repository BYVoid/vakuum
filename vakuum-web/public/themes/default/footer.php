</div>

<div id="footer">
<p>Server Time: <?php echo $this->formatTime(time()); ?></p>
<p>Script Executing Time: <?php printf("%.0f",$this->getScriptExecutingTime() * 1000) ?>ms</p>
<p>Database Query: <?php echo $this->getDatabaseQueryCount() ?></p>
</div>

</div>

</body>
</html>
