<?php


namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use think\facade\Db;

class Address extends BaseModel
{

    /**
     * 录入地址
     * @return array
     */
    public static function enterAddress($post){
        $arry=explode('@',$post['name']);
        if ($post['pid'] == "0") {
            Db::startTrans();// 启动事务
            try {
                foreach ($arry as $item) {
                    $data['pid'] = 0;
                    $data['name'] = $item;
                    $data['merger_name'] = $item;
                    $data['level'] = 1;
                    self::create($data);
                }
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback(); // 回滚事务
                throw new BaseException(['msg'=>$e->getMessage()]);
            }
            return true;
        }

        $pname=self::where('id',$post['pid'])->value('name');
        if(!$pname){
            throw new BaseException('没有这个一级地址！');
        }
        Db::startTrans();// 启动事务
        try {
            foreach ($arry as $item) {
                $data['pid']=$post['pid'];
                $data['name']=$item;
                $data['merger_name']=$pname.",".$item;
                $data['level']=2;
                self::create($data);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback(); // 回滚事务
            throw new BaseException(['msg'=>$e->getMessage()]);
        }
        return true;
    }

    public static function upAddress($post)
    {
        $one= self::where(['id'=>$post['id']])->find();
        $pname=self::where(['id'=>$one->pid])->value('name');
        $pname=$pname.",".$post['name'];
        $res=self::where(['id'=>$post['id']])->save(['name'=>$post['name'],'merger_name'=>$pname]);
        return $res;
    }

    public static function delAddress($id)
    {
        $one= self::where(['id'=>$id])->find();
        if($one['pid']==0){
            self::where(['pid'=>$id])->delete();
        }
        self::where(['id'=>$id])->delete();
    }
}
