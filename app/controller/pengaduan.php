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
    	$file = $this->model->getFile($idPengaduan,'idPengaduan');
    	$penelaahan = $this->model->getPenelaahan($idPengaduan);
    	$disposisi = $this->model->getDisposisi($idPengaduan);
    	$tglBalas = $this->model->getTglBalas($idPengaduan);
    	$comment = $this->model->getComment($idPengaduan);
    	$survey = $this->model->getSurvey($idPengaduan);

    	$data[0]['isi'] = html_entity_decode(htmlspecialchars_decode($data[0]['isi'],ENT_NOQUOTES));
    	// $data[0]['judul'] = html_entity_decode(htmlspecialchars_decode($data[0]['judul'],ENT_NOQUOTES));

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

    	$this->view->assign('survey',$survey);
    	$this->view->assign('tglBalas',$tglBalas);
    	$this->view->assign('disposisi',$disposisi);
    	$this->view->assign('comment',$comment);
    	$this->view->assign('penelaahan',$penelaahan);
    	$this->view->assign('file',$file);
    	$this->view->assign('dataPengaduan',$dataPengaduan);
    	$this->view->assign('data',$data[0]);
    	$this->view->assign('user',$this->user);
    	$this->view->assign('id',$idPengaduan);

    	return $this->loadView('pengaduan/detail');
    }

    function ins_laporan()
    {
    	global $basedomain,$CONFIG;
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
		    	$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		    	$_POST['judul'] = htmlentities(htmlspecialchars($_POST['judul'], ENT_QUOTES));
		    	$_POST['idUser'] = $this->user['idUser'];
		    	$_POST['status'] = 1;
		    	$_POST['n_status'] = 2;
		    	unset($_POST['g-recaptcha-response']);
		    	unset($_POST['termagree']);
		    	if($_POST['perorangan'] == 'on') $_POST['perorangan'] = 1; 
		    	$_POST['fase'] = 1;

		    	$latestId = $this->model->insert_laporan($_POST);

		    	if(!empty($_FILES['myfile']['name'])){
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

                //kirim email
                $this->view->assign('name',$this->user['name']); 
                $this->view->assign('judul',$_POST['judul']);
                $this->view->assign('tanggal',$_POST['tanggal']);
                $this->view->assign('idLaporan',$latestId['id'].date('Y'));
                $this->view->assign('id',$latestId['id']);

                $html = $this->loadView('pengaduan/emailTemplate');
                $send = sendGlobalMail(trim($this->user['email']),$CONFIG['email']['EMAIL_FROM_DEFAULT'],$html); 

		    	echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."pengaduan'</script>";
		    	exit;

	    	}
    	}else {
            echo "<script>alert('Silahkan Cek Captcha terlebih dahulu')</script>";
            redirect($basedomain."register");
        }
    }

    function ins_balas()
    {
    	global $basedomain;

		$_POST['idUser'] = $this->user['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		// db($_POST);
		$this->model->insert_balas($_POST);
		    		
		if(!empty($_FILES['myfile']['name'])){
    		$upload = uploadFile('myfile');
    		//insert ke file
    		$idComment = $this->model->getLatestId('bsn_comment');

    		$files['nama'] = $upload['full_name'];
    		$files['path'] = $upload['full_path'];
    		$files['type'] = 1;
    		$files['idComment'] = $idComment['id'];
    		$files['n_status'] = 1;

    		$this->model->insert_file($files);

    	}

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."pengaduan/detail/?id={$_POST['idPengaduan']}'</script>";
		exit;
    }

    function ins_survey()
    {	
    	global $basedomain;
    	
    	$_POST['idUser'] = $this->user['idUser'];
    	$_POST['n_status'] = 1;

    	$this->model->insert_survey($_POST);

    	echo "<script>alert('Terima kasih atas survey anda');window.location.href='".$basedomain."pengaduan/detail/?id={$_POST['idPengaduan']}'</script>";
		exit;
    }
}

?>
