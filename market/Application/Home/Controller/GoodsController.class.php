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
    public function searchGoods($pn = 0,$type = "",$title = "",$price = 0,$vol = 0){
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
        
        $data = $Goods->where($fields)->order($order)->limit($pn,10)->getField('id,title,price,overplus,intro,user_id,type');
        $sql = $sql = $Goods->getLastSql();
        
        //echo $sql;
        $serchData['sql'] = $sql;
        $serchData['data'] = $data;
        
        $this->ajaxReturn($serchData);
    }
    
    /**
     * 添加商品
     * 
     */
    public function addGoods($title = "test" ,$price = 90,$vol = 1,$desc = "test",$userId =1){
    	//实例化  Goods
    	$Goods = M('Goods');
    	$data = array();
    	$data['title'] = $title;
    	$data['price'] = $price;
    	$data['overplus'] = $vol;
    	$data['intro'] = "test";
    	$data['user_id'] = $userId;
    	$data['type'] = "衣";
    	
    	//插入数据库
    	//$sql = $Goods->add($data)->buildSql();
    	$result = $Goods->add($data);
    	$sql = $Goods->getLastSql();
    	$addData['sql'] = $sql;
    	if ($result){
    		$addData['id'] = $result;
    		$addData['message'] = "插入成功";
    	}else{
    		$addData['id'] = -1;
    		$addData['message'] = "插入失败";
    	}
    	$this->ajaxReturn($addData);
    }
}
