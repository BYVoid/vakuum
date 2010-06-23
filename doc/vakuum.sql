-- phpMyAdmin SQL Dump
-- version 3.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 06 月 23 日 11:28
-- 服务器版本: 5.1.41
-- PHP 版本: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `vakuum`
--

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_config`
--

DROP TABLE IF EXISTS `vakuum_config`;
CREATE TABLE IF NOT EXISTS `vakuum_config` (
  `cfg_key` varchar(64) NOT NULL,
  `cfg_value` text NOT NULL,
  PRIMARY KEY (`cfg_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `vakuum_config`
--

INSERT INTO `vakuum_config` (`cfg_key`, `cfg_value`) VALUES
('judger_list_page_size', '1'),
('judger_testdata', '/home/byvoid/develop/vakuum/judger/testdata/'),
('judge_allowed', '1'),
('judge_default_memory_limit', '128000'),
('judge_default_output_limit', '1280000'),
('judge_default_time_limit', '1000'),
('judge_return_key', 'vakuum-web-develop'),
('judge_return_site', 'http://vakuum-web'),
('judge_source_length_max', '102400'),
('plugin_NicEdit_upload_allow', '1'),
('plugin_NicEdit_upload_path', 'public/upload'),
('plugin_ReCaptcha_private_key', '6LeYQAwAAAAAAEsEvDuDJUHZmy5xK7xMjD7mdNx2'),
('plugin_ReCaptcha_public_key', '6LeYQAwAAAAAAEdZzsF159-HV8QjL8t5pr35EvWM'),
('problem_list_page_size', '5'),
('record_list_page_size', '10'),
('register_allowed', '1'),
('register_captcha', '0'),
('register_email_validate', '1'),
('register_form_restrict', 'a:6:{s:9:"user_name";a:2:{s:10:"length_min";s:1:"5";s:10:"length_max";s:2:"16";}s:13:"user_password";a:2:{s:10:"length_min";s:1:"5";s:10:"length_max";s:2:"32";}s:8:"nickname";a:2:{s:10:"length_min";s:1:"2";s:10:"length_max";s:2:"16";}s:5:"email";a:1:{s:10:"length_max";s:2:"64";}s:7:"website";a:1:{s:10:"length_max";s:3:"256";}s:4:"memo";a:1:{s:10:"length_max";s:3:"512";}} '),
('root_path', '/'),
('root_path_prefix', 'index.php/'),
('site_address', ''),
('site_name', 'BYVoid個人評測系統'),
('theme', 'default'),
('time_format', 'Y-m-d H:i:s'),
('time_zone', 'Asia/Shanghai'),
('user_email_display', '0'),
('user_list_page_size', '10');

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_judger`
--

DROP TABLE IF EXISTS `vakuum_judger`;
CREATE TABLE IF NOT EXISTS `vakuum_judger` (
  `judger_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judger_name` varchar(32) NOT NULL,
  `judger_priority` int(11) NOT NULL,
  `judger_enabled` tinyint(1) NOT NULL,
  `judger_available` tinyint(1) NOT NULL,
  `judger_count` int(11) NOT NULL,
  `judger_config` text NOT NULL,
  PRIMARY KEY (`judger_id`),
  UNIQUE KEY `judger_id` (`judger_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `vakuum_judger`
--

INSERT INTO `vakuum_judger` (`judger_id`, `judger_name`, `judger_priority`, `judger_enabled`, `judger_available`, `judger_count`, `judger_config`) VALUES
(1, 'local', 0, 1, 1, 21, '<?xml version="1.0" encoding="utf-8"?>\n<document>\n	<url>http://vakuum-judge</url>\n	<public_key>b0e42c203dee5b5badb766db8ab4622b</public_key>\n	<upload>share</upload>\n	<share>\n		<path>\n			<task>/home/byvoid/develop/vakuum/judger/task/</task>\n			<testdata>/home/byvoid/develop/vakuum/judger/testdata/</testdata>\n		</path>\n	</share>\n</document>\n');

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_prob`
--

DROP TABLE IF EXISTS `vakuum_prob`;
CREATE TABLE IF NOT EXISTS `vakuum_prob` (
  `prob_id` int(10) unsigned NOT NULL,
  `prob_name` varchar(32) NOT NULL,
  `prob_title` varchar(32) NOT NULL,
  `prob_content` text NOT NULL,
  PRIMARY KEY (`prob_id`),
  UNIQUE KEY `prob_name` (`prob_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `vakuum_prob`
