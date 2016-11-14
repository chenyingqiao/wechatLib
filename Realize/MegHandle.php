<?php 
namespace WeiChatLib;
/**
* 
*/
abstract class MegHandle
{
	const Service=0;
	const Mass=1;
	const Normal=2;

	private $LinkListHandle;
	//是否是服务端的消息  以及 发送的客服号码
	protected $serviceMeg=false,$serviceAcount=null;
	//设置是否群发  以及设置的群发参数
	protected $massMeg=false,$massConfig=array("is_to_all"=>true),$touserList=null;

	protected $func,$http;
	/**
	 * 
	 * @param string $LinkList [如果转换成了事件链条的话这个要设置成时间链条的配置key]
	 */
	public function __construct($LinkList="handleLinkList"){
		$this->LinkListHandle=Configuration::getInstance()->getConfig($LinkList);
		$this->func=ToolFactory::createFuncTool();
		$this->http=ToolFactory::createHttpTool();
	}
	/**
	 * 调用下一个handle的执行方法
	 *   __CLASS__ 获取当前类名
  	*	__FUNCTION__ 当前函数名（confirm）
  	*	__METHOD__ 当前方法名 （bankcard::confirm）
	 * @param [type] $now      [现在的类]
	 * @param [type] $function [调用的类]
	 */
	public function HandleNext($now,$function,$option){
		$index=array_search($now, $this->LinkListHandle);
		//如果handle链没有了就不继续往下面执行了
		if(empty($this->LinkListHandle[$index+1])){
			return;
		}
		$class=new $this->LinkListHandle[$index+1]();
		if(!is_array($option)){
			return false;
		}
		return call_user_func_array(array($class,$function),$option);
	}

	/**
	 * 文本消息接收的handle(如果是本类处理的才拦截)
	 * @param  [type] $messge [消息对象]
	 * @return [type]         [无]
	 */
	public function nextHandleToReceive($messge){
		if($this->checkProceeing($messge)){
			$addtion=new MegReceiveAddtion();
			$addtion->addtionAction($messge);
			return true;//结束调用
		}
	}
	/**
	 * 发送并且返回消息(如果是本类处理的才拦截)
	 * @param  [type] $messge         [用户发送过来的消息]
	 * @param  [type] $RequestContent [我们要发售那个的xml消息]
	 * @return [type]                 [description]
	 */
	public function nextHandleToSend($messge,$responseContent){
		$megType=$this->checkMegType($responseContent);
		//判断是不是微信服务器发来的数据，或者是要主动发送的数据
		if($megType==self::Normal){
			$SendMessge=(object)ToolFactory::createFuncTool()->stringDataToaArr($responseContent,Func::xmlData);
		}else{
			$SendMessge=(object)ToolFactory::createFuncTool()->stringDataToaArr($responseContent);
		}
		if($this->checkProceeing($SendMessge)){
			$addtion=new MegSendAddtion();
			// 返回数据的时候添加的行为
			$responseContent=$addtion->addtionAction($responseContent);
			switch ($megType) {
				case self::Normal:
					echo $responseContent;exit;
				case self::Service:
					$this->serviceMegSend($responseContent);
				case self::Mass:
					$this->massMegSend($responseContent);
				default:
					echo $responseContent;exit;
			}
		}
		return array();
	}

	/**
	 * 设置是否发送到客服
	 * @param [type] $is [description]
	 */
	public function setToService(){
		$this->serviceMeg=true;
		$this->massMeg=false;
		return $this;
	}
	/**
	 * 设置当前消息的发送的客服
	 * @param [type] $serviceAccount [description]
	 */
	public function setServiceAccount($serviceAccount){
		$this->serviceAcount=$serviceAccount;
		return $this;
	}
	/**
	 * 设置为群发消息
	 */
	public function setToMass(){
		$this->massMeg=true;
		$this->serviceMeg=false;
		return $this;
	}
	/**
	 * 设置group群发的groupid
	 * @param [type] $group_id [description]
	 */
	public function setMassConfigInGroupid($group_id){
		$this->massConfig["is_to_all"]=false;
		$this->massConfig['group_id']=$group_id;
		return $this;
	}
	/**
	 * 通过openid来进行un
	 * @param [type] $openidList [description]
	 */
	public function setMassConfigInToUser($openidList){
		$this->touserList=$openidList;
	}
	/**
	 * 设置客服的账号
	 * @param [type] &$messgeArr     [description]
	 * @param [type] $serviceAccount [description]
	 */
	public function setAccount(&$messgeArr){
		if(!empty($this->serviceAcount)){
				$messgeArr['customservice']=array(
						"kf_account"=>$this->serviceAccount,
					);
		}
	}
	/**
	 * 设置群发参数
	 * @param [type] &$messgeArr [description]
	 */
	public function setMass(&$messgeArr){
		if(!empty($this->touserList)){
			$messgeArr['touser']=$this->touserList;
		}else{
			$messgeArr_unshift['filter']=$this->massConfig;
			foreach ($messgeArr as $key => $value) {
				$messgeArr_unshift[$key]=$value;
			}
			$messgeArr=$messgeArr_unshift;
		}
	}
	/**
	 * 检查是否是一个群发消息
	 * @param  [type] $postContent [description]
	 * @return [type]              [description]
	 */
	public function checkMegType($postContent){
		if(strstr($postContent,"<xml>")!==false){
			return self::Normal;
		}
		$arr_postContent=$this->func->stringDataToaArr($postContent);
		if(!empty($arr_postContent['filter'])||is_array($arr_postContent['touser'])){
			return self::Mass;
		}elseif(!empty($arr_postContent['touser'])&&!is_array($arr_postContent['touser'])){
			return self::Service;
		}
	}
	/**
	 * 发送群发消息
	 * @param  [type] $postContent [发送的json字符串]
	 * @return [type]              [返回微信服务器的数据]
	 */
	private function massMegSend($postContent){
		$arr_content=$this->func->stringDataToaArr($postContent);
		//是通过什么进行群发
		if(!empty($arr_content['filter'])){
			$url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=ACCESS_TOKEN";
		}else{
			$url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=ACCESS_TOKEN";
		}
		$urlBuild=new UrlBuild($url);
		$url=$urlBuild->otherParamBuild()->getProUrl();
		$httpResult=$this->http->postGetContent($url,$postContent);
		$httpResult=$this->func->stringDataToaArr($httpResult);
		var_dump($httpResult);
		return $httpResult;
	}
	/**
	 * 发送客服消息
	 * @param  [type] $postContent [json字符串]
	 * @return [type]              [微信服务器返回的数据]
	 */
	private function serviceMegSend($postContent){
		$url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN";
		$urlBuild=new UrlBuild($url);
		$url=$urlBuild->otherParamBuild()->getProUrl();
		$httpResult=$this->http->postGetContent($url,$postContent);
		$httpResult=$this->func->stringDataToaArr($httpResult);
		var_dump($httpResult);
		return $httpResult;
	}
}
