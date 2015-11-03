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
		$data=$this->contentHelper->getContent();
        // pr($data);
        $this->view->assign('user',$this->user);
		$this->view->assign('data',$data[0]);
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
