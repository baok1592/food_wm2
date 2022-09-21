<?php


namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use think\facade\Db;
use think\Model;

class Desk extends BaseModel
{
    public function orders()
    {
        return $this->belongsTo('Order','bind_order','order_id');
    }

    public static function onBeforeUpdate(Model $model)
    {
        if($model['state'] ==0 && $model['order_id']>0) {
            $model['state'] = 1;
        }
        return $model;
    }

    public static function create_order($data)
    {
        Db::startTrans();// 启动事务
        try {
            $order=[
                'order_num'=>makeOrderNum(),
                'user_id'=>-1,
                'payment_type'=>'admin',
                'order_money'=>$data['price'],
                'table_num'=>$data['id'],
                'pay_type'=>1,
            ];
            $res=Order::create($order);
            $order_goods=array();
            foreach ($data['json'] as $k=>$v)
            {
                $order_goods[]=[
                    'order_id'=>$res['id'],
                    'goods_id'=>$v['goods_id'],
                    'price'=>$v['price'],
                    'number'=>$v['num'],
                    'goods_name'=>$v['goods_name'],
                    'pic'=>Pros::where(['goods_id'=>$v['goods_id']])->value('img_id')
                ];
            }
            $tb_name=self::where(['id'=>$data['id']])->value('zh_num');
            $log=[
                'order_id'=>$res['id'],
                'type_name'=>$tb_name.'餐桌下单',
                'content'=>$data['price']
            ];
            OrderLog::create($log);
            (new OrderGoods())->saveAll($order_goods);
            $res=self::update(['bind_order'=>$res['id'],'state'=>1],['id'=>$data['id']]);
            Db::commit();
            return $res;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback(); // 回滚事务
            throw new BaseException(['msg'=>'商品添加失败'. $e->getMessage()]);
        }
    }

    public static function add_shop($data,$zh)
    {
        if($zh['state']!=1)
            throw new BaseException(['msg'=>'餐桌状态错误']);
        $order_id=$zh['bind_order'];
        $order=Order::where(['order_id'=>$order_id])->find();
        $order_data=[
            'order_money'=>$data['price']+$order['order_money'],
        ];
        $log=[
            'order_id'=>$order_id,
            'type_name'=>$zh['zh_num'].'价格变动',
            'content'=>$data['price']
        ];
        OrderLog::create($log);
        $res=Order::update($order_data,['order_id'=>$order_id]);
        $res['order_id']=$order_id;
        $res['id']=$order_id;
        $order_goods=array();
        foreach ($data['json'] as $k=>$v)
        {
            $order_goods[]=[
                'order_id'=>$order_id,
                'goods_id'=>$v['goods_id'],
                'price'=>$v['price'],
                'number'=>$v['num'],
                'goods_name'=>$v['goods_name'],
                'pic'=>Pros::where(['goods_id'=>$v['goods_id']])->value('img_id')
            ];
        }
        $res=(new OrderGoods())->saveAll($order_goods);
        return $res;
    }

}
