<?php

namespace app\service;

use app\model\SysConfig;
use EasyWeChat\Factory;
use ruhua\exceptions\BaseException;
use app\model\User as UserModel;

class XcxService extends UserTokenService
{
    public function xcxApp(){
        $appid = SysConfig::where('key', 'xcx_appid')->value('value');
        $secret = SysConfig::where('key', 'xcx_secret')->value('value');
        if (empty($appid) || empty($secret)) {
            throw new BaseException(['msg'=>'未填写微信参数']);
        }
        $config = [
            'app_id' => $appid,
            'secret' => $secret,
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'level' => 'error',
                'file' => ROOT.'/storage/logs/wechat.log',
            ],
        ];
        $app=Factory::miniProgram($config);
        return $app;
    }

    public function getToken($code)
    {
        $wxResult=$this->xcxApp()->auth->session($code);
        if (empty($wxResult)) {
            throw new BaseException(['msg'=>'获取openId时异常']);
        }
        $loginFail = array_key_exists('errcode', $wxResult);
        if ($loginFail){
            throw new BaseException(['msg'=>$wxResult['errmsg']]);
        }
        return $this->grantToken($wxResult);
    }

    //openid，uid放入缓存，$token做缓存键名;
    private function grantToken($wxResult)
    {
        $user = UserModel::where(['openid'=>$wxResult['openid']])->find();
        if (!$user) {
            $data['openid']=$wxResult['openid'];
            $data['invite_code']=rand(1000000,9999999);
            $uid = UserModel::insertGetId($data);
        }else {
            $uid = $user->id;
        }
        $cachedValue = $this->setWxCache($wxResult['openid'], $uid);
        $token = (new TokenService())->getJwtToken($cachedValue);
        return $token;
    }

    //组合uid，openid，权限
    private function setWxCache($openid, $uid)
    {
        $cache['uid'] = $uid;
        $cache['openid'] = $openid;
        $cache['scope'] = 9;
        return $cache;
    }
}