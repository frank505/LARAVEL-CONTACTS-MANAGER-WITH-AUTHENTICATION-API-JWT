<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    protected $base_url;
  public function __construct(UrlGenerator $url)
  {
    $this->middleware("auth:users");
    $this->contacts = new Contacts;
    $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
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
         "message"=> "one or more fields are either missing or invalid expected type entered
         please ensure to enter email or numbers where required",
         "validator_err_message"=>$validator->messages()->toArray(),
         "data"=>$request->all()
        ],400);    
      }
    $profile_picture = $request->profile_image;
     $file_name = "";
     if($profile_picture==null)
     {
        $file_name = "default-avatar.png";
     } else{
       $generated_name = uniqid()."_".time().date("Ymd")."_IMG"; //change file name
       $base64Image = $profile_picture;
       $fileBin = file_get_contents($base64Image);
       $mimeType = mime_content_type($base64Image);
       if("image/png"==$mimeType)
       {
        $file_name = $generated_name.".png";
       }else if("image/jpg"==$mimeType)
       {
        $file_name = $generated_name.".jpg";
       }else if("image/jpeg"==$mimeType)
       {
        $file_name = $generated_name.".jpeg";
       }else{
        return response()->json([
          "success"=>false,
          "message"=>"only png, jpg and jpeg files are accepted"
        ],400);
       }
     } 

     
        
      $this->contacts->firstname = $request->firstname;
      $this->contacts->phonenumber = $request->phonenumber;
      $this->contacts->image_file =  $file_name;
      $this->contacts->lastname = $request->lastname;
      $this->contacts->email = $request->email;
      $this->contacts->save();
      
      if($profile_picture==null){

      }else{
        file_put_contents("./profile_images/".$file_name,$fileBin);
      }
     
      return response()->json([
        'success' => true,
        'message' => 'contact saved successsfully',
    ], 200);

  }



  public function getPaginatedData($pagination=null,Request $request)
  {
    $file_directory = $this->base_url."/profile_images";
  //laravel automatically converts it to json and sends a response text too
  //$auth = auth("admins")->authenticate($request->token);
  if($pagination==null || $pagination==""){
      $contacts =  $this->contacts->get()->toArray();
      return response()->json([
          'success'=>true,
          'data'=>$contacts,
          'file_directory'=>$file_directory
      ],200);
      
  }
      $paginated_contacts =  $this->contacts->paginate($pagination);
      return response()->json([
          'success' => true,
           'data'=>$paginated_contacts,
           'message'=>"data fetched successfully",
           "file_directory"=>$file_directory
      ], 200);  
  }


  public function searchData(Request $request,$search,$pagination=null)
  {
    $file_directory = $this->base_url."/profile_images";

  //laravel automatically converts it to json and sends a response text too
  //$auth = auth("admins")->authenticate($request->token);
  if($pagination==null || $pagination==""){
      $contacts =  $this->contacts::where("firstname", "LIKE", "%$search%")->
      where("email","LIKE","%%")->where("phonenumber","LIKE","%%")->
      get()->toArray();
      return response()->json([
          'success'=>true,
          'data'=>$contacts,
          'file_directory'=>$file_directory
      ],200);
      
  }
      $paginated_contacts =  $this->contacts::where("firstname", "LIKE", "%$search%")->
      orWhere("email","LIKE","%$search%")->orWhere("phonenumber","LIKE","%$search%")->paginate($pagination);
      return response()->json([
          'success' => true,
           'data'=>$paginated_contacts,
           'message'=>"data fetched successfully",
           "file_directory"=>$file_directory
      ], 200);  
  }





  //end of this class
}


