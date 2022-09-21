<?php

namespace app\controller\mobile;

use app\model\Category as CategoryModel;
use app\model\MbDiy as MbDiyModel;
use think\facade\View;

class Lists extends MobileController
{
    public function index()
    {
        $json = MbDiyModel::where('id',2)->value('json');
        $tmpArr = json_decode($json, true);
        $data=[];
        if($tmpArr && count($tmpArr)>0) {
            $data = MbDiyModel::setMbPageData(2, $tmpArr);
        }
        return app('json')->go($data);
    }
}