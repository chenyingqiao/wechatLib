<?php 
namespace WeiChatLib;

/**
* 工具创建工厂
*/
class ToolFactory
{
	/**
	 * 创建http工具
	 * @return [type] [description]
	 */
	public static function createHttpTool(){
		return  new HttpCurl();
	}
	/**
	 * 创建发送服务器数据的工具
	 针对消息
	 * @return [type] [description]
	 */
	public static function createServiceDataSendTool(){
		return new ServiceDataSend();
	}
	/**
	 * 创建接收服务器数据的工具
	 针对消息
	 * @return [type] [description]
	 */
	public static function createServiceDataReceiveTool(){
		return new ServiceDataReceive();
	}
	/**
	 * 返回常用函数工具类
	 * @return [type] [description]
	 */
	public static function createFuncTool(){
		return new Func();
	}
	/**
	 * 创建用户管理类
	 * @return [type] [description]
	 */
	public static function createUserManage(){
		return new UserManage();
	}
	/**
	 * 创建二维码工具
	 * @return [type] [description]
	 */
	public static function createQRcodeManage(){
		return new QRcodeManage();
	}
	/**
	 * 创建菜单管理器
	 * @return [type] [description]
	 */
	public static function createMeunManage(){
		return new MeunManage();
	}
	/**
	 * [createMateriaManage description]
	 * @return [type] [description]
	 */
	public static function createMaterialManage(){
		return new MaterialManage();
	}
	/**
	 * 创建客服管理
	 * @return [type] [description]
	 */
	public static function createServiceManage(){
		return new ServiceManage();
	}
	/**
	 * 创建微信公众平台数据获取的类
	 * @return [type] [description]
	 */
	public static function createDataAcquisition(){
		return new DataManage();
	}
	/**
	 * 创建js注入工具
	 * @return [type] [description]
	 */
	public static function createJsTicketTool(){
		return new JsSdk();
	}
}
