<?php 
namespace WeiChatLib;

/**
* 常用函数集合
*/
class Func{
	const jsonData="json";
	const xmlData="xml";
	public function stringDataToaArr($data,$type=self::jsonData){
		if($type==self::jsonData){
			$resultData=json_decode($data,true);
			if(!is_array($resultData)){
				return false;
			}
		}elseif($type==self::xmlData){
			$resultData=simplexml_load_string($data,"SimpleXMLElement",LIBXML_NOCDATA);
			if(!is_object($resultData)){
				return false;
			}
		}
		// return array_change_key_case($resultData);
		return $resultData;
	}
	/**
	 * 递归遍历数组  赋值给SimpleXmlElement
	 * @param  [type] $ele   [SimpleXmlElement元素]
	 * @param  [type] $key   [键值]
	 * @param  [type] $value [数值]
	 * @return [type]        [description]
	 */
	private function xmlRecursion($ele,$key,$value){
		if(is_array($value)){
			if(array_key_exists(0,$value)){
			        $ele1=$ele;
			}else{
				$ele1=$ele->addChild($key);
			}
			foreach ($value as $key1 => $value1) {
				//如果子兼职是数值的话就吧父亲的键值添加进去
				if (is_numeric($key1)) {
					$key1=$key;
				}
				$this->xmlRecursion($ele1,$key1,$value1);
			}
		}else{
			//<![CDATA[     ]]>
			if(!is_numeric($value)){
				$value="<![CDATA[".$value."]]>";
			}
			$ele->addChild($key,$value);
			return;
		}
	}
	/**
	 * 将arr转化成json字符串或者是xml
	 * @return [type] [description]
	 */
	public function arrToStringData($arr,$type=self::jsonData){
		if($type==self::jsonData){
			return json_encode($arr,JSON_UNESCAPED_UNICODE);
		}elseif($type==self::xmlData){
			$xmlTemplete=<<<XML
			<xmlElement></xmlElement>
XML;
			$SimpleXMLElement=simplexml_load_string($xmlTemplete, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->xmlRecursion($SimpleXMLElement,"xml",$arr);
			return htmlspecialchars_decode($SimpleXMLElement->children()->asXML());
		}
	}
	/**
	 *  测试超时时间
	 * @param  [type]  $time    [记录的之前旧的时间]
	 * @param  [type]  $OutTime [超时的时间  用字符表示 1h:1小时 1m:一分钟 1s:一秒钟]
	 * @return boolean          [是否超时 true 是 false 否]
	 */
	function isAccessTokenTimeout($timeOld,$OutTime){
		if(($time=strstr($OutTime,"h",true))!==false){
			$timeSecend=$time*3600;
		}elseif(($time=strstr($OutTime,"m",true))!==false){
			$timeSecend=$time*60;
		}elseif(($time=strstr($OutTime,"s",true))!==false){
			$timeSecend=$time;
		}else{
			$timeSecend=$OutTime;
		}
		if(time()-$timeOld>=$timeSecend){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 检查是否是请求出错
	 * @param  [type]  $arr [返回的json转化成数组]
	 * @return boolean      [description]
	 */
	public function isWeiChatError($arr){
		if(is_array($arr)){
			return false;
		}
		$keys=array_keys($arr);
		if(in_array("errcode",$keys)&&in_array("errmsg",$keys)){
			if($arr['errcode']==0){
				return false;
			}
			throw new \Exception("WeiChat Error".$arr['errmsg'], 1);
		}
	}
	/**
	 * 递归转换数组的key为小写或者大写
	 * @param  [type] $array [需要转换的数组]
	 * @return [type]        [description]
	 */
	public function recursion_array_change_case(&$array,$type=CASE_LOWER){
		foreach ($array as $key => $value) {
			if(is_array($value)){
				$array[$key]=array_change_key_case($value,$type);
				$this->recursion_array_change_case($array[$key]);
			}
		}
	}
	/**
	 * 解密
	 * @param  [type] $messge [取得的服务器数据数组]
	 * @return [type]         [返回解密后的消息的数组]
	 */
	public function decryptionMeg($postData){
		$token=Configuration::getInstance()->getConfig("token");
		$appid=Configuration::getInstance()->getConfig("appID");
		$encodingAesKey=Configuration::getInstance()->getConfig("EncodingAESKey");
		$WeiChatMegEncrypt=new WeiChatMegEncrypt($token,$encodingAesKey,$appid);
		$result="";//解密后的原文
		$errorCode=$WeiChatMegEncrypt->decryptMsg($_GET["msg_signature"],$_GET['timestamp'],$_GET['nonce'],$postData,$result);
		if($errorCode!=0){
			throw new \Exception("密文解析错误码:".$errorCode, 1);
		}
		return $this->stringDataToaArr($result,self::xmlData);
	}
	/**
	 * 加密消息
	 * @param  [type] $responseContent [回复的数据]
	 * @return [type]                  [返回可以直接回复给威信服务器的字符串]
	 */
	public function encryptMeg($responseContent){
		$token=Configuration::getInstance()->getConfig("token");
		$appid=Configuration::getInstance()->getConfig("appID");
		$encodingAesKey=Configuration::getInstance()->getConfig("EncodingAESKey");
		$WeiChatMegEncrypt=new WeiChatMegEncrypt($token,$encodingAesKey,$appid);
		$result="";
		$errorCode=$WeiChatMegEncrypt->encryptMsg($responseContent,$_GET['timestamp'],$_GET['nonce'],$result);
		if($errorCode!=0){
			throw new \Exception("密文解析错误码:".$errorCode, 1);
		}
		return $result;
	}
}