--

INSERT INTO `vakuum_prob` (`prob_id`, `prob_name`, `prob_title`, `prob_content`) VALUES
(1001, '1001', '锯木场选址', '<span style="font-weight: bold;">描述</span><br>\r\n<br>\r\n从山顶上到山底下沿着一条直线种植了n棵老树。当地的政府决定把他们砍下来。为了不浪费任何一棵木材，树被砍倒后要运送到锯木厂。木材只能按照一个方向运\r\n输：朝山下运。山脚下有一个锯木厂。另外两个锯木厂将新修建在山路上。你必须决定在哪里修建两个锯木厂，使得传输的费用总和最小。假定运输每公斤木材每米\r\n需要一分钱。请你计算最小运输费用。<span style="font-weight: bold;"></span><br>\r\n<br>\r\n<span style="font-weight: bold;">输入</span><br>\r\n<br>\r\n输入的第一行为一个正整数n——树的个数（2≤n≤20 000）。树从山顶到山脚按照1，2……n标号。<br>\r\n接下来n行，每行有两个正整数（用空格分开）。<br>\r\n第i+1行含有：wi——第i棵树的重量（公斤为单位）和 di——第i棵树和第i+1棵树之间的距离，1≤wi ≤10 000，0≤di≤10 \r\n000。最后一个数dn，表示第n棵树到山脚的锯木厂的距离。<br>\r\n保证所有树运到山脚的锯木厂所需要的费用小于2000 000 000分。<br>\r\n<br>\r\n<span style="font-weight: bold;">输出</span><br>\r\n<br>\r\n输出只有一行一个数：最小的运输费用。<br>\r\n<br>\r\n<span style="font-weight: bold;">样例输入</span><br>\r\n<br>\r\n9<br>\r\n1 2<br>\r\n2 1<br>\r\n3 3<br>\r\n1 1<br>\r\n3 2<br>\r\n1 6<br>\r\n2 1<br>\r\n1 2<br>\r\n1 1<br>\r\n<br>\r\n样例输出<br>\r\n26<br>\r\n<br>');

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_probmeta`
--

DROP TABLE IF EXISTS `vakuum_probmeta`;
CREATE TABLE IF NOT EXISTS `vakuum_probmeta` (
  `pmeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pmeta_prob_id` bigint(20) unsigned NOT NULL,
  `pmeta_key` varchar(64) NOT NULL,
  `pmeta_value` text NOT NULL,
  PRIMARY KEY (`pmeta_id`),
  UNIQUE KEY `pmeta_id` (`pmeta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `vakuum_probmeta`
--

