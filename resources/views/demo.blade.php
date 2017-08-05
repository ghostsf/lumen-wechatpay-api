<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,user-scalable=0">
    <title>DEMO</title>
    <link rel="stylesheet" href="res/weui.css">
    <link rel="stylesheet" href="res/example.css">
</head>
<body>
<div class="page">
    <div class="bd">
        <div class="weui_cells_title">微信支付</div>
        <div class="weui_cells">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">金额(￥)</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="number" id="fee" placeholder="请输入数字金额,单位分">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">描述</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" type="text" id="des" placeholder="请描述">
                </div>
            </div>
            <div class="weui_btn_area">
                <input type="button" onclick="wxpay();" class="weui_btn weui_btn_primary" value="提交"/>
            </div>
        </div>
    </div>

</div>

<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>

<!-- layer -->
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>

<script type="text/javascript">
    /* 微信支付 */
    function wxpay() {
        $.showLoading("正在加载...");
        //测试时修改为自己的openId 如果不修改会出现【下单账号与支付账号不一致】的提示 这里最好授权获取
        var openid = "o_pncsidC-pRRfCP4zj98h6slREw";
        var fee = $("#fee").val();
        var des = $("#des").val();

        $.post("{{url('wepayapi/v1/createOrder4JSBridge')}}",
                {
                    openid: openid,
                    fee: fee,
                    des: des,
                },
                function (res) {
                    $.hideLoading();
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
                        layer.alert(res.error_desc);
                    }
                });
    }

    function onBridgeReady(json) {
        WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                json,
                function (res) {
                    // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                        alert('支付成功');
                    } else {
                        alert('支付失败');
                    }
                }
        );
    }

</script>
</body>
</html>