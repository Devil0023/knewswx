{{$wxuser["nickname"]}}
{{$wxuser["headimgurl"]}}
{{$wxuser["points"]}}
{{$wxuser["fill"]}}


<form method="POST" action="/wechat/usercenter/update">
    {{csrf_field()}}
    <input type="text" name="address" value="{{$wxuser["address"]}}"/>
    <input type="text" name="mobile" value="{{$wxuser["mobile"]}}"/>
    <button type="submit">submit</button>
</form>

<br/><br/>

<form method="POST" action="/wechat/usercenter/exchange/3">
    {{csrf_field()}}
    <button type="submit">submit</button>
</form>