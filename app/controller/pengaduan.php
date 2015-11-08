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
		
		$data = $this->model->getPengaduan($this->user['idUser']);
		$this->view->assign('dataPengaduan',$data);

        $this->view->assign('user',$this->user);
		return $this->loadView('pengaduan/pengaduan');
    }

    function detail()
    {
    	$idPengaduan = $_GET['id'];

    	$data = $this->model->getPengaduan($this->user['idUser'],$idPengaduan);
    	$dataPengaduan = $this->model->getPengaduan($this->user['idUser']);
    	$file = $this->model->getFile($idPengaduan);
    	$penelaahan = $this->model->getPenelaahan($idPengaduan);
    	$tglBalas = $this->model->getTglBalas($idPengaduan);
        // pr($data);
        // pr($dataPengaduan);
        // pr($file);
        // pr($penelaahan);
        // pr($tglBalas);
    	$data[0]['isi'] = html_entity_decode($data[0]['isi']);

    	if($data[0]['n_status'] == 1)
    	{
    		$data[0]['n_status'] = 'Aktif';
    	} elseif ($data[0]['n_status'] == 2) {
    		$data[0]['n_status'] = 'Ditindak Lanjuti';
    	} elseif ($data[0]['n_status'] == 3) {
    		$data[0]['n_status'] = 'Tidak Ditindak Lanjuti';
    	} elseif ($data[0]['n_status'] == 4) {
    		$data[0]['n_status'] = 'Selesai';
    	}

    	$this->view->assign('tglBalas',$tglBalas);
    	$this->view->assign('penelaahan',$penelaahan);
    	$this->view->assign('file',$file);
    	$this->view->assign('dataPengaduan',$dataPengaduan);
    	$this->view->assign('data',$data[0]);
    	$this->view->assign('user',$this->user);

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
		    	$_POST['status'] = 1;
		    	unset($_POST['g-recaptcha-response']);
		    	unset($_POST['termagree']);
		    	if($_POST['perorangan'] == 'on') $_POST['perorangan'] = 1; 
		    	$_POST['fase'] = 1;

		    	$latestId = $this->model->insert_laporan($_POST);

		    	if(isset($_FILES['myfile'])){
		    		$upload = uploadFile('myfile');
		    		//insert ke file
		    		// $idPengaduan = $this->model->getLatestId();
		    		
		    		$files['nama'] = $upload['full_name'];
		    		$files['path'] = $upload['full_path'];
		    		$files['type'] = 1;
		    		$files['idPengaduan'] = $latestId['id'];
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
