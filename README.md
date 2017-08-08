# lumen-wechatpay-api
lumen 微信支付 接口

## 接口说明：
+ **公众号JSBridge**
> 127.0.0.1/lumen-wechatpay-api/public/wepayapi/v1/createOrder4JSBridge
> post: openid | 金额fee | 描述des
> return:  config ：[] JSBridge支付所需参数 | out_trade_no : 订单号

+ **JSSDK**
> 127.0.0.1/lumen-wechatpay-api/public/wepayapi/v1/createOrder4JSSDK
> post: openid | 金额fee | 描述des
> return:  config ：[] jssdk支付所需参数 | out_trade_no : 订单号

+ **APP**
> 127.0.0.1/lumen-wechatpay-api/public/wepayapi/v1/createOrder4APP
> post: 金额fee | 描述des
> return:  config ：[] app支付所需参数 | out_trade_no : 订单号

+ **回调地址**
> 127.0.0.1/lumen-wechatpay-api/public/wepayapi/v1/notify

======================================

## 配置说明：

**配置文件 .env**

设置以下参数
WECHAT_APPID=
WECHAT_PAYMENT_MERCHANT_ID=
WECHAT_PAYMENT_KEY=

回调地址
NOTIFY_URL=
