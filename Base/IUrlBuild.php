<?php 
namespace WeiChatLib;
/*
*
 */
interface IUrlBuild{
	/**
	 * 微信服务器url基本变量替换
	 * @return [type] [description]
	 */
	public function baseParamBuild($arr);
	/**
	 * 微信服务器附加变量替换
	 * @return [type] [description]
	 */
	public function otherParamBuild($arr);
	/**
	 * 获取建造的url变量
	 * @return [type] [description]
	 */
	public function getProUrl();
}
