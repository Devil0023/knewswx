{{$wxuser["nickname"]}}
{{$wxuser["headimgurl"]}}
{{$wxuser["points"]}}
{{$wxuser["fill"]}}

完善信息：
<form method="POST" action="/wechat/usercenter/update">
    {{csrf_field()}}
    <input type="text" name="address" value="{{$wxuser["address"]}}"/>
    <input type="text" name="mobile" value="{{$wxuser["mobile"]}}"/>
    <button type="submit">submit</button>
</form>

<br/><br/>

兑换奖品2：
<form method="POST" action="/wechat/usercenter/exchange/2">
    {{csrf_field()}}
    <button type="submit">submit</button>
</form>

<br/><br/>

签到：
<form method="POST" action="/wechat/usercenter/sign">
    {{csrf_field()}}
    <button type="submit">submit</button>
</form>