INSERT INTO `vakuum_probmeta` (`pmeta_id`, `pmeta_prob_id`, `pmeta_key`, `pmeta_value`) VALUES
(1, 1001, 'adding_time', '1277212369'),
(2, 1001, 'data_config', '<?xml version="1.0" encoding="utf-8"?>\n<document>\n	<name>1001</name>\n	<input>stdin</input>\n	<output>stdout</output>\n	<checker>\n		<name>std_checker</name>\n		<type>standard</type>\n	</checker>\n	<time_limit>1000</time_limit>\n	<memory_limit>128000</memory_limit>\n	<output_limit>128000</output_limit>\n	<case>\n		<input>two0.in</input>\n		<output>two0.out</output>\n	</case>\n	<case>\n		<input>two1a.in</input>\n		<output>two1a.out</output>\n	</case>\n	<case>\n		<input>two1b.in</input>\n		<output>two1b.out</output>\n	</case>\n	<case>\n		<input>two1b.in</input>\n		<output>two1b.out</output>\n	</case>\n	<case>\n		<input>two2.in</input>\n		<output>two2.out</output>\n	</case>\n	<case>\n		<input>two3.in</input>\n		<output>two3.out</output>\n	</case>\n	<case>\n		<input>two4.in</input>\n		<output>two4.out</output>\n	</case>\n	<case>\n		<input>two5.in</input>\n		<output>two5.out</output>\n	</case>\n	<case>\n		<input>two6.in</input>\n		<output>two6.out</output>\n	</case>\n	<case>\n		<input>two7.in</input>\n		<output>two7.out</output>\n	</case>\n	<case>\n		<input>two8.in</input>\n		<output>two8.out</output>\n	</case>\n	<case>\n		<input>two9.in</input>\n		<output>two9.out</output>\n	</case>\n	<case>\n		<input>two10.in</input>\n		<output>two10.out</output>\n	</case>\n	<version>1277218830</version>\n</document>\n'),
(3, 1001, 'display', '1'),
(4, 1001, 'verified', '1');

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_record`
--

DROP TABLE IF EXISTS `vakuum_record`;
CREATE TABLE IF NOT EXISTS `vakuum_record` (
  `record_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `record_prob_id` bigint(20) unsigned NOT NULL,
  `record_user_id` bigint(20) unsigned NOT NULL,
  `record_judger_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `record_id` (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `vakuum_record`
--

INSERT INTO `vakuum_record` (`record_id`, `record_prob_id`, `record_user_id`, `record_judger_id`) VALUES
(2, 1001, 1, 1),
(3, 1001, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_recordmeta`
--

