<?php 
namespace WeiChatLib;
/**
* 
*/
class WeiChat
{
	public function __construct(){
		self::Ini();
		//接入服务器
		$token=new SafeCheck();
		$token->valid();
	}
	public static function Ini(){
		chdir(dirname(__FILE__));//将相对路径变化到本文件
		require_once("./Realize/Initialize.php");
		//初始化自动载入
		new Initialize();
	}
	public function startReceive(){
		$MegHandleStart=new MegHandleStart();
		$MegHandleStart->Receive();
		$messge=$MegHandleStart->_getMessge();
		$Meg=(new TextMegHandle())->createMessge("welcome to see me ! ",$messge);
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
		// var_dump($Meg);
		$MegHandleStart->Send($Meg);
	}
	public function userTest(){
		$usermanage=ToolFactory::createUserManage();
		//用户信息数据测试
		// $code= $usermanage->getUserCode("http://4b7thcqbe9.proxy.qqbrowser.cc");
		// echo $code;
		// $access_token=$usermanage->getUserAccessToken($code);
		// var_dump($access_token);
		// $userinfo=$usermanage->getUserInfo($access_token['access_token'],$access_token['openid']);
		// var_dump($userinfo);
		$result= $usermanage->getUserInfoByListOpenid(array(
				"oZWc3t69hk6XlRaCOmgv5SfI1TO8",
				"oZWc3t4DwN6qQSaooL8nX5ixC95g",
			));
		// $usermanage->delectGroup(104);
		// $usermanage->delectGroup(105);
		$getAllGroup=$usermanage->getAllGroup();
		// $usermanage->moveUserToGroup("oZWc3tzh5Fcw2KmzcMuJ8CVHoIBY",1);
		var_dump($result);
		var_dump($getAllGroup);
	}
	public function QRtest(){
		$qr=ToolFactory::createQRcodeManage();
		$result=$qr->QRcode(100);
		// $result=$qr->QRcode(100,QRcodeManage::QRcodeTemporaty,60);
		echo $result;
		echo "<img src='".$result."'></img>";
	}
	public function meun(){
		$meun=ToolFactory::createMeunManage();
		$meun->_setMeunDataOne($meun->meunButtonCreate("测试","click","这个是一级菜单"));
		$meun->_setMeunDataOne($meun->meunButtonCreate("子菜单"));
		$meun->_setMeunDataSecond($meun->meunButtonCreate("one","view","http://www.baidu.com"),1);
		$meun->_setMeunDataSecond($meun->meunButtonCreate("tow","click","哈哈"),1);
		$meun->_setMeunDataSecond($meun->meunButtonCreate("three","click","ying"),1);
		$meun->_setMatchruleSex(1);
		var_dump($meun->MeunData);
		// $result=$meun->meunSet();
		// var_dump($result);
	}
	public function Materia(){
		$Materia=ToolFactory::createMaterialManage();
		// $result=$Materia->uploadMaterial("C:\\xampp\\htdocs\\myweb\\WeiChatLib\\ying.jpg",MaterialManage::natureForever);
		// $result=$Materia->uploadMaterial("C:\\xampp\\htdocs\\myweb\\WeiChatLib\\ying.mp4",MaterialManage::natureTemporary,MaterialManage::video);
		// $result=$Materia->uploadMaterial("C:\\xampp\\htdocs\\myweb\\WeiChatLib\\ying.mp4",MaterialManage::natureForever,MaterialManage::video,array('title'=>"aaa","introduction"=>"aaa"));
		// $result2=$Materia->ImageText_Image("C:\\xampp\\htdocs\\myweb\\WeiChatLib\\ying.jpg");
		// $save=$Materia->getMerial("QOWvDW3H3U4e2mvCZmA2K5GoQn_145D3305QY6lxA0Hx0KAY2F7oiFW8Dhx90yG0");
		$result=$Materia->getMerialList(MaterialManage::news);
		// $newsList=array(
		// 	$Materia->createNewsMerialDataItem("newstitle", "useaymrEilTDv1XpezfJrreYc95PViTHV2CrZ1Q5Xus", "chenyingqiao", "hah", 0, "<h1>welcome</h1>", "http://www.baidu.com/"),
		// 	$Materia->createNewsMerialDataItem("newstitle2", "useaymrEilTDv1XpezfJrreYc95PViTHV2CrZ1Q5Xus", "chenyingqiao", "hah", 0, "<h1>welcome</h1>", "http://www.baidu.com/"),
		// 	);
		// $result=$Materia->addNewsMerial($newsList);
		
		var_dump($result);
		// var_dump($result2);
		// var_dump($save);
	}
	/**
	 * 下载媒体文件测试
	 * @return [type] [description]
	 */
	public function downTest(){
		$aa="https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/logo_white_fe6da1ec.png";
		$content=file_get_contents($aa);
		$storage=new Storage("fuck.png");
		$storage->write($content);
		echo "c";
	}
	/**
	 * 客服管理测试
	 */
	public function ServiceManageTest(){
		$ServiceManage=ToolFactory::createServiceManage();
		$result=$ServiceManage->crudService(ServiceManage::add,"tes@gh_9e1940c5ce7e","test1","123456");
		$serviceList=$ServiceManage->crudService();
		var_dump($result);
		var_dump($serviceList);
	}
	/**
	 * 模板消息发送测试
	 * @return [type] [description]
	 */
	public function sendTemplateMeg(){
		$TemplateMeg=new TemplateMegHandle();
		// $TemplateMeg->setDefaultIndustry();
		$send=$TemplateMeg->sendTemplateMeg("oZWc3t4DwN6qQSaooL8nX5ixC95g", "3iFlqvGTLqeRohDrhoxi90ktbhlBAbrt6PTY6OclY9s", "http://www.baidu.com");
		var_dump($send);
	}
	/**
	 * 客服消息发送测试
	 */
	public function ServiceMassgeSend(){
		$HandleStart=new MegHandleStart("oZWc3t4DwN6qQSaooL8nX5ixC95g");
		$messge=$HandleStart->_getMessge();
		// $serviceMeg=(new ImageMegHandle())->setToService()->createMessge("vvxDihWq2GTQdiZK2VcT_p2N9nkyak3x4qYdxTx8Vdc",$messge);
		$serviceMeg=(new NewsMegHandle())->setToService()->createMessge(array(
				array("title"=>"图文测试","description"=>"这是一个图文测试","picurl"=>"https://mmbiz.qlogo.cn/mmbiz/LNYdVN1e3QhSOc3KfsElTqCnojgZZzrbKSeCugpO1B0MWkoDZoCWibUm3Lh4fiaKQ0vMnW6oian7tgGY7qHNq2CYQ/0?wx_fmt=jpeg","url"=>"http://www.baidu.com/"),
			),$messge);
		// $serviceMeg=(new VideoMegHandle())->setToService()->createMessge("qNcD1orN0u_gBDPxY-0gJiiBNZ9FYObkzSgGWq0SEo8",$messge);
		
		// $serviceMeg=(new TextMegHandle())->setToService()->CreateMessge("这是一条客服主动发送的消息",$messge);
		var_dump($serviceMeg);
		$HandleStart->Send($serviceMeg);
	}

