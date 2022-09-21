<?php

namespace app\controller\cms;

use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\User as UserModel;
use ruhua\exceptions\BaseException;
use ruhua\utils\Logs;

class User extends BaseController
{

    public function userAll($type='')
    {
        $res=UserModel::userTypeAll($type);
        return app('json')->go($res);
    }
    public function del($id)
    {
        (new IDPostiveInt)->goCheck();
        $user = UserModel::where('id',$id)->find();
        if(!$user){
            throw new BaseException(['msg'=>'用户不存在']);
        }
        $user->delete();
        Logs::Write('删除了用户：'.$id);
        return app('json')->go();
    }
    public function userDisable($id)
    {
        (new IDPostiveInt)->goCheck();
        $user = UserModel::where('id',$id)->find();
        if(!$user){
            throw new BaseException(['msg'=>'用户不存在']);
        }
        $state=$user->is_disable?0:1;
        $user->save(['is_disable'=>$state]);
        return app('json')->go();
    }
}