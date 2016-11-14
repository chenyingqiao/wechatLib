<?php
namespace WeiChatLib;
use \Exception;
/**
* 获取微信服务器一些基本的数据的类
* 微信服务器返回公共错误消息：
* 	{"errcode":40013,"errmsg":"invalid appid"}
*/
class ServiceDataSend
{
	private $UrlBuild;
	public function __construct(){
		$this->UrlBuild=new UrlBuild();
	}
	/**
	 * 获取access_token
	 *   =====保存到内存中的access_token 是一个数组包含 access_token, expires,time等数据
	 * 微信服务器返回：
	 * 	{"access_token":"ACCESS_TOKEN","expires_in":7200}
	 * @return [type] [access_token]
	 */
	public function getAccessToken(){
		//直接从内存配置中过去access_token
		$access_token=Configuration::getInstance()->getConfig("access_token");
		//并且检测没有过时
		if(!empty($access_token)&&!ToolFactory::createFuncTool()->isAccessTokenTimeout($access_token['time'],"2h")){
			return $access_token['access_token'];
		}
		//吧access_token保存到内存中
		$setAccessToken=function($value){
			Configuration::getInstance()->setConfig("access_token",$value);
		};
		//依赖注入
		$IStorage=new Storage();
		$StorageResult=$IStorage->read();
		//存储介质里面没有保存access_token的信息，或者access_token已经过期
		// if($StorageResult===false||$this->isAccessTokenTimeout($StorageResult['time'],"2h")){
		if($StorageResult===false||ToolFactory::createFuncTool()->isAccessTokenTimeout($StorageResult['time'],"2h")){
			//发送access_token的请求
			$httpcurl=ToolFactory::createHttpTool();
			$postUrl=Configuration::getInstance()->getConfig("getAccessToken");
			$this->UrlBuild->_setUrl($postUrl);
			$postUrl=$this->UrlBuild->baseParamBuild()->getProUrl();//取得完全体的url
			$postContent=$httpcurl->post($postUrl);//获取请求返回的数据
			$jsonContent=json_decode($postContent[0],true);
			ToolFactory::createFuncTool()->isWeiChatError($jsonContent);
			$jsonContent['time']=time();
			$access_token=$jsonContent["access_token"];
			$setAccessToken($jsonContent);
			$IStorage->write($jsonContent);
		}else{
			$access_token=$StorageResult["access_token"];
			$setAccessToken($StorageResult);
		}
		return $access_token;
	}
	/**
	 * jsapi_ticket 的获取
	 * @return [type] [description]
	 */
	public function getJsapiTicket(){
		//直接从内存配置中过去access_token
		$ticket=Configuration::getInstance()->getConfig("ticket");
		//并且检测没有过时
		if(!empty($ticket)&&!ToolFactory::createFuncTool()->isAccessTokenTimeout($ticket['time'],"2h")){
			return $ticket['ticket'];
		}
		//吧ticket保存到内存中
		$setJsApiTicker=function($value){
			Configuration::getInstance()->setConfig("ticket",$value);
		};
		//依赖注入
		$IStorage=new Storage(Configuration::getInstance()->getConfig("jsapi_ticket_savefile"));
		$StorageResult=$IStorage->read();
		//存储介质里面没有保存ticket的信息，或者ticket已经过期
		// if($StorageResult===false||$this->isAccessTokenTimeout($StorageResult['time'],"2h")){
		if($StorageResult===false||ToolFactory::createFuncTool()->isAccessTokenTimeout($StorageResult['time'],"2h")){
			//发送ticket的请求
			$httpcurl=ToolFactory::createHttpTool();
			$postUrl=Configuration::getInstance()->getConfig("getJsapiTicket");
			$this->UrlBuild->_setUrl($postUrl);
			$postUrl=$this->UrlBuild->otherParamBuild()->getProUrl();//取得完全体的url
			$postContent=$httpcurl->post($postUrl);//获取请求返回的数据
			$jsonContent=json_decode($postContent[0],true);
			ToolFactory::createFuncTool()->isWeiChatError($jsonContent);
			$jsonContent['time']=time();
			$ticket=$jsonContent["ticket"];
			$setJsApiTicker($jsonContent);
			$IStorage->write($jsonContent);
		}else{
			$ticket=$StorageResult["ticket"];
			$setJsApiTicker($StorageResult);
		}
		return $ticket;
	}
}
