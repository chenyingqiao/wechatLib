<?php
namespace WeiChatLib;

/**
* 素材上传
* 素材包括图文消息的素材
*/
class MaterialManage
{
	const image="image";
	const voice="voice";
	const video="video";
	const thumb="thumb";
	const news="news";
	//素材的类型
	const natureTemporary=0;
	const natureForever=1;

	private $Extlist=array("bmp","png","jpeg","jpg","gif","mp3","wma","wav","amr","mp4");

	private $http,$func;
	public function __construct(){
		$this->http=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
	}
	/**
	 * 上传临时素材
	 * @param  [type] $fileAbsolutePath [文件的绝对路径]
	 * @param  [type] $type             [类型 默认是图像]
	 * @param  [type] $nature           [素材的保存类型 永久还是临时的 默认临时]
	 * @param  [type] $vedioData        [上传永久的视频素材的话要使用这个数据
	 *                                 array('title'=>"","introduction"=>"")
	 * （公众号文章视频的话网页可以使用外链）]
	 * @return [type]                   [
	 *         普通素材：{"type":"TYPE","media_id":"MEDIA_ID","created_at":123456789}

		  永久素材:  {"media_id":MEDIA_ID,"url":URL}
	 * ]
	 */
	public function uploadMaterial($fileAbsolutePath,$nature=self::natureTemporary,$type=self::image,$videoData=null){
		$data=$this->fileDatetion($fileAbsolutePath);
		if(empty($data)){
			return false;
		}
		$key=($nature==self::natureTemporary?"temporaryMaterial":"foreverMaterial");
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig($key));
		$buildData=array(
				"TYPE"=>$type
			);
		$proBuild=$urlBuild->otherParamBuild($buildData)->getProUrl();
		//如果是永久的素材就添加类型
		if($nature!=self::natureTemporary){
			$data=array();
			$data['type']=$type;
			$data['media']="@".$fileAbsolutePath;
		}
		if($type==self::video&&$nature==self::natureForever){
			if(empty($videoData)||empty($videoData['title'])||empty($videoData['introduction'])){
				throw new \Exception("视频描述的数据不完整或者出现错误", 1);
			}
			$data['description']=ToolFactory::createFuncTool()->arrToStringData($videoData);
		}
		$return=$this->http->postGetContent($proBuild,$data);
		$return=$this->func->stringDataToaArr($return);
		return $return;
	}
	/**
	 * 上传图文素材中图文内容中的图片数据
	 * 腾讯不允许使用系统之外的图片数据
	 * 上传图文消息内的图片获取URL 请注意，本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。图片仅支持jpg/png格式，大小必须在1MB以下。 
	 * @param [type] $FilePath [图像的物理位置]
	 * @return {
    "url":  "http://mmbiz.qpic.cn/mmbiz/gLO17UPS6FS2xsypf378iaNhWacZ1G1UplZYWEYfwvuU6Ont96b1roYs CNFwaRrSaKTPCUdBK9DgEHicsKwWCBRQ/0"}
	 */
	public function ImageText_Image($FilePath){
		$data=$this->fileDatetion($FilePath);
		if(empty($data)){
			return false;
		}
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("ImageText_Image"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$return=$this->http->postGetContent($proBuild,$data);
		$return=$this->func->stringDataToaArr($return);
		return $return;
	}
	/**
	 * 获取文件到本地
	 * @param  [type] $mediaId [素材id]
	 * @param  [type] $nature  [素材类型]
	 * @return [type]          [description]
	 */
	public function getMerial($mediaId,$nature=self::natureTemporary,$ext){
		$key=($nature==self::natureTemporary?"getMerialTamporary":"getMerialForever");
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig($key));
		if($nature==self::natureTemporary){
			$buildData=array(
					"MEDIA_ID"=>$mediaId
				);
		}else{
			$buildData=array();
			$data=array(
					"media_id"=>$mediaId
				);
		}
		$proBuild=$urlBuild->otherParamBuild($buildData)->getProUrl();
		if($nature==self::natureTemporary){
			//临时的素材直接保存
			(new Storage(dir_media_weichatlib.$mediaId.".".$ext))->write(file_get_contents($proBuild));
			return "save tamporary material";
			// header("Location:".$proBuild);
		}else{
			$returnFile=$this->http->postGetContent($proBuild,$data);
			$return=$this->func->stringDataToaArr($returnFile);
			if($return==false){
				//普通的永久素材直接保存
				(new Storage(dir_media_weichatlib.$mediaId.".".$ext))->write($returnFile);
				return "save forever material";
			}
			if(!empty($return['title'])){
				//视频素材通过url进行保存
				(new Storage(dir_media_weichatlib.$mediaId.".".$ext))->write(file_get_contents($return['down_url']));
				return "save forever material video";
			}
			return $return;//图文直接返回数据k
		}
	}
	/**
	 * [addNewsMerial description]
	 * @param [type] $NewsData [素材的二维数组可以包含多个数据item 使用createNewsMerialDataItem创建]
	 *
	 * https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=ACCESS_TOKEN
	 * 这个可能是上传临时的图文素材
	 * 
	 */
	public function addNewsMerial($NewsData){
		if(count($NewsData)>8){
			throw new Exception("素材数量不能大于8个", 1);
		}
		$News=ToolFactory::createFuncTool()->arrToStringData(array("articles"=>$NewsData));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("addNewsMerial"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$return=$this->http->postGetContent($proBuild,$News);
		$return=$this->func->stringDataToaArr($return);
		return $return;
	}

	public function updateNewsMerial($NewsData){
		if(count($NewsData)>8){
			throw new Exception("素材数量不能大于8个", 1);
		}
		$News=ToolFactory::createFuncTool()->arrToStringData(array("articles"=>$NewsData));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("updateNewsMerial"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$return=$this->http->postGetContent($proBuild,$News);
		$return=$this->func->stringDataToaArr($return);
		return $result;
	}
	/**
	 * 获取素材总数量
	 * @return [type] [description]
	 */
	public function getMerialCount(){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getMerialCount"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$return=$this->http->getGetContent($proBuild);
		$return=$this->func->stringDataToaArr($return);
		return $return;
	}
	/**
	 * {
	    "type":TYPE,
	    "offset":OFFSET,
	    "count":COUNT
	}
	
	 * @param  [type] $type       [素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news） ]
	 * @param  [type] $startIndex [开始的位置]
	 * @param  [type] $pageCount  [返回的数量]
	 * @return [type]             [查看手册]
	 */
	public function getMerialList($type,$startIndex=0,$pageCount=20){
		if($pageCount>20){
			$pageCount=20;
		}
		$Data=ToolFactory::createFuncTool()->arrToStringData(array(
			"type"=>$type,
			"offset"=>$startIndex,
			"count"=>$pageCount,
			));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getMerialList"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$return=$this->http->postGetContent($proBuild,$Data);
		$return=$this->func->stringDataToaArr($return);
		return $return;
	}
	/**
	 * [createNewsMerialDataItem description]
	 * @param  [type] $title              [标题]
	 * @param  [type] $thumb_media_id     [ 图文消息的封面图片素材id（必须是永久mediaID）  ]
	 * @param  [type] $author             [作者]
	 * @param  [type] $digest             [图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空]
	 * @param  [type] $show_cover_pic     [是否显示封面，0为false，即不显示，1为true，即显示]
	 * @param  [type] $content            [图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS ]
	 * @param  [type] $content_source_url [description]
	 * @return [type]                     [图文消息的原文地址，即点击“阅读原文”后的URL  ]
	 */
	public function createNewsMerialDataItem($title,$thumb_media_id,$author,$digest,$show_cover_pic,$content,$content_source_url){
		return array(
				"title"=>$title,
				"thumb_media_id"=>$thumb_media_id,
				"author"=>$author,
				"digest"=>$digest,
				"show_cover_pic"=>$show_cover_pic,
				"content"=>$content,
				"content_source_url"=>$content_source_url
			);
	}

	/**
	 * 删除永久素材
	 * @param  [type] $MerialId [description]
	 * @return [type]           [description]
	 */
	public function delectMerialForever($MerialId){
		$data=array("media_id"=>$MerialId);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("delectMerialForever"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$data=ToolFactory::createFuncTool()->arrToStringData($data);
		$return=$this->http->postGetContent($proBuild,$data);
		$return=$this->func->stringDataToaArr($return);
		return $return;
	}
	/**
	 * 验证文件并且组合post的数据
	 * 如果数据中有不是文件的数据
	 * 也要包含到数据中
	 * @param  [type] $fileArr [不能使用数组，微信上传接口不能批量上传]
	 * @return [type]          [返回组合好的上传数据]
	 */
	private function fileDatetion($fileArr){
		$result=array();
		if(!is_file($fileArr)){
			return array();
		}
		$pathinfo=pathinfo($fileArr);
		$ext=$pathinfo['extension'];
		if(!in_array($ext,$this->Extlist)){
			throw new \Exception("格式错误", 1);
		}
		$filename=$pathinfo['filename'];
		$size=filesize($fileArr)/1024;//mb
		return array($filename=>"@".$fileArr);
	}
}