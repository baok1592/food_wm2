<?php


namespace app\model;


use app\validate\MachineValidate;
use ruhua\bases\BaseModel;

class Machine extends BaseModel
{

    //添加打印机配置
    public static function add_feie($post){
        $validate=new MachineValidate();
        $validate->goCheck();
        $form=$validate->getDataByRule($post);
        $res = self::create($form);
        return $res;
    }

    //修改打印机配置
    public static function edit_feie($post){
        $validate=new MachineValidate(['id']);
        $validate->goCheck();
        $form=$validate->getDataByRule($post);
        unset($form['id']);
        $res= self::where('id',$post['id'])->save($form);
        return $res;
    }

    //获取打印机配置
    public static function get_feie(){
        $data = self::select();
        return $data;
    }


    //打印小票
    public function FeieDy($order){
        $data1 = self::select();
        foreach ($data1 as $key=>$value) {
            $config = [
                'user' => $value['feie_user'],
                'ukey' => $value['feie_ukey'],
                'key' => $value['feie_key'],
                'sn' => $value['feie_sn'],
                'name' => $value['feie_name'],
                'is_printer' => $value['is_printer'],
                'feie_formwork_id' => $value['feie_formwork_id'],
                'times'=>$value['times'],
            ];
        }
        (new FeieDy($config))->printMsg($order, 1);
    }
}
