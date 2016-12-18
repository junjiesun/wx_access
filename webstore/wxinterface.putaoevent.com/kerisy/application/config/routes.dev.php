<?php
/**
 * Created by PhpStorm.
 * User: Brian
 * Date: 2015/11/2
 * Time: 16:12
 */

return [
	[
		'prefix' => 'Front',
		'domain' => 'ldev.wxinterface.putaoevent.com',
//		'domain' => 'wxinterface.ptdev.cn',
		'routes' => [
			['GET', '/', 'Core/Index/index'],                                           //授权跳转页面
			['GET', 'getuser', 'Core/Index/getUser'],                                   //获取用户信息
			['GET', 'test', 'Core/Index/test'],   										//测试
			['GET', 'jssdk', 'Core/Index/jssdk'],   									//获取签名
			['GET', 'token', 'Core/Index/token'],   									//获取token
		]
	],
	[
		'prefix' => 'Admin',
		'domain' => 'ldev.admin-wxinterface.putaoevent.com',
//		'domain' => 'admin-wxinterface.ptdev.cn',
		'routes' => [
			['GET', '/', 'core/Index/index'],
			['GET', 'signin', 'core/Index/signinPage'],                					//登录页面
			['GET', 'service/signin', 'core/Index/signin'],            					//登录
			['GET', 'logout', 'core/Index/logout'],                     				//退出
			['POST', 'upload/image', 'Core/UploadFile/uploadImage'], 					//上传图片
            ['GET', 'wechat/list', 'core/WeChat/weChatPublicNumberList'],               //退出
            ['GET', 'service/wechat/add', 'core/WeChat/wechatAdd'],        				//添加
            ['GET', 'service/wechat/edit', 'core/WeChat/wechatEdit'],        		    //修改
        ]
	],
];
