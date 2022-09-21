<?php

namespace app\controller\cms;

use ruhua\bases\BaseController;
use app\model\Order as OrderModel;
use app\model\Pros as ProsModel;
use think\facade\Db;

class Common extends BaseController
{
    //统计
    public function statistics()
    {
        $order = new OrderModel();
        $goodsModel = new ProsModel();

        $shipment = $order->where(['state' => 0, 'payment_state' => 1, 'shipment_state' => 0])->field(Db::raw('count(*) as all_num'))->find();
        $refund = $order->where(['state' => -1, 'payment_state' => 1])->field(DB::raw('count(*) as all_num'))->find();
        $month_order = $order->where(['state' => 1, 'payment_state' => 1])->whereTime('create_time',date('m'))
            ->field(Db::raw('count(id) as month_order','sum(order_money) as month_money'))->find();

        $data['total_money']=$order->where(['state'=>1])->sum('order_money');
        $data['today_order']=$order->where([['state','>',-1],['payment_state','=',1],])->whereTime('create_time','today')->count();
        $data['today_money']=$order->whereTime('create_time','today')->where(['state'=>1])->sum('order_money');
        $data['total_order']=$order->count();

        $data['shipment'] = $shipment['all_num'] ? $shipment['all_num'] : 0; //待配送
        $data['tui'] = $refund['all_num'] ? $refund['all_num'] : 0;
        $data['month_order'] = $month_order['month_order'] ? $month_order['month_order'] : 0;
        $data['month_money'] = $month_order['month_money'] ? $month_order['month_money'] : 0;

        $data['yesterday_order'] = $order->where([['state','>',-1],['payment_state','=',1],])->whereTime('create_time','yesterday')->count();
        $data['yesterday_money'] = $order->whereTime('create_time','yesterday')->where(['state'=>1])->sum('order_money');

        $data['time']=date('Y-m-d H:i:s');
        $data['total_goods']=$goodsModel->count();
        return app('json')->go($data);
    }


}