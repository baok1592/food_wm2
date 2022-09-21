<?php

namespace app\controller\mobile;

use app\model\Category as CategoryModel;
use app\model\Pros as ProsModel;

class Pros extends MobileController
{
    //所有分类
    public function allCategory()
    {
        $data= CategoryModel::with('img')->field('id,name,img_id,sort')
            ->order('sort asc')->select()->toArray();;
        return app('json')->go($data);
    }

    //某分类下所有商品
    public function prosByCategory($cid)
    {
        $data=ProsModel::getCategoryPros($cid);
        return app('json')->go($data);
    }

    //某标签的所有商品
    public function prosByTag($tag="hot")
    {
        $data=ProsModel::getProsbyTag($tag);
        return app('json')->go($data);
    }

    //搜索商品
    public function search($key)
    {
        $data=ProsModel::searchPro(trim($key));
        return app('json')->go($data);
    }

    //商品详情
    public function detail($id)
    {
        $data=ProsModel::getProsContent($id);
        return app('json')->go($data);
    }
}