<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SanitizeController;
use Illuminate\Routing\UrlGenerator;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserResetPasswordMail;
Use Symfony\Component\HttpFoundation\Response;
Use DB;
use Carbon\Carbon;



class AuthController extends Controller
{
    //

    //
    public $loginAfterSignUp = true;
     protected $user;
     protected $base_url;
    public function __construct(UrlGenerator $url){
        $this->middleware("auth:users",['except'=>['login','register','sendResetPasswordLink']]);
        $this->user = new User;
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'firstname' => 'required|string',
        'lastname'=>'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
                ]
    );
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

   $check_email = $this->user->where("email",$request->email)->count();

   if($check_email!=0){
      return response()->json([
        "success"=>false,
        "message"=>"email is already taken",
       ],400);
   }
   
   $this->user::create(
            [
            'firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'email'=>$request->email,
             'password'=>Hash::make($request->password),
            ]);
           
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
        return response()->json([
            'success' => true,
            'data' => $user,
            'expires_in'=>auth("users")->factory()->getTTL() * 60 * 24 * 30,
        ], 200);

        }

    public function login(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password'), 
        ['email' => 'required|email',
        'password' => 'required|string|min:6']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
    
         $input = $request->only("email","password");

        $jwt_token = null;
 
        if (!$jwt_token = auth('users')->attempt($input)) {
            return response()->json([
                'success' => false,
            'message' => 'Invalid Email or Password',
            ], 401);
        }
      
     //   $token_time_frame = auth("users")->factory()->setTTL(NULL);

          $user = auth("users")->authenticate($request->token);
          $id = $user->id;
          return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'expires_in'=>auth("users")->factory()->getTTL(),
            'id'=>$id
        ]);
    }
  

    public function sendResetPasswordLink()
    {

    }

}
