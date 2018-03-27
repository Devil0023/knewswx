<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>个人资料</title>
	<link rel="stylesheet" type="text/css" href="/skin/styles/public.css">
</head>
<body>
	<div class="loadingMask">
		<div class="loadWrapper">
			<div class="loading"></div>
			<p>loading...</p>
		</div>
	</div>
	<div class="flexBox">
		<div class="text-center">
			<div class="profile">
				<div class="portrait"><img src="{{$wxuser["headimgurl"]}}"/></div>
				<div class="username"><b>{{$wxuser["nickname"]}}</b></div>
			</div>
			<div class="lineTitle"><span>完善个人资料</span></div>
			<form>
				<div class="smallTitle">手机号</div>
				<div class=" form-input text-input">
					<input class=" text-center" type="text" name="mobile" placeholder="请输入您的手机号码" maxlength="11" value="{{$wxuser['mobile']}}">
				</div>
				<div class="smallTitle">联系地址</div>
				<div class=" form-input text-input">
					<textarea class=" text-center" name="address" placeholder="请输入您的联系地址">{{$wxuser['address']}}</textarea>
				</div>
				<div class="form-input">
					<input class="btnRed" type="submit" value="保存"/>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="/skin/scripts/public.js"></script>
</body>
</html>