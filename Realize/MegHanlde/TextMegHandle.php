<?php 
namespace WeiChatLib;

/**
	 接收
	*       <xml>
 	*       <ToUserName><![CDATA[toUser]]></ToUserName>
 	*       <FromUserName><![CDATA[fromUser]]></FromUserName> 
 	*       <CreateTime>1348831860</CreateTime>
 	*       <MsgType><![CDATA[text]]></MsgType>
 	*       <Content><![CDATA[this is a test]]></Content>
 	*       <MsgId>1234567890123456</MsgId>
 	*       </xml>
	 *	===========================================
	*	参数			描述
	*	ToUserName	开发者微信号
	*	FromUserName	发送方帐号（一个OpenID）
	*	CreateTime		消息创建时间 （整型）
	*	MsgType		text
	*	Content		文本消息内容
	*	MsgId		消息id，64位整型
	 *
	 *
	 回复
	 *	<xml>
	 *	<ToUserName><![CDATA[toUser]]></ToUserName>
	 *	<FromUserName><![CDATA[fromUser]]></FromUserName>
	 *	<CreateTime>12345678</CreateTime>
	 *	<MsgType><![CDATA[text]]></MsgType>
	 *	<Content><![CDATA[你好]]></Content>
	 *	</xml>
	 *	===========================================
	 * 	参数	        		是否必须		描述
	 *	ToUserName	是	        			接收方帐号（收到的OpenID）
	 *	FromUserName	是	  		开发者微信号
	 *	CreateTime		是			消息创建时间 （整型）
	 *	MsgType		是			text
	 *	Content		是			回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
	 * @param [type] $messge [description]
	 */
class TextMegHandle extends MegHandle implements IMegHandle
{
	//规定消息的类型
	const MEG_TYPE="text";
	//消息链条配置循序
	public function __construct($LinkList="handleLinkList"){
		parent::__construct($LinkList);
	}
	/**
	 * 文本消息接收的handle(如果是本类处理的才拦截)
	 * @param  [type] $messge [消息对象]
	 * @return [type]         [无]
	 */
	public function nextHandleToReceive($messge){
		$return=parent::nextHandleToReceive($messge);
		if($return==true){
			return;
		}
		parent::HandleNext(__CLASS__,__FUNCTION__,array($messge));
	}
	/**
	 * 发送并且返回消息(如果是本类处理的才拦截)
	 * @param  [type] $messge         [用户发送过来的消息]
	 * @param  [type] $RequestContent [我们要发售那个的xml消息]
	 * @return [type]                 [description]
	 */
	public function nextHandleToSend($messge,$RequestContent){
		$result=parent::nextHandleToSend($messge,$RequestContent);
		if(!empty($result)){
			return $result;
		}
		parent::HandleNext(__CLASS__,__FUNCTION__,array($messge,$RequestContent));
	}
	/**
	 * 检查并且处理数据
	 * @param  [type] $messge [description]
	 * @return [type]         [description]
	 */
	public function checkProceeing($messge){
		if(@$messge->MsgType==self::MEG_TYPE||@$messge->msgtype==self::MEG_TYPE)
			return true;
		else
			return false;
	}
	/**
	 * 创建回复的xml字符串
	 * @param  [type] $Content [回复的文字内容]
	 * @param  [type] $messge  [用户发送的消息信息]
	 * @return [type]          [返回xml]
	 *
	{
	    "touser":"OPENID",
	    "msgtype":"text",
	    "text":
	    {
	         "content":"Hello World"
	    }
	}
	 * 
	 */
	public function createMessge($Content,$messge){
		if($this->serviceMeg){
			$messgeArr=array(
					"touser"=>$messge->FromUserName,
					"msgtype"=>self::MEG_TYPE,
					"text"=>array(
							"content"=>$Content
						)
				);
			$this->setAccount($messgeArr);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr);
		}elseif($this->massMeg){
			$messgeArr=array(
					"msgtype"=>self::MEG_TYPE,
					"text"=>array(
							"content"=>$Content
						)
				);
			$this->setMass($messgeArr);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr);
		}else{
			$messgeArr=array(
					"ToUserName"=>$messge->FromUserName,
					"FromUserName"=>$messge->ToUserName,
					"CreateTime"=>time(),
					"MsgType"=>self::MEG_TYPE,
					"Content"=>$Content
				);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr,Func::xmlData);
		}
	}
}