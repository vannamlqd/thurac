<?php
namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Session;
use Validator;

class UserController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }
    //list
    public function listUser(){
        $users = User::orderby('id','desc')->get();
        return view('User::listuser',['users'=>$users]);
    }
    //del
    public function delUser($id){
        User::destroy($id);
        return redirect()->route('listUser')->with(['bag_message'=>'Xóa tài khoản thành công','bag_level'=>'success']);
    }
    //add
    public function addUser(){

        return view('User::adduser');
    }
    public function postadduser(Request $request){
        $this->validate($request,
           [
                'email'=>'required|email|unique:users',
                'password'=>'required',
                'name'=>'required'
            ],
            [
                'email.required'=>'Email trống',
                'email.email' => 'Email sai định dạng',
                'email.unique' => 'Email đã tồn tại',
                'password.required'=>'Mật khẩu trống',
                'name.required'=>'Họ tên trống',
            ]
            );
        // echo $request->type;die;
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "type" => $request->type,
        ];
        // dd($data);
        try{
            User::create($data);
            return redirect()->route('listUser')->with(['bag_message'=>'Thêm tài khoản thành công','bag_level'=>'success']);
        }
        catch(Exception $e){
          return redirect()->route('listUser')->with(['bag_message'=>'Thêm tài khoản thất bại','bag_level'=>'danger']);   
        }
    }
    public function login(){
        if (Auth::check()) {
            return redirect()->route('indexEmail');
        }
        return view('User::login');
    }
    public function postlogin(Request $request){
        $this->validate($request,
           [
                'txtEmail'=>'required|email',
                'txtPassword'=>'required'
            ],
            [
                'txtEmail.required'=>'Email trống',
                'txtEmail.email' => 'Email sai định dạng',
                'txtPassword.required'=>'Mật khẩu',
            ]
            );
        $email = $request->txtEmail;
        $password = $request->txtPassword ;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            session(['user' => Auth::user()]);

            return redirect()->route('dashboar');
        }
        else
            return redirect()->back()->with(['bag_message'=>'Tài khoản không tồn tại','bag_level'=>'danger']);

    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login');
    }
   

}