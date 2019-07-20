<?php 


class User extends dbobject
{

   // protected $this;

   public function __construct()
   {
     
   }
    

//ajax request for login functionality
/**
 * this function requires the username and the password to be passed as an array to the login function 
 * @data = array('username','email)
 */
   public function login($data)
   {
    $username = $data['email'];
	$password = $data['password'];
    $member_details = $this->getcheckdetails($username,$password);
    if($member_details == 1)
    {
        //this means that the user check details exist
        echo json_encode(
            array(
                "response_code"=>0,
                "response_message"=>"login successful",
        "data"=>[],
        )  
        );
    }else if($member_details== 0){
        //this means that the user check details doesnt exist
       return json_encode(
           array(
            "response_code"=>1,
                "response_message"=>"login failed invalid username and password",
                "data"=>[],
           )
           );
    }
    else if($member_details==2)
    {
        return json_encode(
            array(
             "response_code"=>2,
                 "response_message"=>"Your user profile has been disabled",
                 "data"=>[],
            )
            );
    }else if($member_details==3)
    {
        
    }
    {

    }

   }

  //this is to create a user 
  /**
   * this function passes a @data param which is an array 
   *  
   */
   public function createUser($data)
   {
    if(isset($data['subbtn'])){
        /////////////////////////////////////////////////////////////////////////////////////////
                $username = $data['username'];
                $userpassword = $data['userpassword'];
                $firstname = $data['firstname'];
                $lastname = $data['lastname']." ".$data['middlename'];;
                $email = $data['email'];
                $phone = $data['phone'];
                $chgpword_logon = $data['chgpword_logon']!='1'?'0':$data['chgpword_logon'];
                $user_locked = $data['user_locked']!='1'?'0':$data['user_locked'];
                $user_disable = $data['user_disable']!='1'?'0':$data['user_disable'];
                $day_1 = $data['day_1']!='1'?'0':$data['day_1'];
                $day_2 = $data['day_2']!='1'?'0':$data['day_2'];
                $day_3 = $data['day_3']!='1'?'0':$data['day_3'];
                $day_4 = $data['day_4']!='1'?'0':$data['day_4'];
                $day_5 = $data['day_5']!='1'?'0':$data['day_5'];
                $day_6 = $data['day_6']!='1'?'0':$data['day_6'];
                $day_7 = $data['day_7']!='1'?'0':$data['day_7'];
                $override_wh = $data['override_wh']!='1'?'0':$data['override_wh'];
                $extend_wh = $data['extend_wh'];
                if($override_wh!='1') $extend_wh='';
                $role_id = $data['role_id'];
                $operation = $data['operation'];
                $role_id = $data['role_id'];
                $role_name = $this->getitemlabel('role','role_id',$role_id,'role_name');
                // $insurance_coy = $data['insurance_coy'];
                 
                   try {
                    $user_resp = $this->doUser($operation,$username,
                    $userpassword,$firstname,$lastname,$email,$phone,
                     $chgpword_logon, $user_locked, $user_disable,$day_1,
                     $day_2,$day_3,$day_4,$day_5,$day_6,$day_7,$override_wh,
                     $extend_wh,$role_id,$role_name);
                            //code...
                   } catch (Exception $ex) {
                        throw new Exception("error");
                        
                   };
    
                   if($user_resp==-9){
                    return json_encode(
                        array(
                            "response_code"=>1,
                            "response_message"=>"User detail already exist, please enter a different username",
                           "data"=>[],

                        // "success"=>false,
                        // "data"=>"User detail already exist, please enter a different username"
                        )
                    );
                }
                else if($user_resp > 0) {
                    return json_encode(
                        array(
                        "response_code"=>0,
                        "response_message"=>"User detail has been successfully saved",
                        "data"=>[]
                        )
                    );
                }else{
                    return json_encode(
                        array(
                        "response_code"=>1,
                        "response_message"=>"Error : Please check User detail",
                        "data"=>[]
                        )
                        );
            }      
                }
            }


//this is to edit a user
  /**
   * this function passes a @data param which is an array 
   *  
   */
  public function editUser($data)
  {
    $firstname = $data['fname'];
    $lastname = $data['lname'];
    $reg_email = $data['reg_email'];
    $phone = $data['phone'];
    $dob = $data['dob'];
    $gender = $data['gender'];
    $contact_address = $data['address'];
    $user_resp = $this->doEditUser($reg_email,$firstname,$lastname,$phone, $dob,$gender,$contact_address);        
        if($user_resp==-9){
            return json_encode(
                array(
                "response_code"=>1,
                "response_message"=>"User detail already exist, please enter a different username",
                "data"=>[],
                )
            );
        }
        elseif($user_resp > 0) {
            return json_encode(
                array(
                "response_code"=>1,
                "response_message"=>"User detail updated successfully",
                "data"=>[]
                )
            );
        }else{
            return json_encode(
                array(
                "response_code"=>0,
                "response_message"=>"User detail failed to save :",
                "data"=>["error"=>$user_resp]
                )
            );
        }
    }
    
//this is to create a changepassword
  /**
   * this function passes a @data param which is an array 
   *  
   */
  public function changePassword($data)
  {
    $username = $data['username'];
    $oldpassword = $data['oldpassword'];
	$user_password = $data['userpassword'];
		if($this->validatepassword($username,$oldpassword)=='1'){
		$curr_resp = $this->doPasswordChange($username,$user_password);
			if($curr_resp == 1) {
                // echo '<div class="alert alert-success">The User password has been successfully changed </div>';
         return json_encode(
         array(
             "response_code"=>0,
             "response_message"=>"user password has been successfully changed",
             "data"=>[]
         )
         );
			}
			else{
            //	echo '<div class="alert alert-error">Error : Please check password detail</div>';
            return json_encode(
                array(
                    "response_code"=>1,
                    "response_message"=>"error: please check password detail",
                    "data"=>[]
                )
                );
			}
		}
		else
		{
                // echo '<div class="alert alert-error">Your old password is invalid</div>';
                return json_encode(
                    array(
                        "response_code"=>1,
                        "response_message"=>"old password is invalid",
                        "data"=>[]
                    )
                    );
		}
  }

/**
 * passing params @username and @value to update user account to be locked or unlocked
 */
  function updateUserLocked($username,$value){
    $query = "update userdata set user_locked='$value' where username= '$username'";
    echo $query;
    $resultid = $this->db_query($query,false);
    return json_encode(
        array(
            "response_code"=>1,
            "response_message"=>"user account lock updated successfully",
            "data"=>["user_id_status"=>$resultid]
        )
        );
}
  







//end of this class
        }

