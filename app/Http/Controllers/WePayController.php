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
        $fee = $request->input('fee');
        $des = $request->input('des');
        $out_trade_no = $request->input('out_trade_no');
        if ($out_trade_no == "") {
            $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $des,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $fee, // 单位：分
            'openid' => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $config = $payment->configForPayment($result->prepay_id);
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
        $fee = $request->input('fee');
        $des = $request->input('des');
        $out_trade_no = $request->input('out_trade_no');
        if ($out_trade_no == "") {
            $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $des,
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
        $fee = $request->input('fee');
        $des = $request->input('des');
        $out_trade_no = $request->input('out_trade_no');
        if ($out_trade_no == "") {
            $out_trade_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        $attributes = [
            'trade_type' => 'APP', // JSAPI，NATIVE，APP...
            'body' => $des,
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
        Log::info('notifyUrl');
        $app = new Application(config('wechat'));
        $response = $app->payment->handleNotify(function ($notify, $successful) {
            if ($successful) {
                Log::info('回调成功');
//                $order_arr = json_decode($notify, true);
//                $order_guid = $order_arr['out_trade_no'];//订单号
//                //回调成功的逻辑
//                Log::info('回调成功 out_trade_no：' + $order_guid);
                return true;
            }
            Log::info('notifyUrl error');
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
