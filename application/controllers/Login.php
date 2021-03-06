<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
    }

    /**
     * Index Page for this controller.
     */



    public function index()
    {


        $this->global['pageTitle'] = 'e-Healthcare : Welcome';
        $this->load->view('index.php');
       
    }

    function about()
    {
        $this->load->view('about.php');
    }
    

    public function status(){
        $this->load->view('applicationStatus.php');
    }

    public function track(){

        $this->global['pageTitle'] = 'e-Healthcare : Your Application Status';
        $trackingid = $this->input->post('tracking_id');

        // $data['patientApplication'] = $this->user_model->getApplicantDetails($trackingid);
         $result['result'] = $this->login_model->trackPatient($trackingid);

        $this->load->view('patientApplicationDetails.php',$result);
        
    }

       
    // }
    

    public function login_page(){
        $this->isLoggedIn(); 
    }
    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login');
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
    
    /**
     * This function used to logged in user
     */
    public function loginMe()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->input->post('password');
            
            $result = $this->login_model->loginMe($email, $password);
            
            if(!empty($result))
            {
                $lastLogin = $this->login_model->lastLoginInfo($result->userId);

                $sessionArray = array('userId'=>$result->userId,                    
                                        'role'=>$result->roleId,
                                        'roleText'=>$result->role,
                                        'name'=>$result->name,
                                        'email' => $email,
                                        'lastLogin'=> $lastLogin->createdDtm,
                                        'isLoggedIn' => TRUE
                                );

                $this->session->set_userdata($sessionArray);

                unset($sessionArray['userId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);

                $loginInfo = array("userId"=>$result->userId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());

                $this->login_model->lastLogin($loginInfo);
                
                redirect('/dashboard');
            }
            else
            {
                $this->session->set_flashdata('error', 'Email or password mismatch');
                
                redirect('/login');
            }
        }
    }

    /**
     * This function used to load forgot password view
     */
public function addNewHospitalUser()
    {
        
        
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('hname','Hospital Name','required|max_length[128]|trim');
            $this->form_validation->set_rules('fname','Hospital Incharge Name','required|max_length[128]|trim');
            $this->form_validation->set_rules('email','Hospital Email','required|valid_email|max_length[128]|trim');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]|max_length[20]|trim');
           
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
               $this->register();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('hname'))));
                $inchargeName = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = $this->security->xss_clean($this->input->post('email'));
                $password = $this->input->post('password');
                $roleId = 3;
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $hospitalInfo = array('hospital_name'=>$name, 'hospital_incharge_name'=>$inchargeName, 'hospital_email'=>$email);
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId, 'name'=> $inchargeName,
                                 'mobile'=>$mobile, 'createdBy'=>NULL, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('user_model');
                $result1 = $this->user_model->addNewHospital($hospitalInfo);
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New User created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('login');
            }
        }
    

    public function register()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('register');
        }
        else
        {
            redirect('/dashboard');
        }
    }

    public function forgotPassword()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('forgotPassword');
        }
        else
        {
            redirect('/dashboard');
        }
    }


    public function SendEmail($EmailTo, $Message, $ReturnData, $Subject, $EmailBcc) {
        try {
            $mail = $this->emailConfig();
            $mail->setFrom('yuvaammu3@gmail.com', 'E-HealthSystem');
            $mail->addAddress($EmailTo);     // Add a recipient
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $Subject;
            $mail->Body = $Message;
            if (!$mail->Send()) {
                return 1;
            } else {
                return 0;
            }
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        }
    }

    protected function emailConfig() {
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'tls://smtp.gmail.com:587';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "yuvaammu3@gmail.com";                 // SMTP username
        $mail->Password = "9003992784";
        return $mail;
    }




    
    /**
     * This function used to generate reset password request link
     */
    function resetPassword()
    {
        $email = $this->input->post('email');     
        $this->load->model('user_model'); 
         $findemail = $this->user_model->ForgotPassword($email); 
         // echo $findemail['email]; 
         if($findemail){
          $mail_message = $this->user_model->sendpassword($findemail); 
          $Subject = "ForgotPassword";
          $this->SendEmail(trim($email), $mail_message, "N", $Subject, "");       
           }else{
          $this->session->set_flashdata('msg',' Email not found!');
          redirect(base_url().'user/Login','refresh');
      }
    }









    function resetPasswordUser()
    {
        $status = '';
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login_email','Email','trim|required|valid_email');
                
        if($this->form_validation->run() == FALSE)
        {
            $this->forgotPassword();
        }
        else 
        {
            $email = $this->security->xss_clean($this->input->post('login_email'));
            
            if($this->login_model->checkEmailExist($email))
            {
                $encoded_email = urlencode($email);
                
                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum',15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();
                
                $save = $this->login_model->resetPasswordUser($data);                
                
                if($save)
                {
                    $data1['reset_link'] = base_url() . "resetPasswordConfirmUser/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);

                    if(!empty($userInfo)){
                        $data1["name"] = $userInfo[0]->name;
                        $data1["email"] = $userInfo[0]->email;
                        $data1["message"] = "Reset Your Password";
                    }

                    $sendStatus = resetPasswordEmail($data1);

                    if($sendStatus){
                        $status = "send";
                        setFlashData($status, "Reset password link sent successfully, please check mails.");
                    } else {
                        $status = "notsend";
                        setFlashData($status, "Email has been failed, try again.");
                    }
                }
                else
                {
                    $status = 'unable';
                    setFlashData($status, "It seems an error while sending your details, try again.");
                }
            }
            else
            {
                $status = 'invalid';
                setFlashData($status, "This email is not registered with us.");
            }
            redirect('/forgotPassword');
        }
    }

    /**
     * This function used to reset the password 
     * @param string $activation_id : This is unique id
     * @param string $email : This is user email
     */
    function resetPasswordConfirmUser($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);
        
        // Check activation id in database
        $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
        
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;
        if ($is_correct >= 1)
        {
            $this->load->view('newPassword', $data);
        }
        else
        {
            redirect('/login');
        }
    }
    
    /**
     * This function used to create new password for user
     */
    function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = $this->input->post("email");
        $activation_id = $this->input->post("activation_code");
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('password','Password','required|max_length[20]');
        $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->resetPasswordConfirmUser($activation_id, urlencode($email));
        }
        else
        {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');
            
            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
            
            if($is_correct >= 1)
            {                
                $this->login_model->createPasswordUser($email, $password);
                
                $status = 'success';
                $message = 'Password changed successfully';
            }
            else
            {
                $status = 'error';
                $message = 'Password changed failed';
            }
            
            setFlashData($status, $message);

            redirect("/login");
        }
    }
}

?>