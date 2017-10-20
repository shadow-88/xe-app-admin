<?php
namespace app\admin\controller;
use think\Controller;
class Login extends Controller {
	public function login() {
		return $this -> fetch();
	}
	public function test() {
		if ( request() -> isPost()) {
			//查找是否存在用户
			$res = db('acc')->where('acc',$_POST['acc'])->find();
			//用户名，密码不正确的返回
			if(!$res){
				return $res;
			}
			//将用户信息存入session
			session('admin.acc',$res);
			return TRUE;
		}
	}
}
