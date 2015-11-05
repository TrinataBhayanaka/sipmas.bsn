<?php

class profil extends Controller {
	
	var $models = FALSE;
	var $view;

	
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
        $this->loginHelper = $this->loadModel('loginHelper');
        $this->userHelper = $this->loadModel('userHelper');
	}
	
	function index(){

		global $basedomain;

		$dataUser['table'] = "bsn_users";
		$dataUser['condition'] = array('type'=>2, 'n_status'=>1, 'idUser'=>$this->user['idUser']);
		$getUser = $this->userHelper->fetchData($dataUser);
		
		if ($getUser){
			
			$this->view->assign('user', $getUser[0]);
		}

		
		if ($_POST['token']){

			if ($_POST['pass']){
				$pass = _p('pass');
	            $pass1 = _p('pass1');
	        	if ($pass === $pass1){
            		$_POST['password'] = sha1($getUser[0]['salt'] . $pass . $getUser[0]['salt']);
            	}    	
			}
			    
            $signup = $this->contentHelper->saveData($_POST,"_users");
            redirect($basedomain . 'profil');
		}
    	return $this->loadView('akun/profile');
    }
    

    
}

?>
