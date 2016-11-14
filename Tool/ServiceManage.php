<?php 
namespace WeiChatLib;

/**
* 客服账号管理类
*/
class ServiceManage
{
	const add=0;
	const delete=1;
	const update=2;
	const get=3;

	private $http,$func;
	public function __construct(){
		$this->http=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
	}
	/**
	 * 添加客服账号
	 * @param [type] $kf_account [客服账号的的登录名 完整客服账号，格式为：账号前缀@公众号微信号]
	 * @param [type] $nickname   [名称]
	 * @param [type] $password   [密码]
	 */
	public function crudService($type=self::get,$kf_account=null,$nickname=null,$password=null){
		$password=md5($password);
		$Data=array(
				"kf_account"=>$kf_account,
				"nickname"=>$nickname,
				"password"=>$password
			);
		$Data=$this->func->arrToStringData($Data);
		if($type==self::add){
			$urlKey="addService";
		}elseif($type==self::delete){
			$urlKey="delectService";
		}elseif($type==self::update){
			$urlKey="updateSerivice";
		}else{
			$urlKey="getService";
		}
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig($urlKey));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		// var_dump($proBuild);
		// var_dump($Data);
		if($type==self::get){
			$result=$this->http->getGetContent($proBuild);
		}else{
			$result=$this->http->postGetContent($proBuild,$Data);
		}
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 设置客服头像
	 * @param [type] $fileAbsolutePath [description]
	 * @param [type] $kf_account       [description]
	 */
	public function setServiceHeaderImage($fileAbsolutePath,$kf_account){
		if(!is_file($fileAbsolutePath)){
			throw new Exception("文件地址出错", 1);
		}
		$data=array(
				"headerImg"=>"@".$fileAbsolutePath
			);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig($urlKey));
		$proBuild=$urlBuild->otherParamBuild(
				array("KFACCOUNT"=>$kf_account)
			)->getProUrl();
		$result=$this->http->postGetContent($proBuild,$data);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 *发送客服消息给客户
	 * @param  [type] $jsonContent [json文本]
	 * @return [type]              [description]
	 */
	public function sendServiceMeg($jsonContent){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("sendServiceMeg"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->http->postGetContent($proBuild,$jsonContent);
		return $result;
	}
	/**
	 * 获取客户聊天记录
	 * @param  [type] $pageNum   [第几页]
	 * @param  [type] $listCount [每页多少个消息记录]
	 * @param  [type] $startTime [description]
	 * @param  [type] $endTime   [description]
	 * @return [type]            [description]
	 */
	public function getServiceChattingRecord($pageNum,$listCount=50,$startTime=null,$endTime=null){
		if(empty($startTime)){
			$startTime=strtotime("-1 day");
		}
		if(empty($endTime)){
			$endTime=time();
		}
		$data=$this->func->arrToStringData(array(
				"starttime"=>$startTime,
				"endtime"=>$endTime,
				"pagesize"=>$listCount,
				"pageindex"=>$pageNum,
			));
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getServiceChattingRecord"));
		$proBuild=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->http->postGetContent($proBuild,$data);
		return $result;
	}
}
 ?>