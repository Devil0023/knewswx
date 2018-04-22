<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
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
        <img src="/img/survey/gh.png" />
        <input type="number" class="code" value="" />
        <img src="/img/survey/tip.png" />
        <input type="button" class="sBtn" value="进 入" id="enter" />
    </div>
</div>
<div class="second">
    <form id="form">
    <?php
    foreach($questions as $question){
        $type = "开放题";
        $area = "<textarea></textarea>";

        switch($question->type){
            case 0: $type = "单选题"; break;
            case 1: $type = "多选题"; break;
            case 2: $type = "开放题"; break;
        }

    ?>

        <div class="tm">
            <p>{{$question->qorder}}. {{$question->question}} [{{$type}}] {{intval($question->isrequired) === 1? "<em>*</em>": "";}}</p>
            <ul class="radio">
                <li data-val="nan"><span></span>男</li>
                <li data-val="nv"><span></span>女</li>
            </ul>
            <input type="hidden" name="sex" value="nan" required/>
        </div>
    <?php
    }
    ?>

        <div class="tm">
            <p>1. 你的性别？ [单选题]<em>*</em></p>
            <ul class="radio">
                <li data-val="nan"><span></span>男</li>
                <li data-val="nv"><span></span>女</li>
            </ul>
            <input type="hidden" name="sex" value="nan" required/>
        </div>
        <div class="tm">
            <p>2. 你的年龄段？ [单选题]<em>*</em></p>
            <ul class="radio">
                <li data-val="1"><span></span>22-28</li>
                <li data-val="2"><span></span>29-35</li>
                <li data-val="3"><span></span>36-40</li>
                <li data-val="4"><span></span>41-50</li>
                <li data-val="5"><span></span>51-60</li>
            </ul>
            <input type="hidden" name="age" value="nan" required/>
        </div>
        <div class="tm">
            <p>6. 你为自己设立的职业目标是？ [多选题]</p>
            <ul class="check">
                <li><span></span>能独当一面，独立负责一部分工作<input class="votebox" value="1" name="mb" style="display:none" type="checkbox"></li>
                <li><span></span>能拥有自己的团队，全盘负责，带领团队完成工作<input class="votebox" value="2" name="mb" style="display:none" type="checkbox"></li>
                <li><span></span>能在职级上有所晋升<input class="votebox" value="3" name="mb" style="display:none" type="checkbox"></li>
                <li><span></span>能在专业领域有显著提升<input class="votebox" value="4" name="mb" style="display:none" type="checkbox"></li>
            </ul>
        </div>
        <div class="tm">
            <p>7. 除薪酬外，你最看重什么？ [多选题]</p>
            <ul class="check">
                <li><span></span>提高自己能力的机会和舞台<input class="votebox" value="1" name="xc" style="display:none" type="checkbox"></li>
                <li><span></span>良好的办公环境<input class="votebox" value="2" name="mb" style="display:none" type="checkbox"></li>
                <li><span></span>和谐的人际关系<input class="votebox" value="3" name="mb" style="display:none" type="checkbox"></li>
                <li><span></span>工作中的认同感<input class="votebox" value="4" name="mb" style="display:none" type="checkbox"></li>
                <li><span></span>其他（请说明）<input class="smTxt" name="" type="text" value="" /><input class="votebox" value="5" name="mb" style="display:none" type="checkbox"></li>
            </ul>
        </div>
        <div class="tm">
            <p>你对深度融合与整体转型的意见和建议。</p>
            <textarea></textarea>
        </div>
        <div class="tm">
            <p>你认为目前迅速提升看看新闻影响力最有效又切实可行的办法是什么？</p>
            <textarea></textarea>
        </div>
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
                $(".mask").find("p").html("您输入的工号有误！");
            }else{
                $(".first").hide();
                $(".second").show();
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
                    $(this).parent("li").siblings("li").removeClass("curr");
                    $(this).parent("li").addClass("curr");
                    $(this).parents("ul.radio").next("input").val($(this).parent("li").attr("data-val"));
                })
            });
        });
        /*-----复选-----*/
        var chkbs = $('.votebox');
        $(".check span").each(function(index){
            $(this).on("click",function(){
                if(!$(this).parent("li").hasClass("curr")){
                    $(this).parent("li").addClass("curr")
                    chkbs[index].checked = true;
                }else{
                    $(this).parent("li").removeClass("curr")
                    chkbs[index].checked= false;
                }

            });
        });

        /*----表单提交----*/
        $("#submit").on("click",function(){
            $.ajax({
                cache: true,
                type: "POST",
                url:"****.php",
                data:$("#form").serialize(),
                async: false,
                error: function(request) {
                    $(".mask").show();
                    $(".mask").find("p").html("提交失败！");
                },
                success: function(data) {
                    $(".mask").show();
                    $(".mask").find("p").html("提交成功！");
                    console.log(data);
                }
            });
        })

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
