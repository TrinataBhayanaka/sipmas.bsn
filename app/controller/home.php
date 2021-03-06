<?php

class home extends Controller {
	
	var $models = FALSE;
	var $view;
	var $reportHelper;
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
		$getUserLogin = $this->isUserOnline();
		$this->user = $getUserLogin[0];
        $this->token = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
        $this->userHelper = $this->loadModel('userHelper');
	}
	
	function index(){
		
        $this->log('surf','Landing page');
        $data=$this->contentHelper->getContent();
        if ($data)$this->view->assign('data',$data[0]);
        $this->view->assign('user',$this->user);
		
		return $this->loadView('home');
    }

    function logout()
    {
    	global $basedomain;

    	// $updateStatusNilai = $this->quizHelper->updateStatusNilai();
    	
    	$doLogout = $this->userHelper->logoutUser();
    	if ($doLogout){
    		redirect($basedomain.'logout.php');exit;
    	}else{
    		redirect($basedomain);
    		logFile('can not logout user');exit;
    	}
    }

    function forgot_password()
    {
        global $basedomain, $CONFIG;

        if ($_POST['token']){
            $email = _p('email');

            $data['table'] = "bsn_users";
            $data['condition'] = array('email'=>$email);
            $checkData = $this->contentHelper->fetchData($data);
            if ($checkData){
                if ($checkData[0]['email']){
                    $passTmp = substr($this->token, 0, 8);
                    $newData['id'] = $checkData[0]['idUser'];
                    $newPass = sha1($checkData[0]['salt'] . $passTmp . $checkData[0]['salt']);
                    $newData['password'] = $newPass;
                    
                    $reset = $this->contentHelper->saveData($newData,"_users");
                    
                    $this->view->assign('encode',$msg); 
                    $this->view->assign('email',$checkData[0]['email']);  
                    $this->view->assign('password',$passTmp);  
                    $this->view->assign('name',$checkData[0]['name']);  
                    $this->view->assign('text',"Your request for reset password have been successfull. You can use this reset password below for temporary use only."); 

                    $html = $this->loadView('emailTemplate');
                    $send = sendGlobalMail(trim($checkData[0]['email']),$CONFIG['email']['EMAIL_FROM_DEFAULT'],$html);
                    logFile($send);
                    if ($send){
                        // $token = "?req=2";
                        echo "<script>alert('Password anda sudah dikirim ke email anda');window.location.href='{$basedomain}'</script>";
                        // redirect($basedomain);
                        exit;
                    }else{
                        $token = "?req=1";
                    }
                    
                    redirect($basedomain . 'home/forgot_password/' . $token);
                }
            }
        }

        if ($_GET['req']) $this->view->assign('req',$_GET['req']); 
    	return $this->loadView('forgot_password');
    }

    function register()
    {   
        
        global $basedomain, $CONFIG, $LOCALE;
        $salt = md5('register');

        if ($_POST['submit']){

            $checkBefore['table'] = "bsn_users";
            $checkBefore['condition'] = array('email'=>$_POST['email']);
            $checkDataBefore = $this->contentHelper->fetchData($checkBefore);
            if ($checkDataBefore){
                if ($checkDataBefore){
                    echo "<script>alert('Email sudah digunakan');window.location.href='{$basedomain}home/register'</script>";
                }
                exit;
            }

            $pass = _p('pass');
            $pass1 = _p('retypePass');
            if ($pass === $pass1){
                $_POST['password'] = sha1($salt . $pass . $salt);
                $_POST['salt'] = $salt;
                $_POST['n_status'] = 0;
                $_POST['register_date'] = date('Y-m-d H:i:s');
                $_POST['login_count'] = 0;
                $_POST['type'] = 2;
                $_POST['email_token'] = $this->token;
                if ($_POST['receiveNotif'])$_POST['data'] = serialize(array('getNotif'=>1));
                
                $signup = $this->contentHelper->saveData($_POST,"_users");
                if ($signup){

                    $data['table'] = "bsn_users";
                    $data['condition'] = array('email'=>$_POST['email']);
                    $checkData = $this->contentHelper->fetchData($data);
                    if ($checkData){
                        // send mail

                        $dataSend['email'] = $checkData[0]['email'];
                        $dataSend['email_token'] = $checkData[0]['email_token'];

                        $serial = encode($dataSend);
                        $this->view->assign('encode',$serial); 
                        $this->view->assign('email',$checkData[0]['email']);  
                        $this->view->assign('password',$pass);
                        $this->view->assign('name',$checkData[0]['name']);  
                        $this->view->assign('text',"Your request for new account."); 
                        
                        $link = "<a href='{$basedomain}home/verified/?token={$serial}'>{$LOCALE['default']['email_verification']}</a>";
                        $this->view->assign('link',$link); 

                        $html = $this->loadView('emailTemplate');
                        $send = sendGlobalMail(trim($checkData[0]['email']),$CONFIG['email']['EMAIL_FROM_DEFAULT'],$html);
                        logFile($send);
                        if ($send) redirect($basedomain . 'home/register_confirmation/?status=1');
                    }

                }else{
                    redirect($basedomain . 'home/register');
                }
            }    
            
        }
        
    	return $this->loadView('akun/register');
    }

    function verified()
    {
        global $basedomain;

        $req = _g('token');
        $decode = decode($req);
        if ($decode){
            $data['table'] = "bsn_users";
            $data['condition'] = array('email'=>$decode['email'], 
                                        'email_token'=>$decode['email_token']);
            $checkData = $this->contentHelper->fetchData($data);

            if ($checkData){
                $updateData['n_status'] = 1;
                $updateData['id'] = $checkData[0]['idUser'];
                
                $update = $this->contentHelper->saveData($updateData,"_users");
                $link = "?status=2";
            }else{
                $link = "?status=0";
            } 

            redirect($basedomain . 'home/register_confirmation/' . $link);
        }
    }

    function register_confirmation()
    {

        $link = _g('status');
        if ($link == 1){
            $html = "<h2>Terima kasih, <strong>Pendaftaran Berhasil</strong></h2>
        <span>Silahkan verifikasi email anda untuk melanjutkan</span>";
        
        }else if ($link == 2){
            $html = "<h2>Terima kasih, <strong>Verifikasi email Berhasil</strong></h2>
        <span>Silahkan login untuk melakukan pengaduan berupa pertanyaan, masukan maupun keluhan yang terkait dengan standardisasi</span>";
        
        }else{
            $html = "<h2>Oppsss, <strong>Terjadi Kesalahan</strong></h2>";
        }
        $this->view->assign('status',$html);
        $this->view->assign('link',$link);

    	return $this->loadView('info');
    }

    function search()
    {
        global $basedomain;
        // pr($this->user);
        if($this->user){
            $data['pencarian']=$this->contentHelper->tracking($_POST['tracking'],$this->user['idUser']);
            // pr($data);

            $this->view->assign('data',$data['pencarian']);
            // exit;
            return $this->loadView('search');
        }else{
            redirect($basedomain.'home');
        }
    }

    function ajax()
    {

        $email = _p('email');
        $data['table'] = "bsn_users";
        $data['condition'] = array('email'=>$email);
        $checkData = $this->contentHelper->fetchData($data);
        if ($checkData) print json_encode(array('status'=>1)); 
        else print json_encode(array('status'=>0));
        exit;
    }

}

?>
