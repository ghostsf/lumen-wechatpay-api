<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,user-scalable=0">
    <title>DEMO - 支付成功</title>
    <link rel="stylesheet" href="res/weui.css">
    <link rel="stylesheet" href="res/example.css">
</head>
<body>
<div class="container" id="container">
    <div class="msg">
        <div class="weui_msg">
            <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
            <div class="weui_text_area">
                <h2 class="weui_msg_title">支付成功</h2>
                <div class="weui_cells">
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary" style="text-align: left">
                            <p>金额(分)</p>
                        </div>
                        <div class="weui_cell_ft">{{$fee}}</div>
                    </div>
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary" style="text-align: left">
                            <p>描述</p>
                        </div>
                        <div class="weui_cell_ft">{{$des}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>