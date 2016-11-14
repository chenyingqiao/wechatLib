<?php 
namespace WeiChatLib;
/**
*开始调用事件交互链条
*/
class MegHandleStart
{
	private $startHandel,$messge;

	/**
	 * 如果是主动发送数据的话就要自动发送的人
	 * @param [type] $ToUser [description]
	 */
	public function __construct($ToUser=null){
		if(!empty($ToUser)){
			@$this->messge->FromUserName=$ToUser;
		}else{
			$this->messge=ToolFactory::createServiceDataReceiveTool()->getGlobeContentToObj();
		}
		$startHandelConfig=Configuration::getInstance()->getConfig("handleLinkList");
		$this->startHandel=new $startHandelConfig[0]();//实例化消息处理链条的开头
		// $this->startHandel=new TextMegHandle();//实例化消息处理链条的开头
	}
	/**
	 * 发送数据并且执行附加动作
	 * @param [type] $RequestContent [返回数据的字符串  （不是数组）]
	 */
	public function Send($RequestContent){
		//主动发送消息自动构建消息类型
		if(empty($this->messge->CreateTime)){
			$arr_RequestContent=ToolFactory::createFuncTool()->stringDataToaArr($RequestContent);
			@$this->messge->MsgType=$arr_RequestContent['msgtype'];
		}
		$this->startHandel->nextHandleToSend($this->messge,$RequestContent);
	}
	/**
	 * 接收数据
	 * 并且执行附加动作
	 */
	public function Receive(){
		$this->startHandel->nextHandleToReceive($this->messge);
	}
	public function _getMessge(){
		return $this->messge;
	}
}
