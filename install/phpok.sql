-- phpMyAdmin SQL Dump
-- version 4.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-01-12 16:05:56
-- 服务器版本： 5.5.20
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpok`
--

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_address`
--

DROP TABLE IF EXISTS `qinggan_address`;
CREATE TABLE IF NOT EXISTS `qinggan_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `country` varchar(255) NOT NULL COMMENT '国家',
  `province` varchar(255) NOT NULL COMMENT '省信息',
  `city` varchar(255) NOT NULL COMMENT '市',
  `county` varchar(255) NOT NULL COMMENT '县',
  `address` varchar(255) NOT NULL COMMENT '地址信息（不含国家，省市县镇区信息）',
  `zipcode` varchar(20) NOT NULL COMMENT '邮编',
  `type_id` enum('shipping','billing') NOT NULL DEFAULT 'shipping' COMMENT '类型，默认走送货地址',
  `mobile` varchar(100) NOT NULL COMMENT '手机号码',
  `tel` varchar(100) NOT NULL COMMENT '电话号码',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `fullname` varchar(100) NOT NULL COMMENT '联系人姓名',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0普通，1默认填写',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0女1男',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员地址库' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_adm`
--

DROP TABLE IF EXISTS `qinggan_adm`;
CREATE TABLE IF NOT EXISTS `qinggan_adm` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID，系统自增',
  `account` varchar(50) NOT NULL COMMENT '管理员账号',
  `pass` varchar(100) NOT NULL COMMENT '管理员密码',
  `email` varchar(50) NOT NULL COMMENT '管理员邮箱',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未审核1正常2管理员锁定',
  `if_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统管理员',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员信息' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `qinggan_adm`
--

INSERT INTO `qinggan_adm` (`id`, `account`, `pass`, `email`, `status`, `if_system`) VALUES
(1, 'admin', 'd993bcfdd033249281ee4a0e64d3cf26:32', 'qinggan@188.com', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_adm_popedom`
--

