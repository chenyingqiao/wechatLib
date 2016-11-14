<?php 
namespace WeiChatLib;

/**
* 用于接入微信服务器以及检车是否是微信服务器发来的连接
*/
class SafeCheck
{
	//token接入
	public function valid()
          {
              @$echoStr = $_GET["echostr"];
              //valid signature , option
              if($this->checkSignature()&&isset($echoStr)){
              	echo $echoStr;
              	exit;
              }
          }
	//检测服务器连接
	public function checkSignature()
	{
	        @$signature = $_GET["signature"];
	        @$timestamp = $_GET["timestamp"];
	        @$nonce = $_GET["nonce"];
	        		
		$token = Configuration::getInstance()->getConfig("token");
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		// var_dump($_GET);
		// var_dump($_SERVER);
		if( $tmpStr == $signature ){
			if(empty($nonce)){
				return false;
			}
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 获取微信服务器ip地址列表
	 * @return [type] [数组形式的列表]
	 */
	public function ipList(){
		$http=ToolFactory::createHttpTool();
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig('ipList'));
		$url=$urlBuild->otherParamBuild()->getProUrl();
		$result=$http->getGetContent($url);
		$result=ToolFactory::createFuncTool()->stringDataToaArr($result);
		return $result;
	}
}