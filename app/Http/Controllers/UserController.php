<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JWTAuth;

class UserController extends Controller
{
    //

    public function __construct(){
        $this->middleware("auth:api",["except" => ["login", "register"]]);
    }

    public function register(Request $request){

        try{// ລະບົບທຳການຄຳນວນ-ເຮັດວຽກ

            // ການດຶງຂໍ້ມູນ email ມາກວດຊອມວ່າຊ້ຳກັນບໍ່
            $check_email = User::where("email", $request->email);
            
            if($check_email->count()){
                $success = false;
                $message = "ອີເມວລ໌ນີ້ ".$request->email." ໄດ້ເຄີຍລົງທະບຽນແລ້ວ!";
            } else {
                
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = hash::make($request->password);
                $user->save();

                $success = true;
                $message = "ລົງທະບຽນສຳເລັດ!";
            }
         

        } catch (\Illuminate\Database\QueryException $ex){ 
            // ດຶງຂໍ້ຄວາມ error ຈາກລະບົບອອກມາ
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message,
        ];

        return response()->json($response);

    }

    public function login(Request $request){

        $user_login = [
            "email"=>$request->email,
            "password"=>$request->password
        ];

        $token = JWTAUTH::attempt($user_login);
        $user = Auth::user();

        if(!$token){
            return response()->json([
                "success" => false,
                "message" => "ອີເມວລ໌ ຫຼືລະຫັດຜ່ານ ບໍ່ຖຶກຕ້ອງ!"
            ]);
        } else {
            return response()->json([
                "success" => true,
                "message" => "ເຂົ້າສູ່ລະບົບສຳເລັດ!",
                "user" => $user,
                "authorisation" => [
                    "token" => $token,
                    "type" => "bearer"
                ]
            ]);
        }

    }

    public function logout(){
  
        // Auth::logout();
        $token = Auth::getToken();
        $invalidate = Auth::invalidate($token);

        if($invalidate){
            return response()->json([
                "success" => true,
                "message" => "ອອກຈາກລະບົບສຳເລັດ!"
            ]);
        }
    }
}
