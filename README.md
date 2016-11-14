#WeiChatLib

这是一个微信基本功能的开发类库
>使用php开发
>不依赖任何的框架

###目前拥有的功能
>对应微信官方文档

*  *获取接口调用凭据*
*  *接收消息(事件以及普通媒体消息)*
*  *发送消息*
*  *消息加解密*
*  *媒体素材管理*
*  *自定义菜单*
*  *账号管理*
*  *数据统计*
*  *微信JS-SDK*
*  *微信智能接口*
*  *微信多客服功能*

### 安装
使用git克隆安装:

```sh
$ git clone https://git.oschina.net/CYQ19931115/WeiChatLib.git WeiChatLib
$ cd WeiChatLib
```

###入门
>在Messge.php中有较多的实例代码可以进行运行测试
>在这之前要记得在config.php中设置appid以及appsecret
>如果公众号是消息加密的状态的话就要设置encrypt为1

###版本
1.0.1

###如果发现bug
>在issues中讨论  thank

###在线文档(制作中)
http://121.42.140.54/index.php?s=/Home/Article/lists/category/runWeichat.html

2016.2.26
1.修复了外部引用找不到文件的问题
2.修复了liunx下autoload文件路径找不到的问题

>部分实例代码

####接收发送消息:

```sh
$MegHandleStart=new MegHandleStart();
//接收消息
$MegHandleStart->Receive();
//获取接收到的消息（object）
$messge=$MegHandleStart->_getMessge();
$Meg=(new TextMegHandle())->createMessge("welcome to see me ! ",$messge);
//创建不同的消息回复
if($messge->MsgType==TextMegHandle::MEG_TYPE){
	$Meg=(new TextMegHandle())->createMessge("welcome to see me ! ",$messge);
}elseif($messge->MsgType==ImageMegHandle::MEG_TYPE){
	// $Meg=(new ImageMegHandle())->createMessge("QOWvDW3H3U4e2mvCZmA2K5GoQn_145D3305QY6lxA0Hx0KAY2F7oiFW8Dhx90yG0",$messge);
	$Meg=(new NewsMegHandle())->createMessge(array(
			array("Title"=>"图文测试","Description"=>"这是一个图文测试","PicUrl"=>"https://mmbiz.qlogo.cn/mmbiz/LNYdVN1e3QhSOc3KfsElTqCnojgZZzrbKSeCugpO1B0MWkoDZoCWibUm3Lh4fiaKQ0vMnW6oian7tgGY7qHNq2CYQ/0?wx_fmt=jpeg","Url"=>"http://www.baidu.com/"),
			array("Title"=>"图文测试","Description"=>"这是一个图文测试","PicUrl"=>"https://mmbiz.qlogo.cn/mmbiz/LNYdVN1e3QhSOc3KfsElTqCnojgZZzrbKSeCugpO1B0MWkoDZoCWibUm3Lh4fiaKQ0vMnW6oian7tgGY7qHNq2CYQ/0?wx_fmt=jpeg","Url"=>"http://www.baidu.com/"),
			array("Title"=>"图文测试","Description"=>"这是一个图文测试","PicUrl"=>"https://mmbiz.qlogo.cn/mmbiz/LNYdVN1e3QhSOc3KfsElTqCnojgZZzrbKSeCugpO1B0MWkoDZoCWibUm3Lh4fiaKQ0vMnW6oian7tgGY7qHNq2CYQ/0?wx_fmt=jpeg","Url"=>"http://www.baidu.com/"),
		),$messge);
}elseif($messge->MsgType==VideoMegHandle::MEG_TYPE){
	$Meg=(new VideoMegHandle())->createMessge("qNcD1orN0u_gBDPxY-0gJiiBNZ9FYObkzSgGWq0SEo8",$messge);
}elseif($messge->MsgType==VoiceMegHandle::MEG_TYPE){
	// $Meg=(new VoiceMegHandle())->createMessge($messge->MediaId,$messge);
	$Meg=(new TextMegHandle())->createMessge($messge->Recognition,$messge);
}
elseif($messge->MsgType==EventHandle::MEG_TYPE){
	$Meg=(new TextMegHandle())->createMessge("你点击了菜单".$messge->EventKey,$messge);
}
//发送出消息
$MegHandleStart->Send($Meg);
```

