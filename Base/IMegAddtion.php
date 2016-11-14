<?php 
namespace WeiChatLib;

/**
* 
*/
interface IMegAddtion
{
	/**
	 * 发送或者接收的时候会对消息进行处理
	 * @return [type] [description]
	 */
	public function addtionAction($messge);
}