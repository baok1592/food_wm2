<?php

namespace app\model;

use app\service\UserTokenService;
use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\CmsException;
use ruhua\exceptions\OrderException;
use ruhua\services\TokenService;
use think\Exception;
use think\facade\Db;
use think\model\concern\SoftDelete;


class OrderUser extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $name = 'order';


    //设置前端外卖订单数据
    public function setWmData($data)
    {
        $order=[
            'order_num'=>makeOrderNum(),
            'user_id'=>0,
            'payment_type'=>'用户下单',
            'desk_id'=>$data['desk_id'],
            'pay_type'=>1, //付款方式
        ];
        $order_goods=[];
        $goods_money=0;
        foreach ($data['json'] as $v){
            if($v['sku_name'] && $v['sku_name']!=''){
                $this->checkSkuPrice($v['goods_id'],$v['sku_name'],$v['price']);
            }else{
                $v['sku_name']=null;
            }
            $goods_money +=$v['price']*$v['num'];
            $order_goods[]=[
                'order_id'=>0,
                'goods_id'=>$v['goods_id'],
                'price'=>$v['price'],
                'number'=>$v['num'],
                'goods_name'=>$v['goods_name'],
                'sku_name'=>$v['sku_name']
            ];
        }
        if($goods_money != $data['money']){
            throw new CmsException(['msg'=>'订单价格异常']);
        }
        $order['goods_money']=$goods_money;
        $order['order_money']=$goods_money;
        return [$order,$order_goods];
    }

    //设置前端餐桌订单数据
    public function setInsideData($data)
    {
        $uid=0;
        $loginType=SysConfig::where('key','login_type')->value('value');
        if($loginType==1) {
            $uid = UserTokenService::getCurrentUid();
        }
        $order=[
            'order_num'=>makeOrderNum(),
            'user_id'=>$uid,
            'payment_type'=>'用户下单-'. ($uid>0?'先付费':'后付费'),
            'desk_id'=>$data['desk_id'],
            'pay_type'=>1, //付款方式
        ];
        $order_goods=[];
        $goods_money=0;
        foreach ($data['json'] as $v){
            if($v['sku_name'] && $v['sku_name']!=''){
                $this->checkSkuPrice($v['goods_id'],$v['sku_name'],$v['price']);
            }else{
                $v['sku_name']=null;
            }
            $goods_money +=$v['price']*$v['num'];
            $order_goods[]=[
                'order_id'=>0,
                'goods_id'=>$v['goods_id'],
                'price'=>$v['price'],
                'number'=>$v['num'],
                'goods_name'=>$v['goods_name'],
                'sku_name'=>$v['sku_name'],
                'pic'=>$v['url']
            ];
        }
        if($goods_money != $data['money']){
            throw new CmsException(['msg'=>'订单价格异常']);
        }
        $order['goods_money']=$goods_money;
        $order['order_money']=$goods_money;
        return [$order,$order_goods];
    }

    //检查订单价格
    private function checkSkuPrice($pro_id,$sku_name,$sku_price){
        $one=ProsSku::where('goods_id',$pro_id)->find();
        if(!$one){
            throw new CmsException(['msg'=>'商品规格不存在']);
        }
        $arr=json_decode($one['json'],true);
        if(!($arr && $arr['list'])){
           throw new CmsException(['msg'=>'商品规格不存在']);
        }
        $res_name="";
        $res_price=0;
        //$names=[];
        foreach ($arr['list'] as $v) {
            $name ="";
            $name .= $v['s1_name'];
            if (isset($v['s2_name'])) {
                $name .= "_" . $v['s2_name'];
            }
            if (isset($v['s3_name'])){
                $name .= "_" . $v['s3_name'];
            }
            //$names[]=$name;
            if ($sku_name == $name){
                $res_name=$name;
                $res_price=$v['price'];
            }
        }
        if($res_price == 0) {
            throw new CmsException(['msg'=>'商品规格未找到对应商品']);
        }
        if($sku_price!=$res_price || $res_price==0) {
            throw new CmsException(['msg'=>'商品规格价格不相符-'.$res_name.':'.$sku_price.'!='.$res_price]);
        }
    }


    //前端下单前计算订单价格
    public function orderCompute($pros)
    {
        $goods_money=0;
        foreach ($pros as $v){
            if($v['sku_name'] && $v['sku_name']!=''){
                $this->checkSkuPrice($v['goods_id'],$v['sku_name'],$v['price']);
            }else{
                $v['sku_name']=null;
            }
            $goods_money +=$v['price']*$v['num'];
        }
        return $goods_money;
    }

    //前端外卖下单
    public function addWmOrder($data)
    {
        $cmsOrder=$this->setWmData($data);
        $order=$cmsOrder[0];
        $order_goods=$cmsOrder[1];
        Db::startTrans();// 启动事务
        try {
            $res=self::create($order);
            $order_id=$res->id;
            $this->upDeskOrderId($data['desk_id'],$order_id,$order['total_money']);
            foreach ($data['json'] as $k=>$v){
                $order_goods[$k]['order_id']=$order_id;
            }
            (new OrderGoods())->saveAll($order_goods);
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback(); // 回滚事务
            throw new CmsException(['msg'=>'订单异常：'. $e->getMessage()]);
        }
        return $res;
    }

    //前端餐桌下单
    public function addInsideOrder($data)
    {
        $cmsOrder=$this->setInsideData($data);
        $order=$cmsOrder[0];
        $order_goods=$cmsOrder[1];
        Db::startTrans();// 启动事务
        try {
            $res=self::create($order);
            $order_id=$res->id;
            $this->upDeskOrderId($data['desk_id'],$order_id,$order['order_money']);
            foreach ($data['json'] as $k=>$v){
                $order_goods[$k]['order_id']=$order_id;
            }
            (new OrderGoods())->saveAll($order_goods);
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback(); // 回滚事务
            throw new CmsException(['msg'=>'订单异常：'. $e->getMessage()]);
        }
        return $res;
    }

    private function upDeskOrderId($desk_id,$order_id,$money){
        try{
            $desk=(new Desk())->find($desk_id);
            if(!$desk){
                throw new CmsException(['msg'=>"餐桌不存在"]);
            }
            if($desk->state==1){
                throw new CmsException(['msg'=>"餐桌已存在订单"]);
            }
            $desk->save(['order_id'=>$order_id,'money'=>$money]);
        }catch (\Exception $e){
            throw new CmsException(['msg'=>$e->getMessage()]);
        }
    }

    //关联规格模型
    public function sku()
    {
        return $this->hasMany('GoodsSku', 'goods_id', 'goods_id');
    }
    //关联用户模型
    public function users()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }
    //关联订单商品模型
    public function ordergoods()
    {
        return $this->hasMany('OrderGoods', 'order_id', 'id');
    }
    //关联图片
    public function imgs()
    {
        return $this->belongsTo('Image', 'goods_picture', 'id')->field('id,url');
    }
    //关联餐桌
    public function desk()
    {
        return $this->belongsTo('desk', 'table_num', 'id');
    }




}
