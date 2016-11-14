<?php 
require_once "WeiChat.php";
$test=new \WeiChatLib\WeiChat();
// $test->meun();
// $test->startReceive();
// $test->Materia();
// $test->userTest();
$test->ServiceManageTest();
// $test->sendTemplateMeg();
// $test->ServiceMassgeSend();
// $test->MassMessgeSend();
// $test->DataTest();
$result=$test->jsTest();
var_dump($result);
// $test->ipList();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
		wx.config({
		    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		    appId: "$result['appId']", // 必填，公众号的唯一标识
		    timestamp: '$result["timestamp"]', // 必填，生成签名的时间戳
		    nonceStr: '$result["nonceStr"]', // 必填，生成签名的随机串
		    signature: '$result["signature"]',// 必填，签名，见附录1
		    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		});
		wx.ready(function(){
			console.log("ready");
			document.write("jssdk is ready!");
		    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
		});
		wx.error(function(res){
			console.log("error");
		    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
		});

	</script>
</body>
</html>