<?php


namespace app\validate;


use ruhua\bases\BaseValidate;

class OrderValidate extends BaseValidate
{

    protected $rule = [
        'desk_id' => 'require',
        'money' => 'float|require',
        'total_money' => 'float|require',
        'coupon_id'=>"integer",
        'json' => 'require' 





    ];


}