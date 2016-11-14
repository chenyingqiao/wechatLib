<?php 
namespace WeiChatLib;

/**
回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
 * @param [type] $messge [description]
*/
class TemplateMegHandle extends MegHandle implements IMegHandle
{
	//规定消息的类型
	const MEG_TYPE="template";

	private $http,$func;
	//消息链条配置循序
	public function __construct($LinkList="handleLinkList"){
		parent::__construct($LinkList);
		$this->http=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
	}
	/**
	 * 文本消息接收的handle(如果是本类处理的才拦截)
	 * @param  [type] $messge [消息对象]
	 * @return [type]         [无]
	 */
	public function nextHandleToReceive($messge){
		$return=parent::nextHandleToReceive($messge);
		if($return==true){
			return;
		}
		parent::HandleNext(__CLASS__,__FUNCTION__,array($messge));
	}
	/**
	 * 发送并且返回消息(如果是本类处理的才拦截)
	 * @param  [type] $messge         [用户发送过来的消息]
	 * @param  [type] $RequestContent [我们要发售那个的xml消息]
	 * @return [type]                 [description]
	 */
	public function nextHandleToSend($messge,$RequestContent){
		$result=parent::nextHandleToSend($messge,$RequestContent);
		if(!empty($result)){
			return $result;
		}
		parent::HandleNext(__CLASS__,__FUNCTION__,array($messge,$RequestContent));
	}
	/**
	 * [sendTemplateMeg description]
	 * @param  [type] $touser      [要发送的人的openid]
	 * @param  [type] $template_id [模板id]
	 * @param  [type] $url         [跳转的路径]
	 * @param  [type] $data        [数据]
	 * @return [type]              [description]
	 */
	public function sendTemplateMeg($touser,$template_id,$url,$data=null){
		$data=$this->func->arrToStringData(array(
				"touser"=>$touser,
				"template_id"=>$template_id,
				"url"=>$url,
				"data"=>$data,
			));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("sendTemplateMeg"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->http->postGetContent($proBuild,$data);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * [setDefaultIndustry description]
	 */
	public function setDefaultIndustry($industry1,$industry2){
		$data=$this->func->arrToStringData(array(
				"industry_id1"=>$industry1,
				"industry_id2"=>$industry2
			));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("setDefaultIndustry"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->http->postGetContent($proBuild,$data);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 获取模板id
	 * @param  [type] $TemplateCode [模板的编号]
	 * @return [type]               [ {
           "errcode":0,
           "errmsg":"ok",
           "template_id":"Doclyl5uP7Aciu-qZ7mJNPtWkbkYnWBWVja26EGbNyk"
       }
]
	 */
	public function getTemplateId($TemplateCode){
		if(!strstr("TM")){
			throw new Exception("模板编码错误", 1);
		}
		$data=$this->func->arrToStringData(array(
				"template_id_short"=>$TemplateCode
			));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getTemplateId"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->http->postGetContent($proBuild,$data);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}

	/**
	 * 检查并且处理数据
	 * @param  [type] $messge [description]
	 * @return [type]         [description]
	 */
	public function checkProceeing($messge){
		if(@$messge->MsgType==self::MEG_TYPE||@$messge->msgtype==self::MEG_TYPE)
			return true;
		else
			return false;
	}
	/**
	 * 创建回复的xml字符串
	 * @param  [type] $Content [媒体图像的id]
	 * @param  [type] $messge  [用户发送的消息信息]
	 * @return [type]          [返回xml]
	 */
	public function createMessge($mediaId,$messge){
		$messgeArr=array(
				"ToUserName"=>$messge->FromUserName,
				"FromUserName"=>$messge->ToUserName,
				"CreateTime"=>time(),
				"MsgType"=>self::MEG_TYPE,
				"Image"=>array("MediaId"=>array())
			);
		if(!is_array($mediaId)){
			$messgeArr['Image']["MediaId"]=array($mediaId);
		}else{
			foreach ($$mediaId as $key => $value) {
				$messgeArr['Image']["MediaId"][]=$value;
			}
		}
		return ToolFactory::createFuncTool()->arrToStringData($messgeArr,$this->serviceMeg?Func::jsonData:Func::xmlData);
	}
}