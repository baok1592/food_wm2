<?php


namespace app\service;


use ruhua\exceptions\BaseException;
use ruhua\exceptions\TokenException;
use ruhua\utils\JwtAuth;
use think\facade\Request;

class UserTokenService
{

    //通过token获取用户的中uid
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }



    //通过token获取该条缓存数据中指定的字段
    public static function getCurrentTokenVar($key)
    {
        $token = Request::header('token');
        if (!$token) {
            throw new BaseException(['msg' => '无token', 'code' => 401]);
        }
        $jwtData = JwtAuth::getInstance()->decode($token);
        if (!$jwtData) {
            throw new BaseException(['msg' => 'token无效', 'code' => 401]);
        }
        if (array_key_exists($key, $jwtData)) {
            return $jwtData[$key];
        } else {
            throw new TokenException(['msg' => '尝试获取的变量并不存在']);
        }
    }

    //获取JWT的token
    public function getJwtToken($cachedValue)
    {
        $token =  JwtAuth::getInstance()->setData($cachedValue)->encode()->getToken();
        return $token;
    }
}