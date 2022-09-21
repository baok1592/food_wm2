<?php


namespace app\validate;



use ruhua\bases\BaseValidate;

class MachineValidate extends BaseValidate
{
    protected $rule = [
        "feie_user"=> 'require',
        "feie_ukey"=> 'require',
        "feie_sn"=> 'require',
        "feie_name"=> 'require',
        "feie_key"=> 'require',
        "is_printer"=> 'require|bool'
    ];

}