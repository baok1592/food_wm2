<?php


namespace app\controller\cms;

use app\model\Order as OrderModel;
use app\model\OrderGoods;
use app\model\OrderLog;
use app\validate\OrderValidate;
use ruhua\bases\BaseController;
use ruhua\exceptions\CmsException;
use think\facade\Request;

class Order extends BaseController{

    //所有订单
    public function getAll()
    {
        $post = input('post.');
        $keyword = '';
        $playsate = '';
        $starttime = '';
        $endtime = '';
        if (isset($post['wordkey'])) {
            $keyword = $post['wordkey'];
        }
        if (isset($post['playstate'])) {
            $playsate = $post['playstate'];
        }
        if (isset($post['starttime'])) {
            $starttime = $post['starttime'];
        }
        if (isset($post['endtime'])){
            $endtime = $post['endtime'];
        }

        $where=[];
        if(!empty($playsate)){
            $where[]=['payment_state','=',intval($playsate)];
        }
        if(!empty($starttime)){
            $where[]=['create_time','>=',strtotime($starttime)];
        }
        if(!empty($endtime)){
            $where[]=['create_time','<=',strtotime($endtime)];
        }
        if(!empty($keyword)){
            $where1="(concat(order_num,receiver_mobile) like '%".$keyword."%')";
            $data = OrderModel::with(['ordergoods','ordergoods.imgs','desk','users' => function ($query) {
                $query->field('id,nickname,headpic');
            }])->where($where)->whereRaw($where1)->order('create_time desc')->select();
            return app('json')->go($data);
        }else{
            $data = OrderModel::with(['ordergoods','desk','users' => function ($query) {
                $query->select('id,nickname,headpic');
            }])->where($where)->order('create_time desc')->select();
            return app('json')->go($data);
        }
    }

    //后台餐桌修改订单
    public function upDeskOrder()
    {
        $validate=new OrderValidate(['order_id']);
        $validate->goCheck();
        $data=$this->request->param();
        $param=$validate->getDataByRule($data);
        (new OrderModel)->order_desk_up($param);
        return app('json')->go();
    }

    //餐桌获取订单详情
    public function deskOrderDetail($id)
    {
        $order_num = OrderModel::where(['id' => $id])->value('order_num');
        if(!$order_num){
            throw new CmsException(['msg'=>'订单不存在']);
        }
        $list=OrderGoods::where('order_id',$id)->select();
        $arr=[];
        foreach ($list as $k=>$v){
            $arr[$k]['goods_id']=$v['goods_id'];
            $arr[$k]['goods_name']=$v['goods_name'];
            $arr[$k]['price']=$v['price'];
            $arr[$k]['num']=$v['number'];
            $arr[$k]['sku_name']=$v['sku_name'];
        }
        $res['order_num']=$order_num;
        $res['pro_arr']=$arr;
        return app('json')->success($res);
    }

    //详情
    public function detail($id,$log=false)
    {

        $data = OrderModel::with(['imgs','users' => function ($query) {
            $query->field('id,nickname,headpic');
        },'ordergoods'])->where(['id' => $id])->find();
        if($log) {
            $data['order']=$data;
            $data['log'] = OrderLog::where(['order_id' => $id])->order('create_time desc')->select();
        }
        return app('json')->success($data);
    }

    public function del($id)
    {
        (new OrderModel)->del($id);
        return app('json')->go();
    }

    //后台餐桌创建订单
    public function order_admin_add()
    {
        $validate=new OrderValidate();
        $validate->goCheck();
        $data=$this->request->param();
        $param=$validate->getDataByRule($data);
        $res=(new OrderModel)->order_desk_add($param);
        return app('json')->go($res);
    }

    //更新订单状态
    public function up_state($id,$type)
    {
        $one=OrderModel::find($id);
        $arr=['shipment_state'];
        if(!$one || !in_array($type,$arr)){
            throw new CmsException(['msg'=>'类型错误']);
        }
        $one[$type]=1;
        $res=$one->save();
        return app('json')->go($res);
    }
}