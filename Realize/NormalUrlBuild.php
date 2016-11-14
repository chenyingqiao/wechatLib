<?php 
namespace WeiChatLib;
/**
*
*/
class UrlBuild implements IUrlBuild
{
	protected $url;
	protected $defaultReplace;
	public function __construct($url=null){
		if(!empty($url)){
			$this->url=$url;
		}
	}
	public function baseParamBuild($arr=null){
		if(empty($this->url)){
			return false;
		}
		if(!empty($arr)&&is_array($arr)){
			$this->defaultReplace=$arr;
		}else{
			$this->defaultReplace=array(
			"APPID"=>Configuration::getInstance()->getConfig("appID"),
			"APPSECRET"=>Configuration::getInstance()->getConfig("appsecret"),
			"SECRET"=>Configuration::getInstance()->getConfig("appsecret"),
			//造成无限互相调用(因为getAccessToken也要实例化urlBuild)
			// "ACCESS_TOKEN"=>ToolFactory::createServiceDataSendTool()->getAccessToken(),
		);
		}
		$replaceArr=$this->replaceArrSplit($this->defaultReplace);
		$this->url= str_replace($replaceArr[0],$replaceArr[1],$this->url);
		return $this;
	}
	/**
	 * 其他的数据添加
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	public function otherParamBuild($arr=array()){
		if(empty($this->url)){
			return false;
		}
		$arr['ACCESS_TOKEN']=ToolFactory::createServiceDataSendTool()->getAccessToken();
		$replaceArr=$this->replaceArrSplit($arr);
		$this->url= str_replace($replaceArr[0],$replaceArr[1],$this->url);
		return $this;
	}
	public function getProUrl(){
		if(empty($this->url)){
			return false;
		}
		return $this->url;
	}

	public function replaceArrSplit($replaceArr){
		$keys=array_keys($replaceArr);
		$values=array_values($replaceArr);
		foreach ($values as $key => $v) {
			if(is_array($v)){
				throw new \Exception("otherParamBuild不能包含数组", 1);
			}
		}
		return array($keys,$values);
	}
	/**
	 * 设置要解析的url
	 * @param [type] $url [description]
	 */
	public function _setUrl($url){
		$this->url=$url;
	}

}
