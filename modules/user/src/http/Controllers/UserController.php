<?php
namespace Modules\user\src\http\Controllers;
use App\Http\Controllers\Controller;
class UserController extends Controller{
    public function index(){
        return view('user::lists');
    }
    public function detail($id){
        return 'detail' .$id;
    }
}