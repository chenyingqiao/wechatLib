<?php 
namespace WeiChatLib;

/**
* 
*/
class IocController
{
	public static function iocFilter(&$classname){
		$iocList=Configuration::getInstance()->getConfig();
		$keys_ioc=array_keys($iocList);
		$key=array_search($classname,$keys_ioc);
		if($key!=false){
			$classname=$iocList[$keys_ioc[$key]];
		}
	}
}
 ?>