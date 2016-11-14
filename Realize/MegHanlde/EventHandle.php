<?php 
namespace WeiChatLib;

/**
回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
 * @param [type] $messge [description]
*/
class EventHandle extends MegHandle implements IMegHandle
{
	//规定消息的类型
	const MEG_TYPE="event";
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
		// parent::nextHandleToReceive($messge);
		// parent::HandleNext(__CLASS__,__FUNCTION__,array($messge));
		//直接接入事件消息链
		$class=Configuration::getInstance()->getConfig("eventHandleLinkList")[0];
		$EventHandleStart=new $class();
		$EventHandleStart->nextHandleToReceive($messge);
	}
	/**
	 * 发送并且返回消息(如果是本类处理的才拦截)
	 * @param  [type] $messge         [用户发送过来的消息]
	 * @param  [type] $RequestContent [我们要发售那个的xml消息]
	 * @return [type]                 [description]
	 */
	public function nextHandleToSend($messge,$RequestContent){
		// parent::nextHandleToSend($messge,$RequestContent);
		// parent::HandleNext(__CLASS__,__FUNCTION__,array($messge,$RequestContent));
		//直接接入事件消息链
		$class=Configuration::getInstance()->getConfig("eventHandleLinkList")[0];
		$EventHandleStart=new $class();
		$EventHandleStart->nextHandleToSend($messge,$RequestContent);
	}
	/**
	 * 检查并且处理数据
	 * @param  [type] $messge [description]
	 * @return [type]         [description]
	 */
	public function checkProceeing($messge){
		if($messge->MsgType==self::MEG_TYPE)
			return true;
		else
			return false;
	}
	/**
	 * 创建回复的xml字符串
	 * @param  [type] $Content [媒体图像的id]
	 * @param  [type] $messge  [用户发送的消息信息]
	 * @return [type]          [返回xml]
	 */
	public function createMessge($mediaId,$messge){
		return "";
	}
}