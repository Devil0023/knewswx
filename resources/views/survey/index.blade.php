<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="http://skin.kankanews.com/zt/2017/20170817/js/flexible.js"></script>
    <title>{{$questionnaire["title"]}}</title>
    <link href="" type="text/css" rel="stylesheet" />
    <link href="/css/survey/dc.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="loading">
    <span class="spinner-loader">Loading&#8230;</span>
    <p>&nbsp;&nbsp;Loading...</p>
</div>
<div class="first">
    <div class="fBox">
    	<form id="jobN">
	        <img src="/img/survey/gh.png" />
	        <input type="number" class="code" name="jobNumber" value="" />
	        <img src="/img/survey/tip.png" />
	        <input type="button" class="sBtn" value="进 入" id="enter" />
        </form>
    </div>
</div>
<div class="second">
    <form id="form">
    <?php
    foreach($questions as $question){

        $area = "";

        switch($question["type"]){
            case 0:
                $type    = "单选题";
                $options = array_filter(explode(PHP_EOL, $question["options"]));

                $area    = "<ul class=\"radio\">";

                foreach($options as $option){
                    $area .= "<li data-val=\"".$option."\"><span></span>".$option."</li>";
                }

                $area .= "</ul>".
                         "<input type=\"hidden\" name=\"Question_".$question["qorder"]."\" value=\"\" required class=\"".(intval($question["isrequired"]) === 1? "bitian": "")."\"/>";

                break;

            case 1:
                $type    = "多选题";
                $options = array_filter(explode(PHP_EOL, $question["options"]));
                $area    = "<ul class=\"check\">";

                foreach($options as $option){
                    if($option === "else"){
                        $area .= "<li><span></span>其他（请说明）<input class=\"smTxt\" name=\"Question_".$question["qorder"]."[]\" type=\"text\" value=\"\" disabled=\"disabled\" />".
                                 "<input class=\"votebox\" value=\"else\" name=\"\" style=\"display:none\" type=\"checkbox\"></li>";
                    }else{
                        $area .= "<li><span></span>".$option."<input class=\"votebox\" value=\"".$option.
                                 "\" name=\"Question_".$question["qorder"]."[]\" style=\"display:none\" type=\"checkbox\"></li>";
                    }

                }

                $area .= "</ul>".(intval($question["isrequired"]) === 1? "<input type=\"hidden\" value=\"\" class=\"bitian\" />": "");
                
               

                break;
            case 2:
                $type = "开放题";
                $area = "<textarea class=\"".(intval($question["isrequired"]) === 1? "bitian": "")."\" name=\"Question_".$question["qorder"]."\"></textarea>";

                break;
        }

    ?>

        <div class="tm">
            <p>
                {{$question["qorder"]}}. {{$question["question"]}} [{{$type}}]
                {!! intval($question["isrequired"]) === 1? "<em>*</em>": "" !!}
            </p>
            {!! $area !!}
        </div>

    <?php
    }
    ?>
    <input type="hidden" name="qid" value="{{$questionnaire->id}}">
    </form>
    <div class="btn"><input type="button" class="sBtn" value="提 交" id="submit" /></div>
</div>
<div class="mask">
    <div class="pop1">
        <p>您输入的工号有误！</p>
        <span class="close" id="close">关闭</span>
    </div>
