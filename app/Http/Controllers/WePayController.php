<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;
use Log;

class WePayController extends Controller
{

    public function test()
    {
        return $this->formatError('接口生成订单失败');
    }

    /**
     * 创建订单 JSBridge
     */
    public function createOrderJSBridge(Request $request)
    {
        $app = new Application(config('wechat'));
        $payment = $app->payment;
        $openid = $request->input('openid');
        $fee = $request->input('fee') * 100;
        $des = $request->input('des');
        $out_trade_no = $request->input('out_trade_no');
        if ($out_trade_no == "") {
            $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => '会员充值-描述（' . $des . '）',
            'attach' => $des,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $fee, // 单位：分
            'openid' => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $config = $payment->configForPayment($result->prepay_id);
            Log::info('支付成功.. out_trade_no=' . $out_trade_no);
            return self::formatBody(['config' => $config, 'out_trade_no' => $out_trade_no]);
        } else {
            return $this->formatError('接口生成订单失败');
        }
    }


    /**
     * 创建订单 JSSDK
     */
    public function createOrderJSSDK(Request $request)
    {
        $app = new Application(config('wechat'));
        $payment = $app->payment;
        $openid = $request->input('openid');
        $fee = $request->input('fee') * 100;
        $des = $request->input('des');
        $out_trade_no = $request->input('out_trade_no');
        if ($out_trade_no == "") {
            $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => '会员充值-描述（' . $des . '）',
            'attach' => $des,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $fee, // 单位：分
            'openid' => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $config = $payment->configForJSSDKPayment($result->prepay_id);
            return self::formatBody(['config' => $config, 'out_trade_no' => $out_trade_no]);
        } else {
            return $this->formatError('接口生成订单失败');
        }
    }


    /**
     * 创建订单 APP
     * configForAppPayment
     */
    public function createOrderAPP(Request $request)
    {
        $app = new Application(config('wechat'));
        $payment = $app->payment;
        $fee = $request->input('fee') * 100;
        $des = $request->input('des');
        $out_trade_no = $request->input('out_trade_no');
        if ($out_trade_no == "") {
            $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $attributes = [
            'trade_type' => 'APP', // JSAPI，NATIVE，APP...
            'body' => '会员充值-描述（' . $des . '）',
            'attach' => $des,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $fee, // 单位：分
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $config = $payment->configForAppPayment($result->prepay_id);
            return self::formatBody(['config' => $config, 'out_trade_no' => $out_trade_no]);
        } else {
            return $this->formatError('接口生成订单失败');
        }
    }

    public function notifyUrl()
    {
        Log::info('notify ...');
        $app = new Application(config('wechat'));
        $notice = $app->notice;
        $response = $app->payment->handleNotify(function ($notify, $successful) use ($notice) {
            if ($successful) {
                Log::info('回调成功 验证成功');
                Log::info('notify json' . $notify);
                $order_arr = json_decode($notify, true);
                $order_guid = $order_arr['out_trade_no'];//订单号
                $result_code = $order_arr['result_code'];
                if ($result_code == 'SUCCESS') {
                    //微信模板消息通知
                    $notice->send([
                        'touser' => $order_arr['openid'],
                        'template_id' => 'template_id',
                        'url' => '',
                        'data' => [
                            "first" => '您好，你已充值成功',
                            "keyword1" => $order_arr['attach'],
                            "keyword2" => round($order_arr['total_fee'] / 100, 2) . '元',
                            "keyword3" => $order_guid,
                            "keyword4" => date('Y-m-d H:i:s',strtotime($order_arr['time_end'])),
                            "remark" => '备注：如有疑问，请点击下方在线客服联系我们。',
                        ],
                    ]);
                }
                return true;
            }
            Log::info('回调 支付失败');
            return false;
        });
        return $response;
    }

    public static function formatBody(array $data = [])
    {
        $data['error_code'] = 0;
        return $data;
    }

    public static function formatError($message = null)
    {
        $data['error_code'] = -1;
        $data['error_desc'] = $message;
        return $data;
    }

}