####用户管理：

```sh
$usermanage=ToolFactory::createUserManage();
//用户信息数据测试
//获取code 参数跳转地址
 $code= $usermanage->getUserCode("http://4b7thcqbe9.proxy.qqbrowser.cc");
 echo $code;
 //获取access_token
 $access_token=$usermanage->getUserAccessToken($code);
 var_dump($access_token);
 //获取用户信息
 $userinfo=$usermanage->getUserInfo($access_token['access_token'],$access_token['openid']);
 var_dump($userinfo);
 //通过openid获取用户信息
$result= $usermanage->getUserInfoByListOpenid(array(
		"oZWc3t69hk6XlRaCOmgv5SfI1TO8",
		"oZWc3t4DwN6qQSaooL8nX5ixC95g",
	));
//删除分组
$usermanage->delectGroup(104);
$usermanage->delectGroup(105);
//获取所有的分株
$getAllGroup=$usermanage->getAllGroup();
//移动用户到分组中
$usermanage->moveUserToGroup("oZWc3tzh5Fcw2KmzcMuJ8CVHoIBY",1);
var_dump($result);
var_dump($getAllGroup);
```

####菜单管理：

```sh
//创建菜单工具
$meun=ToolFactory::createMeunManage();
//创建一级菜单
$meun->_setMeunDataOne($meun->meunButtonCreate("测试","click","这个是一级菜单"));
$meun->_setMeunDataOne($meun->meunButtonCreate("子菜单"));
//创建二级菜单 第二个参数表示的是属于从0开始的母菜单的位置
$meun->_setMeunDataSecond($meun->meunButtonCreate("one","view","http://www.baidu.com"),1);
$meun->_setMeunDataSecond($meun->meunButtonCreate("tow","click","哈哈"),1);
$meun->_setMeunDataSecond($meun->meunButtonCreate("three","click","ying"),1);
//设置个性菜单
$meun->_setMatchruleSex(1);
var_dump($meun->MeunData);
//应用菜单设置
$result=$meun->meunSet();
var_dump($result);
```

>测试账号

<img src="http://image17-c.poco.cn/mypoco/myphoto/20160304/13/17887852420160304131342044.jpg?258x258_120" height = "200" alt="图片名称" align=center />
>回复不同的消息类型就会返回不同的消息

>文件说明

