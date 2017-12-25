<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index($name = '',$age = ''){
        $data['name'] = 'genglintong';
        $data['sex'] = 'man';
        $data['age'] = '22';
        
        //$name = $this->__get($name);
        
        //$sex = I(post.sex,'a');
        //$age = I(get.age,'a');
        //echo $name."\n";
        if ($name != ''){
            $data['name'] = $name;
        }
        
        if ($sex != ''){
            $data['sex'] = $age;
        }
        
        $this->ajaxReturn($data);
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>[ 您现在访问的是Home模块的Index控制器 ]</div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function test(){
        $User  = M("User"); //实例化User对象
        $fields['name'] = 'genglintong';
        
        //查询
        $data = $User->where($fields)->select();
        //echo $User->count();
        //var_dump($data);
        
        $this->ajaxReturn($data);
    }
    
    /**
     * login 用户注册
     * @param name  用户名
     * @param password  用户密码
     * @param is_sale  是否卖/买家  默认买家
     * @return  boolean  是否成功
     */
    public function login($name , $password , $is_sale = 0){
        $data['name'] = $name;
        $data['password'] = md5($password);
        $data['is_sale'] = $is_sale;
        
        $User = M('user');
        
        $log =  $User->add($data);
        //   正常返回主键id   错误则返回false
        
        $this->ajaxReturn($log);
    }
    
    /** logout  用户登录验证
     * @param name 用户名
     * @param password 密码
     * @return  boolean
     */
    public function logout($name , $password){
        $User  = M("User"); //实例化User对象
        $fields['name'] = $name;
        $fields['password'] = md5($password);
        //查询
        $data = $User->where($fields)->getField('name,vol,is_sale');
        
        //var_dump();
        if($data != false){
            $this->ajaxReturn($data);
        }else{
            $this->ajaxReturn(false);
        }
    }
}