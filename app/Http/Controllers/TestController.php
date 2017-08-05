<?php

namespace App\Http\Controllers;

class TestController extends Controller
{

    public function createTest(Request $request)
    {
        return response()->json("fuck.");
    }

    public function updateTest(Request $request, $id)
    {
        return response()->json($request->all());
    }

    public function deleteTest($id)
    {
        return response()->json('删除成功');
    }

    public function index()
    {
        return response()->json("index");
    }
}
