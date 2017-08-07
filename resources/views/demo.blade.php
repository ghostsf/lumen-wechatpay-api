<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,user-scalable=0">
    <title>微信充值</title>
    <link rel="stylesheet" href="res/weui.css">
    <link rel="stylesheet" href="res/example.css">
</head>
<body>
<div class="page">
    <div class="bd">
        <p>&nbsp;</p>
        <div class="weui_cells">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">金额(￥)</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" id="fee" placeholder="请输入数字金额,单位元">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">会员账号</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" id="des" placeholder="请输入会员账号">
                </div>
            </div>
            <div class="weui_btn_area">
                <input type="button" onclick="wxpay();" class="weui_btn weui_btn_primary" value="提交"/>
            </div>
        </div>
    </div>

</div>

<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
{{--<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>--}}
<script type="text/javascript" src="res/layer/layer.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>

<script type="text/javascript">
    function onBridgeReady(json) {
        WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                json,
                function (res) {
                    // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                        layer.msg("支付成功", {shift: 6});
                        self.location = "{{url('success')}}?fee=" + $("#fee").val() + "&des=" + $("#des").val();
                    } else {
                        layer.msg("支付失败", {shift: 6});
                    }
                }
        );
    }
    function wxpay() {
        //测试时修改为自己的openId 如果不修改会出现【下单账号与支付账号不一致】的提示 这里最好授权获取
        var openid = '{{$user->id}}';
        var fee = $("#fee").val();
        var des = $("#des").val();
        $.post("{{url('wepayapi/v1/createOrder4JSBridge')}}",
                {
                    openid: openid,
                    fee: fee,
                    des: des
                },
                function (res) {
                    if (res.error_code == 0) {
                        var data = $.parseJSON(res.config);
                        if (typeof WeixinJSBridge == "undefined") {
                            if (document.addEventListener) {
                                document.addEventListener('WeixinJSBridgeReady', onBridgeReady(data), false);
                            } else if (document.attachEvent) {
                                document.attachEvent('WeixinJSBridgeReady', onBridgeReady(data));
                                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady(data));
                            }
                        } else {
                            onBridgeReady(data);
                        }
                    } else {
                        alert(res.error_desc);
                    }
                });
    }
</script>
</body>
</html>