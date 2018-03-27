<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>首页</title>
	<link rel="stylesheet" type="text/css" href="/skin/styles/public.css">
</head>
<body>
	<div class="loadingMask">
		<div class="loadWrapper text-center">
			<div class="loading"></div>
			<p>loading...</p>
		</div>
	</div>
	<div class="flexBox">
		<div class="text-center">
			<div class="profile">
				<div class="portrait"><img src="{{$wxuser["headimgurl"]}}"/></div>
				<div class="username">{{$wxuser["nickname"]}}</div>
			</div>
			<div class="toolbox">
				<a href="/wechat/usercenter/profile"><span class="smallBtnWhite">修改资料</span></a>
				<a href="/wechat/usercenter/detail"><span class="smallBtnWhite">积分明细</span></a>
			</div>
			<div class="smallTitle">当前积分</div>
			<div class="bigPoint"><i><b>{{$wxuser["points"]}}</b></i></div>
			<div class="btnBlue"><a href="/wechat/prize/list">积分兑换</a></div>
		</div>
	</div>
	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="/skin/scripts/public.js"></script>
</body>
</html>