<?php 
namespace WeiChatLib;

/**
* 
*/
class MegReceiveAddtion implements IMegAddtion
{
	private $func;
	private $isTrunToService=false;
	/**
	 * 可以转发到客服的限制列表
	 * @var array
	 */
	private $canTruntoService=array("text","image");
	public function __construct(){
		$this->func=ToolFactory::createFuncTool();
	}
	//这里可以通过判断消息类型来对消息进行不同的后续处理
	public function addtionAction($messge){
		$this->messgeEventAddtion($messge);
		return $messge;
	}
	//事件消息附加的动作
	public function messgeEventAddtion($messge){
		if ($messge->MsgType=="event") {
			switch ($messge->Event) {
				case 'CLICK':
						$Meg=(new TextMegHandle())->createMessge("click事件".$messge->EventKey,$messge);
					break;
				default:
					# code...
					break;
			}
			$MegHandleStart=new MegHandleStart();
			$MegHandleStart->Send($Meg);
		}
	}


}