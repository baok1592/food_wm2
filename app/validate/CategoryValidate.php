<?php


namespace app\validate;


use ruhua\bases\BaseValidate;
use think\Validate;

class CategoryValidate extends BaseValidate
{
    protected $rule = [
        'name'=> 'require|min:2',
        'img_id'=> 'require|number'
    ];
}