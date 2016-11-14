<?php 
namespace WeiChatLib;
use WeiChatLib\IocController;
/**
*
*/
class Autoload
{
	public function __construct(){
		spl_autoload_register("\WeiChatLib\Autoload::defaultAutoload",true);
		// set_exception_handler(array($this,"defaultExceptionHandle"));
	}
	public static function defaultAutoload($classname){
		if(($namespaceCut=strstr($classname,"\\"))!==false){
			$classname=$namespaceCut;
			$classname=substr($classname,1,strlen($classname)-1);
		}
		IocController::iocFilter($classname);
		$classname=$classname.Configuration::getInstance()->getConfig("ext");
		$DirectoryMap=Directory::_getAutoloadFileMap();
		$keys_directorymap=array_keys($DirectoryMap);
		$key= array_search($classname,$keys_directorymap);
		if($key===false){
			throw new \Exception("载入错误,没有映射的类名.[".$classname."] :(", 1);
		}
		require_once($DirectoryMap[$keys_directorymap[$key]]);
	}
	public function defaultExceptionHandle($exception){
		echo "<h1>".$exception->getMessage()."</h1>";exit;
	}
}