DROP TABLE IF EXISTS `qinggan_adm_popedom`;
CREATE TABLE IF NOT EXISTS `qinggan_adm_popedom` (
  `id` int(10) unsigned NOT NULL COMMENT '管理员ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID，对应popedom表里的id',
  PRIMARY KEY (`id`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员权限分配表';

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_all`
--

DROP TABLE IF EXISTS `qinggan_all`;
CREATE TABLE IF NOT EXISTS `qinggan_all` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `identifier` varchar(100) NOT NULL COMMENT '标识串',
  `title` varchar(200) NOT NULL COMMENT '分类名称',
  `ico` varchar(255) NOT NULL COMMENT '图标',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0普通１系统',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类管理' AUTO_INCREMENT=34 ;

--
-- 转存表中的数据 `qinggan_all`
--

INSERT INTO `qinggan_all` (`id`, `site_id`, `identifier`, `title`, `ico`, `is_system`) VALUES
(4, 1, 'copyright', '页脚版权', 'res/ico/dc8beceb11e914d8.png', 0),
(9, 1, 'contactus', '联系我们', 'res/ico/d218a30a201b7ceb.png', 0);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_cart`
--

DROP TABLE IF EXISTS `qinggan_cart`;
CREATE TABLE IF NOT EXISTS `qinggan_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `session_id` varchar(255) NOT NULL COMMENT 'SESSION_ID号',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID号，为0表示游客',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='购物车' AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `qinggan_cart`
--

INSERT INTO `qinggan_cart` (`id`, `session_id`, `user_id`, `addtime`) VALUES
(8, '937c3053pa231461h9ikj06tf3', 0, 1385052639),
(2, 'al2qjesg9unc9r6akqkarv49r0', 0, 1384270391),
(3, 'f5t4nk3libi4l7g9jsg5902qr3', 0, 1384307117),
(4, 'nbipbv148d46108b5evuoaamc7', 0, 1384738010),
(5, 'ju6mam5melbla470ssr17rko24', 0, 1384765464),
(6, 'gurh3ln5ferjcvtasdh6nfu301', 0, 1384913774),
(7, 'kpj3b3r4o8f5iq77tdkrbg7m74', 0, 1385027927),
(9, 'hfhj54riccip0v1p7nsivbe1f1', 0, 1385134326),
(17, 'vuhlnn716pgeb10fbvo17f4ro1', 0, 1385619140),
(13, 'de6vmbvbh4vkj1holmnrii51t1', 0, 1385347512),
(18, 'uaotrv0uv68auc0bugqlpqu5o5', 0, 1386317376),
(24, '72q3d888tm7pdpg2hu53occb80', 0, 1386489688),
(28, 'irgc7kl7hlr5a3r9ed47hdvj11', 0, 1387159638),
(29, 'mgoo99515j8ha60l2maatvhtv4', 0, 1387282630),
(32, 'tmlp3782t3343266ue5nm2td30', 0, 1387867529),
(33, '0qfnsiudeuukp7bbke0rf2vvc4', 0, 1388127515),
(34, 'ntpmutjov3sk83ujkq31cbvsf2', 2, 1388370009),
(35, '1cp13t7dfesll7n33gvtch6667', 0, 1388409390),
(36, 'b9b1en0r4k9gn7hlhplajk4ml5', 0, 1388627839),
(37, 'vjgsjbfka80416ba8c8jj9ums1', 0, 1388630813),
(38, '2m12engs372os6su4gql0vina5', 0, 1389511674);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_cart_product`
--

DROP TABLE IF EXISTS `qinggan_cart_product`;
CREATE TABLE IF NOT EXISTS `qinggan_cart_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `cart_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购物车ID号',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `title` varchar(255) NOT NULL COMMENT '产品名称',
  `price` float NOT NULL COMMENT '产品单价',
  `qty` int(11) NOT NULL DEFAULT '0' COMMENT '产品数量',
  `ext` text NOT NULL COMMENT '扩展属性',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='购物车里的产品信息' AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `qinggan_cart_product`
--

INSERT INTO `qinggan_cart_product` (`id`, `cart_id`, `tid`, `title`, `price`, `qty`, `ext`) VALUES
(1, 5, 725, '测试1', 180, 1, ''),
(2, 5, 724, '测试1', 180, 3, ''),
(3, 6, 726, '测试1', 180, 1, ''),
(4, 6, 723, '测试1', 180, 8, ''),
(9, 7, 724, '测试1', 180, 1, ''),
(8, 7, 725, '测试1', 180, 1, ''),
(10, 9, 725, '测试1', 180, 2, ''),
(22, 32, 726, '测试1', 180, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_cate`
--

DROP TABLE IF EXISTS `qinggan_cate`;
CREATE TABLE IF NOT EXISTS `qinggan_cate` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID，0为根分类',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不使用1正常使用',
  `title` varchar(200) NOT NULL COMMENT '分类名称',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '分类排序，值越小越往前靠',
  `tpl_list` varchar(255) NOT NULL COMMENT '列表模板',
  `tpl_content` varchar(255) NOT NULL COMMENT '内容模板',
  `psize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '列表每页数量',
  `seo_title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL COMMENT 'SEO关键字',
  `seo_desc` varchar(255) NOT NULL COMMENT 'SEO描述',
  `identifier` varchar(255) NOT NULL COMMENT '分类标识串',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类管理' AUTO_INCREMENT=151 ;

--
-- 转存表中的数据 `qinggan_cate`
--

INSERT INTO `qinggan_cate` (`id`, `site_id`, `parent_id`, `status`, `title`, `taxis`, `tpl_list`, `tpl_content`, `psize`, `seo_title`, `seo_keywords`, `seo_desc`, `identifier`) VALUES
(8, 1, 7, 1, '公司新闻', 10, '', '', 0, '', '', '', 'company'),
(7, 1, 0, 1, '新闻资讯', 10, '', '', 0, '', '', '', 'information'),
(68, 1, 7, 1, '行业新闻', 20, '', '', 0, '', '', '', 'industry'),
(70, 1, 0, 1, '男装服饰', 20, '', '', 0, '', '', '', 'men-s-clothing'),
(71, 1, 70, 1, 'T恤/POLO', 10, '', '', 0, '', '', '', 'txu-polo'),
(72, 1, 70, 1, '衬衫', 20, '', '', 0, '', '', '', 'chenshan');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_currency`
--

DROP TABLE IF EXISTS `qinggan_currency`;
CREATE TABLE IF NOT EXISTS `qinggan_currency` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '货币ID',
  `code` varchar(3) NOT NULL COMMENT '货币标识，仅限三位数的大写字母',
  `val` float(13,8) unsigned NOT NULL COMMENT '货币转化',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序，值越小越往前靠',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `symbol_left` varchar(24) NOT NULL COMMENT '价格左侧',
  `symbol_right` varchar(24) NOT NULL COMMENT '价格右侧',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不使用1使用',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不隐藏1隐藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='货币管理' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `qinggan_currency`
--

INSERT INTO `qinggan_currency` (`id`, `code`, `val`, `taxis`, `title`, `symbol_left`, `symbol_right`, `status`, `hidden`) VALUES
(1, 'CNY', 6.16989994, 10, '人民币', '￥', '', 1, 0),
(2, 'USD', 1.00000000, 20, '美金', 'US$', '', 1, 0),
(3, 'HKD', 7.76350021, 30, '港元', 'HK$', '', 1, 0),
(4, 'EUR', 0.76639998, 40, '欧元', 'EUR', '', 1, 0),
(5, 'GBP', 0.64529997, 50, '英镑', '￡', '', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_email`
--

DROP TABLE IF EXISTS `qinggan_email`;
CREATE TABLE IF NOT EXISTS `qinggan_email` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `site_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID，0表示全部网站',
  `identifier` varchar(255) NOT NULL COMMENT '发送标识',
  `title` varchar(200) NOT NULL COMMENT '邮件主题',
  `content` text NOT NULL COMMENT '邮件内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邮件内容' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `qinggan_email`
--

INSERT INTO `qinggan_email` (`id`, `site_id`, `identifier`, `title`, `content`) VALUES
(4, 1, 'register_code', '获取会员注册资格', '<p>您好，{$email}</p><p>您将注册成为网站【{$config.title} 】会员，请点击下面的地址，进入下一步注册：</p><p><br /></p><p></p><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><a href="{$link}" target="_blank">{$link}</a></p></blockquote><br /><p></p><p>感谢您对本站的关注，茫茫人海中，能有缘走到一起。</p>'),
(5, 1, 'getpass', '取回密码操作', '<p>您好，{$user.account}</p><p>您执行了忘记密码操作功能，请点击下面的链接执行下一步：</p><p><br /></p><p><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><a href="{$link}" target="_blank">{$link}</a></p></blockquote><br /></p><p>感谢您对本站的支持，有什么问题您在登录后可以咨询我们的客服。</p>');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_ext`
--

DROP TABLE IF EXISTS `qinggan_ext`;
CREATE TABLE IF NOT EXISTS `qinggan_ext` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段ID，自增',
  `module` varchar(100) NOT NULL COMMENT '模块',
  `title` varchar(255) NOT NULL COMMENT '字段名称',
  `identifier` varchar(50) NOT NULL COMMENT '字段标识串',
  `field_type` varchar(255) NOT NULL DEFAULT '200' COMMENT '字段存储类型',
  `note` varchar(255) NOT NULL COMMENT '字段内容备注',
  `form_type` varchar(100) NOT NULL COMMENT '表单类型',
  `form_style` varchar(255) NOT NULL COMMENT '表单CSS',
  `format` varchar(100) NOT NULL COMMENT '格式化方式',
  `content` text NOT NULL COMMENT '默认值',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `ext` text NOT NULL COMMENT '扩展内容',
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='字段管理器' AUTO_INCREMENT=203 ;

--
-- 转存表中的数据 `qinggan_ext`
--

INSERT INTO `qinggan_ext` (`id`, `module`, `title`, `identifier`, `field_type`, `note`, `form_type`, `form_style`, `format`, `content`, `taxis`, `ext`) VALUES
(35, 'all-4', '内容', 'content', 'longtext', '', 'code_editor', '', 'html_js', '', 90, 'a:2:{s:5:"width";s:3:"500";s:6:"height";s:3:"120";}'),
(59, 'all-9', '联系人', 'fullname', 'varchar', '填写联系人姓名', 'text', '', 'safe', '', 10, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}'),
(60, 'all-9', '客服QQ', 'qq', 'varchar', '多个QQ号用英文逗号隔开', 'text', '', 'safe', '', 60, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}'),
(61, 'all-9', '邮箱', 'email', 'varchar', '', 'text', '', 'safe', '', 50, 'a:2:{s:8:"form_btn";b:0;s:5:"width";b:0;}'),
(62, 'all-9', '联系地址', 'address', 'varchar', '', 'text', '', 'safe', '', 20, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"500";}'),
(63, 'all-9', '联系电话', 'tel', 'varchar', '', 'text', '', 'safe', '', 40, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}'),
(64, 'all-9', '联系我们小图片', 'thumb', 'varchar', '', 'text', '', 'safe', '', 90, 'a:2:{s:8:"form_btn";s:5:"image";s:5:"width";s:3:"430";}'),
(65, 'all-9', '链接', 'link', 'longtext', '该链接用于链接到更多的联系我们信息页', 'url', '', 'safe', '', 90, 'a:1:{s:5:"width";s:3:"430";}'),
(66, 'all-9', '邮编', 'zipcode', 'varchar', '请填写六位数字的邮编号码', 'text', '', 'safe', '', 30, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}'),
(161, 'project-90', '内容', 'content', 'longtext', '', 'editor', '', 'html_js', '', 255, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"360";s:7:"is_code";b:0;s:9:"btn_image";s:1:"1";s:9:"btn_video";s:1:"1";s:8:"btn_file";s:1:"1";s:8:"btn_page";b:0;}'),
(162, 'project-90', '缩略图', 'thumb', 'varchar', '', 'upload', '', 'safe', '', 30, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"0";}'),
(164, 'project-90', '摘要', 'note', 'longtext', '简要文字描述', 'editor', '', 'html_js', '', 20, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"160";s:7:"is_code";b:0;s:9:"btn_image";s:1:"1";s:9:"btn_video";b:0;s:8:"btn_file";b:0;s:8:"btn_page";b:0;}'),
(165, 'project-92', '内容', 'content', 'longtext', '', 'editor', '', 'html_js', '', 255, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"360";s:7:"is_code";b:0;s:9:"btn_image";s:1:"1";s:9:"btn_video";s:1:"1";s:8:"btn_file";s:1:"1";s:8:"btn_page";b:0;}'),
(201, 'project-93', '图片', 'pictures', 'varchar', '请上传500x400的图片', 'upload', '', 'safe', '', 50, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"1";}');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_extc`
--

DROP TABLE IF EXISTS `qinggan_extc`;
CREATE TABLE IF NOT EXISTS `qinggan_extc` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容值ID，对应ext表中的id',
  `content` longtext NOT NULL COMMENT '内容文本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='扩展字段内容维护';

--
-- 转存表中的数据 `qinggan_extc`
--

INSERT INTO `qinggan_extc` (`id`, `content`) VALUES
(35, '<p style="text-align:center;">Powered By phpok.com 版权所有 &copy; 2004-2013, All right reserved.</p>'),
(59, '苏相锟'),
(61, 'qinggan@188.com'),
(60, '40782502'),
(64, 'res/201310/08/02c7325437ffa0b3.jpg'),
(62, '广东深圳龙华新区民治'),
(63, '15818533971'),
(65, 'a:2:{s:7:"default";s:9:"index.php";s:7:"rewrite";s:10:"index.html";}'),
(66, '518000'),
(162, '352'),
(161, '企业建站系统（下述将用“系统”简称）是一套致力于企业网通用配置平台应用。公司长期专注于微小型企业网络化的研发和经营，拥有八年多的企业建站经验。系统广泛应用于全国多个省市，涉及行业包括保险、服装、电器、化工、物流、房地产、旅游、贸易、珠宝、WAP等行业。&nbsp;<br />&nbsp;<br />公司一贯坚持以“专业是基础，服务是保证，质量是信誉”的理念，来适应和满足客户不断增长的业务需求，提供有竞争力的、可持续发展的产品和技术解决方案。&nbsp;'),
(164, '企业建站系统（下述将用“系统”简称）是一套致力于企业网通用配置平台应用。公司长期专注于微小型企业网络化的研发和经营，拥有八年多的企业建站经验。系统广泛应用于全国多个省市，涉及行业包括保险、服装、电器、化工、物流、房地产、旅游、贸易、珠宝、WAP等行业。 <br /> <br />公司一贯坚持以“专业是基础，服务是保证，质量是信誉”的理念，来适应和满足客户不断增长的业务需求，提供有竞争力的、可持续发展的产品和技术解决方案。 '),
(165, '<p>公司网站：www.phpok.com</p><p>联系地址：深圳市罗湖区东盛路辐照中心7栋3楼</p><p>联系电话：15818533971</p><p><br /></p><p>如何到达：<br />地铁环中线——布心站”下车B出口直走,第一个红绿灯也就是太白路，往右走一直沿着太白路走直到看到左侧有一东盛路，沿着东盛路左侧第一栋就是辐照中心。地铁步行到公司大约15分钟。周围标志性建筑：金威啤酒厂。<br /><br />途径附近公交：<br />乘坐107路，203路，212路，24路，2路，379路，40路，59路，62路，83路，<br />B698路单向行驶，N2路，N6路，到松泉公寓下车。<br /></p>'),
(201, '437,439,441,443,444,442,440,438');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_fields`
--

DROP TABLE IF EXISTS `qinggan_fields`;
CREATE TABLE IF NOT EXISTS `qinggan_fields` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段ID，自增',
  `title` varchar(255) NOT NULL COMMENT '字段名称',
  `identifier` varchar(50) NOT NULL COMMENT '字段标识串',
  `field_type` varchar(255) NOT NULL DEFAULT '200' COMMENT '字段存储类型',
  `note` varchar(255) NOT NULL COMMENT '字段内容备注',
  `form_type` varchar(100) NOT NULL COMMENT '表单类型',
  `form_style` varchar(255) NOT NULL COMMENT '表单CSS',
  `format` varchar(100) NOT NULL COMMENT '格式化方式',
  `content` varchar(100) NOT NULL COMMENT '默认值',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `ext` text NOT NULL COMMENT '扩展内容',
  `area` text NOT NULL COMMENT '使用范围，多个应用范围用英文逗号隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='字段管理器' AUTO_INCREMENT=117 ;

--
-- 转存表中的数据 `qinggan_fields`
--

INSERT INTO `qinggan_fields` (`id`, `title`, `identifier`, `field_type`, `note`, `form_type`, `form_style`, `format`, `content`, `taxis`, `ext`, `area`) VALUES
(6, '内容', 'content', 'longtext', '', 'editor', '', 'html', '', 255, 'a:12:{s:5:"width";s:3:"800";s:6:"height";s:3:"360";s:7:"is_code";b:0;s:9:"btn_image";i:1;s:9:"btn_video";i:1;s:8:"btn_file";i:1;s:8:"btn_page";b:0;s:8:"btn_info";b:0;s:7:"is_read";b:0;s:5:"etype";s:4:"full";s:7:"btn_tpl";b:0;s:7:"btn_map";b:0;}', 'all,cate,module,project,user,usergroup'),
(7, '图片', 'pictures', 'varchar', '支持多图', 'upload', '', 'safe', '', 50, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"1";}', 'all,cate,module,project,user'),
(8, '缩略图', 'thumb', 'varchar', '', 'upload', '', 'safe', '', 30, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"0";}', 'all,cate,module,project,user'),
(9, '压缩文件', 'file', 'varchar', '仅支持压缩文件', 'upload', '', 'safe', '', 60, 'a:3:{s:11:"upload_type";s:3:"zip";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"0";}', 'all,cate,module,project'),
(11, '链接', 'link', 'longtext', '手动指定外部链接时，伪静态链接可以留空', 'url', '', 'safe', '', 90, 'a:1:{s:5:"width";s:3:"500";}', 'all,cate,module,project,user'),
(12, '摘要', 'note', 'longtext', '简要文字描述', 'textarea', '', 'safe', '', 20, 'a:2:{s:5:"width";s:3:"600";s:6:"height";s:2:"80";}', 'all,cate,module,project'),
(13, '性别', 'gender', 'varchar', '', 'radio', '', 'safe', '女', 120, 'a:3:{s:11:"option_list";b:0;s:9:"put_order";s:1:"0";s:10:"ext_select";s:8:"男\r\n女";}', 'all,cate,module,project,user'),
(14, '邮箱', 'email', 'varchar', '', 'text', '', 'safe', '', 130, 'a:2:{s:8:"form_btn";b:0;s:5:"width";b:0;}', 'all,cate,module,project,user'),
(37, '链接方式', 'target', 'varchar', '设置是否在新窗口打开', 'radio', '', 'safe', '_self', 100, 'a:1:{s:11:"option_list";s:5:"opt:6";}', 'cate,module,project'),
(30, '姓名', 'fullname', 'varchar', '', 'text', '', 'safe', '', 10, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 'all,cate,module,project,user'),
(31, '通栏图片', 'banner', 'varchar', '', 'upload', '', 'safe', '', 40, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"0";}', 'all,cate,module,project'),
(34, '附件', 'files', 'varchar', '仅支持rar和zip的压缩包，支持多附件', 'upload', '', 'safe', '', 70, 'a:3:{s:11:"upload_type";s:3:"zip";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"1";}', 'all,cate,module,project'),
(35, '文档', 'doc', 'varchar', '支持在线办公室的文档', 'upload', '', 'safe', '', 80, 'a:3:{s:11:"upload_type";s:8:"document";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"1";}', 'all,cate,module,project'),
(36, '视频', 'video', 'varchar', '支持并推荐您使用FlV格式视频', 'upload', '', 'int', '', 110, 'a:3:{s:11:"upload_type";b:0;s:7:"cate_id";b:0;s:11:"is_multiple";b:0;}', 'module,project'),
(60, '客服QQ', 'qq', 'varchar', '', 'text', '', 'safe', '', 150, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 'all,cate,module,project,user'),
(116, '广告内容', 'ad', 'longtext', '', 'editor', '', 'html_js', '', 255, 'a:12:{s:5:"width";s:3:"600";s:6:"height";s:3:"100";s:7:"is_code";i:1;s:9:"btn_image";i:1;s:9:"btn_video";i:1;s:8:"btn_file";b:0;s:8:"btn_page";b:0;s:8:"btn_info";b:0;s:7:"is_read";b:0;s:5:"etype";s:6:"simple";s:7:"btn_tpl";b:0;s:7:"btn_map";b:0;}', 'all,cate,module,project'),
(75, '联系地址', 'address', 'varchar', '', 'text', '', 'safe', '', 79, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"500";}', 'all,module,user'),
(76, '联系电话', 'tel', 'varchar', '', 'text', '', 'safe', '', 89, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 'all,cate,module,project,user'),
(77, '邮编', 'zipcode', 'varchar', '请填写六位数字的邮编号码', 'text', '', 'safe', '', 30, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 'all,module'),
(79, 'LOGO', 'logo', 'varchar', '网站LOGO，规格：88x31', 'text', '', 'safe', '', 160, 'a:2:{s:8:"form_btn";s:5:"image";s:5:"width";s:3:"500";}', 'all,cate,module,project'),
(80, '图片', 'pic', 'varchar', '', 'text', '', 'safe', '', 255, 'a:2:{s:8:"form_btn";s:5:"image";s:5:"width";s:3:"500";}', 'all,cate,module,project,user'),
(81, '统计', 'statjs', 'varchar', '', 'code_editor', '', 'html_js', '', 255, 'a:2:{s:5:"width";s:3:"500";s:6:"height";s:2:"80";}', 'all'),
(82, '备案号', 'cert', 'varchar', '', 'text', '', 'safe', '', 255, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 'all'),
(95, '发货时间', 'sendtime', 'varchar', '设置发货时间', 'text', '', 'time', '', 255, 'a:2:{s:8:"form_btn";s:4:"date";s:5:"width";s:3:"300";}', 'module'),
(96, '企业名称', 'company', 'varchar', '', 'text', '', 'safe', '', 255, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"500";}', 'all,module,project,user'),
(106, '管理员回复', 'adm_reply', 'longtext', '', 'editor', '', 'html', '', 255, 'a:12:{s:5:"width";s:3:"800";s:6:"height";s:3:"180";s:7:"is_code";b:0;s:9:"btn_image";i:1;s:9:"btn_video";i:1;s:8:"btn_file";i:1;s:8:"btn_page";b:0;s:8:"btn_info";b:0;s:7:"is_read";b:0;s:5:"etype";s:6:"simple";s:7:"btn_tpl";b:0;s:7:"btn_map";b:0;}', 'module'),
(112, '赞', 'good', 'varchar', '设置点赞次数', 'text', '', 'int', '', 20, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"100";}', 'module'),
(113, '省市', 'province_city', 'longtext', '', 'select', '', 'safe', '', 255, 'a:4:{s:11:"option_list";s:5:"opt:2";s:11:"is_multiple";s:1:"0";s:5:"width";b:0;s:10:"ext_select";b:0;}', 'all,cate,module,project,user,usergroup'),
(114, '手机号', 'mobile', 'varchar', '', 'text', '', 'safe', '', 255, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 'all,cate,module,project,user,usergroup');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_gd`
--

DROP TABLE IF EXISTS `qinggan_gd`;
CREATE TABLE IF NOT EXISTS `qinggan_gd` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `identifier` varchar(100) NOT NULL COMMENT '标识串',
  `width` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `height` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片高度',
  `mark_picture` varchar(255) NOT NULL COMMENT '水印图片位置',
  `mark_position` varchar(100) NOT NULL COMMENT '水印位置',
  `cut_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '图片生成方式，支持缩放法、裁剪法、等宽、等高及自定义五种，默认使用缩放法',
  `quality` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '图片生成质量，默认是100',
  `bgcolor` varchar(10) NOT NULL DEFAULT 'FFFFFF' COMMENT '补白背景色，默认是白色',
  `trans` tinyint(3) unsigned NOT NULL DEFAULT '65' COMMENT '透明度',
  `editor` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0普通1默认插入编辑器',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `qinggan_gd`
--

INSERT INTO `qinggan_gd` (`id`, `identifier`, `width`, `height`, `mark_picture`, `mark_position`, `cut_type`, `quality`, `bgcolor`, `trans`, `editor`) VALUES
(2, 'thumb', 212, 120, '', 'bottom-right', 1, 80, 'FFFFFF', 0, 0),
(12, 'auto', 0, 0, '', 'bottom-right', 0, 80, 'FFFFFF', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list`
--

DROP TABLE IF EXISTS `qinggan_list`;
CREATE TABLE IF NOT EXISTS `qinggan_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为根主题，其他ID对应list表的id字段',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `module_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '模块ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `site_id` mediumint(8) unsigned NOT NULL COMMENT '网站ID',
  `title` varchar(255) NOT NULL COMMENT '主题',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未审核，1已审核',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0显示，1隐藏',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `tpl` varchar(255) NOT NULL COMMENT '自定义的模板',
  `seo_title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL COMMENT 'SEO关键字',
  `seo_desc` varchar(255) NOT NULL COMMENT 'SEO描述',
  `tag` varchar(255) NOT NULL COMMENT 'tag标签',
  `attr` varchar(255) NOT NULL COMMENT '主题属性',
  `replydate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后回复时间',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID号，为0表示管理员发布',
  `identifier` varchar(255) NOT NULL COMMENT '内容标识串',
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '价格',
  `currency_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '货币ID，对应currency表',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='内容主表' AUTO_INCREMENT=1251 ;

--
-- 转存表中的数据 `qinggan_list`
--

INSERT INTO `qinggan_list` (`id`, `parent_id`, `cate_id`, `module_id`, `project_id`, `site_id`, `title`, `dateline`, `sort`, `status`, `hidden`, `hits`, `tpl`, `seo_title`, `seo_keywords`, `seo_desc`, `tag`, `attr`, `replydate`, `user_id`, `identifier`, `price`, `currency_id`) VALUES
(519, 0, 0, 21, 41, 1, '官网新版发布', 1380700474, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(520, 0, 0, 23, 42, 1, '网站首页', 1380942032, 10, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(521, 0, 8, 22, 43, 1, '传“视网膜”版iPad Mini年内无法大规模上市', 1380942548, 0, 1, 0, 20, '', '消息人士表示，苹果预计将于本月发布下一代iPad Mini', 'iPad,Mini,新兴,三星,本月,生产,销售,富邦,弗兰克,发售', '网易科技讯 10月2日消息，据国外媒体报道，来自苹果供应链的内部人士透露，苹果将无法在年内大规模推出配备“视网膜”显示屏的新版iPad Mini', 'iPad Mini 新兴 三星 本月 生产 销售', '', 0, 0, '', '0.0000', 0),
(522, 0, 71, 24, 45, 1, '测试1', 1381181330, 0, 1, 0, 0, '', '', '测试', '测试下产品！', '测试', '', 0, 0, '', '180.0000', 1),
(722, 0, 71, 24, 45, 1, '测试1', 1381181330, 0, 1, 0, 0, '', '', '测试', '测试下产品！', '测试', '', 0, 0, '', '180.0000', 2),
(723, 0, 71, 24, 45, 1, '测试1', 1381181330, 0, 1, 0, 3, '', '', '测试', '测试下产品！', '测试', '', 0, 0, '', '180.0000', 4),
(724, 0, 71, 24, 45, 1, '测试1', 1381181330, 0, 1, 0, 2, '', '', '测试', '测试下产品！', '测试', '', 0, 0, '', '180.0000', 2),
(725, 0, 71, 24, 45, 1, '测试1', 1381181330, 0, 1, 0, 4, '', '', '测试', '测试下产品！', '测试', '', 0, 0, '', '180.0000', 3),
(694, 0, 0, 0, 0, 0, 'fasdfasdfasdfasdf', 1381444969, 0, 0, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(695, 0, 0, 0, 0, 0, 'fasdfasdfa', 1381445019, 0, 0, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(710, 0, 0, 40, 87, 1, '注册协议', 1383105236, 0, 1, 0, 14, '', '测试的', 'tt', 'tdfasdfasd', '', '', 0, 0, 'agreement', '0.0000', 0),
(712, 0, 0, 23, 42, 1, '关于我们', 1383355821, 20, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(713, 0, 0, 23, 42, 1, '新闻中心', 1383355842, 30, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(714, 0, 0, 23, 42, 1, '产品展示', 1383355849, 40, 1, 0, 0, '', '', 'Array', 'Array', 'Array', '', 0, 0, '', '0.0000', 0),
(716, 0, 0, 23, 42, 1, '在线留言', 1383355870, 60, 1, 0, 0, '', '', 'Array', 'Array', 'Array', '', 0, 0, '', '0.0000', 0),
(719, 712, 0, 23, 42, 1, '联系我们', 1383355984, 23, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(720, 0, 0, 21, 41, 1, '', 1383356941, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(726, 0, 71, 24, 45, 1, '测试1', 1381181330, 0, 1, 0, 31, '', '', '测试', '测试下产品！', '测试', '', 0, 0, '', '180.0000', 5),
(730, 0, 0, 43, 91, 1, 'qgWeb0.54版发布', 1383633179, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(731, 0, 0, 43, 91, 1, 'qgWeb1.0发布', 1383633204, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(732, 0, 0, 43, 91, 1, '工作室论坛开通', 1383633228, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(734, 0, 0, 43, 91, 1, 'qgWeb3.0发布（分企业版及个人版）', 1383633263, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(735, 0, 0, 43, 91, 1, 'qgweb4.0发布', 1383633283, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(736, 0, 0, 43, 91, 1, '团队决定开发进销存管理系统', 1383633298, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(737, 0, 0, 43, 91, 1, 'qgweb4.1发布（UTF）', 1383633314, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(738, 0, 0, 43, 91, 1, 'qgweb4.2发布（GBK）', 1383633328, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(739, 0, 0, 43, 91, 1, 'qgweb5.0发布（首次实现多语言多风格功能）', 1383633348, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(740, 0, 0, 43, 91, 1, 'qgweb5.2发布，同时更名为：1.0.0版！', 1383633367, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(741, 0, 0, 43, 91, 1, '2.0发布', 1383633392, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(742, 0, 0, 43, 91, 1, '2.2发布（稳定版本）', 1383633404, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(743, 0, 0, 43, 91, 1, '3.0精简版发布', 1383633428, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(744, 0, 0, 43, 91, 1, '3.0完整版发布', 1383633558, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(745, 0, 0, 43, 91, 1, 'phpok3.3完整版发布', 1383633596, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(746, 0, 0, 43, 91, 1, '3.4版发布（后台更换为桌面式）', 1383633635, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(755, 712, 0, 23, 42, 1, '工作环境', 1383640450, 24, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(756, 0, 0, 40, 94, 1, '4发布了', 1383642307, 0, 1, 0, 94, '', '', 'phpok4,企业网站,说明', '经过反反复复的修改，4终于要和大家见面了！', '', '', 0, 0, '', '0.0000', 0),
(757, 712, 0, 23, 42, 1, '公告通知', 1383650285, 25, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(758, 0, 68, 22, 43, 1, '31条航线机票取消打折下限 多与京沪京广高铁竞争', 1383806674, 0, 1, 0, 6, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(759, 0, 68, 22, 43, 1, '阿里TV系统升级 将增加安全监控功能', 1383806741, 0, 1, 0, 2, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(760, 713, 0, 23, 42, 1, '公司新闻', 1383815715, 10, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(761, 713, 0, 23, 42, 1, '行业新闻', 1383815736, 20, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0),
(763, 0, 0, 46, 96, 1, '测试留言', 1384149073, 0, 1, 0, 0, '', '', '这个', '这个是测试用的！管理员回复！', '这个', '', 0, 0, '', '0.0000', 0),
(764, 0, 0, 46, 96, 1, '测试用的', 1384153570, 0, 1, 0, 0, '', '', '测试', '测试内容！测试管理员回复！', '测试', '', 0, 0, '', '0.0000', 0),
(1250, 0, 0, 46, 96, 1, '测试', 1387867385, 0, 1, 0, 0, '', '', '', '', '', '', 0, 0, '', '0.0000', 0);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_21`
--

DROP TABLE IF EXISTS `qinggan_list_21`;
CREATE TABLE IF NOT EXISTS `qinggan_list_21` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `link` longtext NOT NULL COMMENT '链接',
  `target` varchar(255) NOT NULL DEFAULT '_self' COMMENT '链接方式',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片播放器';

--
-- 转存表中的数据 `qinggan_list_21`
--

INSERT INTO `qinggan_list_21` (`id`, `site_id`, `project_id`, `cate_id`, `link`, `target`, `pic`) VALUES
(519, 1, 41, 0, '', '_self', '426'),
(720, 1, 41, 0, 'http://www.phpok.com', '_self', '340');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_22`
--

DROP TABLE IF EXISTS `qinggan_list_22`;
CREATE TABLE IF NOT EXISTS `qinggan_list_22` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `content` longtext NOT NULL COMMENT '内容',
  `note` longtext NOT NULL COMMENT '摘要',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章资讯';

--
-- 转存表中的数据 `qinggan_list_22`
--

INSERT INTO `qinggan_list_22` (`id`, `site_id`, `project_id`, `cate_id`, `thumb`, `content`, `note`) VALUES
(521, 1, 43, 8, '345', '<p style="text-align: center;"><img alt="传“视网膜”版iPad Mini年内无法大规模上市" src="res/201310/05/6df12daae268c66d_37.png" style="vertical-align: top; border: 0px; color: rgb(37, 37, 37); font-family: 宋体, sans-serif; font-size: 16px; line-height: 28px; text-align: center; " /><br /></p><p>网易科技讯 10月2日消息，据国外媒体报道，来自苹果供应链的内部人士透露，苹果将无法在年内大规模推出配备“视网膜”显示屏的新版iPad Mini。</p><br />消息人士称，苹果的供应链现在才加紧生产供iPad Mini使用的视网膜显示屏，这意味着配备“视网膜”显示屏的iPad Mini即使今年能上市，其出货量也将非常有限，从而错过年底假日购物季节创收的好时机。<br /><br />由于竞争对手正在迅速提高产品硬件规格和降低售价，苹果要保持其平板电脑的市场份额并促进销售面临着较大的压力。<br /><br />消息人士表示，苹果预计将于本月发布下一代iPad Mini，但目前尚不清楚该产品具体将推出什么新功能和有什么改进。<br /><br />不过，消费者普遍希望新推出的智能手机和平板电脑，除了其它方面的改进，能配备更高分辨率的显示屏、更高像素的摄像头，以及尺寸上更轻更薄。<br /><br />市场研究公司Forrester的分析师弗兰克·吉列特（Frank Gillett ）指出：“如果苹果推出的下一代iPad Mini不配备视网膜显示屏，消费者会极为不满。”<br /><br />苹果拒绝对此发表评论；来自苹果供应链厂商的消息人士不愿透露姓名，因为此事涉及保密。<br /><br />严格的节能要求<br /><br />苹果所谓的视网膜显示屏，是当显示屏的分辩率足够高时，人类肉眼将无法察觉像素点。<br /><br />苹果部分全尺寸台iPad已配备视网膜显示屏，iPad Mini的一些竞争对手，如谷歌的Nexus 7以及亚马逊将于本月上市的Kindle Fire HDX，这些产品显示屏的分辨率已达到视网膜显示屏的水准。<br /><br />iPad Mini视网膜显示屏生产时间被延误的背后原因还不清楚。但来自苹果供应链的一位消息人士表示，由于严格的节能要求，苹果对面板生产商的认证时间被延长。<br /><br />来自供应链的消息人士称，LG Display、三星旗下显示屏部门和夏普公司都在争夺苹果iPad Mini视网膜显示屏的订单。<br /><br />这三家显示屏制造商拒绝对此发表评论。<br /><br />或推8 GB版iPad Mini<br /><br />消息人士表示，鉴于提高显示屏产量所需的时间，配备视网膜显示屏的iPad Mini要等到明年年初才能大规模上市。<br /><br />这位消息人士预计，苹果或者等到明年年初全面发售视网膜版iPad Mini，或者在今年年底前限量发售该iPad Mini。<br /><br />另外两位消息人士称，苹果还要求供应商降低显示屏的生产成本。其中一位消息人士表示，苹果正考虑推出配备8 GB内存的iPad Mini。<br /><br />配备较小的内存，可以让苹果降低iPad Mini的售价，以促进该产品在中国等新兴市场的销售。在新兴市场上，搭载谷歌Android操作系统的廉价平板电脑正获得越来越大的市场份额。<br /><br />富邦证券分析师亚瑟·廖（Arthur Liao）指出：“现在，与其它7 - 8英寸平板电脑相比，iPad Mini售价最高。即使其售价能下调50美元，也将吸引更多的消费者。”<br /><br />价格不会下调<br /><br />市场研究公司IDC的一项调查显示，今年第二季度，iPad的全球市场份额为32.5％，比去年同期下降了一半，而包括三星Galaxy系列在内的Android平板电脑市场份额达62.6％，比去年同期增长了近四分之一。<br /><br />分析师表示，期待苹果大幅下调新一代iPad Mini售价的人可能会感到失望，例如，本月上市的iPhone 5C价格就高于人们的预期。<br /><br />一位消息人士称：“虽然苹果正在努力削减零部件成本，但不要期望新一代iPad Mini价格会明显下降。最终其售价可能与当前市场上销售的iPad Mini售价差不多，因为新一代iPad Mini硬件配制将实现升级。”（刘春）', ''),
(758, 1, 43, 68, '', '<p style="margin-bottom: 29px; font-size: 16px; line-height: 28px; font-family: 宋体, Arial, sans-serif; text-indent: 2em; ">昨天，中国民用航空局和国家发改委发布通知称，对旅客运输票价实行政府指导价的国内航线，均取消票价下浮幅度限制。与此同时，国内不设打折下限的航线又新增31条。</p><p style="margin-bottom: 29px; font-size: 16px; line-height: 28px; font-family: 宋体, Arial, sans-serif; text-indent: 2em; ">据民航业内人士介绍，根据2004年出台的《民航国内<a class="a-tips-Article-QQ" href="http://stockhtm.finance.qq.com/astock/ggcx/ATSG.OQ.htm" target="_blank" style="text-decoration: none; outline: none; color: rgb(0, 0, 0); border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(83, 109, 166); ">航空运输</a>价格改革方案》，省、自治区内，及直辖市与相邻省、自治区、直辖市之间的短途航线，已经与其他替代运输方式形成竞争的，实行市场调节价，不规定票价浮动幅度。除上述施行市场调节价的航线外，民航国内航空旅客运输票价实行浮动幅度管理，票价上浮幅度最高不得超过基准价的25%。</p><p style="margin-bottom: 29px; font-size: 16px; line-height: 28px; font-family: 宋体, Arial, sans-serif; text-indent: 2em; ">昨天发布的通知规定，对部分与地面主要交通运输方式形成竞争，且由两家(含)以上航空公司共同经营的国内航线，旅客运输票价由实行政府指导价改为市场调节价。航空公司可根据市场供求情况自主确定票价水平的航线新增31条。实行市场调节价的国内航线目录由民航局和国家发改委规定，于每年一季度调整公布。航空公司在上述范围内制定或调整旅客运输票价时，应至少提前7日向社会公布，并通过航空价格信息系统抄报民航局、发改委。上述业内人士分析，此次由政府指导价转为市场调节价的31条航线，大多是与京广、京沪等高铁存在竞争，取消浮动幅度限制，有利于提高民航的竞争力。</p>', ''),
(759, 1, 43, 68, '', '<p align="center" style="margin-top: 20px; margin-right: auto; margin-left: auto; padding-top: 5px; padding-bottom: 5px; line-height: 26px; font-size: 16px; color: rgb(51, 51, 51); font-family: 微软雅黑, Tahoma, Verdana, 宋体; "></p><div class="mbArticleSharePic        " r="1" style="margin: 0px auto; padding: 0px; position: relative; z-index: 10; width: 500px; "><img alt="阿里TV系统升级 将增加安全监控功能" src="res/201311/07/85032f7e7ba3cfd7_37.jpg" style="border: 0px; " /></div><p></p><p style="margin-top: 20px; margin-right: auto; margin-left: auto; padding-top: 5px; padding-bottom: 5px; line-height: 26px; font-size: 16px; color: rgb(51, 51, 51); font-family: 微软雅黑, Tahoma, Verdana, 宋体; text-indent: 2em; "><strong>腾讯科技讯</strong>（范蓉）11月7日消息，阿里TV系统将于本月进行升级，增加家庭安全监控功能。同时，阿里在今年“双11”期间，将采用100万台天猫魔盒免费送的方式，加速阿里TV系统在终端的普及。</p><p style="margin-top: 20px; margin-right: auto; margin-left: auto; padding-top: 5px; padding-bottom: 5px; line-height: 26px; font-size: 16px; color: rgb(51, 51, 51); font-family: 微软雅黑, Tahoma, Verdana, 宋体; text-indent: 2em; ">尽管阿里TV系统已经将电商与TV结合，但阿里并不满足于这一现状。“电视处于客厅的重要位置，阿里一直欲加强自身砝码，安全监控就是其中之一。”阿里一位内部人士表示。</p><p style="margin-top: 20px; margin-right: auto; margin-left: auto; padding-top: 5px; padding-bottom: 5px; line-height: 26px; font-size: 16px; color: rgb(51, 51, 51); font-family: 微软雅黑, Tahoma, Verdana, 宋体; text-indent: 2em; ">据透露，阿里TV系统将提供的安全监控功能，主要通过系统应用、摄像头及智能手机之间的软硬结合来实现。用户只需在智能电视上增设一个摄像头，同时在手机中安装阿里TV助手，就可在任意场所，通过手机屏幕看到家中实时传送过来的画面。</p><p style="margin-top: 20px; margin-right: auto; margin-left: auto; padding-top: 5px; padding-bottom: 5px; line-height: 26px; font-size: 16px; color: rgb(51, 51, 51); font-family: 微软雅黑, Tahoma, Verdana, 宋体; text-indent: 2em; ">业内人士认为，阿里不仅将电视定位为PC、手机、平板之外的第四个屏幕选择，还希望将电视提升到智能家居的大概念中。“加入安全监控功能的阿里TV系统，将使电视在家庭中扮演更加重要的监护设备角色。”</p><p style="margin-top: 20px; margin-right: auto; margin-left: auto; padding-top: 5px; padding-bottom: 5px; line-height: 26px; font-size: 16px; color: rgb(51, 51, 51); font-family: 微软雅黑, Tahoma, Verdana, 宋体; text-indent: 2em; ">据悉，安全监控功能将是阿里TV系统11月份更新的重头戏。未来阿里TV系统还会增加云存储功能，让用户可随时查询、回放家庭生活片段。</p>', '');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_23`
--

DROP TABLE IF EXISTS `qinggan_list_23`;
CREATE TABLE IF NOT EXISTS `qinggan_list_23` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `link` longtext NOT NULL COMMENT '链接',
  `target` varchar(255) NOT NULL DEFAULT '_self' COMMENT '链接方式',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航';

--
-- 转存表中的数据 `qinggan_list_23`
--

INSERT INTO `qinggan_list_23` (`id`, `site_id`, `project_id`, `cate_id`, `link`, `target`) VALUES
(520, 1, 42, 0, 'a:2:{s:7:"default";s:9:"index.php";s:7:"rewrite";s:10:"index.html";}', '_self'),
(712, 1, 42, 0, 'a:2:{s:7:"default";s:21:"index.php?id=about-us";s:7:"rewrite";s:13:"about-us.html";}', '_self'),
(713, 1, 42, 0, 'a:2:{s:7:"default";s:17:"index.php?id=news";s:7:"rewrite";s:9:"news.html";}', '_self'),
(714, 1, 42, 0, 'a:2:{s:7:"default";s:20:"index.php?id=product";s:7:"rewrite";s:12:"product.html";}', '_self'),
(716, 1, 42, 0, 'a:2:{s:7:"default";s:17:"index.php?id=book";s:7:"rewrite";s:9:"book.html";}', '_self'),
(719, 1, 42, 0, 'a:2:{s:7:"default";s:23:"index.php?id=contact-us";s:7:"rewrite";s:15:"contact-us.html";}', '_self'),
(755, 1, 42, 0, 'a:2:{s:7:"default";s:17:"index.php?id=work";s:7:"rewrite";s:9:"work.html";}', '_self'),
(757, 1, 42, 0, 'a:2:{s:7:"default";s:19:"index.php?id=notice";s:7:"rewrite";s:11:"notice.html";}', '_self'),
(760, 1, 42, 0, 'a:2:{s:7:"default";s:30:"index.php?id=news&cate=company";s:7:"rewrite";s:17:"news/company.html";}', '_self'),
(761, 1, 42, 0, 'a:2:{s:7:"default";s:31:"index.php?id=news&cate=industry";s:7:"rewrite";s:18:"news/industry.html";}', '_self');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_24`
--

DROP TABLE IF EXISTS `qinggan_list_24`;
CREATE TABLE IF NOT EXISTS `qinggan_list_24` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `pictures` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` longtext NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品';

--
-- 转存表中的数据 `qinggan_list_24`
--

INSERT INTO `qinggan_list_24` (`id`, `site_id`, `project_id`, `cate_id`, `thumb`, `pictures`, `content`) VALUES
(522, 1, 45, 71, '345', '352,345', '测试下产品！'),
(722, 1, 45, 71, '345', '352,345', '测试下产品！'),
(723, 1, 45, 71, '345', '352,345', '测试下产品！'),
(724, 1, 45, 71, '345', '352,345', '测试下产品！'),
(725, 1, 45, 71, '345', '352,345', '测试下产品！'),
(726, 1, 45, 71, '345', '352,345', '测试下产品！');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_40`
--

DROP TABLE IF EXISTS `qinggan_list_40`;
CREATE TABLE IF NOT EXISTS `qinggan_list_40` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `content` longtext NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关于我们';

--
-- 转存表中的数据 `qinggan_list_40`
--

INSERT INTO `qinggan_list_40` (`id`, `site_id`, `project_id`, `cate_id`, `content`) VALUES
(710, 1, 87, 0, '<p>【注意】欢迎申请使用我公司（下列简称为“本公司”）提供的服务。请您（下列简称为“用户”）仔细阅读以下全部内容。如用户不同意本服务条款任意内容，请勿注册或使用本公司服务。如用户通过进入注册程序并勾选“我同意本公司通行证服务条款”，即表示用户与本公司已达成协议，自愿接受本服务条款的所有内容。此后，用户不得以未阅读本服务条款内容作任何形式的抗辩。</p><p><strong><br /></strong></p><p><strong>1.服务条款的确认和接纳</strong></p><blockquote><p><br /></p><p>本公司服务涉及到的本公司产品的所有权以及相关软件的知识产权归本公司所有。本公司所提供的服务必须按照其发布的公司章程，服务条款和操作规则严格执行。本服务条款的效力范围及于本公司的一切产品和服务，用户在享受本公司任何单项服务时，应当受本服务条款的约束。</p><p>当用户使用本公司各单项服务时，应以本公司认可的方式同意该单项服务的服务条款以及本公司在该单项服务中发出的各类公告（下列简称为“单项条款”），在此情况下单项条款与本服务条款同时对用户产生效力。若单项条款与本服务条款存在同类条款的冲突，则在单项条款约束范围内应以单项条款为准。</p></blockquote><p><strong><br /></strong></p><p><strong>2.本公司通行证服务简介</strong></p><blockquote><p><br /></p><p>本公司运用自己的操作系统通过国际互联网络为用户提供各项服务。用户必须：</p></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>（1）提供设备，如个人电脑、手机或其他上网设备。</p><p>（2）个人上网和支付与此服务有关的费用。</p></blockquote><p>考虑到本公司产品服务的重要性，用户同意：</p></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>（1）提供及时、详尽及准确的个人资料。</p><p>（2）不断更新注册资料，符合及时、详尽准确的要求。本公司通行证注册资料包括：用户的本公司通行证帐号、密码及注册通行证或更新通行证注册资料时输入的所有信息。用户注册通行证账号时填写的身份证件信息不能更新。</p></blockquote><p>如果用户提供给本公司的资料不准确，不真实，不合法有效，本公司保留终止用户使用本公司各项服务的权利。用户同意，其提供的真实、准确的本公司通行证注册资料作为认定用户与其本公司通行证帐号的关联性以及用户身份的唯一证据。用户在享用本公司各项服务的同时，同意接受本公司提供的各类信息服务。本公司提醒用户，用户注册通行证账号或更新注册信息时填写的证件号码，在注册通行证账号成功或补充填写后将无法进行修改，请用户慎重填写各类注册信息。</p><p>用户同意与注册、使用本公司通行证帐号相关的一切资料、数据和记录，包括但不限于通行证帐号名称、注册信息、所有登录、消费记录和相关的使用统计数字等归广州本公司计算机系统有限公司所有。发生争议时，用户同意以广州本公司计算机系统有限公司的系统数据为准，广州本公司计算机系统有限公司保证该数据的真实性。</p><p>尽管有前述约定，对于用户使用通行证帐号享受本公司旗下单项服务（如游戏产品、本公司宝产品等）时产生的一切数据，包括但不限于产品登录记录、消费记录及其他产品日志、产品客户服务记录、用户在产品中创造的社会网络内容等，归具体产品运营主体所有。发生争议时，用户同意以具体产品运营主体的系统数据为准。但是如果单项条款存在与前述不同的约定，则以单项条款约定为准。</p></blockquote><p><br /></p><p><strong>3.服务条款的修改</strong></p><blockquote><p><br /></p><p>本公司有权在必要时通过在网页上发出公告等合理方式修改本服务条款以及各单项服务的相关条款。用户在享受各项服务时，应当及时查阅了解修改的内容，并自觉遵守本服务条款以及该单项服务的相关条款。用户如继续使用本服务条款涉及的服务，则视为对修改内容的同意，当发生有关争议时，以最新的服务条款为准；用户在不同意修改内容的情况下，有权停止使用本服务条款涉及的服务。</p></blockquote><p><br /></p><p><strong>4.服务的变更或中止</strong></p><blockquote><p><br /></p><p>本公司始终在不断变更和改进服务。本公司可能会增加或删除功能，也可能暂停或彻底停止某项服务。用户同意本公司有权行使上述权利且不需对用户或第三方承担任何责任。</p></blockquote><p><br /></p><p><strong>5.用户隐私制度</strong></p><p><br /></p><blockquote><p>用户知悉并同意，为便于向用户提供更好的服务，本公司将在用户自愿选择服务或者提供信息的情况下收集用户的个人信息，并将这些信息进行整合。在用户使用本公司服务时，服务器会自动记录一些信息，包括但不限于URL、IP地址、浏览器类型、使用语言、访问日期和时间等。为方便用户登录或使用本公司的服务，本公司在有需要时将使用cookies等技术，并将收集到的信息发送到对应的服务器。用户可以选择接受或者拒绝cookies。如用户选择拒绝cookies，则用户有可能无法登陆或使用依赖于cookies的服务或者功能。本公司收集的信息将成为本公司常规商业档案的一部分，且有可能因为转让、合并、收购、重组等原因而被转移到本公司的继任公司或者指定的一方。本公司同意善意使用收集的信息，且采取各项措施保证信息安全。</p><p>尊重用户个人隐私是本公司的一项基本政策。所以，本公司不会公开或透露用户的注册资料及保存在本公司各项服务中的非公开内容，除非本公司在诚信的基础上认为透露这些信息在以下几种情况是必要的：</p></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>（1）事先获得用户的明确授权；或</p><p>（2）遵守有关法律规定，包括在国家有关机关查询时，提供用户的注册信息、用户在本公司的网页上发布的信息内容及其发布时间、互联网地址或者域名；或</p><p>（3）保持维护本公司的知识产权和其他重要权利；或</p><p>（4）在紧急情况下竭力维护用户个人和社会大众的隐私安全；或</p><p>（5）根据本条款相关规定或者本公司认为必要的其他情况下。</p></blockquote><p>本公司可能会与第三方合作向用户提供相关的网络服务，在此情况下，如该第三方同意承担与本公司同等的保护用户隐私的责任，则本公司可将用户信息提供给该第三方。</p></blockquote><p><br /></p><p><strong>6.用户的帐号、密码和安全性</strong></p><p><br /></p><blockquote><p>用户一旦注册成功成为用户，用户将得到一个密码和帐号。用户可随时改变用户的密码和图标，也可以结束旧的帐户重开一个新帐户。用户应维持密码及帐号的机密安全，如果用户未保管好自己的帐号和密码而对用户、本公司或第三方造成损害，用户将负全部责任。用户同意若发现任何非法使用用户帐号或安全漏洞的情况，有义务立即通告本公司。</p></blockquote><p><br /></p><p><strong>7.不可抗力条款</strong></p><p><br /></p><blockquote><p>本公司对不可抗力导致的损失不承担责任。本服务条款所指不可抗力包括：天灾、法律法规或政府指令的变更，因网络服务特性而特有的原因，例如境内外基础电信运营商的故障、计算机或互联网相关技术缺陷、互联网覆盖范围限制、计算机病毒、黑客攻击等因素，及其他合法范围内的不能预见、不能避免并不能克服的客观情况。</p></blockquote><p><br /></p><p><strong>8.禁止服务的商业化</strong></p><p><br /></p><blockquote><p>用户承诺，非经本公司同意，用户不能利用本公司各项服务进行销售或其他商业用途。如用户有需要将服务用于商业用途，应书面通知本公司并获得本公司的明确授权。</p></blockquote><p><br /></p><p><strong>9.用户管理</strong></p><p><br /></p><blockquote><p>用户独立承担其发布内容的责任。用户对服务的使用必须遵守所有适用于服务的地方法律、国家法律和国际法律。用户承诺：</p></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>（1）用户在本公司的网页上发布信息或者利用本公司的服务时必须符合中国有关法规，不得在本公司的网页上或者利用本公司的服务制作、复制、发布、传播以下信息：</p></blockquote></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>(a) 违反宪法确定的基本原则的；</p><p>(b) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p><p>(c) 损害国家荣誉和利益的；</p><p>(d) 煽动民族仇恨、民族歧视，破坏民族团结的；</p><p>(e) 破坏国家宗教政策，宣扬邪教和封建迷信的；</p><p>(f) 散布谣言，扰乱社会秩序，破坏社会稳定的；</p><p>(g) 散布淫秽、色情、赌博、暴力、恐怖或者教唆犯罪的；</p><p>(h) 侮辱或者诽谤他人，侵害他人合法权益的；</p><p>(i) 煽动非法集会、结社、游行、示威、聚众扰乱社会秩序的；</p><p>(j) 以非法民间组织名义活动的；</p><p>(k) 含有法律、行政法规禁止的其他内容的。</p></blockquote><p>（2）用户在本公司的网页上发布信息或者利用本公司的服务时还必须符合其他有关国家和地区的法律规定以及国际法的有关规定。用户需遵守法律法规的规定使用本公司微博客服务。</p><p>（3）用户不得利用本公司的服务从事以下活动：</p></blockquote></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>(a) 未经允许，进入计算机信息网络或者使用计算机信息网络资源的；</p><p>(b) 未经允许，对计算机信息网络功能进行删除、修改或者增加的；</p><p>(c) 未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的；</p><p>(d) 故意制作、传播计算机病毒等破坏性程序的；</p><p>(e) 其他危害计算机信息网络安全的行为。</p></blockquote><p>（4）用户不得以任何方式干扰本公司的服务。</p><p>（5）用户不得滥用本公司服务，包括但不限于：利用本公司提供的邮箱服务发送垃圾邮件，利用本公司服务进行侵害他人知识产权或者合法利益的其他行为。</p><p>（6）用户应遵守本公司的所有其他规定和程序。</p></blockquote><p>用户须对自己在使用本公司服务过程中的行为承担法律责任。用户承担法律责任的形式包括但不限于：对受到侵害者进行赔偿，以及在本公司首先承担了因用户行为导致的行政处罚或侵权损害赔偿责任后，用户应给予本公司等额的赔偿。若用户违反以上规定，本公司有权作出独立判断立即暂停或终止对用户提供部分或全部服务，包括冻结、取消用户服务帐号等措施。用户理解，如果本公司发现其网站传输的信息明显属于上段第(1)条所列内容之一，依据中国法律，本公司有义务立即停止传输，保存有关记录，向国家有关机关报告，并且删除含有该内容的地址、目录或关闭服务器。</p><p>用户使用本公司电子公告服务，包括电子布告牌、电子白板、电子论坛、网络聊天室和留言板等以交互形式为上网用户提供信息发布条件的行为，也须遵守本条的规定以及本公司将专门发布的电子公告服务规则，上段中描述的法律后果和法律责任同样适用于电子公告服务的用户。</p></blockquote><p><br /></p><p><strong>10.保障</strong></p><p><br /></p><blockquote><p>用户同意保障和维护本公司全体成员的利益，承担由用户违反服务条款的损害补偿费用，其它人使用用户的电脑、帐号而产生的费用。用户或者使用用户帐号的其他人在进行游戏过程中侵犯第三方知识产权及其他权利而导致被侵权人索赔的，由用户承担责任。</p><p>如用户或其它网络服务提供者利用本公司的服务侵害他人民事权益的，应当承担侵权等法律责任。</p><p>如用户利用本公司的服务实施侵权行为的，被侵权人有权通知本公司采取删除、屏蔽、断开链接等必要措施。本公司接到通知后，因自身过错若未及时采取必要措施的，按法律规定承担责任。</p></blockquote><p><br /></p><p><strong>11.通知</strong></p><p><br /></p><blockquote><p>所有发给用户的通知都可通过电子邮件、常规的信件或在网站显著位置公告的方式进行传送。本公司将通过上述方法之一将消息传递给用户，告知他们服务条款的修改、服务变更、或其它重要事情。同时，本公司保留对申请了本公司邮箱的用户投放商业性广告的权利。</p></blockquote><p><br /></p><p><strong>12.内容、商标的所有权</strong></p><blockquote><p><br /></p><p>本公司提供的内容包括但不限于：非用户上传/提供的文字、软件、声音、相片、视频、图表等。所有这些内容均属于本公司，并受版权、商标、专利和其它财产所有权法律的保护。所以，用户只能在本公司授权下才能使用这些内容，而不能擅自复制、再造这些内容、或创造与内容有关的派生产品。</p></blockquote><p><br /></p><p><strong>13.法律</strong></p><p><br /></p><blockquote><p>本条款适用中华人民共和国的法律，并且排除一切冲突法规定的适用。</p><p>如出现纠纷，用户和本公司一致同意将纠纷交由广州本公司计算机系统有限公司所在地法院管辖。</p></blockquote><p><br /></p><p><strong>14.信息储存及相关知识产权</strong></p><p><br /></p><blockquote><p>本公司对通行证上所有服务将尽力维护其安全性及方便性，但对服务中出现的信息（包括但不限于用户发布的信息）删除或储存失败不承担任何责任。另外本公司有权判定用户的行为是否符合本服务条款的要求，如果用户违背了本服务条款的规定，本公司有权中止或者终止对其本公司通行证帐号的服务。</p><p>本公司尊重知识产权并注重保护用户享有的各项权利。在本公司通行证所含服务中，用户可能需要通过上传、发布等各种方式向本公司提供内容。在此情况下，用户仍然享有此等内容的完整知识产权。用户在提供内容时将授予本公司一项全球性的免费许可，允许本公司使用、传播、复制、修改、再许可、翻译、创建衍生作品、出版、表演及展示此等内容。</p></blockquote><p><br /></p><p><strong>15.青少年用户特别提示</strong></p><p><br /></p><blockquote><p>青少年用户必须遵守全国青少年网络文明公约：</p></blockquote><blockquote><blockquote style="margin: 0px 0px 0px 40px; border: none; padding: 0px; "><p>要善于网上学习，不浏览不良信息；</p><p>要诚实友好交流，不侮辱欺诈他人；</p><p>要增强自护意识，不随意约会网友；</p><p>要维护网络安全，不破坏网络秩序；</p><p>要有益身心健康，不沉溺虚拟时空。</p></blockquote></blockquote><p><br /></p><p><strong>16.其他</strong></p><p><br /></p><blockquote><p>（1）本公司不行使、未能及时行使或者未充分行使本条款或者按照法律规定所享有的权利，不应被视为放弃该权利，也不影响本公司在将来行使该权利。</p><p>（2）如本条款中的任何条款无论因何种原因完全或部分无效或不具有执行力，本服务条款的其余条款仍应有效且具有约束力，本公司及用户均应尽力使该条款设定的本意得到实现。</p><p>（3）本条款中的标题仅为方便而设，不作为解释本条款的依据。</p></blockquote><p><br /></p><p>如用户对本条款内容有任何疑问，可拨打客服电话或登录帮助中心进行查询。</p>'),
(756, 1, 94, 0, '<p>经过反反复复的修改，4终于要和大家见面了！</p><p>这里可能你会失望，也可能有惊喜！</p><p><br /></p><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><strong><span style="font-size:18px;">4有什么令人期待的特性！</span></strong></p></blockquote><span style="line-height: 19px;"><br /></span><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p></p>1、第一次引入<span style="color: rgb(204, 0, 0); ">多站点模式</span>，支持同一个空间开通不同的站点，所有的站点都是在一个后台里管理（注意，这里不是站群）<br />2、<span style="color: rgb(204, 0, 0); ">支持手机访问自动切换到手机模式</span>（如果您的站点风格和手机风格不一样，只要按照一定的文件夹命名，即可轻松实现）<br />3、伪静态化更人性化<br />4、自带三个关键位置，分别是：title，keywords和description<br />5、<strong><span style="color:#990000;">强大的自定义功能</span></strong>，支持：会员字段，项目字段，会员组字段，分类扩展，全局扩展等等，都是可以自定义。使用了一整套的字段管理器<br />6、附件管理支持自定义分类，自定义目录<br />7、插件功能引入，这个功能在3.0版还是很菜的，不过在4.0版中有了很大的改进，会慢慢将教程文档放到网上<br /><p>8、各种配置XML化，如主题属性，附件类型都可以在XML扩展增加</p></blockquote><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><br /><p></p><p><span style="font-size:16px;"><strong>说了这么多好的，再来说说P4的短板吧！</strong></span></p><p><br /></p></blockquote><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">1、4不支持真实静态页功能，这确实很让人伤心。<br />2、要求启用缓存功能，在不启用缓存功能下，的性能很菜的（原因：太多自定义功能了，如果没有缓存，建议您自己租个服务器吧，要在虚拟主机环境下运行，一定要启用缓存）<br />3、自定义功能强大了，不过很多操作起来复杂了。官方已经在尽力简化操作了！但个人感觉还是比以前的复杂了。<br /><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><br /></p></blockquote><span style="line-height: 19px;">最后，祝大伙多多赚钱，嗯，也要注意保重身体：）</span></blockquote>');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_43`
--

DROP TABLE IF EXISTS `qinggan_list_43`;
CREATE TABLE IF NOT EXISTS `qinggan_list_43` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `sendtime` varchar(255) NOT NULL DEFAULT '' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发展历程';

--
-- 转存表中的数据 `qinggan_list_43`
--

INSERT INTO `qinggan_list_43` (`id`, `site_id`, `project_id`, `cate_id`, `sendtime`) VALUES
(730, 1, 91, 0, '1114185600'),
(731, 1, 91, 0, '1120147200'),
(732, 1, 91, 0, '1122825600'),
(734, 1, 91, 0, '1130774400'),
(735, 1, 91, 0, '1149091200'),
(736, 1, 91, 0, '1154361600'),
(737, 1, 91, 0, '1155571200'),
(738, 1, 91, 0, '1159632000'),
(739, 1, 91, 0, '1167580800'),
(740, 1, 91, 0, '1177948800'),
(741, 1, 91, 0, '1204300800'),
(742, 1, 91, 0, '1209571200'),
(743, 1, 91, 0, '1220198400'),
(744, 1, 91, 0, '1280592000'),
(745, 1, 91, 0, '1316016000'),
(746, 1, 91, 0, '1323705600');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_46`
--

DROP TABLE IF EXISTS `qinggan_list_46`;
CREATE TABLE IF NOT EXISTS `qinggan_list_46` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `fullname` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `content` longtext NOT NULL COMMENT '内容',
  `adm_reply` longtext NOT NULL COMMENT '管理员回复',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言模块';

--
-- 转存表中的数据 `qinggan_list_46`
--

INSERT INTO `qinggan_list_46` (`id`, `site_id`, `project_id`, `cate_id`, `fullname`, `email`, `content`, `adm_reply`) VALUES
(763, 1, 96, 0, 'seika', 'qinggan@188.com', '这个是测试用的！', '管理员回复！'),
(764, 1, 96, 0, '', '', '测试内容！', '测试管理员回复！'),
(1250, 1, 96, 0, 'seika', 'test@test.com', '这个也是测试用的~', '');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_list_47`
--

DROP TABLE IF EXISTS `qinggan_list_47`;
CREATE TABLE IF NOT EXISTS `qinggan_list_47` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `webico` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `note` longtext NOT NULL COMMENT '摘要',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`,`project_id`,`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='特色';

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_log`
--

DROP TABLE IF EXISTS `qinggan_log`;
CREATE TABLE IF NOT EXISTS `qinggan_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` text NOT NULL COMMENT '日志内容',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `app` varchar(100) NOT NULL COMMENT '控制器',
  `action` varchar(100) NOT NULL COMMENT '方法',
  `app_id` varchar(100) NOT NULL COMMENT '应用ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='日志表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `qinggan_log`
--

INSERT INTO `qinggan_log` (`id`, `title`, `addtime`, `app`, `action`, `app_id`) VALUES
(1, '0----0', '2013-11-11 11:45:31', 'cart', 'add', 'api');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_module`
--

DROP TABLE IF EXISTS `qinggan_module`;
CREATE TABLE IF NOT EXISTS `qinggan_module` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `title` varchar(255) NOT NULL COMMENT '模块名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不使用1使用',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '模块排序',
  `note` varchar(255) NOT NULL COMMENT '模块说明',
  `layout` text NOT NULL COMMENT '布局',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模块管理，每创建一个模块自动创建一个表' AUTO_INCREMENT=60 ;

--
-- 转存表中的数据 `qinggan_module`
--

INSERT INTO `qinggan_module` (`id`, `title`, `status`, `taxis`, `note`, `layout`) VALUES
(21, '主题+图片+链接', 1, 20, '适用于图片播放器，图片友情链接', 'pic,link,target'),
(22, '主题+摘要+缩略图+内容', 1, 10, '适用于新闻，文章之类', 'hits,dateline,thumb'),
(23, '主题+链接', 1, 30, '适用于导航，页脚文本导航，文字友情链接', 'link,target'),
(24, '主题+缩略图+多图+详细介绍', 1, 40, '适用于产品展示', 'hits,dateline,thumb'),
(40, '主题+内容', 1, 60, '适用简单的新闻，单页面', 'hits,dateline'),
(43, '主题+发展时间', 1, 70, '适用于发展历程，一般是按设定的时间在前台倒序排放', 'sendtime'),
(46, '留言模块', 1, 100, '', 'dateline,fullname,email,content'),
(47, '特色', 1, 110, '', 'note,webico');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_module_fields`
--

DROP TABLE IF EXISTS `qinggan_module_fields`;
CREATE TABLE IF NOT EXISTS `qinggan_module_fields` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段ID，自增',
  `module_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '模块ID',
  `title` varchar(255) NOT NULL COMMENT '字段名称',
  `identifier` varchar(50) NOT NULL COMMENT '字段标识串',
  `field_type` varchar(255) NOT NULL DEFAULT '200' COMMENT '字段存储类型',
  `note` varchar(255) NOT NULL COMMENT '字段内容备注',
  `form_type` varchar(100) NOT NULL COMMENT '表单类型',
  `form_style` varchar(255) NOT NULL COMMENT '表单CSS',
  `format` varchar(100) NOT NULL COMMENT '格式化方式',
  `content` varchar(255) NOT NULL COMMENT '默认值',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `ext` text NOT NULL COMMENT '扩展内容',
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='字段管理器' AUTO_INCREMENT=201 ;

--
-- 转存表中的数据 `qinggan_module_fields`
--

INSERT INTO `qinggan_module_fields` (`id`, `module_id`, `title`, `identifier`, `field_type`, `note`, `form_type`, `form_style`, `format`, `content`, `taxis`, `ext`) VALUES
(92, 21, '链接', 'link', 'longtext', '', 'text', '', 'safe', '', 90, 'a:2:{s:8:"form_btn";s:3:"url";s:5:"width";s:3:"500";}'),
(82, 22, '缩略图', 'thumb', 'varchar', '', 'upload', '', 'safe', '', 30, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";b:0;}'),
(83, 22, '内容', 'content', 'longtext', '', 'editor', '', 'html_js', '', 255, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"360";s:7:"is_code";b:0;s:9:"btn_image";s:1:"1";s:9:"btn_video";s:1:"1";s:8:"btn_file";s:1:"1";s:8:"btn_page";b:0;}'),
(84, 23, '链接', 'link', 'longtext', '设置导航链接', 'url', '', 'safe', '', 90, 'a:1:{s:5:"width";s:3:"500";}'),
(85, 23, '链接方式', 'target', 'varchar', '设置是否在新窗口打开', 'radio', '', 'safe', '_self', 100, 'a:1:{s:11:"option_list";s:5:"opt:6";}'),
(87, 24, '缩略图', 'thumb', 'varchar', '主要应用于列表及首页调用中使用', 'upload', '', 'safe', '', 30, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";b:0;}'),
(88, 24, '图片', 'pictures', 'varchar', '设置产品的图片，支持多图', 'upload', '', 'safe', '', 50, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";s:1:"1";}'),
(90, 24, '内容', 'content', 'longtext', '填写产品介绍信息', 'editor', '', 'html_js', '', 255, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"360";s:7:"is_code";i:0;s:9:"btn_image";i:1;s:9:"btn_video";i:1;s:8:"btn_file";i:1;s:8:"btn_page";b:0;}'),
(93, 21, '链接方式', 'target', 'varchar', '设置是否在新窗口打开', 'radio', '', 'safe', '_self', 100, 'a:3:{s:11:"option_list";s:5:"opt:6";s:9:"put_order";b:0;s:10:"ext_select";b:0;}'),
(131, 40, '内容', 'content', 'longtext', '', 'editor', '', 'html_js', '', 255, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"360";s:7:"is_code";b:0;s:9:"btn_image";s:1:"1";s:9:"btn_video";s:1:"1";s:8:"btn_file";s:1:"1";s:8:"btn_page";b:0;}'),
(137, 43, '历程时间', 'sendtime', 'varchar', '发展历程的变格时间，前台仅显示年月', 'text', '', 'time', '', 20, 'a:2:{s:8:"form_btn";s:4:"date";s:5:"width";s:3:"300";}'),
(141, 46, '姓名', 'fullname', 'varchar', '', 'text', '', 'safe', '', 10, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}'),
(142, 46, '邮箱', 'email', 'varchar', '', 'text', '', 'safe', '', 130, 'a:2:{s:8:"form_btn";b:0;s:5:"width";b:0;}'),
(143, 46, '内容', 'content', 'longtext', '', 'textarea', '', 'safe', '', 200, 'a:2:{s:5:"width";s:3:"660";s:6:"height";s:3:"180";}'),
(144, 46, '管理员回复', 'adm_reply', 'longtext', '', 'editor', '', 'html', '', 255, 'a:7:{s:5:"width";s:3:"800";s:6:"height";s:3:"100";s:7:"is_code";i:0;s:9:"btn_image";i:0;s:9:"btn_video";i:0;s:8:"btn_file";i:0;s:8:"btn_page";b:0;}'),
(145, 47, '图标', 'webico', 'varchar', '宽100px，高100px', 'upload', '', 'safe', '', 10, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";b:0;}'),
(146, 47, '摘要', 'note', 'longtext', '简单的用一两句话来说明', 'text', '', 'safe', '', 20, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"500";}'),
(200, 21, '图片', 'pic', 'varchar', '', 'upload', '', 'safe', '', 20, 'a:3:{s:11:"upload_type";s:7:"picture";s:7:"cate_id";s:1:"1";s:11:"is_multiple";b:0;}'),
(177, 22, '摘要', 'note', 'longtext', '简要文字描述', 'textarea', '', 'safe', '', 20, 'a:2:{s:5:"width";s:3:"800";s:6:"height";s:2:"80";}');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_opt`
--

DROP TABLE IF EXISTS `qinggan_opt`;
CREATE TABLE IF NOT EXISTS `qinggan_opt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '组ID',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `title` varchar(255) NOT NULL COMMENT '名称',
  `val` varchar(255) NOT NULL COMMENT '值',
  `taxis` int(10) unsigned NOT NULL DEFAULT '255' COMMENT '排序，值越小越往前靠',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='表单列表选项' AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `qinggan_opt`
--

INSERT INTO `qinggan_opt` (`id`, `group_id`, `parent_id`, `title`, `val`, `taxis`) VALUES
(1, 1, 0, '女', '0', 20),
(2, 1, 0, '男', '1', 10),
(5, 2, 0, '福建省', '福建省', 255),
(6, 2, 5, '泉州市', '泉州市', 255),
(7, 2, 6, '永春县', '永春县', 255),
(8, 2, 7, '一都镇', '一都镇', 255),
(9, 2, 8, '美岭村', '美岭村', 255),
(11, 2, 0, '广东省', '广东省', 255),
(12, 2, 11, '深圳市', '深圳市', 255),
(13, 2, 12, '龙岗区', '龙岗区', 10),
(14, 2, 12, '罗湖区', '罗湖区', 20),
(15, 2, 12, '福田区', '福田区', 30),
(16, 2, 12, '龙华区', '龙华区', 40),
(17, 4, 0, '是', '1', 10),
(18, 4, 0, '否', '0', 20),
(21, 6, 0, '当前窗口', '_self', 10),
(22, 6, 0, '新窗口', '_blank', 20),
(23, 7, 0, '启用', '1', 10),
(24, 7, 0, '禁用', '0', 20),
(25, 8, 0, 'UTF-8', 'utf8', 20),
(26, 8, 0, 'GBK', 'gbk', 10);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_opt_group`
--

DROP TABLE IF EXISTS `qinggan_opt_group`;
CREATE TABLE IF NOT EXISTS `qinggan_opt_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID ',
  `title` varchar(100) NOT NULL COMMENT '名称，用于后台管理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='可选菜单管理器' AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `qinggan_opt_group`
--

INSERT INTO `qinggan_opt_group` (`id`, `title`) VALUES
(1, '性别'),
(2, '省市县多级联动'),
(4, '是与否'),
(6, '窗口打开方式'),
(7, '注册'),
(8, '邮件编码');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_order`
--

DROP TABLE IF EXISTS `qinggan_order`;
CREATE TABLE IF NOT EXISTS `qinggan_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `sn` varchar(255) NOT NULL COMMENT '订单编号，唯一值',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID号，为0表示游客',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `qty` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品数量',
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币金额',
  `status` varchar(255) NOT NULL COMMENT '订单的最后状态',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `pay_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '付款方式的ID',
  `pay_price` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '付款价格',
  `pay_currency` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '货币符号',
  `pay_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `pay_status` varchar(255) NOT NULL COMMENT '付款状态（状态参数是受不同的接口影响的）',
  `passwd` varchar(255) NOT NULL COMMENT '密码串',
  `ext` text NOT NULL COMMENT '扩展内容信息，可用于存储一些扩展信息',
  `note` text NOT NULL COMMENT '摘要',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordersn` (`sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单中心' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_order_address`
--

DROP TABLE IF EXISTS `qinggan_order_address`;
CREATE TABLE IF NOT EXISTS `qinggan_order_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `country` varchar(255) NOT NULL DEFAULT '中国' COMMENT '国家',
  `province` varchar(255) NOT NULL COMMENT '省信息',
  `city` varchar(255) NOT NULL COMMENT '市',
  `county` varchar(255) NOT NULL COMMENT '县',
  `address` varchar(255) NOT NULL COMMENT '地址信息（不含国家，省市县镇区信息）',
  `zipcode` varchar(20) NOT NULL COMMENT '邮编',
  `type_id` enum('shipping','billing') NOT NULL DEFAULT 'shipping' COMMENT '类型，默认走送货地址',
  `mobile` varchar(100) NOT NULL COMMENT '手机号码',
  `tel` varchar(100) NOT NULL COMMENT '电话号码',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `fullname` varchar(100) NOT NULL COMMENT '联系人姓名',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0女1男',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单地址库' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_order_product`
--

DROP TABLE IF EXISTS `qinggan_order_product`;
CREATE TABLE IF NOT EXISTS `qinggan_order_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID号',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `title` varchar(255) NOT NULL COMMENT '产品名称',
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '产品单价',
  `qty` int(11) NOT NULL DEFAULT '0' COMMENT '产品数量',
  `thumb` int(11) NOT NULL COMMENT '产品图片ID',
  `ext` text NOT NULL COMMENT '产品扩展属性',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单的产品信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_payment`
--

DROP TABLE IF EXISTS `qinggan_payment`;
CREATE TABLE IF NOT EXISTS `qinggan_payment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `code` varchar(100) NOT NULL COMMENT '标识ID',
  `title` varchar(255) NOT NULL COMMENT '主题',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态0未使用1正在使用中',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序，值越小越往前靠',
  `note` text NOT NULL COMMENT '付款注意事项说明',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '0表示所有站点使用，非0表示指定的站点使用',
  `param` text NOT NULL COMMENT '参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_phpok`
--

DROP TABLE IF EXISTS `qinggan_phpok`;
CREATE TABLE IF NOT EXISTS `qinggan_phpok` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID',
  `type_id` varchar(255) NOT NULL COMMENT '调用类型',
  `identifier` varchar(100) NOT NULL COMMENT '标识串，同一个站点中只能唯一',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '站点ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `cateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `ext` text NOT NULL COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`,`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- 转存表中的数据 `qinggan_phpok`
--

INSERT INTO `qinggan_phpok` (`id`, `title`, `pid`, `type_id`, `identifier`, `site_id`, `status`, `cateid`, `ext`) VALUES
(18, '网站首页图片播放', 41, 'arclist', 'picplayer', 1, 1, 0, 'a:19:{s:5:"psize";i:5;s:6:"offset";b:0;s:7:"is_list";i:1;s:7:"in_text";b:0;s:4:"attr";b:0;s:11:"fields_need";b:0;s:3:"tag";b:0;s:8:"keywords";b:0;s:7:"orderby";b:0;s:4:"cate";b:0;s:8:"cate_ext";b:0;s:12:"catelist_ext";b:0;s:11:"project_ext";b:0;s:11:"sublist_ext";b:0;s:10:"parent_ext";b:0;s:13:"fields_format";b:0;s:8:"user_ext";b:0;s:4:"user";b:0;s:12:"userlist_ext";b:0;}'),
(19, '头部导航内容', 42, 'arclist', 'menu', 1, 1, 0, 'a:20:{s:5:"psize";b:0;s:6:"offset";b:0;s:7:"is_list";i:1;s:7:"in_text";i:1;s:4:"attr";b:0;s:11:"fields_need";b:0;s:3:"tag";b:0;s:8:"keywords";b:0;s:7:"orderby";b:0;s:4:"cate";b:0;s:8:"cate_ext";b:0;s:12:"catelist_ext";b:0;s:11:"project_ext";b:0;s:11:"sublist_ext";b:0;s:10:"parent_ext";b:0;s:13:"fields_format";b:0;s:8:"user_ext";b:0;s:4:"user";b:0;s:12:"userlist_ext";b:0;s:6:"in_sub";i:1;}'),
(20, '公司简介', 90, 'project', 'aboutus', 1, 1, 0, 'a:20:{s:5:"psize";b:0;s:6:"offset";b:0;s:7:"is_list";b:0;s:7:"in_text";b:0;s:4:"attr";b:0;s:11:"fields_need";b:0;s:3:"tag";b:0;s:8:"keywords";b:0;s:7:"orderby";b:0;s:4:"cate";b:0;s:8:"cate_ext";b:0;s:12:"catelist_ext";b:0;s:11:"project_ext";i:1;s:11:"sublist_ext";b:0;s:10:"parent_ext";b:0;s:13:"fields_format";b:0;s:8:"user_ext";b:0;s:4:"user";b:0;s:12:"userlist_ext";b:0;s:6:"in_sub";b:0;}'),
(21, '产品分类', 45, 'catelist', 'products_cate', 1, 1, 70, 'a:20:{s:5:"psize";b:0;s:6:"offset";b:0;s:7:"is_list";b:0;s:7:"in_text";b:0;s:4:"attr";b:0;s:11:"fields_need";b:0;s:3:"tag";b:0;s:8:"keywords";b:0;s:7:"orderby";b:0;s:4:"cate";b:0;s:8:"cate_ext";b:0;s:12:"catelist_ext";b:0;s:11:"project_ext";b:0;s:11:"sublist_ext";b:0;s:10:"parent_ext";b:0;s:13:"fields_format";b:0;s:8:"user_ext";b:0;s:4:"user";b:0;s:12:"userlist_ext";b:0;s:6:"in_sub";b:0;}'),
(22, '最新产品', 45, 'arclist', 'new_products', 1, 1, 70, 'a:20:{s:5:"psize";i:8;s:6:"offset";b:0;s:7:"is_list";i:1;s:7:"in_text";i:1;s:4:"attr";b:0;s:11:"fields_need";s:9:"ext.thumb";s:3:"tag";b:0;s:8:"keywords";b:0;s:7:"orderby";b:0;s:4:"cate";b:0;s:8:"cate_ext";b:0;s:12:"catelist_ext";b:0;s:11:"project_ext";b:0;s:11:"sublist_ext";b:0;s:10:"parent_ext";b:0;s:13:"fields_format";b:0;s:8:"user_ext";b:0;s:4:"user";b:0;s:12:"userlist_ext";b:0;s:6:"in_sub";b:0;}'),
(55, '友情链接', 95, 'arclist', 'link', 1, 1, 0, 'a:20:{s:5:"psize";i:30;s:6:"offset";b:0;s:7:"is_list";i:1;s:7:"in_text";i:1;s:4:"attr";b:0;s:11:"fields_need";b:0;s:3:"tag";b:0;s:8:"keywords";b:0;s:7:"orderby";b:0;s:4:"cate";b:0;s:8:"cate_ext";b:0;s:12:"catelist_ext";b:0;s:11:"project_ext";b:0;s:11:"sublist_ext";b:0;s:10:"parent_ext";b:0;s:13:"fields_format";b:0;s:8:"user_ext";b:0;s:4:"user";b:0;s:12:"userlist_ext";b:0;s:6:"in_sub";b:0;}');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_plugins`
--

DROP TABLE IF EXISTS `qinggan_plugins`;
CREATE TABLE IF NOT EXISTS `qinggan_plugins` (
  `id` varchar(100) NOT NULL COMMENT '插件ID，仅限字母，数字及下划线',
  `title` varchar(255) NOT NULL COMMENT '插件名称',
  `author` varchar(255) NOT NULL COMMENT '开发者',
  `version` varchar(50) NOT NULL COMMENT '插件版本号',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0禁用1使用',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '值越小越往前靠',
  `note` varchar(255) NOT NULL COMMENT '摘要说明',
  `param` text NOT NULL COMMENT '参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件管理器';

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_popedom`
--

DROP TABLE IF EXISTS `qinggan_popedom`;
CREATE TABLE IF NOT EXISTS `qinggan_popedom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID，即自增ID',
  `gid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属组ID，对应sysmenu表中的ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '项目ID，仅在list中有效',
  `title` varchar(255) NOT NULL COMMENT '名称，如：添加，修改等',
  `identifier` varchar(255) NOT NULL COMMENT '字符串，如add，modify等',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限明细' AUTO_INCREMENT=606 ;

--
-- 转存表中的数据 `qinggan_popedom`
--

INSERT INTO `qinggan_popedom` (`id`, `gid`, `pid`, `title`, `identifier`, `taxis`) VALUES
(2, 19, 0, '配置全局', 'gset', 10),
(3, 19, 0, '内容', 'set', 20),
(4, 29, 0, '添加', 'add', 10),
(5, 29, 0, '修改', 'modify', 20),
(6, 29, 0, '删除', 'delete', 30),
(7, 18, 0, '添加', 'add', 10),
(8, 18, 0, '编辑', 'modify', 20),
(9, 18, 0, '删除', 'delete', 30),
(10, 23, 0, '添加', 'add', 10),
(11, 23, 0, '编辑', 'modify', 20),
(12, 23, 0, '删除', 'delete', 30),
(14, 22, 0, 'GD配置', 'gd', 10),
(15, 22, 0, '附件分类维护', 'cate', 20),
(16, 22, 0, '批处理', 'pl', 30),
(17, 16, 0, '配置', 'config', 10),
(18, 16, 0, '安装', 'install', 20),
(19, 16, 0, '卸载', 'uninstall', 30),
(20, 16, 0, '删除', 'delete', 40),
(21, 17, 0, '添加', 'add', 20),
(22, 13, 0, '添加', 'add', 10),
(23, 13, 0, '修改', 'modify', 20),
(24, 13, 0, '删除', 'delete', 30),
(25, 13, 0, '查看', 'list', 5),
(26, 19, 0, '查看', 'list', 5),
(27, 20, 0, '查看', 'list', 10),
(28, 20, 0, '编辑', 'set', 20),
(29, 20, 0, '添加', 'add', 30),
(30, 20, 0, '修改', 'modify', 40),
(31, 20, 0, '删除', 'delete', 50),
(32, 20, 0, '启用/禁用', 'status', 60),
(33, 21, 0, '查看', 'list', 10),
(34, 21, 0, '添加', 'add', 20),
(35, 21, 0, '编辑', 'modify', 30),
(36, 21, 0, '删除', 'delete', 40),
(37, 18, 0, '查看', 'list', 5),
(38, 23, 0, '查看', 'list', 5),
(83, 16, 0, '启用/禁用', 'status', 50),
(40, 16, 0, '查看', 'list', 5),
(41, 17, 0, '查看', 'list', 10),
(42, 18, 0, '扩展', 'ext', 40),
(43, 19, 0, '扩展', 'ext', 30),
(44, 14, 0, '查看', 'list', 10),
(45, 14, 0, '添加', 'add', 20),
(46, 14, 0, '修改', 'modify', 30),
(47, 14, 0, '删除', 'delete', 40),
(48, 25, 0, '查看', 'list', 10),
(49, 25, 0, '配置', 'set', 20),
(80, 14, 0, '启用/禁用', 'status', 50),
(52, 29, 0, '查看', 'list', 5),
(53, 27, 0, '查看', 'list', 10),
(54, 27, 0, '配置', 'set', 20),
(81, 19, 0, '网站', 'site', 40),
(82, 19, 0, '域名', 'domain', 50),
(58, 8, 0, '查看', 'list', 10),
(59, 8, 0, '维护', 'set', 20),
(84, 20, 1, '查看', 'list', 10),
(85, 20, 1, '编辑', 'set', 20),
(63, 6, 0, '查看', 'list', 10),
(64, 6, 0, '维护', 'set', 20),
(86, 20, 22, '查看', 'list', 10),
(67, 7, 0, '查看', 'list', 10),
(68, 7, 0, '添加', 'add', 20),
(69, 7, 0, '修改', 'modify', 30),
(70, 7, 0, '删除', 'delete', 40),
(71, 28, 0, '查看', 'list', 10),
(72, 28, 0, '添加', 'add', 20),
(73, 28, 0, '修改', 'modify', 30),
(74, 28, 0, '删除', 'delete', 40),
(75, 9, 0, '查看', 'list', 10),
(76, 9, 0, '添加', 'add', 20),
(77, 9, 0, '修改', 'modify', 30),
(78, 9, 0, '启用/禁用', 'status', 40),
(79, 29, 0, '启用/禁用', 'status', 40),
(87, 20, 22, '添加', 'add', 30),
(88, 20, 22, '修改', 'modify', 40),
(89, 20, 22, '删除', 'delete', 50),
(90, 20, 22, '启用/禁用', 'status', 60),
(91, 20, 24, '查看', 'list', 10),
(92, 20, 24, '添加', 'add', 30),
(93, 20, 24, '修改', 'modify', 40),
(94, 20, 24, '删除', 'delete', 50),
(95, 20, 24, '启用/禁用', 'status', 60),
(96, 20, 26, '查看', 'list', 10),
(97, 20, 26, '添加', 'add', 30),
(98, 20, 26, '修改', 'modify', 40),
(99, 20, 26, '删除', 'delete', 50),
(100, 20, 26, '启用/禁用', 'status', 60),
(101, 20, 25, '查看', 'list', 10),
(102, 20, 25, '编辑', 'set', 20),
(103, 20, 25, '添加', 'add', 30),
(104, 20, 25, '修改', 'modify', 40),
(105, 20, 25, '删除', 'delete', 50),
(106, 20, 25, '启用/禁用', 'status', 60),
(107, 20, 33, '查看', 'list', 10),
(108, 20, 33, '编辑', 'set', 20),
(109, 20, 34, '查看', 'list', 10),
(110, 20, 34, '添加', 'add', 30),
(111, 20, 34, '修改', 'modify', 40),
(112, 20, 34, '删除', 'delete', 50),
(113, 20, 34, '启用/禁用', 'status', 60),
(114, 20, 35, '查看', 'list', 10),
(115, 20, 35, '编辑', 'set', 20),
(116, 20, 36, '查看', 'list', 10),
(117, 20, 36, '编辑', 'set', 20),
(118, 20, 37, '查看', 'list', 10),
(119, 20, 37, '添加', 'add', 30),
(120, 20, 37, '修改', 'modify', 40),
(121, 20, 37, '删除', 'delete', 50),
(122, 20, 37, '启用/禁用', 'status', 60),
(123, 20, 38, '查看', 'list', 10),
(124, 20, 38, '添加', 'add', 30),
(125, 20, 38, '修改', 'modify', 40),
(126, 20, 38, '删除', 'delete', 50),
(127, 20, 38, '启用/禁用', 'status', 60),
(128, 20, 39, '查看', 'list', 10),
(129, 20, 39, '添加', 'add', 30),
(130, 20, 39, '修改', 'modify', 40),
(131, 20, 39, '删除', 'delete', 50),
(132, 20, 39, '启用/禁用', 'status', 60),
(133, 30, 0, '查看', 'list', 10),
(134, 30, 0, '设置', 'set', 20),
(135, 30, 0, '文件管理', 'filelist', 30),
(136, 30, 0, '删除', 'delete', 40),
(137, 20, 40, '查看', 'list', 10),
(138, 20, 40, '添加', 'add', 30),
(139, 20, 40, '修改', 'modify', 40),
(140, 20, 40, '删除', 'delete', 50),
(141, 20, 40, '启用/禁用', 'status', 60),
(142, 13, 0, '审核', 'status', 40),
(143, 20, 41, '查看', 'list', 10),
(144, 20, 41, '添加', 'add', 30),
(145, 20, 41, '修改', 'modify', 40),
(146, 20, 41, '删除', 'delete', 50),
(147, 20, 41, '启用/禁用', 'status', 60),
(148, 20, 42, '查看', 'list', 10),
(149, 20, 42, '添加', 'add', 30),
(150, 20, 42, '修改', 'modify', 40),
(151, 20, 42, '删除', 'delete', 50),
(152, 20, 42, '启用/禁用', 'status', 60),
(153, 20, 43, '查看', 'list', 10),
(154, 20, 43, '添加', 'add', 30),
(155, 20, 43, '修改', 'modify', 40),
(156, 20, 43, '删除', 'delete', 50),
(157, 20, 43, '启用/禁用', 'status', 60),
(162, 31, 0, '添加站点', 'add', 40),
(159, 31, 0, '查看', 'list', 10),
(160, 31, 0, '删除', 'delete', 20),
(161, 31, 0, '设为默认', 'default', 30),
(163, 20, 44, '查看', 'list', 10),
(164, 20, 44, '编辑', 'set', 20),
(165, 20, 45, '查看', 'list', 10),
(166, 20, 45, '添加', 'add', 30),
(167, 20, 45, '修改', 'modify', 40),
(168, 20, 45, '删除', 'delete', 50),
(169, 20, 45, '启用/禁用', 'status', 60),
(170, 19, 0, '添加站点', 'add', 60),
(171, 20, 46, '查看', 'list', 10),
(172, 20, 46, '添加', 'add', 30),
(173, 20, 46, '修改', 'modify', 40),
(174, 20, 46, '删除', 'delete', 50),
(175, 20, 46, '启用/禁用', 'status', 60),
(176, 20, 47, '查看', 'list', 10),
(177, 20, 47, '添加', 'add', 30),
(178, 20, 47, '修改', 'modify', 40),
(179, 20, 47, '删除', 'delete', 50),
(180, 20, 47, '启用/禁用', 'status', 60),
(181, 20, 48, '查看', 'list', 10),
(182, 20, 48, '添加', 'add', 30),
(183, 20, 48, '修改', 'modify', 40),
(184, 20, 48, '删除', 'delete', 50),
(185, 20, 48, '启用/禁用', 'status', 60),
(186, 20, 49, '查看', 'list', 10),
(187, 20, 49, '添加', 'add', 30),
(188, 20, 49, '修改', 'modify', 40),
(189, 20, 49, '删除', 'delete', 50),
(190, 20, 49, '启用/禁用', 'status', 60),
(191, 20, 50, '查看', 'list', 10),
(192, 20, 50, '添加', 'add', 30),
(193, 20, 50, '修改', 'modify', 40),
(194, 20, 50, '删除', 'delete', 50),
(195, 20, 50, '启用/禁用', 'status', 60),
(196, 20, 51, '查看', 'list', 10),
(197, 20, 51, '编辑', 'set', 20),
(198, 20, 51, '添加', 'add', 30),
(199, 20, 51, '修改', 'modify', 40),
(200, 20, 51, '删除', 'delete', 50),
(201, 20, 51, '启用/禁用', 'status', 60),
(202, 20, 52, '查看', 'list', 10),
(203, 20, 52, '编辑', 'set', 20),
(204, 20, 53, '查看', 'list', 10),
(205, 20, 53, '编辑', 'set', 20),
(206, 20, 54, '查看', 'list', 10),
(207, 20, 54, '添加', 'add', 30),
(208, 20, 54, '修改', 'modify', 40),
(209, 20, 54, '删除', 'delete', 50),
(210, 20, 54, '启用/禁用', 'status', 60),
(211, 20, 55, '查看', 'list', 10),
(212, 20, 55, '添加', 'add', 30),
(213, 20, 55, '修改', 'modify', 40),
(214, 20, 55, '删除', 'delete', 50),
(215, 20, 55, '启用/禁用', 'status', 60),
(216, 20, 56, '查看', 'list', 10),
(217, 20, 56, '编辑', 'set', 20),
(218, 20, 57, '查看', 'list', 10),
(219, 20, 57, '添加', 'add', 30),
(220, 20, 57, '修改', 'modify', 40),
(221, 20, 57, '删除', 'delete', 50),
(222, 20, 57, '启用/禁用', 'status', 60),
(223, 20, 58, '查看', 'list', 10),
(224, 20, 58, '添加', 'add', 30),
(225, 20, 58, '修改', 'modify', 40),
(226, 20, 58, '删除', 'delete', 50),
(227, 20, 58, '启用/禁用', 'status', 60),
(228, 20, 59, '查看', 'list', 10),
(229, 20, 59, '添加', 'add', 30),
(230, 20, 59, '修改', 'modify', 40),
(231, 20, 59, '删除', 'delete', 50),
(232, 20, 59, '启用/禁用', 'status', 60),
(233, 20, 60, '查看', 'list', 10),
(234, 20, 60, '添加', 'add', 30),
(235, 20, 60, '修改', 'modify', 40),
(236, 20, 60, '删除', 'delete', 50),
(237, 20, 60, '启用/禁用', 'status', 60),
(238, 20, 61, '查看', 'list', 10),
(239, 20, 61, '添加', 'add', 30),
(240, 20, 61, '修改', 'modify', 40),
(241, 20, 61, '删除', 'delete', 50),
(242, 20, 61, '启用/禁用', 'status', 60),
(243, 20, 62, '查看', 'list', 10),
(244, 20, 62, '编辑', 'set', 20),
(245, 20, 62, '添加', 'add', 30),
(246, 20, 62, '修改', 'modify', 40),
(247, 20, 62, '删除', 'delete', 50),
(248, 20, 62, '启用/禁用', 'status', 60),
(249, 20, 63, '查看', 'list', 10),
(250, 20, 63, '编辑', 'set', 20),
(251, 20, 64, '查看', 'list', 10),
(252, 20, 64, '编辑', 'set', 20),
(253, 20, 65, '查看', 'list', 10),
(254, 20, 65, '添加', 'add', 30),
(255, 20, 65, '修改', 'modify', 40),
(256, 20, 65, '删除', 'delete', 50),
(257, 20, 65, '启用/禁用', 'status', 60),
(258, 20, 66, '查看', 'list', 10),
(259, 20, 66, '添加', 'add', 30),
(260, 20, 66, '修改', 'modify', 40),
(261, 20, 66, '删除', 'delete', 50),
(262, 20, 66, '启用/禁用', 'status', 60),
(263, 20, 67, '查看', 'list', 10),
(264, 20, 67, '编辑', 'set', 20),
(265, 20, 68, '查看', 'list', 10),
(266, 20, 68, '添加', 'add', 30),
(267, 20, 68, '修改', 'modify', 40),
(268, 20, 68, '删除', 'delete', 50),
(269, 20, 68, '启用/禁用', 'status', 60),
(270, 20, 69, '查看', 'list', 10),
(271, 20, 69, '添加', 'add', 30),
(272, 20, 69, '修改', 'modify', 40),
(273, 20, 69, '删除', 'delete', 50),
(274, 20, 69, '启用/禁用', 'status', 60),
(275, 20, 70, '查看', 'list', 10),
(276, 20, 70, '添加', 'add', 30),
(277, 20, 70, '修改', 'modify', 40),
(278, 20, 70, '删除', 'delete', 50),
(279, 20, 70, '启用/禁用', 'status', 60),
(280, 20, 71, '查看', 'list', 10),
(281, 20, 71, '编辑', 'set', 20),
(282, 20, 72, '查看', 'list', 10),
(283, 20, 72, '编辑', 'set', 20),
(284, 20, 73, '查看', 'list', 10),
(285, 20, 73, '编辑', 'set', 20),
(286, 20, 74, '查看', 'list', 10),
(287, 20, 74, '编辑', 'set', 20),
(288, 20, 75, '查看', 'list', 10),
(289, 20, 75, '编辑', 'set', 20),
(290, 20, 76, '查看', 'list', 10),
(291, 20, 76, '编辑', 'set', 20),
(292, 20, 77, '查看', 'list', 10),
(293, 20, 77, '编辑', 'set', 20),
(294, 20, 77, '添加', 'add', 30),
(295, 20, 77, '修改', 'modify', 40),
(296, 20, 77, '删除', 'delete', 50),
(297, 20, 77, '启用/禁用', 'status', 60),
(298, 20, 78, '查看', 'list', 10),
(299, 20, 78, '添加', 'add', 30),
(300, 20, 78, '修改', 'modify', 40),
(301, 20, 78, '删除', 'delete', 50),
(302, 20, 78, '启用/禁用', 'status', 60),
(303, 20, 79, '查看', 'list', 10),
(304, 20, 79, '添加', 'add', 30),
(305, 20, 79, '修改', 'modify', 40),
(306, 20, 79, '删除', 'delete', 50),
(307, 20, 79, '启用/禁用', 'status', 60),
(308, 20, 80, '查看', 'list', 10),
(309, 20, 80, '编辑', 'set', 20),
(310, 20, 81, '查看', 'list', 10),
(311, 20, 81, '添加', 'add', 30),
(312, 20, 81, '修改', 'modify', 40),
(313, 20, 81, '删除', 'delete', 50),
(314, 20, 81, '启用/禁用', 'status', 60),
(315, 20, 82, '查看', 'list', 10),
(316, 20, 82, '添加', 'add', 30),
(317, 20, 82, '修改', 'modify', 40),
(318, 20, 82, '删除', 'delete', 50),
(319, 20, 82, '启用/禁用', 'status', 60),
(320, 20, 83, '查看', 'list', 10),
(321, 20, 83, '编辑', 'set', 20),
(322, 20, 83, '添加', 'add', 30),
(323, 20, 83, '修改', 'modify', 40),
(324, 20, 83, '删除', 'delete', 50),
(325, 20, 83, '启用/禁用', 'status', 60),
(326, 20, 84, '查看', 'list', 10),
(327, 20, 84, '编辑', 'set', 20),
(328, 20, 84, '添加', 'add', 30),
(329, 20, 84, '修改', 'modify', 40),
(330, 20, 84, '删除', 'delete', 50),
(331, 20, 84, '启用/禁用', 'status', 60),
(332, 20, 85, '查看', 'list', 10),
(333, 20, 85, '编辑', 'set', 20),
(334, 20, 85, '添加', 'add', 30),
(335, 20, 85, '修改', 'modify', 40),
(336, 20, 85, '删除', 'delete', 50),
(337, 20, 85, '启用/禁用', 'status', 60),
(338, 20, 86, '查看', 'list', 10),
(339, 20, 86, '编辑', 'set', 20),
(340, 20, 86, '添加', 'add', 30),
(341, 20, 86, '修改', 'modify', 40),
(342, 20, 86, '删除', 'delete', 50),
(343, 20, 86, '启用/禁用', 'status', 60),
(344, 32, 0, '查看', 'list', 10),
(345, 32, 0, '启用/禁用', 'status', 20),
(346, 32, 0, '删除', 'delete', 30),
(347, 32, 0, '修改', 'modify', 40),
(348, 32, 0, '回复', 'reply', 50),
(349, 20, 87, '查看', 'list', 10),
(350, 20, 87, '添加', 'add', 30),
(351, 20, 87, '修改', 'modify', 40),
(352, 20, 87, '删除', 'delete', 50),
(353, 20, 87, '启用/禁用', 'status', 60),
(354, 20, 88, '查看', 'list', 10),
(355, 20, 88, '添加', 'add', 30),
(356, 20, 88, '修改', 'modify', 40),
(357, 20, 88, '删除', 'delete', 50),
(358, 20, 88, '启用/禁用', 'status', 60),
(359, 20, 89, '查看', 'list', 10),
(360, 20, 89, '添加', 'add', 30),
(361, 20, 89, '修改', 'modify', 40),
(362, 20, 89, '删除', 'delete', 50),
(363, 20, 89, '启用/禁用', 'status', 60),
(364, 20, 90, '查看', 'list', 10),
(365, 20, 90, '编辑', 'set', 20),
(366, 20, 91, '查看', 'list', 10),
(367, 20, 91, '添加', 'add', 30),
(368, 20, 91, '修改', 'modify', 40),
(369, 20, 91, '删除', 'delete', 50),
(370, 20, 91, '启用/禁用', 'status', 60),
(371, 20, 92, '查看', 'list', 10),
(372, 20, 92, '编辑', 'set', 20),
(373, 20, 93, '查看', 'list', 10),
(378, 20, 94, '查看', 'list', 10),
(379, 20, 94, '添加', 'add', 30),
(380, 20, 94, '修改', 'modify', 40),
(381, 20, 94, '删除', 'delete', 50),
(382, 20, 94, '启用/禁用', 'status', 60),
(383, 20, 95, '查看', 'list', 10),
(384, 20, 95, '添加', 'add', 30),
(385, 20, 95, '修改', 'modify', 40),
(386, 20, 95, '删除', 'delete', 50),
(387, 20, 95, '启用/禁用', 'status', 60),
(388, 20, 96, '查看', 'list', 10),
(389, 20, 96, '添加', 'add', 30),
(390, 20, 96, '修改', 'modify', 40),
(391, 20, 96, '删除', 'delete', 50),
(392, 20, 96, '启用/禁用', 'status', 60),
(393, 20, 97, '查看', 'list', 10),
(394, 20, 97, '添加', 'add', 30),
(395, 20, 97, '修改', 'modify', 40),
(396, 20, 97, '删除', 'delete', 50),
(397, 20, 97, '启用/禁用', 'status', 60),
(398, 20, 98, '查看', 'list', 10),
(399, 20, 98, '添加', 'add', 30),
(400, 20, 98, '修改', 'modify', 40),
(401, 20, 98, '删除', 'delete', 50),
(402, 20, 98, '启用/禁用', 'status', 60),
(403, 20, 99, '查看', 'list', 10),
(404, 20, 99, '添加', 'add', 30),
(405, 20, 99, '修改', 'modify', 40),
(406, 20, 99, '删除', 'delete', 50),
(407, 20, 99, '启用/禁用', 'status', 60),
(408, 20, 100, '查看', 'list', 10),
(409, 20, 100, '添加', 'add', 30),
(410, 20, 100, '修改', 'modify', 40),
(411, 20, 100, '删除', 'delete', 50),
(412, 20, 100, '启用/禁用', 'status', 60),
(413, 20, 101, '查看', 'list', 10),
(414, 20, 101, '添加', 'add', 30),
(415, 20, 101, '修改', 'modify', 40),
(416, 20, 101, '删除', 'delete', 50),
(417, 20, 101, '启用/禁用', 'status', 60),
(418, 20, 102, '查看', 'list', 10),
(419, 20, 102, '添加', 'add', 30),
(420, 20, 102, '修改', 'modify', 40),
(421, 20, 102, '删除', 'delete', 50),
(422, 20, 102, '启用/禁用', 'status', 60),
(423, 20, 103, '查看', 'list', 10),
(424, 20, 103, '添加', 'add', 30),
(425, 20, 103, '修改', 'modify', 40),
(426, 20, 103, '删除', 'delete', 50),
(427, 20, 103, '启用/禁用', 'status', 60),
(428, 20, 104, '查看', 'list', 10),
(429, 20, 104, '添加', 'add', 30),
(430, 20, 104, '修改', 'modify', 40),
(431, 20, 104, '删除', 'delete', 50),
(432, 20, 104, '启用/禁用', 'status', 60),
(433, 20, 105, '查看', 'list', 10),
(434, 20, 105, '添加', 'add', 30),
(435, 20, 105, '修改', 'modify', 40),
(436, 20, 105, '删除', 'delete', 50),
(437, 20, 105, '启用/禁用', 'status', 60),
(438, 20, 106, '查看', 'list', 10),
(439, 20, 106, '添加', 'add', 30),
(440, 20, 106, '修改', 'modify', 40),
(441, 20, 106, '删除', 'delete', 50),
(442, 20, 106, '启用/禁用', 'status', 60),
(443, 20, 107, '查看', 'list', 10),
(444, 20, 107, '添加', 'add', 30),
(445, 20, 107, '修改', 'modify', 40),
(446, 20, 107, '删除', 'delete', 50),
(447, 20, 107, '启用/禁用', 'status', 60),
(448, 20, 108, '查看', 'list', 10),
(449, 20, 108, '添加', 'add', 30),
(450, 20, 108, '修改', 'modify', 40),
(451, 20, 108, '删除', 'delete', 50),
(452, 20, 108, '启用/禁用', 'status', 60),
(453, 20, 109, '查看', 'list', 10),
(454, 20, 109, '添加', 'add', 30),
(455, 20, 109, '修改', 'modify', 40),
(456, 20, 109, '删除', 'delete', 50),
(457, 20, 109, '启用/禁用', 'status', 60),
(458, 20, 110, '查看', 'list', 10),
(459, 20, 110, '编辑', 'set', 20),
(460, 20, 111, '查看', 'list', 10),
(461, 20, 111, '编辑', 'set', 20),
(462, 20, 112, '查看', 'list', 10),
(463, 20, 112, '编辑', 'set', 20),
(464, 20, 113, '查看', 'list', 10),
(465, 20, 113, '编辑', 'set', 20),
(466, 20, 114, '查看', 'list', 10),
(467, 20, 114, '添加', 'add', 30),
(468, 20, 114, '修改', 'modify', 40),
(469, 20, 114, '删除', 'delete', 50),
(470, 20, 114, '启用/禁用', 'status', 60),
(471, 20, 115, '查看', 'list', 10),
(472, 20, 115, '添加', 'add', 30),
(473, 20, 115, '修改', 'modify', 40),
(474, 20, 115, '删除', 'delete', 50),
(475, 20, 115, '启用/禁用', 'status', 60),
(476, 33, 0, '查看', 'list', 10),
(477, 33, 0, '添加', 'add', 20),
(478, 33, 0, '修改', 'modify', 30),
(479, 33, 0, '删除', 'delete', 40),
(480, 33, 0, '启用/禁用', 'status', 50),
(481, 20, 116, '查看', 'list', 10),
(482, 20, 116, '添加', 'add', 30),
(483, 20, 116, '修改', 'modify', 40),
(484, 20, 116, '删除', 'delete', 50),
(485, 20, 116, '启用/禁用', 'status', 60),
(486, 20, 117, '查看', 'list', 10),
(487, 20, 117, '添加', 'add', 30),
(488, 20, 117, '修改', 'modify', 40),
(489, 20, 117, '删除', 'delete', 50),
(490, 20, 117, '启用/禁用', 'status', 60),
(491, 20, 118, '查看', 'list', 10),
(492, 20, 118, '添加', 'add', 30),
(493, 20, 118, '修改', 'modify', 40),
(494, 20, 118, '删除', 'delete', 50),
(495, 20, 118, '启用/禁用', 'status', 60),
(496, 20, 119, '查看', 'list', 10),
(497, 20, 119, '添加', 'add', 30),
(498, 20, 119, '修改', 'modify', 40),
(499, 20, 119, '删除', 'delete', 50),
(500, 20, 119, '启用/禁用', 'status', 60),
(501, 20, 120, '查看', 'list', 10),
(502, 20, 120, '添加', 'add', 30),
(503, 20, 120, '修改', 'modify', 40),
(504, 20, 120, '删除', 'delete', 50),
(505, 20, 120, '启用/禁用', 'status', 60),
(506, 20, 121, '查看', 'list', 10),
(507, 20, 121, '添加', 'add', 30),
(508, 20, 121, '修改', 'modify', 40),
(509, 20, 121, '删除', 'delete', 50),
(510, 20, 121, '启用/禁用', 'status', 60),
(511, 20, 122, '查看', 'list', 10),
(512, 20, 122, '添加', 'add', 30),
(513, 20, 122, '修改', 'modify', 40),
(514, 20, 122, '删除', 'delete', 50),
(515, 20, 122, '启用/禁用', 'status', 60),
(516, 20, 123, '查看', 'list', 10),
(517, 20, 123, '添加', 'add', 30),
(518, 20, 123, '修改', 'modify', 40),
(519, 20, 123, '删除', 'delete', 50),
(520, 20, 123, '启用/禁用', 'status', 60),
(521, 20, 124, '查看', 'list', 10),
(522, 20, 124, '添加', 'add', 30),
(523, 20, 124, '修改', 'modify', 40),
(524, 20, 124, '删除', 'delete', 50),
(525, 20, 124, '启用/禁用', 'status', 60),
(526, 20, 125, '查看', 'list', 10),
(527, 20, 125, '添加', 'add', 30),
(528, 20, 125, '修改', 'modify', 40),
(529, 20, 125, '删除', 'delete', 50),
(530, 20, 125, '启用/禁用', 'status', 60),
(531, 20, 126, '查看', 'list', 10),
(532, 20, 126, '添加', 'add', 30),
(533, 20, 126, '修改', 'modify', 40),
(534, 20, 126, '删除', 'delete', 50),
(535, 20, 126, '启用/禁用', 'status', 60),
(536, 20, 128, '查看', 'list', 10),
(537, 20, 128, '添加', 'add', 30),
(538, 20, 128, '修改', 'modify', 40),
(539, 20, 128, '删除', 'delete', 50),
(540, 20, 128, '启用/禁用', 'status', 60),
(541, 20, 129, '查看', 'list', 10),
(542, 20, 129, '添加', 'add', 30),
(543, 20, 129, '修改', 'modify', 40),
(544, 20, 129, '删除', 'delete', 50),
(545, 20, 129, '启用/禁用', 'status', 60),
(546, 20, 130, '查看', 'list', 10),
(547, 20, 130, '添加', 'add', 30),
(548, 20, 130, '修改', 'modify', 40),
(549, 20, 130, '删除', 'delete', 50),
(550, 20, 130, '启用/禁用', 'status', 60),
(551, 20, 131, '查看', 'list', 10),
(552, 20, 131, '添加', 'add', 30),
(553, 20, 131, '修改', 'modify', 40),
(554, 20, 131, '删除', 'delete', 50),
(555, 20, 131, '启用/禁用', 'status', 60),
(556, 20, 132, '查看', 'list', 10),
(557, 20, 132, '添加', 'add', 30),
(558, 20, 132, '修改', 'modify', 40),
(559, 20, 132, '删除', 'delete', 50),
(560, 20, 132, '启用/禁用', 'status', 60),
(561, 20, 133, '查看', 'list', 10),
(562, 20, 133, '添加', 'add', 30),
(563, 20, 133, '修改', 'modify', 40),
(564, 20, 133, '删除', 'delete', 50),
(565, 20, 133, '启用/禁用', 'status', 60),
(566, 20, 134, '查看', 'list', 10),
(567, 20, 134, '添加', 'add', 30),
(568, 20, 134, '修改', 'modify', 40),
(569, 20, 134, '删除', 'delete', 50),
(570, 20, 134, '启用/禁用', 'status', 60),
(571, 20, 135, '查看', 'list', 10),
(572, 20, 135, '添加', 'add', 30),
(573, 20, 135, '修改', 'modify', 40),
(574, 20, 135, '删除', 'delete', 50),
(575, 20, 135, '启用/禁用', 'status', 60),
(576, 20, 136, '查看', 'list', 10),
(577, 20, 136, '添加', 'add', 30),
(578, 20, 136, '修改', 'modify', 40),
(579, 20, 136, '删除', 'delete', 50),
(580, 20, 136, '启用/禁用', 'status', 60),
(581, 20, 137, '查看', 'list', 10),
(582, 20, 137, '添加', 'add', 30),
(583, 20, 137, '修改', 'modify', 40),
(584, 20, 137, '删除', 'delete', 50),
(585, 20, 137, '启用/禁用', 'status', 60),
(586, 20, 138, '查看', 'list', 10),
(587, 20, 138, '添加', 'add', 30),
(588, 20, 138, '修改', 'modify', 40),
(589, 20, 138, '删除', 'delete', 50),
(590, 20, 138, '启用/禁用', 'status', 60),
(591, 20, 139, '查看', 'list', 10),
(592, 20, 139, '添加', 'add', 30),
(593, 20, 139, '修改', 'modify', 40),
(594, 20, 139, '删除', 'delete', 50),
(595, 20, 139, '启用/禁用', 'status', 60),
(596, 20, 140, '查看', 'list', 10),
(597, 20, 140, '编辑', 'set', 20),
(598, 20, 141, '查看', 'list', 10),
(599, 20, 141, '编辑', 'set', 20),
(600, 20, 93, '编辑', 'set', 20),
(601, 34, 0, '查看', 'list', 10),
(602, 34, 0, '添加', 'add', 20),
(603, 34, 0, '修改', 'modify', 30),
(604, 34, 0, '审核', 'status', 40),
(605, 34, 0, '删除', 'delete', 50);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_project`
--

DROP TABLE IF EXISTS `qinggan_project`;
CREATE TABLE IF NOT EXISTS `qinggan_project` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID，也是应用ID',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上一级ID',
  `site_id` mediumint(8) unsigned NOT NULL COMMENT '网站ID',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '指定模型ID，为0表页面空白',
  `cate` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '绑定根分类ID',
  `title` varchar(255) NOT NULL COMMENT '名称',
  `nick_title` varchar(255) NOT NULL COMMENT '后台别称',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序，值越小越往前靠',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不使用1使用',
  `tpl_index` varchar(255) NOT NULL COMMENT '封面页',
  `tpl_list` varchar(255) NOT NULL COMMENT '列表页',
  `tpl_content` varchar(255) NOT NULL COMMENT '详细页',
  `ico` varchar(255) NOT NULL COMMENT '图标',
  `orderby` text NOT NULL COMMENT '排序',
  `alias_title` varchar(255) NOT NULL COMMENT '主题别名',
  `alias_note` varchar(255) NOT NULL COMMENT '主题备注',
  `psize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0表示不限制，每页显示数量',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID号，为0表示管理员维护',
  `identifier` varchar(255) NOT NULL COMMENT '标识',
  `seo_title` varchar(255) NOT NULL COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL COMMENT 'SEO关键字',
  `seo_desc` varchar(255) NOT NULL COMMENT 'SEO描述',
  `subtopics` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用子主题功能',
  `is_search` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支持搜索',
  `is_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '必填Tag',
  `is_biz` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0不启用电商，1启用电商',
  `currency_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '默认货币ID',
  `admin_note` text NOT NULL COMMENT '管理员备注，给编辑人员使用的',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0显示1隐藏',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='项目管理器' AUTO_INCREMENT=142 ;

--
-- 转存表中的数据 `qinggan_project`
--

INSERT INTO `qinggan_project` (`id`, `parent_id`, `site_id`, `module`, `cate`, `title`, `nick_title`, `taxis`, `status`, `tpl_index`, `tpl_list`, `tpl_content`, `ico`, `orderby`, `alias_title`, `alias_note`, `psize`, `uid`, `identifier`, `seo_title`, `seo_keywords`, `seo_desc`, `subtopics`, `is_search`, `is_tag`, `is_biz`, `currency_id`, `admin_note`, `hidden`) VALUES
(41, 0, 1, 21, 0, '图片播放器', '', 20, 1, '', '', '', 'res/ico/a9cd524c3d706c22.png', 'l.sort ASC,l.dateline DESC,l.id DESC', '', '', 30, 0, 'picture-player', '', '', '', 0, 0, 0, 0, 0, '', 0),
(42, 0, 1, 23, 0, '导航菜单', '', 30, 1, '', '', '', 'res/ico/d1bdea034755680e.png', 'l.sort ASC,l.dateline DESC,l.id DESC', '导航名称', '', 30, 0, 'menu', '', '', '', 1, 0, 0, 0, 0, '', 0),
(43, 0, 1, 22, 7, '新闻中心', '', 10, 1, 'news_index', '', '', 'res/ico/ce7db9b3215a14e2.png', 'l.sort DESC,l.dateline DESC,l.id DESC', '新闻主题', '', 30, 0, 'news', '', '', '', 0, 1, 0, 0, 0, '', 0),
(87, 0, 1, 40, 0, '关于我们', '', 10, 1, '', '', '', 'res/ico/d1b062159daa84b2.png', 'l.sort ASC,l.dateline DESC,l.id DESC', '', '', 30, 0, 'about', '', '', '', 0, 0, 0, 0, 0, '<p>测试一下帮助信息</p>', 0),
(45, 0, 1, 24, 70, '产品展示', '', 50, 1, 'product_index', '', '', 'res/ico/d1b062159daa84b2.png', '', '产品名称', '', 5, 0, 'product', '', '', '', 0, 1, 0, 1, 1, '', 0),
(90, 87, 1, 0, 0, '公司简介', '', 10, 1, '', '', '', 'res/ico/1c298c58390411a8.png', '', '', '', 30, 0, 'about-us', '', '', '', 0, 0, 0, 0, 0, '<p>测试一下后台备注</p><p></p><p style="text-align:center"><img src="res/201310/02/auto_339.gif" border="0" alt="phpok4" /></p><br />', 0),
(91, 87, 1, 43, 0, '发展历程', '', 20, 1, '', '', '', 'res/ico/bb9e94ea064b1a77.png', 'ext.sendtime DESC,l.sort ASC,l.dateline DESC,l.id DESC', '事件名称', '', 200, 0, 'development-course', '', '', '', 0, 0, 0, 0, 0, '', 0),
(92, 87, 1, 0, 0, '联系我们', '', 30, 1, '', '', '', 'res/ico/307775d38da8491b.png', '', '', '', 30, 0, 'contact-us', '', '', '', 0, 0, 0, 0, 0, '', 0),
(93, 87, 1, 0, 0, '工作环境', '', 40, 1, 'about_picture', '', '', 'res/ico/7e5eb72d3d03ad40.png', '', '', '', 30, 0, 'work', '', '', '', 0, 0, 0, 0, 0, '', 0),
(94, 87, 1, 40, 0, '公告通知', '', 50, 1, '', 'about_notice', 'about_notice_info', 'res/ico/1d9c2ebff3e09fa5.png', 'l.sort DESC,l.dateline DESC,l.id DESC', '通知主题', '', 30, 0, 'notice', '', '', '', 0, 0, 0, 0, 0, '', 0),
(95, 0, 1, 0, 0, '友情链接', '', 60, 1, '', '', '', 'res/ico/49c6c7fa9a22db76.png', 'l.sort DESC,l.dateline DESC,l.id DESC', '网站名称', '', 30, 0, 'link', '', '', '', 0, 0, 0, 0, 0, '', 0),
(96, 0, 1, 46, 0, '在线留言', '', 70, 1, '', '', '', 'res/ico/ae209a51f2345410.png', 'l.sort DESC,l.dateline DESC,l.id DESC', '留言主题', '', 30, 0, 'book', '', '', '', 0, 0, 0, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_reply`
--

DROP TABLE IF EXISTS `qinggan_reply`;
CREATE TABLE IF NOT EXISTS `qinggan_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父回复ID',
  `vouch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '推荐评论',
  `star` tinyint(1) NOT NULL DEFAULT '3' COMMENT '星级',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `ip` varchar(255) NOT NULL COMMENT '回复人IP',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未审核1审核',
  `session_id` varchar(255) NOT NULL COMMENT '游客标识',
  `content` text NOT NULL COMMENT '评论内容',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `adm_content` longtext NOT NULL COMMENT '管理员回复内容',
  `adm_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主题评论表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_res`
--

DROP TABLE IF EXISTS `qinggan_res`;
CREATE TABLE IF NOT EXISTS `qinggan_res` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `cate_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `folder` varchar(255) NOT NULL COMMENT '存储目录',
  `name` varchar(255) NOT NULL COMMENT '资源文件名',
  `ext` varchar(30) NOT NULL COMMENT '资源后缀，如jpg等',
  `filename` varchar(255) NOT NULL COMMENT '文件名带路径',
  `ico` varchar(255) NOT NULL COMMENT 'ICO图标文件',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `title` varchar(255) NOT NULL COMMENT '内容',
  `attr` text NOT NULL COMMENT '附件属性',
  `note` text NOT NULL COMMENT '备注',
  `session_id` varchar(100) NOT NULL COMMENT '操作者 ID，即会员ID用于检测是否有权限删除 ',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID，当该ID为时检则sesson_id，如不相同则不能删除 ',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`id`),
  KEY `ext` (`ext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='资源ID' AUTO_INCREMENT=521 ;

--
-- 转存表中的数据 `qinggan_res`
--

INSERT INTO `qinggan_res` (`id`, `cate_id`, `folder`, `name`, `ext`, `filename`, `ico`, `addtime`, `title`, `attr`, `note`, `session_id`, `user_id`, `download`) VALUES
(342, 6, 'res/ico/', 'a9cd524c3d706c22.png', 'png', 'res/ico/a9cd524c3d706c22.png', 'res/ico/_342.png', 1380705334, '008', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', '3sadian4b10j79qsssdkp7glf0', 0, 0),
(340, 1, 'res/201310/02/', '3c8db4c052fb3211.jpg', 'jpg', 'res/201310/02/3c8db4c052fb3211.jpg', 'res/201310/02/_340.jpg', 1380700469, '980X285-banner', 'a:2:{s:5:"width";i:980;s:6:"height";i:285;}', '', '3sadian4b10j79qsssdkp7glf0', 0, 0),
(339, 1, 'res/201310/02/', '7fb1e110782a14b1.gif', 'gif', 'res/201310/02/7fb1e110782a14b1.gif', 'res/201310/02/_339.gif', 1380699886, 'phpok4', 'a:2:{s:5:"width";i:216;s:6:"height";i:58;}', '', '3sadian4b10j79qsssdkp7glf0', 0, 0),
(343, 6, 'res/ico/', 'd1bdea034755680e.png', 'png', 'res/ico/d1bdea034755680e.png', 'res/ico/_343.png', 1380941965, '055', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'bapnhccnr9lt3qilr1ev7t2ug0', 0, 0),
(344, 6, 'res/ico/', 'ce7db9b3215a14e2.png', 'png', 'res/ico/ce7db9b3215a14e2.png', 'res/ico/_344.png', 1380942365, 'news', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'bapnhccnr9lt3qilr1ev7t2ug0', 0, 0),
(345, 1, 'res/201310/05/', '6df12daae268c66d_37.png', 'png', 'res/201310/05/6df12daae268c66d_37.png', 'res/201310/05/_345.png', 1380942538, '2013100309081597871_550', 'a:2:{s:5:"width";i:550;s:6:"height";i:367;}', '', '', 0, 0),
(347, 6, 'res/ico/', '307775d38da8491b.png', 'png', 'res/ico/307775d38da8491b.png', 'res/ico/_347.png', 1380942837, '034', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'bapnhccnr9lt3qilr1ev7t2ug0', 0, 0),
(349, 6, 'res/ico/', 'dc8beceb11e914d8.png', 'png', 'res/ico/dc8beceb11e914d8.png', 'res/ico/_349.png', 1380943014, 'copyright', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'bapnhccnr9lt3qilr1ev7t2ug0', 0, 0),
(350, 6, 'res/ico/', 'd218a30a201b7ceb.png', 'png', 'res/ico/d218a30a201b7ceb.png', 'res/ico/_350.png', 1380943066, '018', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'bapnhccnr9lt3qilr1ev7t2ug0', 0, 0),
(351, 6, 'res/ico/', '1c298c58390411a8.png', 'png', 'res/ico/1c298c58390411a8.png', 'res/ico/_351.png', 1381179886, 'company', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'n8fnfs29la334mckhrdvk2p2d4', 0, 0),
(352, 1, 'res/201310/08/', '3820bb76e7d78cbb.jpg', 'jpg', 'res/201310/08/3820bb76e7d78cbb.jpg', 'res/201310/08/_352.jpg', 1381180667, 'about', 'a:2:{s:5:"width";i:300;s:6:"height";i:225;}', '', 'n8fnfs29la334mckhrdvk2p2d4', 0, 0),
(353, 1, 'res/201310/08/', '02c7325437ffa0b3.jpg', 'jpg', 'res/201310/08/02c7325437ffa0b3.jpg', 'res/201310/08/_353.jpg', 1381180851, 'contactus', 'a:2:{s:5:"width";i:238;s:6:"height";i:124;}', '', 'n8fnfs29la334mckhrdvk2p2d4', 0, 0),
(354, 6, 'res/ico/', 'd1b062159daa84b2.png', 'png', 'res/ico/d1b062159daa84b2.png', 'res/ico/_354.png', 1381181194, 'products', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'n8fnfs29la334mckhrdvk2p2d4', 0, 0),
(426, 1, 'res/201311/02/', 'daa38ccb4a36eb1d.jpg', 'jpg', 'res/201311/02/daa38ccb4a36eb1d.jpg', 'res/201311/02/_426.jpg', 1383356935, 'banner1', 'a:2:{s:5:"width";i:981;s:6:"height";i:240;}', '', 'vuopnaqcg34dk3cdrr09ehl9n3', 0, 0),
(427, 6, 'res/ico/', '6598529afcac7c27.png', 'png', 'res/ico/6598529afcac7c27.png', 'res/ico/_427.png', 1383363655, 'api', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'vuopnaqcg34dk3cdrr09ehl9n3', 0, 0),
(357, 6, 'res/ico/', '2d4e794a786ecee9.png', 'png', 'res/ico/2d4e794a786ecee9.png', 'res/ico/_357.png', 1381282760, '050', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ircdav4iepq78acd3ecn5vk5p4', 0, 0),
(446, 6, 'res/ico/', '49c6c7fa9a22db76.png', 'png', 'res/ico/49c6c7fa9a22db76.png', 'res/ico/_446.png', 1384084458, 'link', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', '257njalptjq3bc7ik8mgdsjjq5', 0, 0),
(366, 6, 'res/ico/', '7f3f0b0d8c10c070.png', 'png', 'res/ico/7f3f0b0d8c10c070.png', 'res/ico/_366.png', 1381287352, 'partners', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ircdav4iepq78acd3ecn5vk5p4', 0, 0),
(378, 6, 'res/ico/', '818db8113b4cf1d6.png', 'png', 'res/ico/818db8113b4cf1d6.png', 'res/ico/_378.png', 1381302833, 'seo', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ircdav4iepq78acd3ecn5vk5p4', 0, 0),
(384, 6, 'res/ico/', '60dbdcc63dce7dba.png', 'png', 'res/ico/60dbdcc63dce7dba.png', 'res/ico/_384.png', 1381310353, 'old', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ircdav4iepq78acd3ecn5vk5p4', 0, 0),
(388, 6, 'res/ico/', 'cdfa2428af69eede.png', 'png', 'res/ico/cdfa2428af69eede.png', 'res/ico/_388.png', 1381350910, 'share', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'sq342e3raenuv3e1vdkts08g12', 0, 0),
(389, 6, 'res/ico/', '410a240926c0f49e.png', 'png', 'res/ico/410a240926c0f49e.png', 'res/ico/_389.png', 1381355270, 'shop', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'sq342e3raenuv3e1vdkts08g12', 0, 0),
(394, 6, 'res/ico/', '44c7378471c39ea7.png', 'png', 'res/ico/44c7378471c39ea7.png', 'res/ico/_394.png', 1381357702, 'cart', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'sq342e3raenuv3e1vdkts08g12', 0, 0),
(401, 6, 'res/ico/', '1d9c2ebff3e09fa5.png', 'png', 'res/ico/1d9c2ebff3e09fa5.png', 'res/ico/_401.png', 1381396370, 'process', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'kv8pegdnrksjgkdlthaff8b6s3', 0, 0),
(402, 6, 'res/ico/', '1c82209b05976ea5.png', 'png', 'res/ico/1c82209b05976ea5.png', 'res/ico/_402.png', 1381396465, 'culture', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'kv8pegdnrksjgkdlthaff8b6s3', 0, 0),
(403, 6, 'res/ico/', '7555f8832204fcda.png', 'png', 'res/ico/7555f8832204fcda.png', 'res/ico/_403.png', 1381396547, 'certificate', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'kv8pegdnrksjgkdlthaff8b6s3', 0, 0),
(404, 6, 'res/ico/', '02c690d2b7be80a4.png', 'png', 'res/ico/02c690d2b7be80a4.png', 'res/ico/_404.png', 1381397417, '029', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'kv8pegdnrksjgkdlthaff8b6s3', 0, 0),
(407, 6, 'res/ico/', '7b3cc9eef71b4fdb.png', 'png', 'res/ico/7b3cc9eef71b4fdb.png', 'res/ico/_407.png', 1381401685, 'book', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'kv8pegdnrksjgkdlthaff8b6s3', 0, 0),
(420, 6, 'res/ico/', '97253025044334d6.png', 'png', 'res/ico/97253025044334d6.png', 'res/ico/_420.png', 1381818297, 'help', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'vvndtoc1a573kgg9398t0sdc73', 0, 0),
(445, 1, 'res/201311/07/', '85032f7e7ba3cfd7_37.jpg', 'jpg', 'res/201311/07/85032f7e7ba3cfd7_37.jpg', 'res/201311/07/_445.jpg', 1383806735, '94640369', 'a:2:{s:5:"width";i:500;s:6:"height";i:375;}', '', '', 0, 0),
(424, 6, 'res/ico/', 'ae209a51f2345410.png', 'png', 'res/ico/ae209a51f2345410.png', 'res/ico/_424.png', 1382324238, '043', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ude00422qiapje6181i8f3nnp1', 0, 0),
(425, 6, 'res/ico/', '40b1f0f771d5db9a.png', 'png', 'res/ico/40b1f0f771d5db9a.png', 'res/ico/_425.png', 1383353456, 'code', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'vuopnaqcg34dk3cdrr09ehl9n3', 0, 0),
(430, 1, 'res/201311/03/', '4b064fe1833835e8_89.png', 'png', 'res/201311/03/4b064fe1833835e8_89.png', 'res/201311/03/_430.png', 1383489622, 'base64_1383489622', 'a:2:{s:5:"width";i:258;s:6:"height";i:81;}', '', '', 0, 0),
(434, 1, 'res/201311/05/', 'bb80cf71fcd9cc4d.jpg', 'jpg', 'res/201311/05/bb80cf71fcd9cc4d.jpg', 'res/201311/05/_434.jpg', 1383619415, 'qinggan', 'a:2:{s:5:"width";i:119;s:6:"height";i:116;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 23, 0),
(435, 6, 'res/ico/', 'bb9e94ea064b1a77.png', 'png', 'res/ico/bb9e94ea064b1a77.png', 'res/ico/_435.png', 1383633002, 'history', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(436, 6, 'res/ico/', '7e5eb72d3d03ad40.png', 'png', 'res/ico/7e5eb72d3d03ad40.png', 'res/ico/_436.png', 1383639434, 'env', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(437, 1, 'res/201311/05/', 'cf8fbd247f2c4b0f.jpg', 'jpg', 'res/201311/05/cf8fbd247f2c4b0f.jpg', 'res/201311/05/_437.jpg', 1383639885, '08', 'a:2:{s:5:"width";i:592;s:6:"height";i:352;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(438, 1, 'res/201311/05/', '70a1f7d03a1c01f0.jpg', 'jpg', 'res/201311/05/70a1f7d03a1c01f0.jpg', 'res/201311/05/_438.jpg', 1383639903, '07', 'a:2:{s:5:"width";i:658;s:6:"height";i:484;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(439, 1, 'res/201311/05/', '3f6301a2fd66310a.jpg', 'jpg', 'res/201311/05/3f6301a2fd66310a.jpg', 'res/201311/05/_439.jpg', 1383639928, '06', 'a:2:{s:5:"width";i:819;s:6:"height";i:511;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(440, 1, 'res/201311/05/', '555eb1306c10b746.jpg', 'jpg', 'res/201311/05/555eb1306c10b746.jpg', 'res/201311/05/_440.jpg', 1383639939, '05', 'a:2:{s:5:"width";i:625;s:6:"height";i:474;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(441, 1, 'res/201311/05/', '79ec6dd52f6fc051.jpg', 'jpg', 'res/201311/05/79ec6dd52f6fc051.jpg', 'res/201311/05/_441.jpg', 1383639958, '04', 'a:2:{s:5:"width";i:669;s:6:"height";i:489;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(442, 1, 'res/201311/05/', 'd6e331dd123ea8dc.jpg', 'jpg', 'res/201311/05/d6e331dd123ea8dc.jpg', 'res/201311/05/_442.jpg', 1383639973, '03', 'a:2:{s:5:"width";i:684;s:6:"height";i:491;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(443, 1, 'res/201311/05/', 'f3634ab543688020.jpg', 'jpg', 'res/201311/05/f3634ab543688020.jpg', 'res/201311/05/_443.jpg', 1383640035, '02', 'a:2:{s:5:"width";i:678;s:6:"height";i:483;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(444, 1, 'res/201311/05/', 'b9865e13c7ce0c10.jpg', 'jpg', 'res/201311/05/b9865e13c7ce0c10.jpg', 'res/201311/05/_444.jpg', 1383640046, '01', 'a:2:{s:5:"width";i:773;s:6:"height";i:495;}', '', 'ooo23fj5htqg7r7hnegqstpvp4', 0, 0),
(447, 6, 'res/ico/', 'c2a4754a5ef65d14.jpg', 'jpg', 'res/ico/c2a4754a5ef65d14.jpg', 'res/ico/_447.jpg', 1384191075, 'logo48px', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'mabp2slsphrjgfevc77j7o8to6', 0, 0),
(456, 6, 'res/ico/', 'f83d649b6e52e2df.png', 'png', 'res/ico/f83d649b6e52e2df.png', 'res/ico/_456.png', 1384228366, '054', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'k28o8ikjkhkg3mf5p9i3gj3vr0', 0, 0),
(474, 6, 'res/ico/', '51017cbf05b1a055.png', 'png', 'res/ico/51017cbf05b1a055.png', 'res/ico/_474.png', 1384350132, '035', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'f5t4nk3libi4l7g9jsg5902qr3', 0, 0),
(493, 6, 'res/ico/', '74df7c678862b460.png', 'png', 'res/ico/74df7c678862b460.png', 'res/ico/_493.png', 1384503490, '001', 'a:2:{s:5:"width";i:48;s:6:"height";i:48;}', '', 'd59emnm6a7ojp8eqq4kihnbme2', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_res_cate`
--

DROP TABLE IF EXISTS `qinggan_res_cate`;
CREATE TABLE IF NOT EXISTS `qinggan_res_cate` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '资源分类ID',
  `title` varchar(255) NOT NULL COMMENT '分类名称',
  `root` varchar(255) NOT NULL DEFAULT '/' COMMENT '存储目录',
  `folder` varchar(255) NOT NULL DEFAULT 'Ym/d/' COMMENT '存储目录格式',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1默认0非默认',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='资源分类存储' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `qinggan_res_cate`
--

INSERT INTO `qinggan_res_cate` (`id`, `title`, `root`, `folder`, `is_default`) VALUES
(1, '默认分类', 'res/', 'Ym/d/', 1),
(6, '图标库', 'res/ico/', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_res_ext`
--

DROP TABLE IF EXISTS `qinggan_res_ext`;
CREATE TABLE IF NOT EXISTS `qinggan_res_ext` (
  `res_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '附件ID',
  `gd_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'GD库方案ID',
  `x1` mediumint(9) NOT NULL DEFAULT '0' COMMENT '手工裁剪定位x1',
  `y1` mediumint(9) NOT NULL DEFAULT '0' COMMENT '手工裁剪定位y1',
  `x2` mediumint(9) NOT NULL DEFAULT '0' COMMENT '手工裁剪定位x2',
  `y2` mediumint(9) NOT NULL DEFAULT '0' COMMENT '手工裁剪定位y2',
  `w` mediumint(9) NOT NULL DEFAULT '0' COMMENT '参数下的宽',
  `h` mediumint(9) NOT NULL DEFAULT '0' COMMENT '参数下的高',
  `filename` varchar(255) NOT NULL COMMENT '文件地址（含路径）',
  `filetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后',
  PRIMARY KEY (`res_id`,`gd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='生成扩展图片';

--
-- 转存表中的数据 `qinggan_res_ext`
--

INSERT INTO `qinggan_res_ext` (`res_id`, `gd_id`, `x1`, `y1`, `x2`, `y2`, `w`, `h`, `filename`, `filetime`) VALUES
(342, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_342.png', 1384653770),
(340, 2, 0, 0, 0, 0, 0, 0, 'res/201310/02/thumb_340.jpg', 1384653770),
(339, 2, 0, 0, 0, 0, 0, 0, 'res/201310/02/thumb_339.gif', 1384653770),
(343, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_343.png', 1384653770),
(344, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_344.png', 1384653770),
(345, 2, 0, 0, 0, 0, 0, 0, 'res/201310/05/thumb_345.png', 1384653770),
(347, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_347.png', 1384653766),
(349, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_349.png', 1384653766),
(350, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_350.png', 1384653766),
(351, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_351.png', 1384653766),
(352, 2, 0, 0, 0, 0, 0, 0, 'res/201310/08/thumb_352.jpg', 1384653766),
(353, 2, 0, 0, 0, 0, 0, 0, 'res/201310/08/thumb_353.jpg', 1384653766),
(354, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_354.png', 1384653766),
(357, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_357.png', 1384653766),
(427, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_427.png', 1384653757),
(366, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_366.png', 1384653766),
(378, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_378.png', 1384653766),
(384, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_384.png', 1384653762),
(388, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_388.png', 1384653762),
(426, 2, 0, 0, 0, 0, 0, 0, 'res/201311/02/thumb_426.jpg', 1384653757),
(427, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_427.png', 1384653757),
(426, 12, 0, 0, 0, 0, 0, 0, 'res/201311/02/auto_426.jpg', 1384653757),
(389, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_389.png', 1384653762),
(394, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_394.png', 1384653762),
(401, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_401.png', 1384653762),
(402, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_402.png', 1384653762),
(403, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_403.png', 1384653762),
(404, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_404.png', 1384653762),
(407, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_407.png', 1384653762),
(407, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_407.png', 1384653762),
(420, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_420.png', 1384653762),
(420, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_420.png', 1384653762),
(424, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_424.png', 1384653757),
(424, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_424.png', 1384653757),
(425, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_425.png', 1384653757),
(425, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_425.png', 1384653757),
(430, 12, 0, 0, 0, 0, 0, 0, 'res/201311/03/auto_430.png', 1384653757),
(430, 2, 0, 0, 0, 0, 0, 0, 'res/201311/03/thumb_430.png', 1384653757),
(339, 12, 0, 0, 0, 0, 0, 0, 'res/201310/02/auto_339.gif', 1384653770),
(445, 12, 0, 0, 0, 0, 0, 0, 'res/201311/07/auto_445.jpg', 1384653751),
(445, 2, 0, 0, 0, 0, 0, 0, 'res/201311/07/thumb_445.jpg', 1384653751),
(446, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_446.png', 1384653751),
(446, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_446.png', 1384653751),
(353, 12, 0, 0, 0, 0, 0, 0, 'res/201310/08/auto_353.jpg', 1384653766),
(352, 12, 0, 0, 0, 0, 0, 0, 'res/201310/08/auto_352.jpg', 1384653766),
(345, 12, 0, 0, 0, 0, 0, 0, 'res/201310/05/auto_345.png', 1384653770),
(340, 12, 0, 0, 0, 0, 0, 0, 'res/201310/02/auto_340.jpg', 1384653770),
(434, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_434.jpg', 1384653757),
(434, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_434.jpg', 1384653757),
(435, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_435.png', 1384653757),
(435, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_435.png', 1384653757),
(436, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_436.png', 1384653757),
(436, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_436.png', 1384653757),
(437, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_437.jpg', 1384653757),
(437, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_437.jpg', 1384653757),
(438, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_438.jpg', 1384653757),
(438, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_438.jpg', 1384653757),
(439, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_439.jpg', 1384653751),
(439, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_439.jpg', 1384653751),
(440, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_440.jpg', 1384653751),
(440, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_440.jpg', 1384653751),
(441, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_441.jpg', 1384653751),
(441, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_441.jpg', 1384653751),
(442, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_442.jpg', 1384653751),
(442, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_442.jpg', 1384653751),
(443, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_443.jpg', 1384653751),
(443, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_443.jpg', 1384653751),
(444, 12, 0, 0, 0, 0, 0, 0, 'res/201311/05/auto_444.jpg', 1384653751),
(444, 2, 0, 0, 0, 0, 0, 0, 'res/201311/05/thumb_444.jpg', 1384653751),
(447, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_447.jpg', 1384653751),
(447, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_447.jpg', 1384653751),
(456, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_456.png', 1384653746),
(456, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_456.png', 1384653746),
(474, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_474.png', 1384653746),
(474, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_474.png', 1384653746),
(404, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_404.png', 1384653762),
(403, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_403.png', 1384653762),
(402, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_402.png', 1384653762),
(401, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_401.png', 1384653762),
(394, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_394.png', 1384653762),
(389, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_389.png', 1384653762),
(388, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_388.png', 1384653762),
(384, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_384.png', 1384653762),
(378, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_378.png', 1384653766),
(366, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_366.png', 1384653766),
(357, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_357.png', 1384653766),
(354, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_354.png', 1384653766),
(351, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_351.png', 1384653766),
(350, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_350.png', 1384653766),
(349, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_349.png', 1384653766),
(347, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_347.png', 1384653766),
(344, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_344.png', 1384653770),
(343, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_343.png', 1384653770),
(342, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_342.png', 1384653770),
(493, 12, 0, 0, 0, 0, 0, 0, 'res/ico/auto_493.png', 1384653741),
(493, 2, 0, 0, 0, 0, 0, 0, 'res/ico/thumb_493.png', 1384653741);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_session`
--

DROP TABLE IF EXISTS `qinggan_session`;
CREATE TABLE IF NOT EXISTS `qinggan_session` (
  `id` varchar(32) NOT NULL COMMENT 'session_id',
  `data` text NOT NULL COMMENT 'session 内容',
  `lasttime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='SESSION操作';

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_site`
--

DROP TABLE IF EXISTS `qinggan_site`;
CREATE TABLE IF NOT EXISTS `qinggan_site` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `domain_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '默认域名ID',
  `title` varchar(255) NOT NULL COMMENT '网站名称',
  `dir` varchar(255) NOT NULL DEFAULT '/' COMMENT '安装目录，以/结尾',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `content` text NOT NULL COMMENT '网站关闭原因',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1默认站点',
  `tpl_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '模板ID',
  `url_type` enum('default','rewrite','html') NOT NULL DEFAULT 'default' COMMENT '默认，即带?等能数，rewrite是伪静态页，html为生成的静态页',
  `logo` varchar(255) NOT NULL COMMENT '网站 LOGO ',
  `meta` text NOT NULL COMMENT '扩展配置',
  `currency_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '默认货币ID',
  `register_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0关闭注册1开启注册',
  `register_close` varchar(255) NOT NULL COMMENT '关闭注册说明',
  `login_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0关闭登录1开启',
  `login_close` varchar(255) NOT NULL COMMENT '关闭登录说明',
  `adm_logo29` varchar(255) NOT NULL COMMENT '在后台左侧LOGO地址',
  `adm_logo180` varchar(255) NOT NULL COMMENT '登录LOGO地址',
  `lang` varchar(255) NOT NULL COMMENT '语言包',
  `api` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0不走接口',
  `email_charset` enum('gb','utf-8') NOT NULL DEFAULT 'utf-8' COMMENT '邮箱模式',
  `email_server` varchar(100) NOT NULL COMMENT '邮件服务器',
  `email_port` varchar(10) NOT NULL COMMENT '端口',
  `email_ssl` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'SSL模式',
  `email_account` varchar(100) NOT NULL COMMENT '邮箱账号',
  `email_pass` varchar(100) NOT NULL COMMENT '邮箱密码',
  `email_name` varchar(100) NOT NULL COMMENT '发件人名称',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `seo_title` varchar(255) NOT NULL COMMENT 'SEO主题',
  `seo_keywords` varchar(255) NOT NULL COMMENT 'SEO关键字',
  `seo_desc` text NOT NULL COMMENT 'SEO摘要',
  `biz_sn` varchar(255) NOT NULL COMMENT '订单号生成规则',
  `biz_payment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '默认支付方式',
  `biz_billing` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0未绑定',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站管理' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `qinggan_site`
--

INSERT INTO `qinggan_site` (`id`, `domain_id`, `title`, `dir`, `status`, `content`, `is_default`, `tpl_id`, `url_type`, `logo`, `meta`, `currency_id`, `register_status`, `register_close`, `login_status`, `login_close`, `adm_logo29`, `adm_logo180`, `lang`, `api`, `email_charset`, `email_server`, `email_port`, `email_ssl`, `email_account`, `email_pass`, `email_name`, `email`, `seo_title`, `seo_keywords`, `seo_desc`, `biz_sn`, `biz_payment`, `biz_billing`) VALUES
(1, 1, '企业网站', '/phpok/', 1, '网站正在建设中！', 1, 1, 'default', 'res/201311/03/4b064fe1833835e8_89.png', '', 1, 1, '本系统暂停新会员注册，给您带来不便还请见谅，如需会员服务请联系QQ：40782502', 1, '本系统暂停会员登录，给您带来不便还请见谅！', '', '', '', 0, 'utf-8', 'smtp.126.com', '25', 0, 'suxiangkun', '', 'phpok企业站', 'suxiangkun@126.com', '网站建站|企业网站建站|网站建设|企业网站建设', '网站建站,企业网站建站,网站建设,企业网站建设', '高效的企业网站建设系统，可实现高定制化的企业网站电商系统，实现企业网站到电子商务企业网站。定制功能更高，操作更简单！', 'prefix[P]-year-month-date-number', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_site_domain`
--

DROP TABLE IF EXISTS `qinggan_site_domain`;
CREATE TABLE IF NOT EXISTS `qinggan_site_domain` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `site_id` mediumint(8) unsigned NOT NULL COMMENT '网站ID',
  `domain` varchar(255) NOT NULL COMMENT '域名信息',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站指定的域名' AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `qinggan_site_domain`
--

INSERT INTO `qinggan_site_domain` (`id`, `site_id`, `domain`) VALUES
(1, 1, 'localhost');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_stock`
--

DROP TABLE IF EXISTS `qinggan_stock`;
CREATE TABLE IF NOT EXISTS `qinggan_stock` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '仓库ID，自增ID',
  `title` varchar(255) NOT NULL COMMENT '仓库名称',
  `address` varchar(255) NOT NULL COMMENT '仓库地址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不使用1使用',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品仓库' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_sysmenu`
--

DROP TABLE IF EXISTS `qinggan_sysmenu`;
CREATE TABLE IF NOT EXISTS `qinggan_sysmenu` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID，0为根菜单',
  `title` varchar(100) NOT NULL COMMENT '分类名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态0禁用1正常',
  `appfile` varchar(100) NOT NULL COMMENT '应用文件名，放在phpok/admin/目录下，记录不带.php',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序，值越小越往前靠，可选0-255',
  `func` varchar(100) NOT NULL COMMENT '应用函数，为空使用index',
  `identifier` varchar(100) NOT NULL COMMENT '标识串，用于区分同一应用文件的不同内容',
  `if_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0常规项目，1系统项目',
  `site_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '0表示全局网站',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台系统菜单' AUTO_INCREMENT=35 ;

--
-- 转存表中的数据 `qinggan_sysmenu`
--

INSERT INTO `qinggan_sysmenu` (`id`, `parent_id`, `title`, `status`, `appfile`, `taxis`, `func`, `identifier`, `if_system`, `site_id`) VALUES
(1, 0, '设置', 1, 'setting', 50, '', '', 1, 0),
(3, 0, '会员', 1, 'user', 30, '', '', 0, 0),
(5, 0, '内容', 1, 'index', 10, '', '', 0, 0),
(6, 1, '表单选项', 1, 'opt', 54, '', '', 0, 0),
(7, 1, '字段维护', 1, 'fields', 55, '', '', 0, 0),
(8, 1, '模块管理', 1, 'module', 53, '', '', 0, 0),
(9, 1, '核心配置', 1, 'system', 57, '', '', 1, 0),
(13, 3, '会员列表', 1, 'user', 31, '', '', 0, 0),
(14, 3, '会员组', 1, 'usergroup', 33, '', '', 0, 0),
(25, 3, '会员字段', 1, 'user', 255, 'fields', '', 0, 0),
(16, 1, '插件管理', 1, 'plugin', 70, '', '', 0, 0),
(18, 5, '分类管理', 1, 'cate', 14, '', '', 0, 0),
(19, 5, '全局内容', 1, 'all', 11, '', '', 0, 0),
(20, 5, '内容管理', 1, 'list', 12, '', '', 0, 0),
(22, 5, '资源管理', 1, 'res', 18, '', '', 0, 0),
(23, 5, '数据调用', 1, 'call', 16, '', '', 0, 0),
(27, 1, '项目管理', 1, 'project', 51, '', '', 0, 0),
(28, 1, '邮件通知模板', 1, 'email', 56, '', '', 0, 0),
(29, 1, '管理员维护', 1, 'admin', 100, '', '', 0, 0),
(30, 1, '风格管理', 1, 'tpl', 60, '', '', 0, 0),
(31, 1, '站点管理', 1, 'site', 110, '', '', 0, 0),
(32, 5, '评论管理', 1, 'reply', 17, '', '', 0, 1),
(33, 1, '货币及汇率', 1, 'currency', 80, '', '', 0, 1),
(34, 5, '订单管理', 1, 'order', 15, '', '', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_tag`
--

DROP TABLE IF EXISTS `qinggan_tag`;
CREATE TABLE IF NOT EXISTS `qinggan_tag` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `pingyin` varchar(255) NOT NULL COMMENT '拼音，用于索引',
  `title` varchar(255) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '关键字网址',
  `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '次数',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用次数',
  PRIMARY KEY (`id`),
  KEY `pingyin` (`pingyin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键字管理器' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_tag_list`
--

DROP TABLE IF EXISTS `qinggan_tag_list`;
CREATE TABLE IF NOT EXISTS `qinggan_tag_list` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关键字ID',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  PRIMARY KEY (`id`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键字明细记录';

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_temp`
--

DROP TABLE IF EXISTS `qinggan_temp`;
CREATE TABLE IF NOT EXISTS `qinggan_temp` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `tbl` varchar(100) NOT NULL COMMENT '表',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl` (`tbl`,`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='临时表单存储器' AUTO_INCREMENT=112 ;

--
-- 转存表中的数据 `qinggan_temp`
--

INSERT INTO `qinggan_temp` (`id`, `tbl`, `admin_id`, `content`) VALUES
(26, 'cate-69', 3, 'a:7:{s:2:"id";s:2:"69";s:5:"title";s:12:"测试分类";s:10:"identifier";s:19:"test-categorization";s:9:"parent_id";s:2:"68";s:6:"banner";s:0:"";s:5:"taxis";s:3:"255";s:6:"status";s:1:"1";}'),
(18, 'project-25', 1, 'a:14:{s:2:"id";s:2:"25";s:5:"title";s:12:"页头导航";s:9:"parent_id";s:1:"0";s:10:"identifier";s:4:"lead";s:4:"note";s:0:"";s:6:"module";s:2:"12";s:7:"orderby";s:0:"";s:4:"cate";s:1:"0";s:9:"tpl_index";s:0:"";s:8:"tpl_list";s:0:"";s:11:"tpl_content";s:0:"";s:5:"taxis";s:3:"200";s:6:"status";s:1:"1";s:3:"ico";s:7:"055.png";}'),
(20, 'all-7', 1, 'a:5:{s:2:"id";s:1:"7";s:7:"company";s:0:"";s:7:"address";s:0:"";s:3:"tel";s:0:"";s:2:"qq";s:8:"40782502";}'),
(25, 'all-1', 1, 'a:9:{s:2:"id";s:1:"1";s:12:"smtp_charset";s:3:"gbk";s:11:"smtp_server";s:12:"smtp.126.com";s:9:"smtp_port";s:2:"25";s:8:"smtp_ssl";s:1:"0";s:9:"smtp_user";s:10:"suxiangkun";s:9:"smtp_pass";s:10:"a********6";s:13:"smtp_nickname";s:14:"企业站";s:5:"email";s:18:"suxiangkun@126.com";}'),
(27, 'project-1', 1, 'a:2:{s:2:"id";s:1:"1";s:5:"title";s:12:"网站首页";}'),
(35, 'all-8', 1, 'a:5:{s:2:"id";s:1:"8";s:8:"reg_open";s:1:"1";s:15:"reg_mail_notice";s:1:"0";s:14:"reg_mail_check";s:1:"0";s:9:"reg_check";s:1:"0";}'),
(85, 'all-23', 1, 'a:3:{s:2:"id";s:2:"23";s:4:"cert";s:0:"";s:7:"content";s:79:"© 2012-2013 百帮一助残创业组委会  版权所有 热线：400-667-5180";}'),
(75, 'cate-109', 1, 'a:9:{s:2:"id";s:3:"109";s:5:"title";s:18:"户外捕虫产品";s:10:"identifier";s:23:"outdoor-insect-products";s:9:"parent_id";s:3:"107";s:5:"taxis";s:2:"20";s:6:"status";s:1:"1";s:5:"psize";s:1:"0";s:8:"tpl_list";s:0:"";s:11:"tpl_content";s:0:"";}'),
(78, 'project-75', 1, 'a:4:{s:2:"id";s:2:"75";s:5:"title";s:15:"联系卫捕士";s:4:"note";s:0:"";s:7:"content";s:281:"<p>制造商：上海星让实业有限公司</p>      <p>地址：上海徐汇区石龙路411弄18号2号楼</p>      <p>电话：021-34694728</p>      <p>手机：18916595069</p>      <p>传真：021-54485926</p>      <p>QQ：171288173</p>      <p>邮箱：1721336@zzz007.com</p>";}'),
(110, 'cate-8', 1, 'a:12:{s:2:"id";s:1:"8";s:5:"title";s:12:"公司新闻";s:10:"identifier";s:7:"company";s:9:"parent_id";s:1:"7";s:5:"taxis";s:2:"10";s:6:"status";s:1:"1";s:9:"seo_title";s:0:"";s:12:"seo_keywords";s:0:"";s:8:"seo_desc";s:0:"";s:5:"psize";s:1:"0";s:8:"tpl_list";s:0:"";s:11:"tpl_content";s:0:"";}'),
(111, 'cate-71', 1, 'a:13:{s:2:"id";s:2:"71";s:5:"title";s:9:"T恤/POLO";s:10:"identifier";s:8:"txu-polo";s:9:"parent_id";s:2:"70";s:5:"taxis";s:2:"10";s:6:"status";s:1:"1";s:2:"qq";s:5:"ttttt";s:9:"seo_title";s:0:"";s:12:"seo_keywords";s:0:"";s:8:"seo_desc";s:0:"";s:5:"psize";s:1:"0";s:8:"tpl_list";s:0:"";s:11:"tpl_content";s:0:"";}');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_tpl`
--

DROP TABLE IF EXISTS `qinggan_tpl`;
CREATE TABLE IF NOT EXISTS `qinggan_tpl` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `title` varchar(100) NOT NULL COMMENT '模板名称',
  `author` varchar(100) NOT NULL COMMENT '开发者名称',
  `folder` varchar(100) NOT NULL DEFAULT 'www' COMMENT '模板目录',
  `refresh_auto` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1自动判断更新刷新0不刷新',
  `refresh` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1强制刷新0普通刷新',
  `ext` varchar(20) NOT NULL DEFAULT 'html' COMMENT '后缀',
  `folder_change` varchar(255) NOT NULL COMMENT '更改目录',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模板管理' AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `qinggan_tpl`
--

INSERT INTO `qinggan_tpl` (`id`, `title`, `author`, `folder`, `refresh_auto`, `refresh`, `ext`, `folder_change`) VALUES
(1, '默认风格', 'phpok.com', 'www', 1, 0, 'html', 'css,images,js');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_user`
--

DROP TABLE IF EXISTS `qinggan_user`;
CREATE TABLE IF NOT EXISTS `qinggan_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID，即会员ID',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主要会员组',
  `user` varchar(100) NOT NULL COMMENT '会员账号',
  `pass` varchar(100) NOT NULL COMMENT '会员密码',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态ID，0未审核1正常2锁定',
  `regtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `email` varchar(200) NOT NULL COMMENT '邮箱，可用于取回密码',
  `mobile` varchar(50) NOT NULL COMMENT '手机或电话',
  `code` varchar(255) NOT NULL COMMENT '验证串，可用于取回密码',
  `avatar` varchar(255) NOT NULL COMMENT '会员头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员管理' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_user_ext`
--

DROP TABLE IF EXISTS `qinggan_user_ext`;
CREATE TABLE IF NOT EXISTS `qinggan_user_ext` (
  `id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `fullname` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `gender` varchar(255) NOT NULL DEFAULT '' COMMENT '性别',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员扩展字段';

--
-- 转存表中的数据 `qinggan_user_ext`
--

INSERT INTO `qinggan_user_ext` (`id`, `fullname`, `gender`) VALUES
(2, '', ''),
(21, '', ''),
(8, '', ''),
(9, 'demo', ''),
(10, '', ''),
(23, '苏相锟', '');

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_user_fields`
--

DROP TABLE IF EXISTS `qinggan_user_fields`;
CREATE TABLE IF NOT EXISTS `qinggan_user_fields` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段ID，自增',
  `title` varchar(255) NOT NULL COMMENT '字段名称',
  `identifier` varchar(50) NOT NULL COMMENT '字段标识串',
  `field_type` varchar(255) NOT NULL DEFAULT '200' COMMENT '字段存储类型',
  `note` varchar(255) NOT NULL COMMENT '字段内容备注',
  `form_type` varchar(100) NOT NULL COMMENT '表单类型',
  `form_style` varchar(255) NOT NULL COMMENT '表单CSS',
  `format` varchar(100) NOT NULL COMMENT '格式化方式',
  `content` varchar(255) NOT NULL COMMENT '默认值',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `ext` text NOT NULL COMMENT '扩展内容',
  `is_edit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不可编辑1可编辑',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='字段管理器' AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `qinggan_user_fields`
--

INSERT INTO `qinggan_user_fields` (`id`, `title`, `identifier`, `field_type`, `note`, `form_type`, `form_style`, `format`, `content`, `taxis`, `ext`, `is_edit`) VALUES
(21, '姓名', 'fullname', 'varchar', '', 'text', '', 'safe', '', 10, 'a:2:{s:8:"form_btn";b:0;s:5:"width";s:3:"300";}', 1),
(22, '性别', 'gender', 'varchar', '', 'radio', '', 'safe', '', 120, 'a:2:{s:11:"option_list";s:5:"opt:1";s:9:"put_order";b:0;}', 1);

-- --------------------------------------------------------

--
-- 表的结构 `qinggan_user_group`
--

DROP TABLE IF EXISTS `qinggan_user_group`;
CREATE TABLE IF NOT EXISTS `qinggan_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员组ID',
  `title` varchar(255) NOT NULL COMMENT '会员组名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0不使用1使用',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1为会员注册默认组',
  `is_guest` tinyint(1) NOT NULL DEFAULT '0' COMMENT '游客组',
  `read_popedom` text NOT NULL COMMENT '阅读权限',
  `post_popedom` text NOT NULL,
  `reply_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '回复1免审核0需要审核',
  `post_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0需要审核1免审核',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1开放供用户选择，0不开放',
  `taxis` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `register_status` varchar(100) NOT NULL COMMENT '1通过0审核email邮件code邀请码mobile手机',
  `tbl_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关联验证串项目',
  `fields` text NOT NULL COMMENT '会员字段，多个字段用英文逗号隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员组信息管理' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `qinggan_user_group`
--

INSERT INTO `qinggan_user_group` (`id`, `title`, `status`, `is_default`, `is_guest`, `read_popedom`, `post_popedom`, `reply_status`, `post_status`, `is_open`, `taxis`, `register_status`, `tbl_id`, `fields`) VALUES
(2, '普通会员', 1, 1, 0, 'all', '', 0, 0, 1, 10, '0', 0, 'fullname,gender'),
(3, '游客组', 1, 0, 1, 'all', '96', 0, 0, 0, 200, '', 0, ''),
(5, '商家会员', 1, 0, 0, 'all', '41,42', 0, 0, 1, 10, '0', 0, 'fullname,gender');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
