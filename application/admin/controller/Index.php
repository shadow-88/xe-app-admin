<?php
namespace app\admin\controller;

class Index extends Common{
	protected $autoWriteTimestamp = true;
	//跳转到默认的功能
	public function index(){
		return $this->redirect('index/'.json_decode(session('admin.acc')['func'])[0]->url);
    }
	//上传功能
	public function upload(){
		if ( request() -> isPost()) {
			$file = request()->file('file');
		    $info = $file->rule('md5')->move(ROOT_PATH . 'public' . DS . 'uploads');
		    if($info){
		    	return ['code'=>0,'msg'=>'图片上传成功','url'=>$info->getSaveName()];
		    }else{
		        return ['code'=>1,'err'=>$file->getError()];
		    }
		}
    }
//统一操作
	public function updata(){
		if ( request() -> isPost() && $_POST['id']) {
			$res = db($_POST['table'])->where('id',$_POST['id'])->setField('data',$_POST['data']);
		}else if( request() -> isPost()){
			switch ($_POST['table']) {
				case "home":
					$data = ['area' => session('admin.acc')['area'],'data' => $_POST['data'],'by'=>session('admin.acc')['id'].'&'.time()];
					break;
				case "chan":
					$data = ['s_id' => $_POST['s_id'],'data' => $_POST['data'],'by'=>session('admin.acc')['id'].'&'.time()];
					break;
				default:
					$data = ['data' => $_POST['data'],'by'=>session('admin.acc')['id'].'&'.time()];
			}
			$res = db($_POST['table'])->insert($data);
		}
		return $res;
    }
	public function changetype(){
		if ( request() -> isPost()) {
			$res = db($_POST['table'])->where('id',$_POST['id'])->setField('type',$_POST['type']);
			return $res;
		}
    }
	public function changeweight(){
		if ( request() -> isPost()) {
			$res = db($_POST['table'])->where('id',$_POST['id'])->setField('weight',$_POST['weight']);
			return $res;
		}
    }
	public function changeallweight(){
		if ( request() -> isPost()) {
			$res = db($_POST['table'])->where('id',$_POST['id'])->setField('allweight',$_POST['weight']);
			return $res;
		}
    }
//网站设置
	public function setup(){
		return $this->fetch();
    }
//首页设置
	public function home(){
		return $this->fetch();
    }
	public function homelist(){
		$type=$_GET['type'];
		$res=db('home')->field('id,name,img,data,weight,type')->where('type','between',$type)->where('area',session('admin.acc')['area'])->order([/*'weight'=>'desc',*/'update'=>'desc'])->page($_GET['page'],$_GET['limit'])->select();
		$count=db('home')->field('count(*)')->where('type','between',$type)->where('area',session('admin.acc')['area'])->select();
		if($res && $count){
			return ['code'=>0,'count'=>$count[0]['count(*)'],'data'=>$res];
		}else{
			return ['code'=>1,'msg'=>"无数据"];
		}
    }
//商家管理
	public function seller(){
		return $this->fetch();
    }
	public function sellerlist(){
		$type=$_GET['type'];
		$key=isset($_GET['key'])?$_GET['key']:"";
		$res=db('seller')->field('id,data,weight,type')->where('type','between',$type)->where('data','like','%'.$key.'%')->order([/*'weight'=>'desc',*/'update'=>'desc'])->page($_GET['page'],$_GET['limit'])->select();
		$count=db('seller')->field('count(*)')->where('type','between',$type)->where('data','like','%'.$key.'%')->select();
		if($res && $count){
			return ['code'=>0,'count'=>$count[0]['count(*)'],'data'=>$res];
		}else{
			return ['code'=>1,'msg'=>"无数据"];
		}
    }
//产品管理
	public function chan(){
		return $this->fetch();
    }
	public function chanlist(){
		//s_id
		$type=$_GET['type'];
		$key=isset($_GET['key'])?$_GET['key']:"";
		$res=db('chan')->field('id,data,weight,type')->where('type','between',$type)->where('data','like','%'.$key.'%')->order([/*'weight'=>'desc',*/'update'=>'desc'])->page($_GET['page'],$_GET['limit'])->select();
		$count=db('chan')->field('count(*)')->where('type','between',$type)->where('data','like','%'.$key.'%')->select();
		if($res && $count){
			return ['code'=>0,'count'=>$count[0]['count(*)'],'data'=>$res];
		}else{
			return ['code'=>1,'msg'=>"无数据"];
		}
    }
	public function getchan(){
		//s_id
		$type=$_POST['type'];
		$res=db('chan')->field('id,data')->where('type','between',$type)->order(['update'=>'desc'])->select();
		if($res){
			return ['code'=>0,'data'=>$res];
		}else{
			return ['code'=>1,'msg'=>"无数据"];
		}
    }
	public function order(){
		return $this->fetch();
    }
}
