<?php


namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\CmsException;

class Form extends BaseModel
{

    public static function add($param)
    {
        $data= self::filter($param);
        $res = self::create($data);
        if(!$res){
            throw new CmsException(['msg'=>'表单添加失败']);
        }
    }

    public static function up($param)
    {
        $id=$param['id'];
        $model =self::find($id);
        if(!$model){
            throw new CmsException(['msg'=>'表单不存在']);
        }
        if(!$param['title'] || !$param['form'][0]){
            throw new CmsException(['msg'=>'表单参数错误']);
        }
        $data=self::filter($param);
        $res=$model->save($data);
        if(!$res){
            throw new CmsException(['msg'=>'表单添加失败']);
        }
    }

    private static function filter($param)
    {
        if(!$param['title'] || !$param['form'][0]){
            throw new CmsException(['msg'=>'表单参数错误']);
        }
        $data['title']=$param['title'];
        $newArr=[];
        foreach ($param['form'] as $v){
            if(!$v['name'] || trim($v['name'])=="") {
                continue;
            }
            $newArr[]=$v;
        }

        $data['json']=json_encode( $newArr,JSON_UNESCAPED_UNICODE);;
        return $data;
    }
    public function getJsonAttr($v)
    {
        return json_decode($v,true);
    }
}