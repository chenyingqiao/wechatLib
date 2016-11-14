<?php 
namespace WeiChatLib;
/**
* 
*/
class MegSendAddtion implements IMegAddtion
{
	private $func;
	public function __construct(){
		$this->func=ToolFactory::createFuncTool();
	}
	/**
	 * 发送的时候对消息进行处理
	 * @param  [type] $RequestContent [回复的消息xml或json]
	 * @return [type]                 [description]
	 */
	public function addtionAction($responseContent){
		$this->encrypt($responseContent);
		return $responseContent;
	}
	public function encrypt(&$responseContent){
		$encrypt=Configuration::getInstance()->getConfig("encrypt");
		if($encrypt!=0){
			$responseContent=$this->func->encryptMeg($responseContent);
		}
	}
}