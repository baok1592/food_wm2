<?php


namespace app\validate;



use ruhua\bases\BaseValidate;

class CouponValidate extends BaseValidate
{
    protected $rule = [
        'full' => 'require',
        'reduce' => 'require',
        'name' => 'require',
        'state' => 'bool|require',
        'start_time' => 'date|require',
        'end_time' => 'date|require',
        'day' => 'integer|require',
        'infinite'=> 'bool|require',
        'stock'=> 'require'
    ];

}