	//群发测试
	public function MassMessgeSend(){
		$HandleStart=new MegHandleStart("oZWc3t4DwN6qQSaooL8nX5ixC95g");
		$messge=$HandleStart->_getMessge();
		$serviceMeg=(new TextMegHandle())->setToMass()->CreateMessge("这是一条群发的消息",$messge);
// 		$serviceMeg='{
//    "filter":{
//       "is_to_all":true
//    },
//    "text":{
//       "content":"testtststststststst"
//    },
//     "msgtype":"text"
// }';
		var_dump($serviceMeg);
		$HandleStart->Send($serviceMeg);
	}

	//微信公众平台用户数据查询测试
	public function DataTest(){
		$dataAcquisition=ToolFactory::createDataAcquisition();
		$result=$dataAcquisition->setTime(strtotime("-5 day"),strtotime("-2 day"))->getData("fuck");
		var_dump($result);
	}
	/**
	 * 测试js  没有在网页中进行实际测试
	 * @return [type] [description]
	 */
	public function jsTest(){
		$jsinject=ToolFactory::createJsTicketTool();
		$result=$jsinject->getJsInjectData();
		return $result;
		// var_dump($result);
	}
	/**
	 * [ipList description]
	 * @return [type] [description]
	 */
	public function ipList(){
		$safe=new SafeCheck();
		$result=$safe->ipList();
		var_dump($result);
	}
}