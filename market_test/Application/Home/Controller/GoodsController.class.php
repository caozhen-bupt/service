<?php
namespace Home\Controller;
use Think\Controller;

class GoodsController extends Controller{
    
    /**
     * 搜索接口
     * @param number $pn  pagenum  默认为0
     * @param string $type  类型   为空则表示不按照类型展示
     * @param string $title  按照title模糊搜索  为空则表示不按照标题搜索
     * @param number $price  0  不按照价格搜索   -1  从低到高   -2  从高到低    >0   表示大于$title的商品
     * @param number $vol  0  不按照余量搜索   1  从低到高   2  从高到低
     */
    public function search($pn = 0,$type = "",$title = "",$price = 0,$vol = 0){
        //实例化  Goods
        $Goods = M('Goods');
        
        $fields = array();
        $order = array();
        
        //$fields['pn'] = $pn;
        
        if ($type != ""){
            $fields['type'] = array('eq',$type);
        }
        if ($title != ""){
            $fields['title'] = array('like',"%".$title."%");
        }
        if ($price == -1){
           //从低到高排序
           $order['price'] = 'asc';
        }elseif ($price == -2){
            //从高到低排序
            $order['price'] = 'desc';
        }elseif ($price > 0){
            $fields['price'] = array('gt',$price);
        }
        if ($vol == -1){
            //从低到高排序
            $order['vol'] = 'asc';
        }elseif ($vol == -2){
            //从高到低排序
            $order['vol'] = 'desc';
        }elseif ($vol > 0){
            $fields['vol'] = array('gt',$vol);
        }
        
        $sql = $Goods->where($fields)->order($order)->limit($pn,10)->buildSql();
        $data = $Goods->where($fields)->order($order)->limit($pn,10)->getField('id,title,price,overplus,desc,user_id,type');
        
        //echo $sql;
        $serchData['sql'] = $sql;
        $serchData['data'] = $data;
        
        $this->ajaxReturn($serchData);
    }
}
