<?php 
namespace WeiChatLib;

/**
* 消息接收处理链
* 消息发送处理链
*/
interface IMegHandle
{
	/**
	 * 传递消息给下个handle让下一个handle处理   接收   到的数据
	 * @param  [type] $messge [description]
	 * @return [type]         [description]
	 */
	public function nextHandleToReceive($messge);
	/**
	 * 传递消息给下个handle 处理发送的数据
	 * @param  [type] $messge [description]
	 * @return [type]         [description]
	 */
	public function nextHandleToSend($messge,$RequestContent);

	/**
	 * 检查并且处理数据 
	 * @return [type] [返回是否处理如果处理了就返回true]
	 */
	public function checkProceeing($messge);
}