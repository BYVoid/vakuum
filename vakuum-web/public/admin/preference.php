<?php $this->title='参数设置' ?>
<?php $this->display('header.php') ?>

<form method="post" action="<?php echo $this->locator->getURL('admin_preference_doedit') ?>?action=preferences">
<table>
	<tr>
		<td style="width:10em"><h2>基本设置</h2></td>
		<td style="width:14em"></td>
		<td></td>
	</tr>
	<tr>
		<td>站点名称</td>
		<td><?php echo $this->showInputbox($this->preferences['site_name'],'site_name') ?></td>
		<td>输入站点的名称。</td>
	</tr>
	<tr>
		<td>站点域名</td>
		<td><?php echo $this->showInputbox($this->preferences['site_address'],'site_address') ?></td>
		<td>如果不希望绑定域名（或IP）地址，请留空。</td>
	</tr>
	<tr>
		<td>Vakuum根路径</td>
		<td><?php echo $this->showInputbox($this->preferences['root_path'],'root_path') ?></td>
		<td>域名到首页地址之间的部分。</td>
	</tr>
	<tr>
		<td>Vakuum路径前缀</td>
		<td><?php echo $this->showInputbox($this->preferences['root_path_prefix'],'root_path_prefix') ?></td>
		<td>如果不使用路径重写，请设置为"index.php/"。</td>
	</tr>
	<tr>
		<td><h2>样式设置</h2></td>
	</tr>
	<tr>
		<td>主题</td>
		<td><?php echo $this->showInputbox($this->preferences['theme'],'theme') ?></td>
		<td>主题的名称。</td>
	</tr>
	<tr>
		<td>默认时区</td>
		<td><?php echo $this->showInputbox($this->preferences['time_zone'],'time_zone') ?></td>
		<td>如果不知道如何填写，请参阅<a href="http://php.net/manual/en/timezones.php" target="_blank">Timezone</a>。</td>
	</tr>
	<tr>
		<td>时间格式</td>
		<td><?php echo $this->showInputbox($this->preferences['time_format'],'time_format') ?></td>
		<td>预览:<?php echo date($this->preferences['time_format'],time()) ?>。每个字母的含义参见<a href="http://php.net/manual/en/function.date.php" target="_blank">date</a>。</td>
	</tr>
	<tr>
		<td>用户列表分页长度</td>
		<td><?php echo $this->showInputbox($this->preferences['problem_list_page_size'],'problem_list_page_size') ?></td>
		<td>用户列表默认的每一页记录的数目。</td>
	</tr>
	<tr>
		<td>题目列表分页长度</td>
		<td><?php echo $this->showInputbox($this->preferences['user_list_page_size'],'user_list_page_size') ?></td>
		<td>题目列表默认的每一页记录的数目。</td>
	</tr>
	<tr>
		<td>记录列表分页长度</td>
		<td><?php echo $this->showInputbox($this->preferences['record_list_page_size'],'record_list_page_size') ?></td>
		<td>记录列表默认的每一页记录的数目。</td>
	</tr>
	<tr>
		<td>评测机列表分页长度</td>
		<td><?php echo $this->showInputbox($this->preferences['judger_list_page_size'],'judger_list_page_size') ?></td>
		<td>评测机列表默认的每一页记录的数目。</td>
	</tr>
	<tr>
		<td><h2>用户设置</h2></td>
	</tr>
	<tr>
		<td>允许注册</td>
		<td><?php echo $this->showCheckbox($this->preferences['register_allowed'],'register_allowed') ?></td>
		<td>用户可以注册。</td>
	</tr>
	<tr>
		<td>使用验证码</td>
		<td><?php echo $this->showCheckbox($this->preferences['register_captcha'],'register_captcha') ?></td>
		<td>用户注册时需要输入<a href="http://recaptcha.net/" target="_blank">reCaptcha</a>验证码以进行图灵测试。</td>
	</tr>
	<tr>
		<td>邮件验证</td>
		<td><?php echo $this->showCheckbox($this->preferences['register_email_validate'],'register_email_validate') ?></td>
		<td>用户在注册或更改邮箱地址时，需要对发邮件进行验证。</td>
	</tr>
	<tr>
		<td>用户Email显示</td>
		<td>
			<fieldset>
				<?php echo $this->showRadio(0,$this->preferences['user_email_display'] == 0,"user_email_display") ?>完全隐藏<br />
				<?php echo $this->showRadio(1,$this->preferences['user_email_display'] == 1,"user_email_display") ?>完全显示<br />
				<?php echo $this->showRadio(2,$this->preferences['user_email_display'] == 2,"user_email_display") ?>显示但需验证码<br />
			</fieldset>
		</td>
		<td>在用户信息页面显示用户的Email地址。</td>
	</tr>
	<tr>
		<td><h2>题目设置</h2></td>
	</tr>
	<tr>
		<td>允许提交</td>
		<td><?php echo $this->showCheckbox($this->preferences['judge_allowed'],'judge_allowed') ?></td>
		<td>决定用户是否可以提交评测。</td>
	</tr>
	<tr>
		<td>代码长度限制</td>
		<td><?php echo $this->showInputbox($this->preferences['judge_source_length_max'],'judge_source_length_max') ?></td>
		<td>用户提交的代码长度限制，单位字节(Byte)。</td>
	</tr>
	<tr>
		<td>默认时间限制</td>
		<td><?php echo $this->showInputbox($this->preferences['judge_default_time_limit'],'judge_default_time_limit') ?></td>
		<td>题目时间限制缺省时默认的时间限制，单位毫秒(ms)。</td>
	</tr>
	<tr>
		<td>默认内存限制</td>
		<td><?php echo $this->showInputbox($this->preferences['judge_default_memory_limit'],'judge_default_memory_limit') ?></td>
		<td>题目内存限制缺省时默认的内存限制，单位千字节(KB)。</td>
	</tr>
	<tr>
		<td>默认输出限制</td>
		<td><?php echo $this->showInputbox($this->preferences['judge_default_output_limit'],'judge_default_output_limit') ?></td>
		<td>题目输出限制缺省时默认的输出限制，单位千字节(KB)。</td>
	</tr>
	<tr>
		<td><h2>评测设置</h2></td>
	</tr>
	<tr>
		<td>记录返回地址</td>
		<td><?php echo $this->showInputbox($this->preferences['judge_return_site'],'judge_return_site') ?></td>
		<td>评测机返回信号时访问的地址。</td>
	</tr>
	<tr>
		<td>连接密钥</td>
		<td><?php echo $this->showInputbox($this->preferences['judge_return_key'],'judge_return_key') ?></td>
		<td>评测机返回信号时访问的验证密钥。</td>
	</tr>
	<tr>
		<td>测试数据路径</td>
		<td><?php echo $this->showInputbox($this->preferences['judger_testdata'],'judger_testdata') ?></td>
		<td>服务器上存储测试数据文件的路径。</td>
	</tr>
</table>

<input type="submit" value="提交修改" />

</form>
<?php $this->display('footer.php') ?>