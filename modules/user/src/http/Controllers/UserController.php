<?php
namespace Modules\user\src\http\Controllers;
use Modules\user\src\http\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller{
    public function index(){
        return view('user::lists');
    }
    public function detail($id){
        return 'detail' .$id;
    }
    public function create(){
        $user= new User();
        $user->name = 'John Doe';
        $user->email = 'john.doe@example.com';
        $user->password = Hash::make('123123');
        $user->save();
        return 'ok';
    }
}