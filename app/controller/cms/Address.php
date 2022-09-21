<?php


namespace app\controller\cms;

use app\model\Address as AddressModel;
use ruhua\bases\BaseController;

class Address extends BaseController
{
    //获取地址列表
    public function getAll(){
        $Res=AddressModel::select();
        $data=[];
        foreach ($Res->toArray() as $v){
            if($v['level']==1){
                array_push($data,$v);
                foreach ($Res->toArray() as $j){
                    if($j['pid']==$v['id']){
                        array_push($data,$j);
                    }
                }
            }

        }
        return app('json')->go($data);
    }

    //录入地址
    public function add()
    {
        $post = input('post.');
        if(!$post['name'] || !isset($post['pid'])){
            return app('json')->fail('地址为空！');
        }
        $res=AddressModel::enterAddress($post);
        return app('json')->go($res);
    }
    /**
     * 修改地址
     */
    public function up(){
        $post=input('post.');
        if(!$post['name'] || !$post['id']){
            return app('json')->fail('地址为空！');
        }
        $res=AddressModel::upAddress($post);
        return app('json')->go($res);
    }
    //删除地址
    public function del($id){
        AddressModel::delAddress($id);
        return app('json')->go();
    }

}