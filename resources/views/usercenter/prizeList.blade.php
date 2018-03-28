<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>积分兑换</title>
	<link rel="stylesheet" type="text/css" href="/skin/styles/public.css">
</head>
<body>
	{{csrf_field()}}
	<div class="loadingMask">
		<div class="loadWrapper">
			<div class="loading"></div>
			<p>loading...</p>
		</div>
	</div>
	<div class="container">
		<div class="smallprofile">
			<div class="portrait"><img src="{{$wxuser["headimgurl"]}}"/></div>
			<div class="username"><b>{{$wxuser["nickname"]}}</b></div>
			<div class="mallPoint"><i><b>{{$wxuser["points"]}}</b></i>分</div>
		</div>
		<div class="smallTitle lineTitle text-left"><span>积分兑换</span></div>
		<ul class="prizeList">
			@foreach ($list as $val)
			<li>
				<a href="/wechat/prize/detail/{{$val['id']}}">
				<div class="prizeImg"><img src="/uploads/{{$val['img']}}"><span class="caution">剩余<i>{{$val['left']}}</i>份</span></div>
				</a>
				<div class="prizeDetail">
					<p class="prizeTitle">{{$val["prize"]}}</p>
					<p class="prizeSubTitle">积分兑换</p>
					<div class="row-flexwrapper">
						<div class="point"><i><b>{{$val["cost"]}}</b></i>积分</div>
						<div class="prizeState {{$val['left']==0?'soldout':''}}">
							<span class="smallBtnGray text-center">已结束</span>
							<span class="prizeChange smallBtnRed  text-center" data-point="{{$val["cost"]}}" data-pid="{{$val['id']}}">兑换</span>
						</div>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
	<div class="changeWrapper hide">
		<div class="flexBox">
			<div>
				<p>是否确认兑换此礼物？</p>
				<p>将消耗 <i class="changePoint"></i> 积分</p>
				<div class="row-flexwrapper text-center">
					<span class="smallBtnRed changeForSure">兑换</span>
					<span class="smallBtnGray cancelChange">取消</span>
				</div>
			</div>
		</div>
	</div>
	<div class="changeProfileWrapper hide">
		<div class="flexBox">
			<div>
				<p>请先完善您的个人资料</p>
				<p>我们才能准确配送礼物</p>
				<div class="row-flexwrapper text-center">
					<span class="smallBtnRed"><a href="/wechat/usercenter/profile">完善个人资料</a></span>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="/skin/scripts/public.js"></script>
</body>
</html>