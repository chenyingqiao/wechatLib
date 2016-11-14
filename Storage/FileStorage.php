<?php 
namespace WeiChatLib;
/**
* 文件存储的保存数据的方式
*/
class Storage implements IStorage
{
	private $file_cursor,$filename;
	public function __construct($filename=null){
		if(empty($filename)){
			$this->filename=dir_storage_weichatlib.Configuration::getInstance()->getConfig('access_token_savefile');
		}else{
			$this->filename=$filename;
		}
	}
	/**
	 * 写入数据(默认每次都覆盖)
	 * @param  [type] $content [写入的数据  数组]
	 * @return [type]          [写入是否成功]
	 */
	public function write($content){
		$this->file_cursor=fopen($this->filename,"w+");
		if(is_array($content)){
			$content=serialize($content);
		}
		$result=fwrite($this->file_cursor,$content);
		if($result!==false){
			return false;
		}else{
			return true;
		}
	}
	/**
	 * 读取数据
	 * @return [type] [description]
	 */
	public function read(){
		file_exists($this->filename)?$model="r":$model="w+";
		$this->file_cursor=fopen($this->filename,$model);
		$fileLength=filesize($this->filename);
		if(empty($fileLength)){
			return false;
		}
		$result=fread($this->file_cursor,$fileLength);
		if(($resultUnserze=unserialize($result))!==false){
			return $resultUnserze;
		}
		return false;
	}
	public function __destruct(){
		fclose($this->file_cursor);
	}
}
