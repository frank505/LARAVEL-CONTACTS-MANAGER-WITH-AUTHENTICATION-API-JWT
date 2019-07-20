<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Contacts;
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



class ContactController extends Controller
{
    //
    protected $contacts;
  public function __construct(UrlGenerator $url)
  {
    $this->middleware("auth:users",['except'=>['addContact']]);
    $this->contacts = new Contacts;
    
  }


  public function addContact(Request $request)
  {
    $validator = Validator::make($request->all(),
        [
            'firstname' => 'required|string',
        'phonenumber' => 'required',
                ]
    );


    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
    
      $profile_picture = $request->file("files");

      return response()->json([
        "success"=>false,
        "message"=>$profile_picture,
       ],200);    
    
    //   if($profile_picture==NULL){
    //       $file_name = "default-avatar.png";
    //   }else{
    //       $file_extension = $profile_picture->getClientOriginalExtension();
    //       //return $file_extension;
    //       $file_name = uniqid()."_".time().date("Ymd")."_IMG.".$file_extension; //change file name
    //       $task_dir = "/contact_images"; //directory for the image to be uploaded
    //       $profile_picture->move($task_dir, $file_name); //more like the move_uploaded_file in php except that more modifications
    //   }
        
    //   $this->contacts->firstname = $request->firstname;
    //   $this->contacts->phonenumber = $request->phonenumber;
    //   $this->contacts->image_file =  $file_name;
    //   $this->contacts->lastname = $request->lastname;
    //   $this->contacts->email = $request->email;
    //   $this->contacts->save();
    //   // $this->contacts::create([
    //   //     'firstname'=>$request->firstname,
    //   //     'phonenumber'=>$request->phonenumber,
    //   //     'image_file'=>$file_name,
    //   //     'lastname'=>$request->lastname,
    //   //     'email'=>$request->email,
    //   // ]);
      
    //   return response()->json([
    //     'success' => true,
    //     'data' => 'contact saved successsfully',
    // ], 200);

  }



}
