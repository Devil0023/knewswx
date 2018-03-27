<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>积分明细</title>
	<link rel="stylesheet" type="text/css" href="/skin/styles/public.css">
</head>
<body>
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
		<div class="smallTitle lineTitle text-left"><span>积分明细</span></div>
		<ul class="pointList">
			@foreach ($list as $val)
			<li class="row-flexwrapper">
				<div class="date">{{$val['created_at']}}</div>
				<div class="action">{{$val['desc']}}</div>
				<div class="point"><span>{{$val['points']}}</span>积分</div>
			</li>
			@endforeach
		</ul>
	</div>

	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="/skin/scripts/public.js"></script>
</body>
</html>