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
			//$.post("url",{pid:pid,point:point},function(){})
			alert("兑换成功")
			$(this).unbind("click")
			$(".changeProfileWrapper").removeClass("hide");
		})
	})
	$(".cancelChange").bind("click",function() {
		$(".changeWrapper").addClass("hide");
	})
})