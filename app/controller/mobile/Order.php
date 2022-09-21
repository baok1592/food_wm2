<?php

namespace app\controller\mobile;

use app\model\OrderUser as OrderUserModel;
use app\model\OrderGoods as OrderGoodsModel;
use app\service\UserTokenService;
use app\validate\OrderValidate;

class Order extends MobileController
{
    //计算订单价格
    public function orderCompute($pros)
    {
        $res=(new OrderUserModel)->orderCompute($pros);
        return app('json')->go(['total'=>$res]);
    }

    //前端创建订单
    public function createOrderbyUser()
    {
        $validate=new OrderValidate();
        $validate->goCheck();
        $param=$validate->getDataByRule(input('post.'));
        if($param['desk_id']<0){
            $res=(new OrderUserModel)->addWmOrder($param); //外卖点餐
        }else{
            $res=(new OrderUserModel)->addInsideOrder($param); //餐桌点餐
        }
        return app('json')->go($res);
    }

    //订单详情
    public function orderDetail($id)
    {
        $data['order']=OrderUserModel::find($id);
        $data['pros']=OrderGoodsModel::where('order_id',$id)
            ->field('goods_id,goods_name,sku_name,number,price,pic')->select();
        return app('json')->go($data);
    }

    public function orderMyAll()
    {
        $uid= UserTokenService::getCurrentUid();
        $res=OrderUserModel::where('user_id',$uid)->select();
        return app('json')->go($res);
    }


}