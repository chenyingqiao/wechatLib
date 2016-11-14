<?php 
namespace WeiChatLib;
/**
* 
*/
class QRcodeManage
{
	const QRcodeTemporaty=0;
	const QRcodeForever=1;
	private $func,$httpTool;
	public function __construct(){
		$this->func=ToolFactory::createFuncTool();
		$this->httpTool=ToolFactory::createHttpTool();
	}
	/**
	 * 创建二维码
	 * @param [type]  $scane          [二维码的场景数值]
	 * @param [type]  $type           [是什么类型的二维码]
	 * @param integer $expire_seconds [如果是临时二维码的话设置超时时间]
	 * @param boolean $resultImageURl [是否直接返回二维码图片的连接
	 * 或者是返回 
	 * {"ticket":"ticket","expire_seconds":60,"url":"http:\/\/weixin.qq.com\/q\/kZgfwMTm72WWPkovabbI"}
	 * 的数组形式]
	 */
	public function QRcode($scane,$type=self::QRcodeForever,$expire_seconds=604800,$resultImageURl=true){
		if(!is_numeric($scane)&&$type==self::QRcodeForever){
			$postData['action_info']=array("scene"=>array("scene_str"=>$scane));
			$postData['action_name']="QR_LIMIT_STR_SCENE";
		}elseif(is_string($scane)&&$type==self::QRcodeForever){
			$postData['action_info']=array("scene"=>array("scene_id"=>$scane));
			$postData['action_name']="QR_LIMIT_SCENE";
		}else{
			$postData['action_info']=array("scene"=>array("scene_id"=>$scane));
			$postData['action_name']="QR_SCENE";
			$postData['expire_seconds']=$expire_seconds;
		}
		$postData=$this->func->arrToStringData($postData);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("QRcode"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		if($resultImageURl){
			$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getImageUrl"));
			return $urlBuild->otherParamBuild(array("TICKET"=>$result['ticket']))->getProUrl();
		}
		return $result;
	}
	/**
	 *长连接转换成短连接
	 * @return [type] [转换结果]
	 */
	public function longUrlToShort($url){
		$postData['action']="long2short";
		$postData['long_url']=$url;
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("QRcode"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
}