<?php 
namespace WeiChatLib;
use WeiChatLib\Autoload;

/**
* 用于初始化一些defind变量和一些应用的
*/
class Initialize
{
	public function __construct(){
		$this->initConst();
		$this->baseRequire();
		$this->initAutoload();
		$this->initConfig();
	}
	/**
	 * 初始化一些变量
	 * @return [type] [description]
	 */
	public function initConst(){
		header("Content-type: text/html; charset=utf-8");
		define("dir_root_weichatlib",dirname(dirname(__file__)));//文档根目录
		define("dir_conf_weichatlib",dir_root_weichatlib."/Config/");//配置文件目录
		define("dir_realize_weichatlib", dir_root_weichatlib."/Realize/");//实现类目录
		define("dir_tool_weichatlib", dir_root_weichatlib."/Tool/");//实现类目录
		define("dir_storage_weichatlib", dir_root_weichatlib."/Storage/");//实现类目录
		define("dir_media_weichatlib", dir_storage_weichatlib."Media/");//实现类目录
	}
	/**
	 * 初始化配置文件
	 * @return [type] [description]
	 */
	public function initConfig(){
		Configuration::getInstance();
	}
	public function initAutoload(){
		new Autoload();
	}
	public function baseRequire(){
		require_once dir_realize_weichatlib."Autoload.php";
		require_once dir_realize_weichatlib."IocController.php";
		require_once dir_realize_weichatlib."Configuration.php";
		require_once dir_tool_weichatlib."Directory.php";
	}
}
