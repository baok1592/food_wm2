 

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for news_address
-- ----------------------------
DROP TABLE IF EXISTS `news_address`;
CREATE TABLE `news_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) DEFAULT NULL COMMENT '父id',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `merger_name` varchar(255) DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) unsigned NOT NULL DEFAULT 1 COMMENT '层级 1 2 3 ',
  `ucid` int(1) DEFAULT NULL COMMENT 'ucid',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `name,level` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='地区总表';
 

 
DROP TABLE IF EXISTS `news_admins`;
CREATE TABLE `news_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0 COMMENT 'w7_id',
  `username` varchar(40) DEFAULT NULL,
  `password` varchar(60) NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `phone` varchar(20) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL DEFAULT '0',
  `state` int(1) NOT NULL DEFAULT 0 COMMENT '是否禁用',
  `description` varchar(200) NOT NULL DEFAULT '0' COMMENT '描述',
  `login_time` int(11) DEFAULT NULL COMMENT '登录时间',
  `create_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
 
DROP TABLE IF EXISTS `news_article`;
CREATE TABLE `news_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `short_title` varchar(255) NOT NULL COMMENT '短标题',
  `desc` varchar(140) NOT NULL DEFAULT '' COMMENT '摘要',
  `content` text NOT NULL COMMENT '正文',
  `img_id` varchar(255) NOT NULL COMMENT '图片ID',
  `author` varchar(50) NOT NULL COMMENT '发布者用户名 ',
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `source` varchar(255) NOT NULL COMMENT '来源',
  `sort` int(11) NOT NULL COMMENT '排序',
  `jump_url` varchar(80) NOT NULL COMMENT '外链',
  `read_count` int(11) NOT NULL COMMENT '阅读量',
  `label` varchar(80) NOT NULL COMMENT '标签',
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-显示、1-隐藏',
  `is_top` int(11) NOT NULL COMMENT '是否头条0否1是',
  `is_new` int(11) NOT NULL DEFAULT 0 COMMENT '是否最新',
  `is_hot` int(11) NOT NULL DEFAULT 0 COMMENT '是否热门',
  `is_vip` int(11) DEFAULT 0 COMMENT '是否VIP',
  `date` int(11) DEFAULT NULL COMMENT '文章自定义日期',
  `create_time` int(11) DEFAULT NULL COMMENT '文章发布时间',
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=6553 ROW_FORMAT=COMPACT COMMENT='CMS文章表';

 
DROP TABLE IF EXISTS `news_auth_group`;
CREATE TABLE `news_auth_group` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL DEFAULT '''\\''\\''''',
  `state` tinyint(1) NOT NULL DEFAULT 1,
  `rules` varchar(1000) NOT NULL DEFAULT '',
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of news_auth_group
-- ----------------------------
INSERT INTO `news_auth_group` VALUES ('1', '超级管理员', '1', '7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,5,27,28,29,6,30,4,31,2,32,3,33,1,36,37', null);
INSERT INTO `news_auth_group` VALUES ('2', '网站编辑', '1', '7,9,10,11,12,13,14,33,8,1,21,22,23,30,25,27', null);

