<?php 
namespace WeiChatLib;

/**
* 用户数据分析
*/
class DataManage
{
	private $http,$func;
	private $begin_time,$end_time;
	//在官方帮助文档里面的数据
	private $funcList=array(
		"getusersummary",
		"getusercumulate",

		"getarticlesummary",
		"getarticletotal",
		"getuserread",
		"getuserreadhour",
		"getusershare",
		"getusersharehour",

		"getupstreammsg",
		"getupstreammsghour",
		"getupstreammsgweek",
		"getupstreammsgmonth",
		"getupstreammsgdist",
		"getupstreammsgdistweek",
		"getupstreammsgdistmonth",

		"getinterfacesummary",
		"getinterfacesummaryhour"
		);
	public function __construct(){
		$this->http=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
	}
	private function urlBuildeQuick($otherParamArr=array(),$urlkey="getWeichatData"){
		$url=Configuration::getInstance()->getConfig($urlkey);
		$urlBuilde=new UrlBuild($url);
		$url=$urlBuilde->otherParamBuild($otherParamArr)->getProUrl();
		return $url;
	}
	/**
	 * 对获取数据的时间片进行设定
	 * 	begin_date  是  获取数据的起始日期，begin_date和end_date的差值需小于“最大时间跨度”（比如最大时间跨度为1时，begin_date和end_date的差值只能为0，才能小于1），否则会报错  
		end_date  是  获取数据的结束日期，end_date允许设置的最大值为昨日  

	 * @param [type] $begin_time [开始时间]
	 * @param [type] $end_time   [结束时间]
	 */
	public function setTime($begin_time,$end_time){
		if(is_numeric($begin_time)){
			$this->begin_time=date("Y-m-d",$begin_time);
		}else{
			$this->begin_time=$begin_time;
		}
		if(is_numeric($end_time)){
			$this->end_time=date("Y-m-d",$end_time);
		}else{
			$this->end_time=$end_time;
		}
		return $this;
	}
	/**
	 * 获取微信平台的数据
	 * @param  [type] $function [url中的url的名称]
	 * @return [type]           [返回获取到的数据array]
	 */
	public function getData($function){
		if(!in_array($function, $this->funcList)){
			throw new \Exception("api".$function."不在列表中", 1);
		}
		if(empty($this->begin_time)||empty($this->end_time)){
			return false;
		}
		$url=$this->urlBuildeQuick(array("FUNCTION"=>$function));
		$data=$this->func->arrToStringData(array(
				"begin_date"=>$this->begin_time,
				"end_date"=>$this->end_time
			));
		$result=$this->http->postGetContent($url,$data);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}

}