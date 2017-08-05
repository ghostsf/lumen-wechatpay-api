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
        return "test json";
    }

    /**
     * 创建订单 JSAPI
     */
    public function createOrderJSAPI(Request $request)
    {
        $app = new Application(config('wechat'));
        $payment = $app->payment;
        $openid = $request->input('openid');
        $fee = $request->input('fee');
        $des = $request->input('des');
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $des,
            'total_fee' => $fee, // 单位：分
            'openid' => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            Log::info('生成订单号..' . $result->prepay_id);
            $config = $payment->configForJSSDKPayment($result->prepay_id);
            return self::formatBody(['config' => $config, 'prepay_id' => $result->prepay_id]);
        } else {
            Log::info('接口生成订单失败');
            return $this->formatError('接口生成订单失败');
        }
    }


    /**
     * 创建订单 APP
     */
    public function createOrderAPP(Request $request)
    {
        $app = new Application(config('wechat'));
        $payment = $app->payment;
        $openid = $request->input('openid');
        $fee = $request->input('fee');
        $des = $request->input('des');
        $attributes = [
            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
            'body' => $des,
            'total_fee' => $fee, // 单位：分
            'openid' => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            Log::info('生成订单号..' . $result->prepay_id);
            $config = $payment->configForAppPayment($result->prepay_id);
            return self::formatBody(['config' => $config, 'prepay_id' => $result->prepay_id]);
        } else {
            Log::info('接口生成订单失败');
            return $this->formatError('接口生成订单失败');
        }
    }

    public function notifyUrl(Request $request)
    {
        $app = new Application(config('wechat'));
        $response = $app->payment->handleNotify(function ($notify, $successful) {
            if ($successful) {
                $order_arr = json_decode($notify, true);
                $order_guid = $order_arr['out_trade_no'];//订单号
                //回调成功的逻辑
                Log::info('回调成功 out_trade_no：' + $order_guid);
                return true;
            }
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
