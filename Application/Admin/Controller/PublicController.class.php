<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {
    public function index(){
        $this->display();
    }
    public function login(){
        $this->display("index");
    }
    //验证登陆表单
    public function checkLogin(){
        $username=I('username','');
        $password=I('password','');
        $verify_code=I('verify','');
        if($username==''||$password==''||$verify_code==''){
            $this->redirect("Public/index");
        }
        if(!$this->_verifyCheck($verify_code)){
            $this->error("验证码错误！！！");
        }
        $user=M('user')->where(array('username'=>$username))->find();
        if(!$user||md5($password)!=$user['password']){
            $this->error("用户名或密码错误！！！");
        }
        if(!$user['status']){   //status为0时表示锁定
            $this->error("用户被锁定！！！");
        }else{
            $data['last_login_ip'] =  get_client_ip();
            $data['last_login_time']=time();
            if(M("user")->where(array('id'=>$user['id']))->save($data)){
                M("user")->where(array('id'=>$user['id']))->setInc("login_num");
            }
            //dump($data);
            session(C('USER_AUTH_KEY'),$user['id']);
            session('uname',$user['username']);
            //session('myInfo',$user);
            //dump($user);die;
            $this->success("登录成功...",U("Index/index"));
        }
    }
    //登出
    public function logout(){
        if($_SESSION[C('USER_AUTH_KEY')]) {
            session_destroy();
            $this->redirect('Public/index');
        }else {
            $this->error('已经登出！','Public/index');
        }
    }
    public function verify(){
        $config = array(
            'fontSize'    =>    20,     // 验证码字体大小
            'length'      =>    2,      // 验证码位数
            'useNoise'    =>   false,  // 关闭验证码杂点
            'useCurve'    =>   false,  //是否使用混淆曲线
            'imageH'    =>  40,          // 验证码图片高度
            'imageW'    =>  470,          // 验证码图片宽度
        );
        $Verify =new \Think\Verify($config);
        $Verify->entry();
    }
    //验证验证码
    private function _verifyCheck($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
}