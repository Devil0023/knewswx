$(document).ready(function() {
	var height=$(window).height(),dHeight=$("body").height();
	if(height>dHeight){
		$("body").height(height);
	}
	$(".loadingMask").addClass("hide");
	$(".prizeChange").bind("click",function() {
		var pid=$(this).data("pid"),point=parseInt($(this).data("point"));
		$(".changeWrapper>.flexBox>div p i").html(point);
		$(".changeWrapper").removeClass("hide");
		$(".changeForSure").bind("click",function() {
			var totalpointsDiv=$(".mallPoint i b");
			var totalpoints=parseInt(totalpointsDiv.html());
			$.post("/wechat/usercenter/exchange/"+pid,{_token:$("input[name='_token']").val()}).then(function(res){
				try{res=JSON.parse(res);}catch(e){}
				var error_code=parseInt(res.error_code)
				if(error_code===0){
					alert("兑换成功")
					totalpointsDiv.html(totalpoints-point)
					$(this).unbind("click")
					$(".changeForSure").unbind("click");
					$(".changeWrapper").addClass("hide");
				}else if(error_code===400011){
					$(this).unbind("click")
					$(".changeProfileWrapper").removeClass("hide");
				}else{
					alert(res.error_message)
				}
			},function(e){
				//alert(JSON.stringify(e))
				alert("兑换失败")
			})
			
		})
	})
	$(".cancelChange").bind("click",function() {
		$(".changeForSure").unbind("click");
		$(".changeWrapper").addClass("hide");
	})
})