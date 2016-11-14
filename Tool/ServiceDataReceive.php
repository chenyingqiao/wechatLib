<?php 
namespace WeiChatLib;

/**
* 
*/
class ServiceDataReceive{
	private $func;
	public function __construct(){
		$this->func=ToolFactory::createFuncTool();
	}
	/**
	 * 获取服务器发送过来的数据
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function getGlobeContentToObj($type=Func::xmlData){
		$receiveDebug=Configuration::getInstance()->getConfig("receiveDebug");
		if ($receiveDebug) {
			$PostContent=Configuration::getInstance()->getConfig("receiveDebugXML");
		}else{
			$PostContent= $GLOBALS["HTTP_RAW_POST_DATA"];
		}
		if(empty($PostContent)){
			return false;
		}
		$isDecrypion=$this->decryption($PostContent);
		if($isDecrypion){
			return $PostContent;
		}
		$data=ToolFactory::createFuncTool()->stringDataToaArr($PostContent,$type);
		return $data;
	}
	//解密函数
	public function decryption(&$messge){
		$encrypt=Configuration::getInstance()->getConfig("encrypt");
		if($encrypt!=0){
			if(empty($_GET['encrypt_type'])||$_GET['encrypt_type']=="raw"){
				throw new \Exception("服务器没有发送加密消息，请更换配置", 1);
			}
			$messge=$this->func->decryptionMeg($messge);
			return true;
		}
		return false;
	}
}
