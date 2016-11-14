<?php 
namespace WeiChatLib;
/**
* 
*/
class JsSdk
{
	private $http,$func;
	public function __construct(){
		$this->http=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
	}
	//获取注入需要的参数
	public function getJsInjectData($url=false){
		 // 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		if(!$url){
		    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}
		$jsapiTicket=ToolFactory::createServiceDataSendTool()->getJsapiTicket();
		$timestamp = time();
    		$nonceStr = $this->createNonceStr();
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
		  "appId"     => Configuration::getInstance()->getConfig("appID"),
		  "nonceStr"  => $nonceStr,
		  "timestamp" => $timestamp,
		  "signature" => $signature,
		  "rawString" => $string,
		  "url"=>$url
		);
		return $signPackage;
	}
	/**
	 * AESLL循序排序加密
	 * @param  integer $length [description]
	 * @return [type]          [description]
	 */
	private function createNonceStr($length = 16) {
	  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	  $str = "";
	  for ($i = 0; $i < $length; $i++) {
	    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	  }
	  return $str;
	}
}
