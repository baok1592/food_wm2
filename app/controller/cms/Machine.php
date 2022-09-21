<?php


namespace app\controller\cms;

use app\model\Machine as MachineModel;
use app\validate\MachineValidate;
use ruhua\bases\BaseController;

class Machine extends BaseController
{
    //获取打印机配置
    public function getAll(){

        $res = MachineModel::get_feie();
        return app('json')->go($res);
    }
    //添加打印机配置
    public function add(){
        $post =input('post.');
        $res = MachineModel::add_feie($post);
        return app('json')->go($res);
    }
    //修改打印机配置
    public function up(){
        $post = input('post.');
        $res = MachineModel::edit_feie($post);
        return app('json')->go($res);
    }
    //删除打印机配置
    public function del($id){
        $res = MachineModel::where('id',$id)->delete();
        return app('json')->go($res);
    }

}