-- ----------------------------
-- Table structure for news_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `news_auth_group_access`;
CREATE TABLE `news_auth_group_access` (
  `aid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`aid`,`group_id`) USING BTREE,
  KEY `uid` (`aid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

INSERT INTO `news_auth_group_access` VALUES ('1', '1');

-- ----------------------------
-- Table structure for news_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `news_auth_rule`;
CREATE TABLE `news_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(1) NOT NULL DEFAULT 0,
  `title` char(40) DEFAULT '' COMMENT '中文名',
  `auth_name` char(40) DEFAULT NULL COMMENT 'cms中英文名',
  `cname` varchar(255) DEFAULT NULL COMMENT '控制器名',
  `fnames` varchar(255) DEFAULT NULL COMMENT '函数名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of news_auth_rule
-- ----------------------------
INSERT INTO `news_auth_rule` VALUES ('1', '0', '内容', 'ctx', null, null);
INSERT INTO `news_auth_rule` VALUES ('2', '0', '订单', 'order', null, null);
INSERT INTO `news_auth_rule` VALUES ('3', '0', '手机', 'mobile', null, null);
INSERT INTO `news_auth_rule` VALUES ('4', '0', '其他', 'other', null, null);
INSERT INTO `news_auth_rule` VALUES ('5', '0', '用户', 'userManage', null, null);
INSERT INTO `news_auth_rule` VALUES ('6', '0', '配置', 'sys', null, null);
INSERT INTO `news_auth_rule` VALUES ('7', '1', '文章', 'ArticleList', 'cms.article', 'getAll,add,up,detail,editState,addWangImg,getCidArticle');
INSERT INTO `news_auth_rule` VALUES ('8', '1', '文章删除', 'ArticleDel', 'cms.article', 'del');
INSERT INTO `news_auth_rule` VALUES ('9', '1', '餐桌', 'Desk', 'cms.desk', 'getAll,add,up,detail,end');
INSERT INTO `news_auth_rule` VALUES ('10', '1', '餐桌删除', 'DeskDel', 'cms.desk', 'del');
INSERT INTO `news_auth_rule` VALUES ('11', '1', '产品', 'ProList', 'cms.pros', 'getAll,add,up,detail,editState,getCidPros');
INSERT INTO `news_auth_rule` VALUES ('12', '1', '产品删除', 'ProDel', 'cms.pros', 'del');
INSERT INTO `news_auth_rule` VALUES ('13', '1', '栏目', 'CategoryList', 'cms.category', 'getAll,up,detail,upHomeTmp');
INSERT INTO `news_auth_rule` VALUES ('14', '1', '栏目增删', 'CategoryManage', 'cms.category', 'del,add,up_sort,editState');
INSERT INTO `news_auth_rule` VALUES ('15', '4', '小票机', 'Machine', 'cms.machine', 'getAll,add,up,del,detail');
INSERT INTO `news_auth_rule` VALUES ('17', '3', '手机端装修', 'mdiy', 'cms.mobile', 'tmp_all,add,up,del,detail,upName');
INSERT INTO `news_auth_rule` VALUES ('18', '3', '手机端导航', 'mnav', 'cms.mobile', 'getNavsall,navsUp');
INSERT INTO `news_auth_rule` VALUES ('19', '3', '公众号', 'gzh', null, null);
INSERT INTO `news_auth_rule` VALUES ('20', '3', '微信小程序', 'wxxcx', null, null);
INSERT INTO `news_auth_rule` VALUES ('21', '4', '广告', 'AD', 'cms.ad', 'add,up,del');
INSERT INTO `news_auth_rule` VALUES ('22', '4', '资源管理', 'resource', 'cms.resource', 'getResourcType,getCategoryType,getImgCategoryData,addImg,upImgbackUrl');
INSERT INTO `news_auth_rule` VALUES ('23', '4', '资源分类', 'resourceCate', 'cms.resource', 'categoryAdd');
INSERT INTO `news_auth_rule` VALUES ('24', '4', '资源删除', 'resourceDel', 'cms.resource', 'dels');
INSERT INTO `news_auth_rule` VALUES ('25', '5', '用户列表', 'userList', 'cms.user', 'userAll');
INSERT INTO `news_auth_rule` VALUES ('26', '5', '用户删禁', 'userDel', 'cms.user', 'del,userDisable');
INSERT INTO `news_auth_rule` VALUES ('27', '6', '配置', 'setSys', 'cms.system', 'getConfigType,up');
INSERT INTO `news_auth_rule` VALUES ('28', '6', '管理员', 'admins', 'cms.admins', 'alls');
INSERT INTO `news_auth_rule` VALUES ('29', '6', '管理组', 'groups', 'cms.group', 'alls');
INSERT INTO `news_auth_rule` VALUES ('30', '4', '统计', 'statistics', 'cms.common', 'statistics');
INSERT INTO `news_auth_rule` VALUES ('31', '4', '地址管理', 'Address', 'cms.address', 'getAll,up,del,add');
INSERT INTO `news_auth_rule` VALUES ('32', '3', '手机导航', 'mnav', 'cms.mobile', 'getNavsall,navsUp');
INSERT INTO `news_auth_rule` VALUES ('33', '1', '优惠券', 'Coupon', 'cms.coupon', 'getAll,add,up,del');
INSERT INTO `news_auth_rule` VALUES ('34', '4', '表单', 'Form', 'cms.form', 'getAll,add,up,getDataAll');
INSERT INTO `news_auth_rule` VALUES ('35', '4', '表单删除', 'FormManage', 'cms.form', 'del,delDataItem');
INSERT INTO `news_auth_rule` VALUES ('36', '2', '订单', 'Order', 'cms.order', 'getAll,detail,deskOrderDetail');
INSERT INTO `news_auth_rule` VALUES ('37', '2', '订单管理', 'Order', 'cms.order', 'order_admin_add,del,up_state,upDeskOrder');

-- ----------------------------
-- Table structure for news_auto_revert
-- ----------------------------
DROP TABLE IF EXISTS `news_auto_revert`;
CREATE TABLE `news_auto_revert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_name` varchar(255) NOT NULL COMMENT '规则名称',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `type` varchar(20) NOT NULL COMMENT '类型 纯文字、图文、图片',
  `content` varchar(255) DEFAULT NULL COMMENT '回复内容，图文和图片存id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of news_auto_revert
-- ----------------------------
INSERT INTO `news_auto_revert` VALUES ('1', '发1回复2', '2', 'text', '2');

-- ----------------------------
-- Table structure for news_banner
-- ----------------------------
DROP TABLE IF EXISTS `news_banner`;
CREATE TABLE `news_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL COMMENT 'Banner名称，通常作为标识',
  `type` tinyint(6) DEFAULT NULL COMMENT 'pc0,手机1',
  `description` varchar(255) DEFAULT NULL COMMENT 'Banner描述',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='banner管理表';

-- ----------------------------
-- Records of news_banner
-- ----------------------------
INSERT INTO `news_banner` VALUES ('1', '0', '首页幻灯', '0', null, null, null);
INSERT INTO `news_banner` VALUES ('2', '0', '列表顶部', '0', null, null, null);
INSERT INTO `news_banner` VALUES ('3', '0', '手机首页幻灯', '1', null, null, null);
INSERT INTO `news_banner` VALUES ('4', '0', '封面顶部', '0', null, null, null);

-- ----------------------------
-- Table structure for news_banner_item
-- ----------------------------
DROP TABLE IF EXISTS `news_banner_item`;
CREATE TABLE `news_banner_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `banner_id` int(11) NOT NULL COMMENT '外键，关联banner表',
  `img_id` varchar(255) NOT NULL COMMENT '外键，关联image表',
  `sort` int(11) NOT NULL DEFAULT 0,
  `jump_id` int(11) NOT NULL DEFAULT 0,
  `jump_type` varchar(255) NOT NULL DEFAULT '1' COMMENT '跳转类型',
  `one` varchar(255) DEFAULT NULL,
  `two` varchar(255) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='banner子项表';

-- ----------------------------
-- Records of news_banner_item
-- ----------------------------
INSERT INTO `news_banner_item` VALUES ('1', '0', '广告1', '3', '1', '0', '0', 'lists', null, null, null, null);
 
-- ----------------------------
-- Table structure for news_category
-- ----------------------------
DROP TABLE IF EXISTS `news_category`;
CREATE TABLE `news_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '栏目标题',
  `type` char(20) NOT NULL COMMENT '类型：lists,cards,pros,cover...',
  `pid` int(11) NOT NULL DEFAULT 0,
  `level` tinyint(4) NOT NULL DEFAULT 0,
  `sort` int(11) DEFAULT NULL,
  `img_id` int(255) DEFAULT 0 COMMENT '商品分类图片',
  `is_hidden` int(1) NOT NULL DEFAULT 0 COMMENT '1 显示 0隐藏',
  `is_top` int(1) unsigned zerofill NOT NULL DEFAULT 0 COMMENT '是否手机端首页显示0否1是',
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=244 ROW_FORMAT=COMPACT COMMENT='商品分类表';

-- ----------------------------
-- Records of news_category
-- ----------------------------
INSERT INTO `news_category` VALUES ('1', '0', '分类1', '', '0', '0', '0', '1', '0', '0', null);
INSERT INTO `news_category` VALUES ('2', '0', '分类2', '', '0', '0', '0', '2', '0', '0', null);

-- ----------------------------
-- Table structure for news_coupon
-- ----------------------------
DROP TABLE IF EXISTS `news_coupon`;
CREATE TABLE `news_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT 3 COMMENT '类型1:店铺优惠券',
  `name` varchar(50) DEFAULT NULL COMMENT '优惠券名称',
  `state` int(11) NOT NULL DEFAULT 1 COMMENT '状态1:使用1次，0使用无数次',
  `is_show` int(11) NOT NULL DEFAULT 0 COMMENT 'vip特权券是否能领取,0不可领取,1能领取,2撤回申请中',
  `stock` int(11) DEFAULT NULL COMMENT '库存null为无限张',
  `infinite` int(11) NOT NULL DEFAULT 0 COMMENT '库存类型(0有限1无限张)',
  `full` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '满多少，0为无门槛',
  `reduce` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '减多少',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `day` int(11) DEFAULT NULL COMMENT '使用时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `ucid` int(1) DEFAULT NULL COMMENT 'ucid',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券表';

 
-- ----------------------------
-- Table structure for news_desk
-- ----------------------------
DROP TABLE IF EXISTS `news_desk`;
CREATE TABLE `news_desk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT '' COMMENT '桌号',
  `bz` varchar(255) DEFAULT NULL COMMENT '备注',
  `ucid` int(11) NOT NULL DEFAULT 3,
  `h5_img` varchar(255) DEFAULT NULL,
  `xcx_img` varchar(255) DEFAULT NULL,
  `num` int(11) NOT NULL DEFAULT 0 COMMENT '餐桌人数',
  `money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '结账金额',
  `time` int(11) NOT NULL DEFAULT 0 COMMENT '时间',
  `state` int(11) NOT NULL DEFAULT 0 COMMENT '0空闲1待结账',
  `order_id` int(11) NOT NULL DEFAULT 0 COMMENT '绑定订单id',
  `service` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of news_desk
-- ----------------------------
INSERT INTO `news_desk` VALUES ('1', '1号餐桌', null, '0', null, null, '0', '0.00', '0', '0', '0', '0');
-- ----------------------------
-- Table structure for news_form
-- ----------------------------
DROP TABLE IF EXISTS `news_form`;
CREATE TABLE `news_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `json` text DEFAULT NULL,
  `is_hidden` int(11) DEFAULT 0,
  `create_time` int(11) DEFAULT NULL,
  `upadte_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news_form
-- ----------------------------

-- ----------------------------
-- Table structure for news_form_data
-- ----------------------------
DROP TABLE IF EXISTS `news_form_data`;
CREATE TABLE `news_form_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `json` text DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news_form_data
-- ----------------------------

-- ----------------------------
-- Table structure for news_image
-- ----------------------------
DROP TABLE IF EXISTS `news_image`;
CREATE TABLE `news_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `url` varchar(255) NOT NULL COMMENT '图片路径',
  `cid` int(11) NOT NULL,
  `state` int(11) DEFAULT 1,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='图片总表';


INSERT INTO `news_image` VALUES ('1', '0', '1/632a806a46ca9.png', '1', '1', '1663729770');
INSERT INTO `news_image` VALUES ('2', '0', '1/632a807f3bb1f.png', '1', '1', '1663729791');

-- ----------------------------
-- Table structure for news_machine
-- ----------------------------
DROP TABLE IF EXISTS `news_machine`;
CREATE TABLE `news_machine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feie_user` varchar(40) NOT NULL DEFAULT '' COMMENT '飞鹅打印机账号',
  `feie_ukey` varchar(40) NOT NULL DEFAULT '' COMMENT '飞鹅打印机ukey',
  `feie_sn` varchar(40) NOT NULL DEFAULT '' COMMENT '飞鹅打印机Sn',
  `feie_name` varchar(40) NOT NULL DEFAULT '' COMMENT '打印名称',
  `feie_key` varchar(40) NOT NULL DEFAULT '' COMMENT '飞鹅打印机key',
  `is_printer` int(2) NOT NULL COMMENT '打印机类型(0小票机1标签机）',
  `feie_formwork_id` int(11) NOT NULL DEFAULT 0 COMMENT '飞鹅打印机模板',
  `times` int(11) NOT NULL DEFAULT 1 COMMENT '打印次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='飞鹅打印机配置信息';

-- ----------------------------
-- Records of news_machine
-- ---------------------------- 

-- ----------------------------
-- Table structure for news_machine_work
-- ----------------------------
DROP TABLE IF EXISTS `news_machine_work`;
CREATE TABLE `news_machine_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '模板名称',
  `title` text CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '标题',
  `json` varchar(255) DEFAULT NULL COMMENT '模板',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='飞鹅打印机模板';

-- ----------------------------
-- Records of news_machine_work
-- ----------------------------

-- ----------------------------
-- Table structure for news_mb_diy
-- ----------------------------
DROP TABLE IF EXISTS `news_mb_diy`;
CREATE TABLE `news_mb_diy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '页面名称',
  `type` varchar(255) DEFAULT NULL COMMENT '页面类型：lists/pros/cover...',
  `json` text DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of news_mb_diy
-- ----------------------------
INSERT INTO `news_mb_diy` VALUES ('1', null, '首页', 'home', '[{\"name\":\"hero\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"轮播图\"},{\"name\":\"grid\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"宫格\"},{\"name\":\"coupon\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"优惠券\"},{\"name\":\"point\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"积分\"},{\"name\":\"text\",\"child\":\"a\",\"withid\":0,\"json\":[\"店铺地址，电话等\"],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"文字\"}]', '1654533874', '1663054294', null);
INSERT INTO `news_mb_diy` VALUES ('2', null, '菜单', 'pros', '[{\"name\":\"search\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"搜索\"},{\"name\":\"address\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"地址\"},{\"name\":\"menu\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"菜单\"}]', '1654533883', '1663052687', null);
INSERT INTO `news_mb_diy` VALUES ('3', null, '我的', 'cover', '[{\"name\":\"user\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"个人\"},{\"name\":\"lists\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"列表\"},{\"name\":\"text\",\"child\":\"a\",\"withid\":0,\"json\":[\"店铺地址，电话等\"],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"文字\"}]', '1654533891', '1663053093', null);
INSERT INTO `news_mb_diy` VALUES ('4', null, '手机文章列表', 'lists', '[{\"name\":\"lists\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"列表\"}]', '1654533901', '1654831813', null);
INSERT INTO `news_mb_diy` VALUES ('5', null, '通用封面', 'cover', '[{\"name\":\"content\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"原文本\"},{\"name\":\"user\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"个人\"},{\"name\":\"lists\",\"child\":\"a\",\"withid\":19,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"列表\"},{\"name\":\"text\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"文字\"}]', '1654533908', '1662371020', null);
INSERT INTO `news_mb_diy` VALUES ('6', null, '联系我们', 'cover', '[{\"name\":\"us\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"联系\"},{\"name\":\"content\",\"child\":\"a\",\"withid\":0,\"json\":[],\"radios\":[],\"img\":\"\",\"jumpid\":0,\"title\":\"原文本\"}]', '1654684818', '1654702377', null);

-- ----------------------------
-- Table structure for news_mb_navs
-- ----------------------------
DROP TABLE IF EXISTS `news_mb_navs`;
CREATE TABLE `news_mb_navs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(60) DEFAULT NULL,
  `jump_type` varchar(60) DEFAULT NULL,
  `jump_id` int(11) DEFAULT NULL,
  `state` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of news_mb_navs
-- ----------------------------
INSERT INTO `news_mb_navs` VALUES ('1', '0', '首页', 'house', 'home', '0', '0');
INSERT INTO `news_mb_navs` VALUES ('2', '0', '企业环境', 'menu', 'cards', '23', '0');
INSERT INTO `news_mb_navs` VALUES ('3', '0', '最新动态', 'DocumentCopy', 'lists', '19', '0');
INSERT INTO `news_mb_navs` VALUES ('4', '0', '关于我们', 'Box', 'cover', '22', '1');

-- ----------------------------
-- Table structure for news_money_log
-- ----------------------------
DROP TABLE IF EXISTS `news_money_log`;
CREATE TABLE `news_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `content` varchar(800) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `wx_refund` varchar(50) DEFAULT NULL COMMENT '微信退款id',
  `create_time` int(11) DEFAULT NULL,
  `ucid` int(1) DEFAULT NULL COMMENT 'ucid',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单日志表';

-- ----------------------------
-- Records of news_money_log
-- ----------------------------

-- ----------------------------
-- Table structure for news_order
-- ----------------------------
DROP TABLE IF EXISTS `news_order`;
CREATE TABLE `news_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_num` varchar(40) DEFAULT NULL COMMENT '订单编号',
  `user_id` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0 COMMENT '0未完成 1已完成 2已评价 -1退款中 -2已退款-3关闭订单',
  `shipment_state` int(11) NOT NULL DEFAULT 0 COMMENT '运输（验证）状态  0待配送 1已配送 2已收货',
  `payment_state` int(11) NOT NULL DEFAULT 0 COMMENT '支付状态 0 1',
  `rate_id` int(11) DEFAULT 0,
  `coupon_id` int(11) DEFAULT 0 COMMENT '优惠券ID',
  `order_from` varchar(4) DEFAULT NULL COMMENT '来源小程序或wap',
  `payment_type` varchar(60) DEFAULT NULL COMMENT '支付来源',
  `goods_money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '商品总价',
  `reduction_money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '满减价格',
  `coupon_money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '优惠券价格',
  `edit_money` decimal(10,2) DEFAULT 0.00,
  `order_money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '订单总价',
  `user_ip` varchar(40) DEFAULT NULL,
  `goods_picture` varchar(400) DEFAULT NULL,
  `message` varchar(300) DEFAULT NULL COMMENT '备注',
  `receiver_name` varchar(60) DEFAULT NULL COMMENT '收货人',
  `receiver_mobile` varchar(60) DEFAULT NULL COMMENT '收货人手机',
  `receiver_city` varchar(60) DEFAULT NULL,
  `receiver_address` varchar(200) DEFAULT NULL,
  `courier_num` varchar(60) DEFAULT NULL,
  `courier` varchar(255) DEFAULT NULL,
  `remark_one` varchar(255) DEFAULT NULL,
  `remark_two` varchar(255) DEFAULT NULL,
  `drive_type` varchar(255) DEFAULT NULL COMMENT '配送方式',
  `prepay_id` varchar(255) DEFAULT NULL,
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `points` int(11) DEFAULT 0,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `other` text DEFAULT NULL COMMENT '其他要求',
  `mobile` varchar(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `count` int(11) NOT NULL DEFAULT 0,
  `pay_cate` varchar(255) NOT NULL DEFAULT '3' COMMENT '支付方式1微信支付2余额支付3暂不支付',
  `ucid` int(11) DEFAULT NULL,
  `desk_id` int(11) DEFAULT NULL COMMENT '餐桌ID',
  `yzcode` varchar(255) DEFAULT NULL,
  `invite_code` varchar(10) DEFAULT NULL,
  `fx_money` float NOT NULL DEFAULT 0,
  `vmoney` decimal(10,2) DEFAULT 0.00 COMMENT '会员折扣价格',
  `ph_create_order` tinyint(2) NOT NULL DEFAULT 0 COMMENT '0其他方式下单1手机下单',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单表';

 
-- ----------------------------
-- Table structure for news_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `news_order_goods`;
CREATE TABLE `news_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `goods_name` varchar(300) DEFAULT NULL,
  `sku_name` varchar(300) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `number` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `state` int(11) NOT NULL DEFAULT 0 COMMENT '订单状态0未完成 1已完成 2已评价 -1退款中 -2已退款-3关闭订单	',
  `pic` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `remark` varchar(400) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `ucid` int(1) DEFAULT NULL,
  `notes` varchar(40) DEFAULT NULL COMMENT '注释',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单商品详情表';

 
-- ----------------------------
-- Table structure for news_order_log
-- ----------------------------
DROP TABLE IF EXISTS `news_order_log`;
CREATE TABLE `news_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `content` varchar(800) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `wx_refund` varchar(50) DEFAULT NULL COMMENT '微信退款id',
  `create_time` int(11) DEFAULT NULL,
  `ucid` int(1) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单日志表';

-- ----------------------------
-- Records of news_order_log
-- ----------------------------

-- ----------------------------
-- Table structure for news_pros
-- ----------------------------
DROP TABLE IF EXISTS `news_pros`;
CREATE TABLE `news_pros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `before_price` decimal(10,2) DEFAULT NULL COMMENT '原价',
  `desc` varchar(140) NOT NULL DEFAULT '' COMMENT '摘要',
  `content` text NOT NULL COMMENT '正文',
  `stock` int(11) DEFAULT NULL,
  `sale` int(11) DEFAULT NULL,
  `img_id` int(11) NOT NULL COMMENT '图片ID',
  `img_ids` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `sort` int(11) NOT NULL COMMENT '排序',
  `label` varchar(80) NOT NULL COMMENT '标签',
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-显示、1-隐藏',
  `is_hot` int(11) NOT NULL DEFAULT 0 COMMENT '是否热门',
  `is_top` int(11) NOT NULL COMMENT '是否推荐',
  `create_time` int(11) DEFAULT NULL COMMENT '发布时间',
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=6553 ROW_FORMAT=COMPACT COMMENT='CMS文章表';

 
-- ----------------------------
-- Table structure for news_pros_sku
-- ----------------------------
DROP TABLE IF EXISTS `news_pros_sku`;
CREATE TABLE `news_pros_sku` (
  `sku_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表序号',
  `goods_id` int(11) NOT NULL DEFAULT 0 COMMENT '商品编号',
  `ucid` int(11) NOT NULL DEFAULT 0 COMMENT 'ucid',
  `json` text NOT NULL,
  PRIMARY KEY (`sku_id`) USING BTREE,
  UNIQUE KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=481 ROW_FORMAT=COMPACT COMMENT='商品skui规格价格库存信息表';

 
-- ----------------------------
-- Table structure for news_rate
-- ----------------------------
DROP TABLE IF EXISTS `news_rate`;
CREATE TABLE `news_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `rate` int(11) NOT NULL DEFAULT 5,
  `content` varchar(800) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `imgs` text DEFAULT NULL COMMENT '图片集',
  `headpic` varchar(255) DEFAULT NULL COMMENT '头像',
  `nickname` varchar(20) NOT NULL DEFAULT '0',
  `reply_content` varchar(255) DEFAULT NULL COMMENT '回复内容',
  `reply_time` int(11) NOT NULL DEFAULT 0 COMMENT '回复时间',
  `aid` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `create_time` int(11) DEFAULT NULL,
  `video` varchar(300) DEFAULT NULL COMMENT '视频地址',
  `update_time` int(11) DEFAULT NULL,
  `ucid` int(1) DEFAULT NULL COMMENT 'ucid',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='评价表';

-- ----------------------------
-- Records of news_rate
-- ----------------------------

-- ----------------------------
-- Table structure for news_resource
-- ----------------------------
DROP TABLE IF EXISTS `news_resource`;
CREATE TABLE `news_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `type` char(20) DEFAULT NULL COMMENT 'img,video,file',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of news_resource
-- ----------------------------

-- ----------------------------
-- Table structure for news_sys_backup
-- ----------------------------
DROP TABLE IF EXISTS `news_sys_backup`;
CREATE TABLE `news_sys_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `size` varchar(20) DEFAULT NULL COMMENT '大小',
  `url` varchar(255) DEFAULT NULL COMMENT '路径',
  `create_time` int(11) DEFAULT NULL,
  `ucid` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of news_sys_backup
-- ----------------------------

-- ----------------------------
-- Table structure for news_sys_config
-- ----------------------------
DROP TABLE IF EXISTS `news_sys_config`;
CREATE TABLE `news_sys_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(11) NOT NULL DEFAULT 0,
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项',
  `value` text DEFAULT NULL COMMENT '配置值json',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '描述',
  `type` int(11) NOT NULL COMMENT '1基础配置2网站信息3微信相关',
  `is_use` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否启用 1启用 0不启用',
  `switch` int(11) NOT NULL DEFAULT 0 COMMENT '0输入框1双选2三选3？4上传图',
  `update_time` int(11) NOT NULL,
  `other` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=963 ROW_FORMAT=COMPACT COMMENT='第三方配置表';

-- ----------------------------
-- Records of news_sys_config
-- ----------------------------
INSERT INTO `news_sys_config` VALUES ('1', '0', 'web_url', '', '网址', '1', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('2', '0', 'site_type', '0', '点餐方式', '1', '1', '2', '0', '店内');
INSERT INTO `news_sys_config` VALUES ('3', '0', 'login_type', '0', '付款模式', '1', '1', '2', '1', '后付[餐桌],先付[微信]');
INSERT INTO `news_sys_config` VALUES ('5', '0', 'gzh_appid', '', '公众号appid', '3', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('6', '0', 'gzh_secret', '', '公众号密钥', '3', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('7', '0', 'wx_token_expire', '0', 'token有效期', '1', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('8', '0', 'xcx_appid', '', '小程序appid', '3', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('9', '0', 'xcx_secret', '', '小程序秘钥', '3', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('11', '0', 'site_name', '如花扫码点餐外卖', '站点名称', '1', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('13', '0', 'home_cpy', '', '公司名称', '2', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('14', '0', 'seo_title', '', '首页标题', '1', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('15', '0', 'cpy_address', '公司地址', '公司地址', '2', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('16', '0', 'seo_desc', '简单易用', '首页描述', '1', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('21', '0', 'accessKeyId', '0', 'OSS-KeyId', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('22', '0', 'accessKeySecret', '0', 'OSS-KeySecret', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('23', '0', 'bucket', '0', 'OSS-bucket名称', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('24', '0', 'endpoint', '0', 'OSS访问域名', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('29', '0', 'longitude', '3', '企业经度', '2', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('30', '0', 'latitude', '0', '企业纬度', '2', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('31', '0', 'beian', 'ICP备案号', '企业备案号', '2', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('32', '0', 'email', '邮箱：', '邮箱', '2', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('33', '0', 'tel', '1234567', '联系电话', '2', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('37', '0', 'yzm_tmp_id', '', '短信模板id', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('38', '0', 'yzm_sign', '', '短信签名', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('39', '0', 'yzm_keyid', '', '短信keyid', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('40', '0', 'yzm_secret', '', '短信秘钥', '4', '1', '0', '0', null);
INSERT INTO `news_sys_config` VALUES ('42', '0', 'pay_num', '', '商户id', '3', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('43', '0', 'pay_key', '', '商户key', '3', '1', '0', '1567744893', null);
INSERT INTO `news_sys_config` VALUES ('92', '0', 'servers', '', '客服开关', '5', '1', '1', '1', '0');
INSERT INTO `news_sys_config` VALUES ('94', '0', 'logo', '', 'Logo图标', '5', '1', '4', '0', null);
INSERT INTO `news_sys_config` VALUES ('95', '0', 'seo_keys', '开源', 'SEO关键字', '1', '1', '0', '0', '');

-- ----------------------------
-- Table structure for news_tui
-- ----------------------------
DROP TABLE IF EXISTS `news_tui`;
CREATE TABLE `news_tui` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT '订单id',
  `tui_num` varchar(30) NOT NULL DEFAULT '0' COMMENT '退款单号',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `order_num` varchar(40) DEFAULT NULL COMMENT '订单号',
  `money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '价钱',
  `message` varchar(255) DEFAULT NULL COMMENT '信息',
  `because` varchar(255) DEFAULT NULL COMMENT '原因',
  `ip` varchar(100) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0退款中1已退款2驳回中',
  `aid` int(11) NOT NULL DEFAULT 0,
  `wx_id` varchar(50) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `ucid` int(11) NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='退货管理表';

-- ----------------------------
-- Records of news_tui
-- ----------------------------

-- ----------------------------
-- Table structure for news_user
-- ----------------------------
DROP TABLE IF EXISTS `news_user`;
CREATE TABLE `news_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '昵称',
  `unionid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `openid_gzh` varchar(70) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '公众号openid',
  `openid_zfb` varchar(70) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '支付宝openid',
  `openid_app` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `openid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '小程序openid',
  `money` double(11,2) NOT NULL DEFAULT 0.00 COMMENT '余额',
  `sign_time` bigint(20) DEFAULT NULL COMMENT '上次签到时间',
  `sign_day` bigint(20) DEFAULT NULL COMMENT '连续签到天数',
  `level_id` bigint(20) DEFAULT 1 COMMENT '用户等级',
  `headpic` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '电话',
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户真实姓名',
  `ucid` int(1) DEFAULT NULL COMMENT 'ucid',
  `delete_time` bigint(20) DEFAULT NULL,
  `create_time` int(20) DEFAULT NULL,
  `update_time` int(20) DEFAULT NULL,
  `invite_code` int(11) DEFAULT NULL COMMENT '邀请码',
  `invite_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邀请链接',
  `points_id` int(11) NOT NULL DEFAULT 0,
  `web_auth` int(11) NOT NULL DEFAULT 0,
  `group_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

 
-- ----------------------------
-- Table structure for news_user_address
-- ----------------------------
DROP TABLE IF EXISTS `news_user_address`;
CREATE TABLE `news_user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '收获人姓名',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(400) DEFAULT NULL,
  `detail` varchar(400) DEFAULT NULL COMMENT '详细地址',
  `user_id` int(11) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `ucid` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='用户地址表';

-- ----------------------------
-- Records of news_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for news_user_coupon
-- ----------------------------
DROP TABLE IF EXISTS `news_user_coupon`;
CREATE TABLE `news_user_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `coupon_id` int(11) DEFAULT NULL COMMENT '优惠券ID',
  `full` decimal(10,2) DEFAULT NULL COMMENT '满多少',
  `reduce` decimal(10,2) DEFAULT NULL COMMENT '减多少',
  `end_time` int(11) DEFAULT NULL COMMENT '有效时间',
  `status` int(11) DEFAULT 0 COMMENT '使用状态(0未使用1已使用2已完成3已过期',
  `create_time` int(11) DEFAULT NULL COMMENT '领取时间',
  `ucid` int(1) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户优惠券表';

-- ----------------------------
-- Records of news_user_coupon
-- ----------------------------