DROP TABLE IF EXISTS `vakuum_recordmeta`;
CREATE TABLE IF NOT EXISTS `vakuum_recordmeta` (
  `rmeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rmeta_record_id` bigint(20) unsigned NOT NULL,
  `rmeta_key` varchar(64) NOT NULL,
  `rmeta_value` text NOT NULL,
  PRIMARY KEY (`rmeta_id`),
  UNIQUE KEY `rmeta_id` (`rmeta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `vakuum_recordmeta`
--

INSERT INTO `vakuum_recordmeta` (`rmeta_id`, `rmeta_record_id`, `rmeta_key`, `rmeta_value`) VALUES
(9, 2, 'lang', 'cpp'),
(10, 2, 'status', '3'),
(11, 2, 'result_text', '0'),
(12, 2, 'submit_time', '1277217745'),
(13, 2, 'memory', '968\n'),
(14, 2, 'time', '56'),
(15, 2, 'source', '/* \r\n * Problem: CEOI2004 two\r\n * Author: Guo Jiabao\r\n * Time: 2009.7.9 22:40\r\n * State: Solved\r\n * Memo: 动态规划 凸完全单调性优化\r\n*/\r\n#include <iostream>\r\n#include <cstdio>\r\n#include <cstdlib>\r\n#include <cmath>\r\n#include <cstring>\r\nusing namespace std;\r\nconst int MAXN=20001,INF=~0U>>1;\r\nstruct MonoQueue\r\n{\r\n	int x,y,i;\r\n}MQ[MAXN];\r\nint dist[MAXN],weight[MAXN],A[MAXN],B[MAXN],Y[MAXN],F[MAXN];\r\nint N,Ans,Top;\r\n\r\nvoid init()\r\n{\r\n	int i,d;\r\n	scanf("%d",&N);\r\n	N++;\r\n	for (i=1;i<N;i++)\r\n	{\r\n		scanf("%d%d",&weight[i],&d);\r\n		dist[i+1] = dist[i] + d;\r\n		A[i] = A[i-1] + weight[i];\r\n		B[i] = B[i-1] + weight[i] * dist[i];\r\n	}\r\n	Ans = INF;\r\n}\r\ninline int W(int i,int j)\r\n{\r\n	return dist[i] * (A[i-1] - A[j-1]) - (B[i-1] - B[j-1]);\r\n}\r\nvoid MQ_ins(int i,int x,int y)\r\n{\r\n	long long x1,y1,x2,y2,x3,y3;\r\n	double k1,k2;\r\n	for (;Top>1;)\r\n	{\r\n		x1 = MQ[Top-1].x;\r\n		y1 = MQ[Top-1].y;\r\n		x2 = MQ[Top].x;\r\n		y2 = MQ[Top].y;\r\n		x3 = x;\r\n		y3 = y;\r\n		k2 = double(y3-y2)/(x3-x2);\r\n		k1 = double(y2-y1)/(x2-x1);\r\n		if (k1 > k2)\r\n			break;\r\n		Top--;\r\n	}\r\n	MQ[++Top].x = x;\r\n	MQ[Top].y = y;\r\n	MQ[Top].i = i;\r\n}\r\ninline bool adapt(int i,int k)\r\n{\r\n	int j = MQ[k + 1].i;\r\n	k = MQ[k].i;\r\n	return A[i] * (dist[k] - dist[j]) > Y[k] - Y[j];\r\n}\r\nvoid solve()\r\n{\r\n	int i,j,k;\r\n	for (i=N;i>=1;i--)\r\n	{\r\n		Y[i] = dist[i] * A[i-1] - B[i-1] - A[i] * dist[N] + B[i];\r\n		MQ_ins(i,dist[i],Y[i]);\r\n	}\r\n	for (i=N-2,j=1;i>=1;i--)\r\n	{\r\n		while (!adapt(i,j))\r\n			j++;\r\n		k = MQ[j].i;\r\n		F[i] = W(i,1) + W(k,i+1) + W(N,k+1);\r\n		//printf("F[%d] = %d (%d)\\n",i,F[i],k);\r\n		if (F[i] < Ans)\r\n			Ans = F[i];\r\n	}\r\n}\r\nint main()\r\n{\r\n	init();\r\n	solve();\r\n	printf("%d\\n",Ans);\r\n	return 0;\r\n}\r\n'),
(16, 2, 'score', '1'),
(17, 2, 'result', '<?xml version="1.0" encoding="utf-8"?>\n<document>\n	<compile>\n		<result>0</result>\n		<option>0</option>\n		<compiler_message>vkm_2.cpp: In function ''void init()'':\nvkm_2.cpp:25: warning: ignoring return value of ''int scanf(const char*, ...)'', declared with attribute warn_unused_result\nvkm_2.cpp:29: warning: ignoring return value of ''int scanf(const char*, ...)'', declared with attribute warn_unused_result\n</compiler_message>\n	</compile>\n	<execute>\n		<case>\n			<case_id>1</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>2</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>3</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>4</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>5</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>6</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>7</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>4</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>8</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>4</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>9</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>8</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>10</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>8</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>11</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>8</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>12</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>16</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>13</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>8</time_used>\n			<memory_used>968\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n	</execute>\n</document>\n'),
(18, 3, 'lang', 'cpp'),
(19, 3, 'status', '3'),
(20, 3, 'result_text', '2'),
(21, 3, 'submit_time', '1277218450'),
(22, 3, 'memory', '576\n'),
(23, 3, 'time', '7112'),
(24, 3, 'source', '/* \n * Problem: CEOI2004 two\n * Author: Guo Jiabao\n * Time: 2009.7.9 20:35\n * State: Unsolved\n * Memo: 动态规划 朴素\n*/\n#include <iostream>\n#include <cstdio>\n#include <cstdlib>\n#include <cmath>\n#include <cstring>\nusing namespace std;\nconst int MAXN=20001,INF=~0U>>1;\nint dist[MAXN],weight[MAXN],A[MAXN],B[MAXN];\nint N,Ans,Top;\n\nvoid init()\n{\n	int i,d;\n	scanf("%d",&N);\n	N++;\n	for (i=1;i<N;i++)\n	{\n		scanf("%d%d",&weight[i],&d);\n		dist[i+1] = dist[i] + d;\n		A[i] = A[i-1] + weight[i];\n		B[i] = B[i-1] + weight[i] * dist[i];\n	}\n	Ans = INF;\n}\ninline int W(int i,int j)\n{\n	return dist[i] * (A[i-1] - A[j-1]) - (B[i-1] - B[j-1]);\n}\nvoid simple()\n{\n	int i,j,d,t,tj;\n	for (i=1;i<N-1;i++)\n	{\n		t = INF;\n		for (j=i+1;j<N;j++)\n		{\n			d = W(i,1) + W(j,i+1) + W(N,j+1);\n			if (d < t)\n			{\n				t = d;\n				tj =j;\n			}\n		}\n		//printf("F[%d] = %d (%d)\\n",i,t,tj);\n		if (t < Ans)\n			Ans = t;\n	}\n}\nint main()\n{\n	init();\n	simple();\n	printf("%d\\n",Ans);\n	return 0;\n}\n'),
(25, 3, 'score', '0.53846153846154'),
(26, 3, 'result', '<?xml version="1.0" encoding="utf-8"?>\n<document>\n	<compile>\n		<result>0</result>\n		<option>0</option>\n		<compiler_message>vkm_3.cpp: In function ''void init()'':\nvkm_3.cpp:21: warning: ignoring return value of ''int scanf(const char*, ...)'', declared with attribute warn_unused_result\nvkm_3.cpp:25: warning: ignoring return value of ''int scanf(const char*, ...)'', declared with attribute warn_unused_result\n</compiler_message>\n	</compile>\n	<execute>\n		<case>\n			<case_id>1</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>2</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>3</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>4</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>5</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>0</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>6</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>4</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>7</case_id>\n			<result>0</result>\n			<option>0</option>\n			<time_used>4</time_used>\n			<memory_used>576\n</memory_used>\n			<score>1.00</score>\n			<check_message>Correct.</check_message>\n		</case>\n		<case>\n			<case_id>8</case_id>\n			<result>2</result>\n			<option>0</option>\n			<time_used>1172</time_used>\n			<memory_used>572\n</memory_used>\n			<score>0</score>\n			<check_message></check_message>\n		</case>\n		<case>\n			<case_id>9</case_id>\n			<result>2</result>\n			<option>0</option>\n			<time_used>1112</time_used>\n			<memory_used>572\n</memory_used>\n			<score>0</score>\n			<check_message></check_message>\n		</case>\n		<case>\n			<case_id>10</case_id>\n			<result>2</result>\n			<option>0</option>\n			<time_used>1300</time_used>\n			<memory_used>572\n</memory_used>\n			<score>0</score>\n			<check_message></check_message>\n		</case>\n		<case>\n			<case_id>11</case_id>\n			<result>2</result>\n			<option>0</option>\n			<time_used>1200</time_used>\n			<memory_used>572\n</memory_used>\n			<score>0</score>\n			<check_message></check_message>\n		</case>\n		<case>\n			<case_id>12</case_id>\n			<result>2</result>\n			<option>0</option>\n			<time_used>1248</time_used>\n			<memory_used>572\n</memory_used>\n			<score>0</score>\n			<check_message></check_message>\n		</case>\n		<case>\n			<case_id>13</case_id>\n			<result>2</result>\n			<option>0</option>\n			<time_used>1072</time_used>\n			<memory_used>572\n</memory_used>\n			<score>0</score>\n			<check_message></check_message>\n		</case>\n	</execute>\n</document>\n');

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_user`
--

DROP TABLE IF EXISTS `vakuum_user`;
CREATE TABLE IF NOT EXISTS `vakuum_user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(16) NOT NULL,
  `user_nickname` varchar(32) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `vakuum_user`
--

INSERT INTO `vakuum_user` (`user_id`, `user_name`, `user_nickname`, `user_password`) VALUES
(1, 'byvoid', 'byvoid', '5dc4eca0317e995684a31112eb7fbbd9');

-- --------------------------------------------------------

--
-- 表的结构 `vakuum_usermeta`
--

DROP TABLE IF EXISTS `vakuum_usermeta`;
CREATE TABLE IF NOT EXISTS `vakuum_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `umeta_user_id` bigint(20) unsigned NOT NULL,
  `umeta_key` varchar(64) NOT NULL,
  `umeta_value` text NOT NULL,
  PRIMARY KEY (`umeta_id`),
  UNIQUE KEY `umeta_id` (`umeta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `vakuum_usermeta`
--

INSERT INTO `vakuum_usermeta` (`umeta_id`, `umeta_user_id`, `umeta_key`, `umeta_value`) VALUES
(1, 1, 'email', 'byvoid.kcp@gmail.com'),
(2, 1, 'website', 'http://www.byvoid.com'),
(3, 1, 'memo', '我很懒，什么也不写。'),
(4, 1, 'register_time', '1277211373'),
(5, 1, 'identity', 'general,administrator');

