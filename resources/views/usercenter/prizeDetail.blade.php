<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>商品详细-{{$prize['prize']}}</title>
	<link rel="stylesheet" type="text/css" href="/skin/styles/public.css">
</head>
<body>
	<div class="loadingMask">
		<div class="loadWrapper">
			<div class="loading"></div>
			<p>loading...</p>
		</div>
	</div>
	<div class="productDetail">
		<div class="productImage">
			<img src="/uploads/{{$prize['img']}}"/>
		</div>
		<div class="productTitle text-center">{{$prize['prize']}}</div>
		<div class="clearfix point">
			<p class="left">兑换所需积分</p>
			<p class="right"><span>{{$prize['cost']}}</span>分</p>
		</div>
		<div class="productIntro">
			<p>商品说明：</p>
			<p>{{$prize['intro']}}</p>
		</div>
		<div class="prizeChange btnOringe text-center" data-point="{{$prize['cost']}}" data-pid="1">确认兑换</div>
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
					<span class="smallBtnRed"><a href="profile.html">完善个人资料</a></span>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="/skin/scripts/public.js"></script>
</body>
</html>