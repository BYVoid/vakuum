<?php $this->title="数据设置" ?>
<?php $this->display('header.php') ?>

<p><a href="<?php echo $this->locator->getURL('admin_problem_list')?>">返回题目管理</a></p>

<form action="<?php echo $this->locator->getURL('admin_problem_doedit') ?>" method="post">
<table>
	<tr>
		<td>
			<table border="1">
				<caption>基本信息</caption>
				<tr>
					<td>标题</td>
					<td><input type="text" size="30" value="<?php echo $this->escape($this->data_config['title']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>ID</td>
					<td><input type="text" name="id" size="30" value="<?php echo $this->escape($this->data_config['id']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>名称</td>
					<td><input type="text" name="name" size="30" value="<?php echo $this->escape($this->data_config['name']) ?>" readonly="readonly" /></td>
				</tr>
				<tr>
					<td>输入</td>
					<td><input type="text" name="input" size="30" value="<?php echo $this->escape($this->data_config['input']) ?>" /></td>
				</tr>
				<tr>
					<td>输出</td>
					<td><input type="text" name="output" size="30" value="<?php echo $this->escape($this->data_config['output']) ?>" /></td>
				</tr>
				<tr>
					<td>比较程序</td>
					<td>
						<table id="checker">
							<tr>
								<td>文件名</td>
								<td><input type="text" name="checker[name]" size="20" value="<?php echo $this->escape($this->data_config['checker']['name']) ?>" /></td>
							</tr>
							<tr>
								<td>类型</td>
								<td>
									标准 <input type="radio" name="checker[type]" onclick="toggleCheckerCustom(0)" value="standard" />
									自定义 <input type="radio" name="checker[type]" onclick="toggleCheckerCustom(1)" value="custom" />
								</td>
							</tr>
							<tr>
								<td>源文件</td>
								<td><input type="text" name="checker[custom][source]" size="20" value="<?php echo $this->escape($this->data_config['checker']['custom']['source']) ?>" /></td>
							</tr>
							<tr>
								<td>语言</td>
								<td>
									<select name="checker[custom][language]">
										<option value="">不编译</option>
										<option value="cpp">C++</option>
										<option value="c">C</option>
										<option value="pas">Pascal</option>
									</select>
								</td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td>附加文件</td>
					<td>
						<table id="addtional_files">
							<tr><td><input id="btnAddAditionalFile" type="button" value="添加附加文件" onclick="addAditionalFile()"/></td></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table border="1">
				<caption>全局限制</caption>
				<tr>
					<td>时间限制(ms)</td>
					<td><input type="text" name="time_limit" size="30" value="<?php echo $this->escape($this->data_config['time_limit']) ?>"  /></td>
				</tr>
				<tr>
					<td>内存限制(KB)</td>
					<td><input type="text" name="memory_limit" size="30" value="<?php echo $this->escape($this->data_config['memory_limit']) ?>" /></td>
				</tr>
				<tr>
					<td>输出限制(KB)</td>
					<td><input type="text" name="output_limit" size="30" value="<?php echo $this->escape($this->data_config['output_limit']) ?>" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table>
				<caption>测试数据</caption>
				<tr>
					<td><input id="btnAddCase" type="button" value="添加测试点" onclick="addCase()" /> </td>
				</tr>
				<tr>
					<td>
						<table id="testcases" border="1">
							<tr>
								<td>输入文件</td>
								<td>输出文件</td>
								<td>时间限制(ms)</td>
								<td>内存限制(KB)</td>
								<td>输出限制(KB)</td>
								<td>操作</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="hidden" name="data" value="data" />
<input type="submit" value="提交" />
</form>

<div style="display: none;" id="af_template">
<input type="text" name="additional_file[]" size="20" value="" /><input type="button" onclick="delAditionalFile(this)" value="删除" />
</div>

<div style="display: none;" id="case_template">
<table>
	<tr>
		<td><input type="text" name="case_input[]" size="20" value="" /></td>
		<td><input type="text" name="case_output[]" size="20" value=""  /></td>
		<td><input type="text" name="case_time_limit[]" size="10" value=""  /></td>
		<td><input type="text" name="case_memory_limit[]" size="10" value=""  /></td>
		<td><input type="text" name="case_output_limit[]" size="10" value=""  /></td>
		<td><input type="button" value="删除该测试点" onclick="delCase(this)"/> </td>
	</tr>
</table>
</div>

<script language="javascript">
function toggleCheckerCustom(option)
{
	if (option)
		$('#checker tr:gt(1)').show(0);
	else
		$('#checker tr:gt(1)').hide(0);
}

function addAditionalFile()
{
	$('#addtional_files tbody').append('<tr><td>'+$('#af_template').html()+'</td></tr>');
	if (arguments.length > 0)
		$('#addtional_files input:text').val(arguments[0]);
}

function delAditionalFile(obj)
{
	$(obj.parentNode.parentNode).remove();
}

function addCase()
{
	last_textboxs=$('#testcases > tbody > tr:last input:text');
	$('#testcases > tbody').append($('#case_template tbody').html());
	textboxs=$('#testcases > tbody > tr:last input:text');
	for (i=0;i<5 && i<arguments.length;i++)
		$(textboxs[i]).val(arguments[i]);
	
	if (arguments.length == 0 && last_textboxs[0] != undefined)
	{
		for (i=0;i<2;i++)
		{
			str = $(last_textboxs[i]).val();
			for (j=str.length-1;j>=0;j--)
			{
				if (str[j] >= '0' && str[j] <= '9')
				{
					for (k=j-1;k>=0;k--)
					{
						if (!(str[k] >= '0' && str[k] <= '9'))
						{
							num = str.substr(k+1,j-k);
							break;
						}
					}
					num = Number(num) + 1;
					str = str.substr(0,k+1) + num + str.substr(j+1);
					break;
				}
			}
			$(textboxs[i]).val(str);
		}
	}
}

function delCase(obj)
{
	$(obj.parentNode.parentNode).remove();
}

$(document).ready(function()
{
	checker_type = $('#checker :radio[value="<?php echo $this->escape($this->data_config['checker']['type']) ?>"]');
	checker_type.attr('checked','checked');
	toggleCheckerCustom(checker_type.val()=='custom');

	checker_language = $('#checker option[value="<?php echo $this->escape($this->data_config['checker']['custom']['language']) ?>"]');
	checker_language.attr('selected','selected');

	<?php foreach($this->data_config['additional_file'] as $item): ?>
	addAditionalFile("<?php echo $this->escape($item) ?>");
	<?php endforeach; ?>

	<?php foreach($this->data_config['case'] as $item): ?>
	addCase("<?php echo $this->escape($item['input']) ?>","<?php echo $this->escape($item['output']) ?>","<?php echo $this->escape(isset($item['time_limit'])?$item['time_limit']:'') ?>","<?php echo $this->escape(isset($item['memory_limit'])?$item['memory_limit']:'') ?>","<?php echo $this->escape(isset($item['output_limit'])?$item['output_limit']:'') ?>");
	<?php endforeach; ?>
});


</script>

<?php $this->display('footer.php') ?>
