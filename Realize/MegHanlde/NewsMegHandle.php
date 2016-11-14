<?php 
namespace WeiChatLib;

/**
回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
 * @param [type] $messge [description]
*/
class NewsMegHandle extends MegHandle implements IMegHandle
{
	//规定消息的类型
	const MEG_TYPE="news";
	//消息链条配置循序
	public function __construct($LinkList="handleLinkList"){
		parent::__construct($LinkList);
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
	 * @param  [type] $NewsData [必须包含着Title(标题) Description(描述内容) PicUrl(图片地址) Url(跳转链接)]
	 * @param  [type] $messge  [用户发送的消息信息]
	 * @return [type]          [返回xml直接回复给用微信服务器]
	 *
	 *客服回复的数据结构
	{
	    "touser":"OPENID",
	    "msgtype":"news",
	    "news":{
	        "articles": [
	         {
	             "title":"Happy Day",
	             "description":"Is Really A Happy Day",
	             "url":"URL",
	             "picurl":"PIC_URL"
	         },
	         {
	             "title":"Happy Day",
	             "description":"Is Really A Happy Day",
	             "url":"URL",
	             "picurl":"PIC_URL"
	         }
	         ]
	    }
	}

	 * 
	*<xml>
	*<ToUserName><![CDATA[toUser]]></ToUserName>
	*<FromUserName><![CDATA[fromUser]]></FromUserName>
	*<CreateTime>12345678</CreateTime>
	*<MsgType><![CDATA[news]]></MsgType>
	*<ArticleCount>2</ArticleCount>
	*<Articles>
		*<item>
			*<Title><![CDATA[title1]]></Title> 
			*<Description><![CDATA[description1]]></Description>
			*<PicUrl><![CDATA[picurl]]></PicUrl>
			*<Url><![CDATA[url]]></Url>
		*</item>
		*<item>
			*<Title><![CDATA[title]]></Title>
			*<Description><![CDATA[description]]></Description>
			*<PicUrl><![CDATA[picurl]]></PicUrl>
			*<Url><![CDATA[url]]></Url>
		*</item>
	*</Articles>
	*</xml> 
	 */
	public function createMessge($NewsData,$messge){
		if($this->serviceMeg){
			$messgeArr=array(
					"touser"=>$messge->FromUserName,
					"msgtype"=>self::MEG_TYPE,
					"news"=>array(
							"articles"=>$NewsData
						)
				);
			$this->setAccount($messgeArr);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr);
		}elseif($this->massMeg){
			//群发图文消息的时候NewsDat 必须是一个mediaid
			if(is_array($NewsData)){
				throw new Exception("群发图文消息必须使用mediaid", 1);
			}
			$NewsData=$this->func->recursion_array_change_case($NewsData);
			$messgeArr=array(
					"msgtype"=>self::MEG_TYPE,
					"mpnews"=>array(
							"media_id"=>$NewsData
						)
				);
			$this->setMass($messgeArr);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr);
		}else{
			$messgeArr=array(
					"ToUserName"=>$messge->FromUserName,
					"FromUserName"=>$messge->ToUserName,
					"CreateTime"=>time(),
					"MsgType"=>self::MEG_TYPE,
					"ArticleCount"=>count($NewsData),
					"Articles"=>array("item"=>$NewsData)
				);
			return ToolFactory::createFuncTool()->arrToStringData($messgeArr,Func::xmlData);
		}
	}
}