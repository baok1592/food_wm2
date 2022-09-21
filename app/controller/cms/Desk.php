<?php


namespace app\controller\cms;

use app\model\Desk as DeskModel;
use app\model\Order as OrderModel;
use ruhua\bases\BaseController;
use ruhua\exceptions\CmsException;
use think\facade\Request;

class Desk extends BaseController
{
    public function getAll(){
        $res=DeskModel::select();
        return app('json')->go($res);
    }
    
    //添加餐桌
    public function add(){
        $name=input('post.name');
        $res=DeskModel::create(['name'=>$name]);
        return app('json')->go(['id'=>$res->id]);
    }
    
    //删除餐桌
    public function del($id){
        $res=DeskModel::destroy($id);
        return app('json')->go($res);
    }

    //修改餐桌信息
    public function up(){
        $arr=Request::param(['id','name']);
        $name=trim($arr['name']);
        $res=DeskModel::where('id',$arr['id'])->update(['name'=>$name]);
        return app('json')->go($res);
    }

    //餐桌结算
    public function end($id)
    {
        $where[]=['id','=',$id];
        $where[]=['state','=',1];
        $one=DeskModel::where($where)->find();
        if(!$one){
            throw new CmsException(['msg'=>'餐桌状态错误']);
        }
        try{
            $order=(new OrderModel())->where('id',$one->order_id)->find();
            if($order) {
                $order->save(['state' => 2, 'payment_state' => 1]);
            }
            $one->save(['order_id'=>0,'state'=>0,'money'=>0]);

        }catch (\Exception $e){
            throw new CmsException(['msg'=>'餐桌状态更新失败']);
        }
        return app('json')->go();
    }





}