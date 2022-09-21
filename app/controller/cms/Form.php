<?php


namespace app\controller\cms;

use app\model\Form as FormModel;
use app\validate\ADValidate;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use ruhua\exceptions\CmsException;


class Form extends BaseController
{


    //获取所有
    public function getAll()
    {
        $data=FormModel::withoutField('json')->order('id asc')->select();
        return app('json')->go($data);
    }



    //添加
    public function add()
    {
        $param = $this->request->param();
        FormModel::add($param);
        return app('json')->go();
    }

    //删除
    public function del($id)
    {
        (new IDPostiveInt)->goCheck();
        FormModel::destroy($id);
        return app('json')->go();
    }

    //更新
    public function up()
    {
        $param = $this->request->param();
        FormModel::up($param);
        return app('json')->go();
    }

    //某表单详情
    public function getDetail($id)
    {
        (new IDPostiveInt)->goCheck();
        $data=FormModel::find($id);
        return app('json')->go($data);
    }
    //所有表单数据
    public function getDataAll($page=1,$size=10)
    {

    }

    //某条数据
    public function getDataItem($id)
    {

    }


}