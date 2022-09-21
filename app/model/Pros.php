<?php


namespace app\model;


use app\model\Category as CategoryModel;
use app\service\TokenService;
use ruhua\bases\BaseModel;
use ruhua\exceptions\CmsException;
use ruhua\exceptions\PcException;
use think\facade\Db;
use think\model\concern\SoftDelete;

class Pros extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden=['type'];

    public function category(){
        return $this->belongsTo('Category','category_id','category_id');
    }


    //关联规格
    public function sku()
    {
        return $this->hasMany('ProsSku', 'goods_id', 'id');
    }



    public function img()
    {
        return $this->belongsTo('Image','img_id','id')->bind(['url']);
    }

    //文章或图集
    public static function getAll()
    {
        $data=self::with(['img','sku'])->hidden(['img','content','delete_time','uniacid'])
            ->order('id desc')->select()->toArray();
        foreach ($data as $k=>$v){
            if($v['sku'] && $v['sku'][0]) {
                $str=$v['sku'][0]['json'];
                $arr = json_decode($str,true);
                $data[$k]['sku'] =$arr['list'];
            }
        }
        return $data;
    }

    //某栏目下的商品
    public static function getCategoryPros($cid=null)
    {
        $where[]=['is_hidden','=',0];
        if($cid) {
            $where[]=['category_id','=',$cid];
        }
        $data = self::with(['img'])->where($where)->withoutField('img,content,is_top,is_hot')->select();
        return $data;
    }

    //某标签下所有商品
    public static function getProsbyTag($tag)
    {
        $where['is_hidden']=0;
        if($tag=='top'){
            //新品
            $where['is_top']=1;
        }
        if($tag=='hot'){
            $where['is_hot']=1;
        }
        $data=self::with(['img','sku'])->where($where)->withoutField('img,content,is_top,is_hot')->select();
        return $data;
    }

    //搜索商品
    public static function searchPro($key)
    {
        $where[]=['is_hidden','=',0];
        if($key != ""){
            $where[]=['title','like','%'.$key.'%'];
        }
        $data=self::with(['img','sku'])->where($where)->withoutField('img,content,is_top,is_hot')->select();
        return $data;
    }

    //old所有推荐
    public static function getProsbyTop($cid=0)
    {
        $where['is_top']=1;
        if($cid>0){
            $where['category_id']=$cid;
        }
        $data=self::with('img')->where($where)->withoutField('img,content')->select();
        return $data;
    }


    public static function createPros($form)
    {
        if(count($form['img_ids'])<1){
            throw new CmsException(['msg'=>'图片错误']);
        }
        $form['img_id']=$form['img_ids'][0];
        $form['img_ids']=implode(',',$form['img_ids']);
        $sku = false;
        if($form['sku'] && count($form['sku'])>0) {
            $sku = $form['sku'];
        }
        unset($form['sku']);
        Db::startTrans();// 启动事务
        try{
            $res = self::create($form);
            if ($sku) {
                (new SkuModel())->addSku($res['id'], $sku);//添加sku
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback(); // 回滚事务
            throw new CmsException(['msg'=>'添加失败:'.$e->getMessage()]);
        }
    }

    public static function updatePros($form)
    {
        $id=$form['id'];
        unset($form['id']);
        $Pros =self::find($id);
        if(!$Pros){
            throw new CmsException(['msg'=>'文章不存在']);
        }
        if(count($form['img_ids'])<1){
            throw new CmsException(['msg'=>'图片错误']);
        }
        $form['img_id']=$form['img_ids'][0];
        $form['img_ids']=implode(',',$form['img_ids']);
        $Pros->save($form);

        $sku=$form['sku'];
        unset($form['sku']);
        Db::startTrans();// 启动事务
        try{
            $Pros->save($form);
            if (is_array($sku) && count($sku)>0) {
                (new SkuModel())->editSku($id, $sku);//添加sku
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback(); // 回滚事务
            throw new CmsException(['msg'=>'添加失败:'.$e->getMessage()]);
        }
    }

    //商品详情
    public static function getProsContent($id)
    {
        $data=self::with(['img','sku'])->hidden(['img','aid'])->where('id',$id)->find()->toArray();
        if(!$data){
            throw new CmsException(['msg'=>'文章不存在']);
        }
        if($data['sku'] && $data['sku'][0]) {
            $str=$data['sku'][0]['json'];
            $data['sku'] = json_decode($str,true);
        }
        $data['urls']=Image::getIds($data['img_ids']);
        $data['img_ids']=explode(",",$data['img_ids']);
        return $data;
    }


    public static function getCidAll($id)
    {
        //where in 可以是数组，也可以是字符串
        $data=self::with('img')->hidden(['img'])->where('category_id',$id)->select()->toArray();
        if(!$data){
            throw new PcException(['msg'=>'产品不存在']);
        }
        return $data;
    }

    //某栏目下所有推荐/热门/最新
    public static function getCidIs($id,$type="hot")
    {
        $where[]=['category_id','=',$id];
        if($type=='hot') {
            $where[] = ['is_hot', '=', 1];
        }
        if($type=='new') {
            $where[] = ['is_new', '=', 1];
        }
        if($type=='top') {
            $where[] = ['is_top', '=', 1];
        }
        //where in 可以是数组，也可以是字符串
        $data=self::with('img')->hidden(['img'])->where($where)->select()->toArray();
        return $data?:[];
    }

}