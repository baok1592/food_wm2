<?php

namespace app\controller\mobile;

use app\model\User as UserModel;
use app\model\MbDiy as MbDiyModel;
use app\service\UserTokenService;
use app\service\XcxService;

class User extends MobileController
{
    public function index()
    {
        $json = MbDiyModel::where('id',3)->value('json');
        $tmpJson = json_decode($json, true);
        $data=MbDiyModel::setMbPageData(3,$tmpJson);
        return app('json')->go($data);
    }

    //小程序登陆
    public function xcxLogin($code)
    {
        $token=(new XcxService)->getToken($code);
        return app('json')->go(['token'=>$token]);
    }

    //用户我的信息
    public function userInfo()
    {
        $uid = UserTokenService::getCurrentUid();
        $data=UserModel::field('id,nickname,headpic,openid')->find($uid);
        if($data){
            $data['nickname']=base64_decode($data['nickname']);
        }
        return app('json')->go($data);
    }


    //更新我的信息
    public function upMyInfo()
    {
        $post=input('post.');
        UserModel::upInfo($post);
        return app('json')->go($post);
    }
}

