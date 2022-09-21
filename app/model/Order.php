<?php

namespace app\model;

use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\CmsException;
use ruhua\exceptions\OrderException;
use ruhua\services\TokenService;
use think\facade\Db;
use think\model\concern\SoftDelete;


class Order extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    //设置后台订单数据
    public function setCmsData($data)
    {
        $order=[
            'order_num'=>makeOrderNum(),
            'user_id'=>0,
            'payment_type'=>'后台下单',
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
        $order['total_money']=$goods_money;
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



    //后台餐桌下单
    public function order_desk_add($data)
    {
        $cmsOrder=$this->setCmsData($data);
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

    //后台更新订单
    public function order_desk_up($data)
    {
        $order_id=$data['order_id'];
        unset($data['order_id']);
        $order=self::find($order_id);
        if(!$order){
            throw new CmsException(['msg'=>'订单错误']);
        }
        $cmsOrder=$this->setCmsData($data);
        $goods_money=$cmsOrder[0]['goods_money'];
        $order_goods=$cmsOrder[1];
        Db::startTrans();// 启动事务
        try {
            OrderGoods::destroy(['order_id'=>$order_id]);

            foreach ($data['json'] as $k=>$v){
                $order_goods[$k]['order_id']=$order_id;
            }
            (new OrderGoods())->saveAll($order_goods);

            $save['order_money']=$goods_money;
            $save['goods_money']=$goods_money;
            $order->save($save);

            Desk::where('id',$data['desk_id'])->save(['money'=>$goods_money]);

            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback(); // 回滚事务
            throw new CmsException(['msg'=>'订单异常：'. $e->getMessage()]);
        }
    }

    //删除订单
    //如果这里是静态方法，就无法删除其他模型的数据
    public function del($id)
    {
        Db::startTrans();// 启动事务
        try{
            $one=Order::where('id',$id)->findOrEmpty();
            if ($one->isEmpty()) {
                return;
            }
            Order::destroy($id);
            $ids=OrderGoods::where('order_id',$id)->column('id');
            OrderGoods::destroy($ids);
            Db::commit();
        }catch(\Exception $e){
            Db::rollback(); // 回滚事务
            throw new CmsException(['msg'=>'删除失败']);
        }
        return app('json')->go();
    }


    /**
     * 用户提交评价
     * @param $uid
     * @param $post
     * @return mixed
     * @throws
     */
    public static function setPj($uid, $post)
    {
        $where['user_id'] = $uid;
        $where['order_id'] = $post['id'];
        $order = self::where($where)->where(['state' => 1])->find();
        if (!$order) {
            throw new BaseException(['msg'=>'评价出现错误']);
        }
        $orderGoods = new OrderGoods();
        $order_id = $order['order_id'];
        $goods_ids = $orderGoods->where('order_id', $order_id)->column('goods_id');
        $goods_ids=array_column($goods_ids,'goods_id');
        $user=User::find($uid);

        //foreach ($post['goods_id'] as $v){
        if (in_array($post['goods_id'], $goods_ids)) {
            $data['user_id'] = $where['user_id'];
            $data['rate'] = $post['rate'];
            $data['content'] = $post['content'];
            $data['order_id'] = $order_id;
            $data['goods_id'] = $post['goods_id'];
            $data['headpic'] = $user['headpic'];
            $data['nickname'] = $user['nickname'];
            $data['ucid']=0;
            $data['imgs'] = implode(',',$post['imgs']);
            $data['created_at'] = time();
        }

        Db::startTrans();// 启动事务
        try {
            $i=OrderGoods::where(['order_id' => $post['id'], 'user_id' => $uid, 'goods_id' => $post['goods_id']])->update(['state' => 2]);
            $status=OrderGoods::Where(['order_id' => $post['id'],'state'=>1])->find();
            if(!$status){
                $order->save(['state' => 2]);
            }
            Rate::create($data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw  new BaseException(['msg'=>$e->getMessage()]);
        }
        //}
        return true;
    }

    public function getEditMoneyAttr($value)
    {
        if($value>0)
            return "+".$value;
        return $value;
    }


    /**
     * 申请退款
     * @param $uid
     * @param $post
     * @return mixed
     * @throws
     */
    public function tuikuan_approve($uid, $post)
    {

        $user=User::where('id',$uid)->find();
        $order = self::where('order_id',$post['order_id'])->find();
        $data['order_id'] = $order['order_id'];
        $data['nickname'] = $user['nickname'];
        $data['order_num'] = $order['order_num'];
        $data['money'] =$order['order_money'];
        $data['because'] = $post['radio'] ?: "";
        $data['message'] =  "";
        $data['created_at']=time();
        $data['ip'] = $_SERVER["REMOTE_ADDR"]; //买家IP
        if($order['state']<0)
            return ['msg'=>'已申请过了','code'=>400];

        if(!isset($post['goods_id'])){
            self::where('order_id',$post['order_id'])->update(['state'=>-1]);
            //   $order->save(['state' => -1]);
        }else{
            $goodsWhere['goods_id']=$post['goods_id'];
        }
        $goodsWhere['order_id']=$post['order_id'];
        $goodsWhere['user_id']=$uid;
        $log = [
            'order_id' => $post['order_id'],
            'type_name' => '退款申请',
            'content' => $data['money']
        ];
        Db::startTrans();
        try {
            OrderGoods::where($goodsWhere)->update(['state' => -1]);
            $tui = Tui::create($data);
            OrderLog::create($log);
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            throw new OrderException(['msg'=>'退款操作失败'.$e->getMessage()]);
        }
        if ($tui) {
            return ['code'=>200,'msg'=>'申请成功'];
        } else {
            return ['code'=>400,'msg'=>'申请失败'];
        }
    }
    /**
     * 添加订单备注信息
     * @param $param
     * @return mixed
     * @throws
     */
    public static function up_remark_model($param)
    {
        Db::startTrans();
        try {
            self::where('order_id', $param['order_id'])->update(['remark_one' => $param['remark']]);
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '添加备注';
            $save['content'] = $param['remark'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '备注信息录入失败']);
        }
    }

    /**
     * 修改订单价格
     * @param $param
     * @return mixed
     * @throws
     */
    public function edit_price_model($param)
    {
        $id=$param['order_id'];
        $order = self::where('order_id',$id )->find();
        if(!$order){
             throw new OrderException(['msg'=>'订单错误']);
        }
        Db::startTrans();
        try {
            $order = self::where('order_id',$id )->find();
            if($order['edit_money']==0){
                $order['edit_money'] = $param['price']*1;
                $order['order_money'] = $order['order_money'] + $order['edit_money'] ;
                $order['message'] = "改价：".$order['edit_money'];
            }else{
                $order['order_money'] = $order['order_money'] - $order['edit_money'];
                $order['edit_money'] = $param['price']*1;
                $order['order_money'] = $order['order_money'] + $order['edit_money'] ;
                $order['message'] = "改价：".$order['edit_money'];
            }
            if ($order['order_money'] < 0.01) {
                throw new OrderException(['msg'=>'价格错误']);
            }
            $order->save();
            $save['order_id'] = $id;
            $save['type_name'] = '修改订单金额';
            $save['content'] = $param['price'];

            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }



    /**
     * 关闭订单
     */
    public static function closeOrder()
    {
        $where['state'] = 0;
        $where['payment_state'] = 0;
        $time = time() - 15 * 60; //1关闭15分钟未支付的订单
        self::where($where)->whereTime('create_time', '<', $time)->update(['state' => -3]);
    }

    /**
     * 获取订单指定字段
     * @param $id
     * @param $field
     * @return mixed
     */
    public static function getOrderAttr($id, $field)
    {
        $value = self::where('order_id', $id)->value($field);
        if (!$value) {
            throw new BaseException('获取字段失败');
        }
        return $value;
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
    //关联抵用券模型
    public function coupon()
    {
        return $this->belongsTo('Coupon', 'coupon_id', 'id');
    }

    //关联订单商品模型
    public function ordergoods()
    {
        return $this->hasMany('OrderGoods', 'order_id', 'id');
    }

    //关联评价模型
    public function rate()
    {
        return $this->hasMany('Rate', 'order_id', 'order_id');
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

    public function invitecode()
    {
        return $this->belongsTo('User','invite_code','invite_code');
    }


}
