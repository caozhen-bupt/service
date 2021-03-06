<?php
namespace Home\Controller;
use Think\Controller;

class CarController extends BaseController{
    
    public function viewCar($carId = 1 ,$pn = 0){    
        $Car =M("Car");
        $Goods = D("Goods");
        
        $fields = array();
        $order = array();
        
        $car_id= $carId;
        //var_dump($car_id."tttt");
        if($car_id !=""){
        	//var_dump("tset");
            $fields['car_id'] = array('eq',$car_id);
        }
        
        $data = $Car->where($fields)->limit($pn*10,10)->getField('id,car_id,goods_id,nums');
        //$sql = $sql = $Car->getLastSql();
        //$viewData['sql'] = $sql;
        $carData = array();
        
        foreach ($data as $value){
        	$goodsData = $Goods->getGoods($value['goods_id']);
        	//var_dump($goodsData);
        	if ($goodsData) {
        		$value['goods'] = $goodsData;
        		$dataCar = array();
        		$dataCar['id'] = $value['id'];
        		$dataCar['name'] = $value['goods'][0]['title'];
        		$dataCar['price'] = $value['goods'][0]['price'];
        		$dataCar['nums'] = $value['nums'];
        		$carData[] = $dataCar;
        	}
        }
        $viewData['message'] = self::MESSAGESUCCSEE;
        $viewData['data'] = $carData;
        $this->ajaxReturn($viewData);
    }
    
    /**
     * 添加至购物车
     */
    public function addCar(){     
        $Car = M('Car');
        $data = array();
        
        $goods_id = I('post.goodsId');
        $car_id = I('post.carId');
        $nums = I('post.nums');
        
        //$goods_id = 1;
        //$car_id = 2;
        //$nums = 3;
        
        //对于0参数的判断
        if ($goods_id == 0 || $car_id == 0) {
        	$addData['id'] = -1;
            $addData['message'] = "failed";
            $this->ajaxReturn($addData);
        }
        
        $data['goods_id'] = $goods_id;
        $data['car_id'] = $car_id;
        $data['nums'] = $nums;
        
        $map['goods_id'] = $goods_id;
        $map['car_id'] = $car_id;
        $map['_logic'] = 'AND';
        
        //是否有记录
        $res = $Car->where($map)->getField('id,nums,car_id');
        //$sql =  $Car->getLastSql();
        //var_dump($res);
        if($res){
        	$res = current($res);
            $id = $res['id'];
            $oldnum = $res['nums'];
            $newData['nums'] = $oldnum + $nums;
            $fields['id'] = $id;
            $Car->where($fields)->save($newData);
            //$sql =  $Car->getLastSql();
            //var_dump($sql);
            $addData['id'] = $id;
            $addData['message'] = "successful";
        }else{
        	$result = $Car->add($data);
        	if ($result){
            	$addData['id'] = $result;
            	$addData['message'] = "successful";
        	}
        	else{
            	$addData['id'] = -1;
            	$addData['message'] = "failed";
        	}   
        }
        $this->ajaxReturn($addData);
    }
    
    public function deleteCar($carId = 0){       
        $fields = array();
        $Car = M('Car');
        
        $fields['id'] = $carId;
            
        $result = $Car->where($fields)->delete();
        
        if ($result != false && $result > 0) {
        	$deleteData['message'] = self::MESSAGEERROR;
        }else {
        	$deleteData['message'] = self::MESSAGESUCCSEE;
        }
        $this->ajaxReturn($deleteData);
    }
    
    /**
     * 修改购物车
     * @param number $carId
     * @param number $nums
     */
    public function updateCar($carId = 0,$nums = 0){
    	$Car = M("Car");
    	$fields['id'] = I('post.carId');
    	$data['nums'] = I('post.nums');
    	if ($nums >0) {
    		$result = $Car-> where($fields)->save($data);
			$sql = $Car->getLastSql(); 		
			$updateData['sql'] = $sql;
			if ($result) {
    			$updateData['message'] = self::MESSAGESUCCSEE;
    			$this->ajaxReturn($updateData);
    		}
    	}
    	$updateData['message'] = self::MESSAGEERROR;
    	$this->ajaxReturn($updateData);
    }
    
    public function purchase(){
    	$put=file_get_contents('php://input');
    	$postData=json_decode($put,true);
    	
    	$Car = D('Car');
    	$Goods = D('Goods');
    	$User = D("User");
        var_dump($postData);	
    	//第一步： 获取所有carID
    	$carData = $postData;
    //	$carData[] = 12;
    	foreach ($carData as $value){
    		//启动事务
    		$Car->startTrans();
    		$Goods->startTrans();
    		$User->startTrans();
    		
    		//第二步：修改余量
    		$carMessage = $Car->getCar($value);
    		$carMessage = current($carMessage);
    		$goods_id = $carMessage['goods_id'];
    		$fieldsGoods['id'] = $goods_id;
    	    $result1 = $Goods->where($fieldsGoods)->setDec('overplus',$carMessage['nums']);
    		var_dump($result1);
    		//第三步：完成交易
    		
    		$goodsMessage = $Goods->getGoods($goods_id);
    		$goodsMessage = current($goodsMessage);
    		$money = $carMessage['nums'] * $goodsMessage['price'];
    		
    		$fieldsUser1['user_id'] = $goodsMessage['user_id'];
    		$result2 = $User->where($fieldsUser1)->setInc('money',$money);
    		$fieldsUser2['user_id'] = $carMessage['car_id'];
    		$result3 = $User->where($fieldsUser2)->setDec('money',$money);
    		$result5 = $User->where($fieldsUser1)->setInc('vol');
    		$result6 = $User->where($fieldsUser2)->setInc('vol'); 
    		
    		var_dump($result2);
    		var_dump($result3);
    		
    		//第四步：清空购物车
    		$fieldsCar['id'] = $value;
    		$result4 = $Car->where($fieldsCar)->delete();
    		var_dump($result4);
    		//事务提交
    		if ($result1 && $result2 && $result3 && $result4 && $result5 && $result6) {
    			$Car->commit();
    			$User->commit();
    			$Goods->commit();
    			echo "success";
    		}else{
    			$Car->rollback();
    			$User->rollback();
    			$Goods->rollback();
    			echo "wrong";
    		}
    	}
    }
}