</div>
<script src="http://skin.kankanews.com/v6/js/libs/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    $(function(){
        var img1 = new Image();
        img1.src = "/img/survey/tip.png";
        function imgLoad(img, callback) {
            var start = false;
            var timer = setTimeout(function () {
                if(!start) {callback();start = true;}
            },3000);
            img.onload = function() {
                if(!start){ callback(img);start = true;}
                clearInterval(timer);
            }
        }
        imgLoad(img1, function() {
            $(".loading").remove();
        });
        /*--进入--*/
        $("#enter").on("click",function(){
	    	if($(".code").val()==""){
	    		$(".mask").show();
	    		$(".mask").find("p").html("请输入您的工号！");
	    		return false;
	    	}else{
	    		$.ajax({
					cache: true,
					type: "POST",
					url:   "/survey/questionnaire/check",
					data:$("#jobN").serialize(),
					async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

					error: function(request) {
						$(".mask").show();
			    		$(".mask").find("p").html("系统有误，请稍候再试！");
					},

					success: function(data) {

					    console.log(data);
                        //var dataObj = JSON.parse(data);

						if(data.error_code != "0"){
							$(".mask").show();
			    			$(".mask").find("p").html(data.error_message);
						}else{
                            $(".first").hide();
                            $(".second").show();
                        };

					}
				});

	    	}
	    });
        /*--关闭弹层--*/
        $("#close").on("click",function(){
            $(this).parents(".mask").hide();
        });
        /*---单选----*/
        $(".radio").each(function(){
            $(this).find("span").each(function(){
                $(this).on("click",function(){
		   			if(!$(this).parent("li").hasClass("curr")){
		   				$(this).parent("li").siblings("li").removeClass("curr");
			   			$(this).parent("li").addClass("curr");
			   			$(this).parents("ul.radio").next("input").val($(this).parent("li").attr("data-val"));
		   			}else{
		   				$(this).parent("li").removeClass("curr");
		   				$(this).parents("ul.radio").next("input").val("");
		   			}
		   			
		   		})
            });
        });
        /*-----复选-----*/
        $(".check").each(function(){
		   	var chkbs = $(this).find('.votebox');
		   	var num = 0;
		   	$(this).find("span").each(function(index){
		   		$(this).on("click",function(){
		   			if(!$(this).parent("li").hasClass("curr")){
		   				num +=1;
			  			$(this).parent("li").addClass("curr")
			  			chkbs[index].checked = true;
			  			if($(this).parent("li").find("input.smTxt")){
			  				$(this).parent("li").find("input.smTxt").removeAttr("disabled");
			  			}
			  			$(this).parents("ul.check").next("input").val("ok");
			  		}else{
			  			num -=1;
			  			$(this).parent("li").removeClass("curr");
			  			chkbs[index].checked = false;
			  			if($(this).parent("li").find("input.smTxt")){
			  				$(this).parent("li").find("input.smTxt").val("").attr("disabled","disabled");
			  			};
			  			if(num==0){
			  				$(this).parents("ul.check").next("input").val("");
			  			}
			  		}
		   		})
		   	});
		 });

        /*----表单提交----*/
        var bt=0,sm=0;
        $("#submit").on("click",function(){
		 	var smTxt = $("input.smTxt");
		 	var bitian = $(".bitian");
		 	
		 	$(".bitian").each(function(index){
		 		if($(".bitian")[index].value==""){
		 			bt = 0;
		 			$(".mask").show();
			    	$(".mask").find("p").html("您有必填项未填！");
			    	return false;
		 		}
		 		bt = 1;
		 	});
		 	$("input.smTxt").each(function(index){
		 		if(!smTxt[index].disabled && smTxt[index].value==""){
		 			sm = 0;
		 			$(".mask").show();
			    	$(".mask").find("p").html("你有说明未填！");
			    	return false;
		 		}
		 		sm = 1;
		 	});
		 	if(bt==1&&sm==1){
		 		sbData();
		 	}
		 })
        	
    	function sbData(){
			$.ajax({
				cache: true,
                type: "POST",
                url:"/survey/questionnaire/{{$questionnaire->id}}/submit",

                data:$("#form").serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                async: false,
                error: function(request) {
                    $(".mask").show();
                    $(".mask").find("p").html("提交失败！");
                },
                success: function(data) {
                    $(".mask").show();
                    $(".mask").find("p").html(data.error_message);
                    bt = 0;
                    sm = 0;
                    console.log(data);
                }
            });
		}
           
    });
    function callback(data, success, dataAndEvents) {
        var script = document.createElement("script");
        if (!dataAndEvents) {
            script.async = "async";
        }
        if (success) {
            script.onload = success;
        }
        script.src = data;
        document.body.appendChild(script);
    }
    callback("http://skin.kankanews.com/mobilev2/js/weixinv2.js?20150115", function() {
        wxshare({
            titlepic: "",
            title: document.title,
            des: ""
        });
    });
</script>
</body>
</html>
