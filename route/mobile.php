<?php
use think\facade\Route;

//手机端接口
Route::group('client/', function() {
    Route::get('foot_navs', 'mobile.index/getFootNavs'); //获取底部导航
    Route::get('index', 'mobile.index/index'); //首页数据
    Route::get('cover', 'mobile.user/index');//user页面

    Route::get('list', 'mobile.lists/index');//某list数据
    Route::get('article_detail', 'mobile.index/articleDetail');//某文章内容详情
    Route::get('sys', 'mobile.index/getsys');//站点信息
    Route::get('desk_all', 'mobile.index/allTable');//所有餐桌
    Route::get('desk_detail', 'mobile.index/tableDetail');//某餐桌详情

    //商品pros
    Route::group('', function(){
        Route::get('category_all', 'mobile.Pros/allCategory');    //所有分类
        Route::get('pros_cid', 'mobile.Pros/prosByCategory');   //某分类下所有商品
        Route::get('pros_tag', 'mobile.Pros/prosByTag');   //某标签的所有商品
        Route::get('pros_search', 'mobile.Pros/search');   //搜索商品
        Route::get('pro_detail', 'mobile.Pros/detail');   //商品详情
    });

    //订单order
    Route::group('', function(){
        Route::post('order_compute', 'mobile.Order/orderCompute');    //计算订单价格
        Route::post('create_order', 'mobile.Order/createOrderbyUser');   //提交订单
        Route::get('order_my_all', 'mobile.Order/orderMyAll');   //我的所有订单
        Route::get('order_detail', 'mobile.Order/orderDetail');   //订单详情
        Route::get('order_tui', 'mobile.Order/orderTui');   //申请退款
    });

    //我的
    Route::group('', function(){
        Route::get('xcx_login', 'mobile.User/xcxLogin');   //小程序登陆
        Route::get('user_info', 'mobile.User/userInfo');   //从数据库获取用户信息
        Route::post('up_my_info', 'mobile.User/upMyInfo');   //更新我的信息
    });
});

