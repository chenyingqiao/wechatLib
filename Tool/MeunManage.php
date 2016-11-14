<?php 
namespace WeiChatLib;

/**
* 威信菜单管理类
* 1、click：点击推事件
*用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event	的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
*2、view：跳转URL
*用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。
*3、scancode_push：扫码推事件
*用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息。
*4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框
*用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息。
*5、pic_sysphoto：弹出系统拍照发图
*用户点击按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息。
*6、pic_photo_or_album：弹出拍照或者相册发图
*用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。
*7、pic_weixin：弹出微信相册发图器
*用户点击按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息。
*8、location_select：弹出地理位置选择器
*用户点击按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息。
*9、media_id：下发消息（除文本消息）
*用户点击media_id类型按钮后，微信服务器会将开发者填写的永久素材id对应的素材下发给用户，永久素材类型可以是图片、音频、视频、图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。
*10、view_limited：跳转图文消息URL
*用户点击view_limited类型按钮后，微信客户端将打开开发者在按钮中填写的永久素材id对应的图文消息URL，永久素材类型只支持图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。
*
*/
class MeunManage
{
	private $key,$view,$madie;
	public $MeunData;
	private $httpTool,$func;
	public function __construct(){
		$this->httpTool=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
		$this->key=array("click","scancode_push","scancode_waitmsg","pic_sysphoto","pic_photo_or_album","pic_weixin","location_select");
		$this->view=array("view");
		$this->madie=array("media_id","view_limited");
		//初始化菜单的位置
		$this->MeunData['button']=array();
	}
	/**
	 * 查询菜单返回菜单数据
	 * @return [type] [description]
	 */
	public function meunGet(){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("meunGet"));
		$proUrl=$urlBuild->baseParamBuild()->otherParamBuild()->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 删除微信菜单
	 * @return [type] [description]
	 */
	public function meunDelete(){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("meunDelete"));
		$proUrl=$urlBuild->baseParamBuild()->otherParamBuild()->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 设置菜单
	 * @return [type] [description]
	 */
	public function meunSet(){
		if (empty($this->MeunData)) {
			return false;
		}
		$meunData=ToolFactory::createFuncTool()->arrToStringData($this->MeunData);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("meunSet"));
		$ProUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($ProUrl,$meunData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 添加一级菜单到数据对象中
	 * @param [type] $buttonData [菜单的数据]
	 */
	public function _setMeunDataOne($buttonData){
		$this->MeunData['button'][]=$buttonData;
		return count($this->MeunData['button']);
	}
	/**
	 * 添加二级菜单到数据对象中
	 * @param [type] $buttonData  [菜单的数据]
	 * @param [type] $matherIndex [菜单位置0 开始]
	 */
	public function _setMeunDataSecond($buttonData,$matherIndex){
		if(!isset($this->MeunData["button"][$matherIndex])
			||!isset($this->MeunData["button"][$matherIndex]['sub_button'])){
			return false;
		}
		$this->MeunData["button"][$matherIndex]['sub_button'][]=$buttonData;
		return $matherIndex;
	}
	/**
	 * 创建数据对象
	 * @param  [type] $name [菜单名称]
	 * @param  [type] $type [菜单的类型  为空的话就是母菜单]
	 * @param  [type] $data [菜单附加的数据  为空的话就是母菜单]
	 * @return [type]       [创建爱你成功的菜单数据]
	 */
	public function meunButtonCreate($name,$type=null,$data=null){
		if(empty($type)&&empty($data)){
			$createButton=array(
					"name"=>$name,
					"sub_button"=>array()
				);
			return $createButton;
		}
		$createButton=array(
				"name"=>$name,
				"type"=>$type
			);
		if(in_array($type,$this->view)){
			$createButton["url"]=$data;
		}elseif(in_array($type,$this->key)){
			$createButton['key']=$data;
		}else{
			$createButton['madie']=$data;
		}
		return $createButton;
	}
	/**
	 * 取得个性化菜单
	 * @return [type] [description]
	 */
	public function getMatchruleMeun(){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getMatchruleMeun"));
		$ProUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->getGetContent($ProUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 个性化菜单匹配规则
	 * group_id  否  用户分组id，可通过用户分组管理接口获取  
           * sex  否  性别：男（1）女（2），不填则不做匹配  
           * client_platform_type  否  客户端版本，当前只具体到系统型号：IOS(1), Android(2),Others(3)，不填则不做匹配  
           * country  否  国家信息，是用户在微信中设置的地区，具体请参考地区信息表  
           * province  否  省份信息，是用户在微信中设置的地区，具体请参考地区信息表  
           * city  否  城市信息，是用户在微信中设置的地区，具体请参考地区信息表  
           * 
	 * @param  [type] $func_name [description]
	 * @param  [type] $args      [description]
	 * @return [type]            [description]
	 */
	public function __call($func_name,$args){
		$setMatchrule=function($key,$value){
			$this->MeunData['matchrule'][$key]=$value;
		};
		if(strstr($func_name,"_setMatchrule")!==false&&count($args)>=1){
			$func=substr($func_name,13);
			$func=strtolower($func);
			$setMatchrule($func,$args[0]);
		}
	}
	/**
	 * 检测是否是完整的菜单按钮的数据单元
	 * @param  [type] $buttonData [description]
	 * @return [type]             [description]
	 */
	private function checkButtonData($buttonData){
		if(empty($buttonData['name'])||empty($buttonData['name'])){
			if(empty($buttonData['sub_button'])){
				return false;
			}
		}
		return ture;
	}
}