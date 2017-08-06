<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    public function __construct()
    {
        $this->middleware('wechat.oauth');
    }

    /**
     * 演示DEMO
     * @return \Illuminate\View\View
     */
    public function demo()
    {
        $user = session('wechat.oauth_user');
        //dd($user);
        return view("demo")->with("user", $user);
    }

    public function success(Request $request)
    {
        $fee = $request->input('fee');
        $des = $request->input('des');
        return view("success")->with("fee", $fee)->with("des", $des);;
    }

}
