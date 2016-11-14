<?php 
namespace WeiChatLib;

/**
* 
*/
class Directory
{
	protected $autoloadFileMap;
	private static $DirectoryInstance;
	protected function __construct(){
		//初始化类库文件和列表
		$this->getFileAndDir(dir_root_weichatlib);
	}
	/**
	 * 单利模式
	 * @return [type] [description]
	 */
	public static function getInstance(){
		if(empty(Directory::$DirectoryInstance)){
			Directory::$DirectoryInstance=new Directory();
		}
		return Directory::$DirectoryInstance;
	}
	/**
	 * 获取文件列表
	 * @return [type] [description]
	 */
	public static function _getAutoloadFileMap(){
		return Directory::getInstance()->autoloadFileMap;
	}
	/**
	 * 递归的获取文件夹中的所有文件和子目录的文件
	 * @param  [type] $dir [description]
	 * @return [type]      [description]
	 */
	private function getFileAndDir($dir){
		if(!file_exists($dir)){
			return false;
		}
		//创建文件夹iterator
		$iterator=new \RecursiveDirectoryIterator($dir,\RecursiveDirectoryIterator::KEY_AS_FILENAME);
		//通过文件夹的iterator来获取遍历这个iterator的递归遍历的集合
		$iterator_iterator=new \RecursiveIteratorIterator($iterator);
		foreach ($iterator_iterator as $key=>$value) {
			if($key==".."||$key=="."){
				continue;
			}
			$fileAbsolutePath=$value->getPath()."/".$value->getFilename();
			$this->autoloadFileMap[$key]=$fileAbsolutePath;
		}
	}
}
