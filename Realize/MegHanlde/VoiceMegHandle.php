<?php 
namespace WeiChatLib;

/**
回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
 * @param [type] $messge [description]
*/
class VoiceMegHandle extends MegHandle implements IMegHandle
{
	//规定消息的类型
	const MEG_TYPE="voice";
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
	 * @param  [type] $Content [媒体音乐的id ]
	 * @param  [type] $messge  [用户发送的消息信息]
	 * @return [type]          [返回xml直接回复给用微信服务器]
	 *
	{
	    "touser":"OPENID",
	    "msgtype":"voice",
	    "voice":
	    {
	      "media_id":"MEDIA_ID"
	    }
	}

	 * 
	*<xml>
	*<ToUserName><![CDATA[toUser]]></ToUserName>
	*<FromUserName><![CDATA[fromUser]]></FromUserName>
	*<CreateTime>12345678</CreateTime>
	*<MsgType><![CDATA[voice]]></MsgType>
	*<Voice>
	*<MediaId><![CDATA[media_id]]></MediaId>
	*</Voice>
	*</xml>
	 */
	public function createMessge($MediaId,$messge){
		if($this->serviceMeg){
			$messgeArr=array(
					"touser"=>$messge->FromUserName,
					"msgtype"=>self::MEG_TYPE,
					"voice"=>array(
							"meida_id"=>$MediaId
						)
				);
			$this->setAccount($messgeArr);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr);
		}elseif($this->massMeg){
			$messgeArr=array(
					"msgtype"=>self::MEG_TYPE,
					"voice"=>array(
							"meida_id"=>$MediaId
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
					"Voice"=>array(
							"MediaId"=>$MediaId
						)
				);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr,Func::xmlData);
		}
	}
}