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
    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
	}
	
	function index(){
		
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
    	return $this->loadView('forgot_password');
    }

    function register()
    {   
        
        global $basedomain;
        $salt = md5('register');

        if ($_POST['submit']){

            $pass = _p('pass');
            $pass1 = _p('retypePass');
            if ($pass === $pass1){
                $_POST['password'] = $salt . $pass . $salt;
                $_POST['salt'] = $salt;
                $signup = $this->contentHelper->saveData($_POST,"_users");
                if ($signup){
                    redirect($basedomain . 'home/register_confirmation');
                }else{
                    redirect($basedomain . 'home/register');
                }
            }    
            
        }
        
    	return $this->loadView('akun/register');
    }

    function register_confirmation()
    {
    	return $this->loadView('info');
    }

    function search()
    {
        // pr($_POST);
        // exit;
        $data['pencarian']=$this->contentHelper->tracking($_POST['tracking']);
        // pr($data);

        $this->view->assign('data',$data['pencarian']);
        // exit;
        return $this->loadView('search');
    }

}

?>
