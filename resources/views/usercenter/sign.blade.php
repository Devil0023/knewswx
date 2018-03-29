<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>签到</title>
	<link rel="stylesheet" type="text/css" href="/skin/styles/public.css">
</head>
<body>
	{{csrf_field()}}
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
			<div class="row-flexwrapper">
				<div class="btnBlue"><a href="/wechat/prize/list">积分兑换</a></div>
				<div class="btnRed sign">立即签到</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="/skin/scripts/public.js"></script>
	<script type="text/javascript">
		$(".sign").bind("click",function() {
			if($(".sign").hasClass("gray")){
				alert("今日已签到");
			}else{
				$.post("/wechat/usercenter/sign",{_token:$("input[name='_token']").val()}).then(function(res){
					try{res=JSON.parse(res);}catch(e){}
					var error_code=parseInt(res.error_code)
					if(error_code===400008||error_code===0){
						$(".sign").addClass("gray")
					}
					if(error_code===0){
						alert("签到成功");
						bigPoint.html(res.points);
						$(".sign").addClass("gray")
						//window.location.href="/wechat/usercenter/index";
					}else{
						alert(res.error_message)
					}
				},function(e){
					alert("签到失败")
				})
			}
		})
	</script>
</body>
</html>