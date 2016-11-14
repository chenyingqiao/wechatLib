<?php 
namespace WeiChatLib;

/**
* 一个配置文件的单例模式，全局
*/
class Configuration
{
	/**
	 * 全局静态变量
	 * @var [type]
	 */
	private static $ConfigInstance;
	/**
	 * 配置的数组
	 * @var [type]
	 */
	protected $Config;
	protected function __construct(){
		$this->Config=require_once(dir_conf_weichatlib."/config.php");
		//吧url的列表页合并进去
		$this->Config=array_merge($this->Config,require_once(dir_conf_weichatlib."/urllist.php"));
		$this->Config=array_merge($this->Config,require_once(dir_conf_weichatlib."/ioc.php"));
	}
	public static function getInstance(){
		$Instance=Configuration::$ConfigInstance;
		if(!isset($Instance)){
			Configuration::$ConfigInstance=new Configuration();
		}
		return Configuration::$ConfigInstance;
	}
	/**
	 * 读取配置文件中的配置
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function getConfig($key=null){
		try{
			if(empty($key)){
				return $this->Config;
			}else{
				return @$this->Config[$key];
			}
		}catch(Exception $e){
			return null;
		}
	}
	/**
	 * 设置配置文件
	 * @param [type] $key   [description]
	 * @param [type] $value [description]
	 */
	public function setConfig($key,$value){
		$item=&$this->Config[$key];
		if(empty($item)){
			$item=$value;
		}else{
			if(isset($value)){
				$item==$value;
			}
		}
	}
}
