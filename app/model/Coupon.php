<?php


namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;

class Coupon extends BaseModel
{
     public function couponGoods()
    {
        return $this->hasMany('CouponGoods', 'coupon_id', 'id');
    }

    /**
     *添加优惠券
     * @param $post
     * @return mixed
     */
    public static function addCoupon($post)
    {
        if ($post['state'] != 1 && $post['state'] != 0) {
            throw new BaseException(['msg'=>'优惠券state错误']);
        }
        $data = self::setCouponDate($post);
        $res = self::create($data);
        return $res;
    }

    public static function up($form)
    {
        $id=$form['id'];
        unset($form['id']);
        $ad =self::find($id);
        if(!$ad){
            throw new BaseException(['msg'=>'优惠券不存在']);
        }
        $ad->save($form);
    }

    //组装数据
    private static function setCouponDate($post)
    {
        if (array_key_exists('state', $post)) {
            $data['state'] = $post['state'];
        }
        if ($post['infinite']) {
            $data['infinite'] = 1;
        } else {
            $data['infinite'] = 0;
            if (!array_key_exists('stock', $post)) {
                throw new BaseException( 'stock未填');
            }
            $data['stock'] = $post['stock'];
        }
        $data['full'] = $post['full'];
        $data['reduce'] = $post['reduce'];
        $data['name'] = $post['name'];
        if ($post['start_time']) {
            $data['start_time'] = strtotime($post['start_time']);
            if (!$post['end_time']) {
                throw new BaseException( 'end_time未填');
            }
            $data['end_time'] = strtotime($post['end_time']);
        }
        if ($post['day']) {
            $data['day'] = $post['day'];
        }
        return $data;
    }
    public function getEndTimeAttr($value)
    {
        if($value)
            return date('Y-m-d',$value);
        else
            return $value;
    }
    public function getStartTimeAttr($value)
    {
        if($value)
            return date('Y-m-d',$value);
        else
            return $value;
    }

}