* -----文件名称:.
* -----文件名称:..
* -----文件名称:IMegAddtion.php    消息附加动作接口 在发送或者接收微信消息的时候执行这个附加的消息动作
* -----文件名称:IMegHandle.php     消息节点接口
* -----文件名称:IUrlBuild.php      消息构建接口
* -----文件名称:.
* -----文件名称:..
* -----文件名称:config.php         系统配置文件
* -----文件名称:ioc.php            控制反转配置文件
* -----文件名称:urllist.php        url列表文件
* 文件名称:index.php               入口文件（demo里面微信消息就是发送到这里）
* 文件名称:LICENSE                 项目开源声明
* 文件名称:Messge.php              demo测试文件
* 文件名称:README.md               
* -----文件名称:.
* -----文件名称:..
* -----文件名称:Autoload.php      自动载入文件
* -----文件名称:Configuration.php 配置文件读取类
* -----文件名称:Initialize.php    系统初始化类
* -----文件名称:IocController.php 控制反转类
* ----------文件名称:.
* ----------文件名称:..
* ---------------文件名称:.
* ---------------文件名称:..
* ---------------文件名称:ErrorCode.php  消息加密解密错误代码
* ---------------文件名称:PKCS7Encoder.php   提供基于PKCS7算法的加解密接口
* ---------------文件名称:Prpcrypt.php       提供接收和推送给公众平台消息的加解密接口.
* ---------------文件名称:SHA1.php           计算公众平台的消息签名接口.
* ---------------文件名称:WeiChatMegEncrypt.php    微信消息的加解密
* ---------------文件名称:XMLParse.php       xml文件的读取
* ----------文件名称:defaultMegReceiveAddtion.php  默认的消息接收附加动作类
* ----------文件名称:defaultMegSendAddtion.php     默认的消息发售那个附加动作类
* -----文件名称:MegHandle.php                      消息节点父类
* -----文件名称:MegHandleStart.php                 消息链条启动的类（weichatlib用的是类似责任链的模式来处理消息的）
* ----------文件名称:.
* ----------文件名称:..
* ----------文件名称:EventHandle.php               时间消息的节点
* ---------------文件名称:.
* ---------------文件名称:..
* ---------------文件名称:ClickEventMegHandle.php  点击事件事件推送
* ---------------文件名称:LocationEventMegHandle.php 地址上交时间事件推送
* ---------------文件名称:SubscribeEventMegHandle.php 关注事件推送
* ---------------文件名称:TemplateSendJobFinishEventMegHandle.php  模板消息推送事件推送
* ---------------文件名称:UnsubscribeEventMegHandle.php 取消关注事件推送
* ---------------文件名称:ViewEventMegHandle.php 点击菜单跳转事件推送
* ----------文件名称:ImageMegHandle.php  图片消息
* ----------文件名称:MusicMegHandle.php  音乐消息
* ----------文件名称:NewsMegHandle.php   图文消息
* ----------文件名称:ShortvideoMegHandle.php  短视频消息
* ----------文件名称:TemplateMegHandle.php    模板消息
* ----------文件名称:TextMegHandle.php        文本消息
* ----------文件名称:VideoMegHandle.php       视频消息
* ----------文件名称:VoiceMegHandle.php        声音消息
* -----文件名称:NormalUrlBuild.php        默认的url构建类
* -----文件名称:.
* -----文件名称:..
* -----文件名称:access\_token.txt          文本方式存储access\_token
* -----文件名称:FileStorage.php           文件存储类
* -----文件名称:IStorage.php              公共存储介质接口（可以实现这个接口来实现access_token的数据库存储）
* -----文件名称:jsapi_ticket.txt          jssdk的jstoken
* ----------文件名称:.
* ----------文件名称:..
* ----------文件名称:QOWvDW3H3U4e2mvCZmA2K5GoQn_145D3305QY6lxA0Hx0KAY2F7oiFW8Dhx90yG0.png
* ----------文件名称:vvxDihWq2GTQdiZK2VcT_v2Yx-nrEB5zCvDcS0WUYzY.txt
* -----文件名称:.
* -----文件名称:..
* -----文件名称:DataManage.php   数据管理类
* -----文件名称:Directory.php    文件夹管理类
* -----文件名称:Func.php         函数库类
* -----文件名称:HttpCurl.php     http请求类
* -----文件名称:JsSdk.php        jssdk注入数据获取类
* -----文件名称:MaterialManage.php   媒体文件管理类
* -----文件名称:MeunManage.php       菜单管理类
* -----文件名称:QRcodeManage.php     二维码管理类
* -----文件名称:SafeCheck.php        安全检查类
* -----文件名称:ServiceDataReceive.php   微信服务器接收数据类
* -----文件名称:ServiceDataSend.php      微信服务器发送数据类
* -----文件名称:ServiceManage.php        客服管理类
* -----文件名称:ToolFactory.php            本文件夹中各种类的静态工厂
* -----文件名称:UserManage.php           用户管理类
* 文件名称:WeiChat.php                   demo类
* 文件名称:ying.jpg
* 文件名称:ying.mp3
* 文件名称:ying.mp4
