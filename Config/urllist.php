<?php 
//由于access_token 在微信公众平台中有2个相同的所以把用户的access_token改成了access_token_user
return array(
		"getAccessToken"=>"https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET",

		//获取用户数据的
		"getUserCode"=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect",
		"getUserAccessToken"=>"https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code",
		"refreshAccessToken"=>"https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN",
		"getUserInfo"=>"https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN_USER&openid=OPENID&lang=zh_CN",
		"checkAccessToken"=>"https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN_USER&openid=OPENID",
		"getUserInfoByOpenid"=>"https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN",
		"getUserInfoByListOpenid"=>"https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=ACCESS_TOKEN",

		//用户分组
		"createUserGroup"=>"https://api.weixin.qq.com/cgi-bin/groups/create?access_token=ACCESS_TOKEN",
		"getAllGroup"=>"https://api.weixin.qq.com/cgi-bin/groups/get?access_token=ACCESS_TOKEN",
		"moveUserToGroup"=>"https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token=ACCESS_TOKEN",
		"delectGroup"=>"https://api.weixin.qq.com/cgi-bin/groups/delete?access_token=ACCESS_TOKEN",
		"setUserNickname"=>"https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=ACCESS_TOKEN",
		"getUserListPage"=>"https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid=NEXT_OPENID",
		
		//二维码
		"QRcode"=>"https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=ACCESS_TOKEN",
		"getImageUrl"=>"https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=TICKET",
		"longUrlToShort"=>"https://api.weixin.qq.com/cgi-bin/shorturl?access_token=ACCESS_TOKEN",

		//菜单
		"meunGet"=>"https://api.weixin.qq.com/cgi-bin/menu/get?access_token=ACCESS_TOKEN",
		"meunDelete"=>"https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=ACCESS_TOKEN",
		"meunSet"=>"https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN ",
		"getMatchruleMeun"=>"https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=ACCESS_TOKEN",

		//素材上传
		"temporaryMaterial"=>"https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE",
		"foreverMaterial"=>"https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=ACCESS_TOKEN",
		"ImageText_Image"=>"https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=ACCESS_TOKEN",
		"getMerialTamporary"=>"https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID",
		"getMerialForever"=>"https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=ACCESS_TOKEN",
		"delectMerialForever"=>"https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=ACCESS_TOKEN",
		"addNewsMerial"=>"https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=ACCESS_TOKEN",
		"updateNewsMerial"=>"https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=ACCESS_TOKEN",
		"getMerialCount"=>"https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=ACCESS_TOKEN",
		"getMerialList"=>"https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=ACCESS_TOKEN",

		//客服管理
		"addService"=>"https://api.weixin.qq.com/customservice/kfaccount/add?access_token=ACCESS_TOKEN",
		"updateService"=>"https://api.weixin.qq.com/customservice/kfaccount/update?access_token=ACCESS_TOKEN",
		"delectService"=>"https://api.weixin.qq.com/customservice/kfaccount/del?access_token=ACCESS_TOKEN",
		"setServiceHeaderImage"=>"http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=ACCESS_TOKEN&kf_account=KFACCOUNT",
		"getService"=>"https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN",
		"sendServiceMeg"=>"https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN",
		"getServiceChattingRecord"=>"https://api.weixin.qq.com/customservice/msgrecord/getrecord?access_token=ACCESS_TOKEN",

		//模板消息
		"setDefaultIndustry"=>"https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=ACCESS_TOKEN",
		"getTemplateId"=>"https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=ACCESS_TOKEN",
		"sendTemplateMeg"=>"https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN",

		//用户数据
		"getWeichatData"=>"https://api.weixin.qq.com/datacube/FUNCTION?access_token=ACCESS_TOKEN",

		//jsapi_ticker
		"getJsapiTicket"=>"https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=jsapi",

		"ipList"=>"https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=ACCESS_TOKEN"
	);
