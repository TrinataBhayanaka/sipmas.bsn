<?php

class pengaduan extends Controller {
	
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
        $this->model = $this->loadModel('mpengaduan');
	}
	
	function index(){
		
        $this->view->assign('user',$this->user);
		return $this->loadView('pengaduan/pengaduan');
    }

    function detail()
    {
    	return $this->loadView('pengaduan/detail');
    }

    function ins_laporan()
    {
    	global $basedomain;
    	// db($_FILES['myfile']);
    	if($_POST['g-recaptcha-response']){

    		$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le_OA0TAAAAAEjdg4YdX12AIdesAu_vr3g8Xsco&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
    		// $response = true;
    		if($response['success'] == false)
            {
              echo '<h2>You are spammer ! Get the @$%K out</h2>';
              exit;
            }
            else
            {
		    	$_POST['isi'] = htmlentities($_POST['isi']);
		    	$_POST['idUser'] = $this->user['idUser'];
		    	$_POST['n_status'] = 1;
		    	unset($_POST['g-recaptcha-response']);
		    	unset($_POST['termagree']);
		    	if($_POST['perorangan'] == 'on') $_POST['perorangan'] = 1; 

		    	$this->model->insert_laporan($_POST);

		    	if(isset($_FILES['myfile'])){
		    		$upload = uploadFile('myfile');
		    		//insert ke file
		    		$idPengaduan = $this->model->getLatestId();
		    		
		    		$files['nama'] = $upload['full_name'];
		    		$files['path'] = $upload['full_path'];
		    		$files['type'] = 1;
		    		$files['idPengaduan'] = $idPengaduan['id'];
		    		$files['n_status'] = 1;

		    		$this->model->insert_file($files);

		    	}

		    	echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."pengaduan'</script>";
		    	exit;

	    	}
    	}else {
            echo "<script>alert('Silahkan Cek Captcha terlebih dahulu')</script>";
            redirect($basedomain."register");
        }
    }
}

?>
