set sql_mode='';

use putao_wxinterface;
set @@character_set_server='utf8mb4';

CREATE TABLE `wx_account` (
  `wx_account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'wx_account ID',
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '名称',
  `appId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'appid',
  `appSecret` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'appSecret',
  `hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '该app的哈希值',
  `type` int(1) DEFAULT NULL COMMENT '1订阅号 2服务号',
  `authorization` int(1) DEFAULT NULL COMMENT '是否静默授权 2是授权登录 1是静默授权',
  `is_deleted` tinyint(1) NOT NULL COMMENT '是否删除',
  `is_close` tinyint(1) NOT NULL COMMENT '是否关闭',
  `create_time` int(11) NOT NULL,
  `modified_time` int(11) NOT NULL,
  PRIMARY KEY (`wx_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='wx_account 列表';

CREATE TABLE `access_token` (
  `wx_account_id` int(11) NOT NULL COMMENT 'wx_account ID',
  `expire_time` int(11) DEFAULT NULL COMMENT '过期时间',
  `access_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'token',
  KEY `idx_access_token_wx_account_id` (`wx_account_id`),
  CONSTRAINT `fk_access_token_wx_account_id` FOREIGN KEY (`wx_account_id`) REFERENCES `wx_account` (`wx_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='access token';

CREATE TABLE `js_api_ticket` (
  `wx_account_id` int(11) NOT NULL COMMENT 'wx_account ID',
  `expire_time` int(11) DEFAULT NULL COMMENT '过期时间',
  `jsapi_ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'jsapi ticket',
  KEY `idx_js_api_ticket_wx_account_id` (`wx_account_id`),
  CONSTRAINT `fk_js_api_ticket_wx_account_id` FOREIGN KEY (`wx_account_id`) REFERENCES `wx_account` (`wx_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='js api ticket';

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'user ID',
  `wx_account_id` int(11) NOT NULL COMMENT 'wx_account ID',
  `openid` varchar(255) COLLATE utf8_unicode_ci COMMENT 'open ID',
  `nickname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '昵称',
  `sex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '性别',
  `province` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '省',
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '市',
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '国家',
  `headimgurl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `unionid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `create_time` int(11) NOT NULL,
  `modified_time` int(11) NOT NULL,
  PRIMARY KEY (`users_id`),
  KEY `idx_users_wx_account_id` (`wx_account_id`),
  CONSTRAINT `fk_users_wx_account_id` FOREIGN KEY (`wx_account_id`) REFERENCES `wx_account` (`wx_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';


CREATE TABLE `callback_url` (
  `wx_account_id` int(11) NOT NULL COMMENT 'wx_account ID',
  `callback_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'callback_url',
  KEY `idx_callback_url_wx_account_id` (`wx_account_id`),
  CONSTRAINT `fk_callback_url_wx_account_id` FOREIGN KEY (`wx_account_id`) REFERENCES `wx_account` (`wx_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='callback_url ';
