<?php

namespace app\model;


use Aliyun\api_demo\SmsDemo;
use app\service\UserTokenService;
use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use think\Model;
use think\model\concern\SoftDelete;


class User extends BaseModel
{

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public static function onBeforeInsert(Model $value)
    {
        //$value['invite_code'] = rand(100000, 999999);
    }

    public static function userTypeAll($type,$count=false)
    {
        if($type=='wx'){
            $res=self::where('openid_gzh','<>','')->select();
        }else if($type=='xcx'){
            $res=self::where('openid','<>','')->select();
        }else{
            $res=self::select();
        }
        if($count){
            $res=count($res);
        }
        return $res;
    }

    //更新用户昵称和头像
    public static function upInfo($post)
    {
        $uid = UserTokenService::getCurrentUid();
        $nickname = base64_encode($post['nickName']);
        $user = self::find($uid);
        $data['nickname'] = $nickname;
        $data['headpic'] = $post['avatarUrl'];
        $res=$user->save($data);
        if(!$res){
            throw new BaseException(['msg'=>'更新信息失败']);
        }
    }

}
