<?php

namespace app\controller\mobile;

use app\model\Article;
use app\model\MbDiy as MbDiyModel;
use app\model\Desk as DeskModel;
use app\model\Order as OrderModel;
use app\model\SysConfig as SysConfigModel;
use ruhua\exceptions\BaseException;


class Index extends MobileController
{


    //首页数据
    public function index()
    {
        $tmpJson=MbDiyModel::getHomeJson();
        $data=MbDiyModel::setMbHomeData($tmpJson);
        return app('json')->go($data);
    }

    //所有餐桌
    public function allTable()
    {
        $data=DeskModel::field('id,name,state')->select();

        return app('json')->go($data);
    }

    //某餐桌详情
    public function tableDetail($id)
    {
        $data=DeskModel::where('id',$id)->field('id,name,state,order_id')->find();
        if(!$data){
            return app('json')->go(["id"=>0]);
        }
        $data['pros']=null;
//        if($data['state']>0 && $data['order_id']>0){
//            $data['pros']=OrderModel::where('id',$data['order_id'])->find();
//        }
        return app('json')->go($data);
    }

    public function getsys()
    {
        $res=SysConfigModel::where('key','in','web_url,site_name,tel,site_type,login_type')->
        field('key,value')->select()->toArray();
        $site=[];
        foreach ($res as $k=>$v){
            $site[$v['key']]=$v['value'];
        }
        return app('json')->go($site);
    }

    public function articleDetail($id)
    {
        $data=Article::where('id',$id)->find();
        return app('json')->go($data);
    }
}