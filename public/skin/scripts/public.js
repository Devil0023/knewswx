$(document).ready(function() {
	var height=$(window).height(),dHeight=$("body").height();
	if(height>dHeight){
		$("body").height(height);
	}
	$(".loadingMask").addClass("hide");
	$(".prizeChange").bind("click",function() {
		var pid=$(this).data("pid"),point=$(this).data("point");
		$(".changeWrapper>.flexBox>div p i").html(point);
		$(".changeWrapper").removeClass("hide");
		$(".changeForSure").bind("click",function() {
			alert("/wechat/usercenter/exchange/"+pid);
			$.post("/wechat/usercenter/exchange/"+pid,{_token:$("input[name='_token']").val()}).then(function(res){
				try{res=JSON.parse(res);}catch(e){}
				var error_code=parseInt(res.error_code)
				if(error_code===0){
					alert("兑换成功")
					$(this).unbind("click")
					$(".changeProfileWrapper").removeClass("hide");
				}else{
					alert(res.error_message)
				}
			},function(e){
				alert(JSON.stringify(e))
				alert("兑换失败")
			})
			
		})
	})
	$(".cancelChange").bind("click",function() {
		$(".changeForSure").unbind("click");
		$(".changeWrapper").addClass("hide");
	})
})