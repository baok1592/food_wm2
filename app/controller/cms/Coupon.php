<?php


namespace app\controller\cms;

use app\model\BannerItem as BannerItemModel;
use app\model\Coupon as CouponModel;
use app\validate\ADValidate;
use app\validate\CouponValidate;
use ruhua\bases\BaseController;

class Coupon extends BaseController
{

    /**
     * cms查看优惠券
     * @return \think\response\Json
     */
    public function getAll()
    {
        $data=CouponModel::select();
        return app('json')->go($data);
    }

    //创建优惠券
    public function add()
    {
        $data=$this->request->param();
        $validate = new CouponValidate();
        $validate->goCheck($data);
        $form=$validate->getDataByRule($data);
        $res=CouponModel::addCoupon($form);
        return app('json')->go($res);
    }

    //更新
    public function up()
    {
        $data=$this->request->param();
        $validate = new CouponValidate(['id']);
        $validate->goCheck();
        $form=$validate->getDataByRule($data);
        CouponModel::up($form);
        return app('json')->go();
    }

    /**
     * 删除优惠券
     * @param $id
     * @return int
     */
    public function del($id)
    {
        $res = CouponModel::where('id', $id)->delete();
        return app('json')->go($res);
    }


}