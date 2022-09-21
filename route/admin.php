<?php

use think\facade\Route;


Route::post('cms/admins_login', 'cms.Admins/login');   //登陆

Route::group('cms', function () {

    //优惠券
    Route::group('', function(){
        Route::get('coupon_all', 'cms.Coupon/getAll');    //获取所有优惠券
        Route::post('coupon_add', 'cms.Coupon/add');   //新增
        Route::post('coupon_up', 'cms.Coupon/up');  //修改
        Route::delete('coupon_del', 'cms.Coupon/del');   //删除
    });

    //配送地址
    Route::group('', function(){
        Route::get('address_all', 'cms.Address/getAll');    //获取所有
        Route::post('address_add', 'cms.Address/add');   //新增
        Route::post('address_up', 'cms.Address/up');  //修改
        Route::delete('address_del', 'cms.Address/del');   //删除
    });

    //小票机
    Route::group('', function(){
        Route::get('machine_all', 'cms.Machine/getAll');    //获取所有
        Route::post('machine_add', 'cms.Machine/add');   //新增
        Route::post('machine_up', 'cms.Machine/up');  //修改
        Route::delete('machine_del', 'cms.Machine/del');   //删除
    });

    //餐桌管理
    Route::group('', function(){
        Route::get('desk_all', 'cms.Desk/getAll');    //获取所有
        Route::put('desk_end', 'cms.Desk/end');  //餐桌结算
        Route::post('desk_add', 'cms.Desk/add');   //新增
        Route::put('desk_up', 'cms.Desk/up');  //修改
        Route::delete('desk_del', 'cms.Desk/del');   //删除
    });

    //订单管理
    Route::group('', function(){
        Route::get('order_all', 'cms.Order/getAll');    //获取所有
        Route::get('order_detail', 'cms.Order/deskOrderDetail');   //餐桌订单详情
        Route::delete('order_del', 'cms.Order/del');   //删除
        Route::put('order_up', 'cms.Order/up_state');   //更新状态
        Route::post('order_admin_add', 'cms.Order/order_admin_add');   //后台创建订单
        Route::post('order_admin_up', 'cms.Order/upDeskOrder');   //后台更新订单
    });

    //管理员
    Route::group('', function () {
        Route::get('admins_list', 'cms.Admins/adminAll');  //获取所有管理员
        Route::post('admins_add', 'cms.Admins/add');   //添加管理员
        Route::get('admins_myinfo', 'cms.Admins/myInfo');  //管理员我的信息
        Route::post('admins_update_gid', 'cms.Admins/updateGid');   //更新管理员GID
        Route::delete('admins_del', 'cms.Admins/del');   //删除管理员
        Route::get('admins_check_login', 'cms.Admins/check_login');   //检查是否登陆
        Route::post('admins_up_psw', 'cms.Admins/updatePassWord');   //修改密码

        Route::get('group_rule_all', 'cms.Group/getRuleAll');  //获取所有规则
        Route::get('admin_groups', 'cms.Group/getAll'); //所有管理组
        Route::post('admin_group_add', 'cms.Group/add'); //新增管理组
        Route::post('admin_group_up', 'cms.Group/up'); //修改管理组
        Route::delete('admin_group_del', 'cms.Group/del'); //删除管理组
    });

    //栏目
    Route::group('', function () {
        Route::get('category_list', 'cms.Category/getAll'); //获取所有栏目
        Route::post('category_add', 'cms.Category/add'); //新增栏目
        Route::delete('category_del', 'cms.Category/del'); //删除栏目
        Route::post('category_up_sort', 'cms.Category/up_sort'); //更新栏目排序
        Route::get('category_id', 'cms.Category/detail'); //获取栏目详情
        Route::post('category_update', 'cms.Category/up'); //更新栏目
        Route::put('category_state', 'cms.Category/editState');  //修改栏目状态
        Route::put('up_home_tmp_ids', 'cms.Category/upHomeTmp');  //修改首页模板ID
    });

    //文章
    Route::group('', function(){
        Route::get('article_all', 'cms.Article/getAll');    //获取所有文章列表
        Route::get('article_cid_ctx', 'cms.Article/getCidArticle');    //某栏目下的商品
        Route::get('article_detail', 'cms.Article/detail');  //获取某文章详情
        Route::post('article_add', 'cms.Article/add');   //新增文章
        Route::delete('article_del', 'cms.Article/del');   //删除文章
        Route::post('article_up', 'cms.Article/up');  //修改文章
        Route::put('article_state', 'cms.Article/editState');  //修改文章状态
        Route::post('addWangImg', 'cms.Article/addWangImg');//新增wang编辑器图片
    });

    //商品
    Route::group('', function(){
        Route::get('pros_all', 'cms.Pros/getAll');    //获取所有商品列表
        Route::get('pros_cid_ctx', 'cms.Pros/getCidPros');    //某栏目下的商品

        Route::get('pros_detail', 'cms.Pros/detail');  //获取某商品详情
        Route::post('pros_add', 'cms.Pros/add');   //新增商品
        Route::delete('pros_del', 'cms.Pros/del');   //删除商品
        Route::post('pros_up', 'cms.Pros/up');  //修改商品
        Route::put('pros_state', 'cms.Pros/editState');  //修改商品状态
    });

    //手机
    Route::group('', function(){
        Route::get('mb_navs_all', 'cms.Mobile/getNavsall');    //获取底部导航
        Route::post('mb_navs_up', 'cms.Mobile/navsUp');    //修改底部导航


        Route::get('mb_template', 'cms.Mobile/tmp_all');    //获取所有手机模板
        Route::post('mb_template_add', 'cms.Mobile/add');    //新增手机模板
        Route::post('mb_template_up', 'cms.Mobile/up');    //修改手机模板
        Route::put('mb_template_up_name', 'cms.Mobile/upName');    //修改手机模板名称
        Route::delete('mb_template_del', 'cms.Mobile/del');    //删除手机模板
        Route::get('mb_tmp_detail', 'cms.Mobile/detail');    //手机模板详情
    });

    //AD广告
    Route::group('', function(){
        Route::get('ad_pc_all', 'cms.Ad/getPcAll');    //获取所有PC广告
        Route::get('ad_mb_all', 'cms.Ad/getMbAll');    //获取所有手机广告
        Route::get('ad_mb_sid', 'cms.Ad/getMbSidAll');    //获取指定ID位的广告
        Route::get('ad_seat', 'cms.Ad/adSeat');    //获取所有广告位
        Route::post('ad_add', 'cms.Ad/add');   //新增广告
        Route::post('ad_up', 'cms.Ad/up');  //修改广告
        Route::delete('ad_del', 'cms.Ad/del');   //删除广告
    });

    //表单
    Route::group('', function(){
        Route::get('form_all', 'cms.Form/getAll');    //获取所有表单
        Route::post('form_add', 'cms.Form/add');   //新增表单
        Route::post('form_up', 'cms.Form/up');  //修改表单
        Route::delete('form_del', 'cms.Form/del');   //删除表单
        Route::get('form_data_all', 'cms.Form/getDataAll');    //获取所有表单的数据
        Route::delete('form_data_del', 'cms.Form/delDataItem');  //删除表单的一条数据
    });

    //配置信息
    Route::group('', function(){
        Route::get('sys', 'cms.System/getConfigType');    //配置信息
        Route::post('sys_up', 'cms.System/up');   //更新配置信息
    });

    //资源管理
    Route::group('', function(){
        Route::get('resource_type', 'cms.Resource/getResourcType');    //获取图片、视频、文件
        Route::post('resource_category_add', 'cms.Resource/categoryAdd');//新增资源分类
        Route::get('resource_category_type', 'cms.Resource/getCategoryType');//获取某类型分类


        Route::post('img_add', 'cms.Resource/addImg');//新增图片
        Route::get('img_category_id', 'cms.Resource/getImgCategoryData');//获取图库某分类下图片

        Route::post('resource_dels', 'cms.Resource/dels');//批量删除资源
        Route::post('upload_img', 'cms.Resource/upImgbackUrl');//上传图片不加入Img表
    });

    //用户
    Route::group('', function(){
        Route::get('user_all', 'cms.User/userAll');    //所有用户
        Route::delete('user_del', 'cms.User/del');   //删除用户
        Route::put('user_disable', 'cms.User/userDisable');   //用户禁用
    });

    //统计
    Route::group('', function(){
        Route::get('statistics', 'cms.Common/statistics');    //统计
    });

})->middleware('cmsAuth');

Route::get('cms/web_data', 'cms.System/getCmsSysData');    //配置信息
Route::get('cms/home_temp', 'cms.Pc/getHomeTmpInfo'); //获取首页模板信息
Route::get('cms/form_detail', 'cms.Form/getDetail');    //获取某表单详情
