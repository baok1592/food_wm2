<?php
declare (strict_types = 1);

namespace app\model;

use ruhua\bases\BaseModel;
use think\model\concern\SoftDelete;


class OrderGoods extends BaseModel
{
    //public const path = '/uploads/imgs/';

    use SoftDelete;
    protected $deleteTime = 'delete_time';

//    public static function getPicAttr($url)
//    {
//        return self::path . $url;
//    }

//    public function goods()
//    {
//        return $this->belongsTo('Goods','goods_id','goods_id');
//    }
}
