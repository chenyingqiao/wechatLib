<?php 
namespace WeiChatLib;

/**
* 用户信息调用和一些请求的工具类
*/
class UserManage
{	
	private $httpTool,$func;
	public function __construct(){
		$this->httpTool=ToolFactory::createHttpTool();
		$this->func=ToolFactory::createFuncTool();
	}
	/**
	 * 
	 * @param  [type] $redirectUrl [授权后重定向的回调链接地址，请使用urlencode对链接进行处理]
	 * @param  string $scope       [应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）]
	 * @param  string $status      [重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节]
	 * @param  string $auto_back      [如果获取微信服务器回掉的code不是在本方法的话
	 * 就使用fase]
	 * @return [type]              [description]
	 */
	public function getUserCode($redirectUrl,$scope="snsapi_userinfo",$status="this_is_code_get",$auto_back=true){
		if($auto_back){
			if(($result=$this->getCodeByBack($status))!=false){
				return $result;
			}
		}
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getUserCode"));
		$buildData=array(
				"REDIRECT_URI"=>urlencode($redirectUrl),
				"SCOPE"=>$scope,
				"STATE"=>$status,
			);
		$proBuild=$urlBuild->baseParamBuild()->otherParamBuild($buildData)->getProUrl();
		// echo $proBuild;exit;
		header("Location:".$proBuild);exit;
	}
	public function getCodeByBack($status){
		$code=$_GET['code'];
		// $status_back=$_GET['status'];
		// if(!empty($code)&&!empty($status_back)&&$status_back===$status){
		if(!empty($code)){
			return $code;
		}else{
			return false;
		}
	}
	/**
	 * {
	*   "access_token":"ACCESS_TOKEN",
	*   "expires_in":7200,
	*   "refresh_token":"REFRESH_TOKEN",
	*   "openid":"OPENID",
	*   "scope":"SCOPE",
	*   "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
	*}
	 * @param  [type] $code [请求的code]
	 * @return [type]       [返回的是用户的数据，出错就是返回会错误代码]
	 */
	public function getUserAccessToken($code){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getUserAccessToken"));
		$ProUrl=$urlBuild->baseParamBuild()->otherParamBuild(array(
				"CODE"=>$code
			))->getProUrl();
		$result=$this->httpTool->postGetContent($ProUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 刷新accessToken
	 * {
	*   "access_token":"ACCESS_TOKEN",
	*   "expires_in":7200,
	*   "refresh_token":"REFRESH_TOKEN",
	*   "openid":"OPENID",
	*   "scope":"SCOPE"
	*}
	 * @param  [type] $refresh_token [舒心access_token 的令牌]
	 * @return [type]                [description]
	 */
	public function refreshAccessToken($refresh_token){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("refreshAccessToken"));
		$proUrl=$urlBuild->baseParamBuild()->otherParamBuild(array(
				"REFRESH_TOKEN"=>$refresh_token
			))->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 获取用户信息
	 * @param  [type] $access_token [description]
	 * @param  [type] $openid       [description]
	 * @return [type]               [description]
	 */
	public function getUserInfo($access_token,$openid){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getUserInfo"));
		$proUrl=$urlBuild->otherParamBuild(array(
				"ACCESS_TOKEN_USER"=>$access_token,
				"OPENID"=>$openid
			))->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 检查accessToken是否可用(这个的返回比较坑爹,正确竟然也是用errorcode)
	 * { "errcode":0,"errmsg":"ok"}
	 * @return [type] [description]
	 */
	public function checkAccessToken($access_token,$openid){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("checkAccessToken"));
		$proUrl=$urlBuild->otherParamBuild(array(
				"ACCESS_TOKEN_USER"=>$access_token,
				"OPENID"=>$openid
			))->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}

	/**
	 * 只是通过openid来获取用户数据
	 *
	 *如果有多个不同的微信应用的话就可以使用unionid的机制来获用户数据
	 *unionid对于所有的用户都是唯一的（openid对于不同公众号的数据是不唯一的）
	 *比如网站应用中的unionid就可以通过weichat公众号的对应unionid的对应openid来获取数据
	 *
	 * 
	 *开发者可通过OpenID来获取用户基本信息。
	 *特别需要注意的是，如果开发者拥有多个移动应用、网站应用和公众帐号，
	 *可通过获取用户基本信息中的unionid来区分用户的唯一性，
	 *因为只要是同一个微信开放平台帐号下的移动应用、
	 *网站应用和公众帐号，用户的unionid是唯一的。
	 *换句话说，同一用户，对同一个微信开放平台下的不同应用，unionid是相同的。
	 * 
	 * @param  [type] $openid [公众号用户的openid]
	 * @return [type]         [description]
	 */
	public function getUserInfoByOpenid($openid){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getUserInfoByOpenid"));
		$proUrl=$urlBuild->baseParamBuild()->otherParamBuild(array(
				"OPENID"=>$openid
			))->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 列表获取用户数据
	 * @param  [type] $arrOpenid [一个openid的数组集合]
	 * @return [type]            [description]
	 */
	public function getUserInfoByListOpenid($arrOpenid){
		if(count($arrOpenid)>100){
			throw new Exception("数据量不能超过100", 1);
		}
		foreach ($arrOpenid as $key => &$value) {
			$value=array(
					"openid"=>$value,
					"lang"=>"zh-CN"
				);
		}
		$OpenidList['user_list']=$arrOpenid;
		$StringOpendiArr=$this->func->arrToStringData($OpenidList);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getUserInfoByListOpenid"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$StringOpendiArr);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}

	/**
	 用户分组操作
	 */
	/**
	 * {"group":{"name":"test"}}
	 * @param [type] $groupName [分组的名称]
	 */
	public function createUserGroup($groupName){
		$data["group"]=array("name"=>$groupName);
		$groupPostData=$this->func->arrToStringData($data);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("createUserGroup"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$groupPostData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 获取所有的分组
	 * @return [type] [分组数据]
	 */
	public function getAllGroup(){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getAllGroup"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 查询用户所在数组
	 * @return [type] [description]
	 */
	public function searchUserInGroup($openid){
		$data["openid"]=$openid;
		$postData=$this->func->arrToStringData($data);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("searchUserInGroup"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 批量移动用户到相应的用户组中
	 * @param  [type] $openidList [description]
	 * @param  [type] $groupId    [description]
	 * @return [type]             [{"errcode": 0, "errmsg": "ok"}]
	 */
	public function moveUserToGroup($openidList,$groupId){
		$postData['openid_list']=$openidList;
		$postData['to_groupid']=$groupId;
		$postData=$this->func->arrToStringData($postData);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("moveUserToGroup"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * 删除用户分组
	 * 注意本接口是删除一个用户分组，
	 * 删除分组后，所有该分组内的用户自动进入默认分组。
	 * @param  [type] $groupId [用户分组id]
	 * @return [type]          [description]
	 */
	public function delectGroup($groupId){
		$postData["group"]=array("id"=>$groupId);
		$postData=$this->func->arrToStringData($postData);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("delectGroup"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 设置用户分组备注名称
	 */
	/**
	 * [setUserNickname description]
	 * @param [type] $openid   [description]
	 * @param [type] $nickname [description]
	 */
	public function setUserNickname($openid,$nickname){
		$postData['openid']=$openid;
		$postData['remark']=$nickname;
		$postData=$this->func->arrToStringData($postData);
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("setUserNickname"));
		$proUrl=$urlBuild->otherParamBuild()->getProUrl();
		$result=$this->httpTool->postGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
	/**
	 * [getUserListPage description]
	 * @param  [type] $nextOpenid [description]
	 * @return [type]             [description]
	 */
	public function getUserListPage($nextOpenid){
		$urlBuild=new UrlBuild(Configuration::getInstance()->getConfig("getUserListPage"));
		$proUrl=$urlBuild->otherParamBuild(array("NEXT_OPENID"=>$nextOpenid))->getProUrl();
		$result=$this->httpTool->getGetContent($proUrl,$postData);
		$result=$this->func->stringDataToaArr($result);
		return $result;
	}
}