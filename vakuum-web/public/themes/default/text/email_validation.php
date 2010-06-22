<p><?php echo $this->validation['user_name'] ?>您好！</p>

<p>这封邮件是 <a href="<?php echo $this->validation['site_address'] ?>"><?php echo $this->validation['site_name'] ?></a> 发送的。你收到这封邮件是为了验证你注册邮件地址是否有效。
<br />如果您已经通过验证了，请忽略这封邮件。</p>
<p>
请点击以下链接(或者复制到您的浏览器)来验证你的邮件地址: <br />
<a href="<?php echo $this->validation['validation_address'] ?>" target="_blank"><?php echo $this->validation['validation_address'] ?></a>
</p>

<p>如果您收到这封邮件纯属意外，请立即删除。</p>