<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class daftar_pengaduan extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		$this->view->assign('app_domain',$app_domain);
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->model = $this->loadModel('mpengaduan');
	}
	
	public function index(){
		
		if($this->admin['satker'] == 3){
			$data = $this->model->getPengaduan();
		} else {
			$data = $this->model->getPengaduanSatker($this->admin['satker']);
		}

		$this->view->assign('dataPengaduan',$data);
		
		return $this->loadView('pengaduan/daftar_pengaduan');

	}
	
	public function detail(){
		$idPengaduan = $_GET['id'];

		$data = $this->model->getPengaduan($idPengaduan);
		$file = $this->model->getFile($idPengaduan,'idPengaduan');

		$data[0]['isi'] = html_entity_decode($data[0]['isi']);

		$this->view->assign('id',$idPengaduan);
		$this->view->assign('file',$file);
		$this->view->assign('dataPengaduan',$data[0]);

		return $this->loadView('pengaduan/detail');

	}
	
	public function tindak_lanjut(){
		$idPengaduan = $_GET['id'];

		$dataPengaduan = $this->model->getPengaduan($idPengaduan);
		$data = $this->model->getComment($idPengaduan);

		$this->view->assign('dataPengaduan',$dataPengaduan[0]);
		$this->view->assign('id',$idPengaduan);
		$this->view->assign('dataComment',$data);

		return $this->loadView('pengaduan/tindak_lanjut');
	
	}
	
	public function penelaahan(){
		$idPengaduan = $_GET['id'];

		$penelaahan = $this->model->getPenelaahan($idPengaduan);
		if($penelaahan)
		{
			$subLingkup = $this->model->getSubLingkup($penelaahan['kategori']);
			$this->view->assign('subLingkup',$subLingkup);			
		}
		$data = $this->model->getPengaduan($idPengaduan);
		$rLingkup = $this->model->getRuangLingkup();
		$satker = $this->model->getSatker();
		$file = $this->model->getFile($idPengaduan,'idPengaduan');

		$this->view->assign('penelaahan',$penelaahan);
		$this->view->assign('file',$file);
		$this->view->assign('satker',$satker);
		$this->view->assign('rLingkup',$rLingkup);
		$this->view->assign('dataPengaduan',$data[0]);
		$this->view->assign('id',$idPengaduan);

		return $this->loadView('pengaduan/penelaahan');
	
	}
	
	public function penelusuran(){
		$idPengaduan = $_GET['id'];

		$data = $this->model->getPengaduan($idPengaduan);
		$penelaahan = $this->model->getPenelaahan($idPengaduan);
		$tglBalas = $this->model->getTglBalas($idPengaduan);
		$dataDisposisi = $this->model->getDisposisi($idPengaduan);
		
		$this->view->assign('disposisi',$dataDisposisi);
		$this->view->assign('penelaahan',$penelaahan);
		$this->view->assign('tglBalas',$tglBalas);
		$this->view->assign('dataPengaduan',$data[0]);
		$this->view->assign('id',$idPengaduan);
		return $this->loadView('pengaduan/penelusuran');
	
	}
	
	public function balas(){
		$idPengaduan = $_GET['id'];

		$data = $this->model->getPengaduan($idPengaduan);
		$dataBalas = $this->model->getBalas($idPengaduan);

		$this->view->assign('dataBalas',$dataBalas);
		$this->view->assign('dataPengaduan',$data[0]);
		$this->view->assign('id',$idPengaduan);

		return $this->loadView('pengaduan/balas');
	
	}
	
	public function disposisi(){
		$idPengaduan = $_GET['id'];

		$data = $this->model->getPengaduan($idPengaduan);
		$dataDisposisi = $this->model->getDisposisi($idPengaduan);
		$satker = $this->model->getSatker();
		// $adminUsers = $this->model->getAdmUsr();

		$this->view->assign('satker',$satker);
		$this->view->assign('dataPengaduan',$data[0]);
		$this->view->assign('disposisi',$dataDisposisi);
		// $this->view->assign('adminUsers',$adminUsers);
		$this->view->assign('id',$idPengaduan);

		return $this->loadView('pengaduan/disposisi');
	
	}

	public function ajaxsubkategori()
	{
		$rLingkup = $_POST['rLingkup'];

		$data = $this->model->getSubLingkup($rLingkup);

		print json_encode($data);

		exit;
	}

	public function ins_penelaahan()
	{
		global $basedomain;

		$this->model->insert_penelaahan($_POST);

		$this->model->upd_fase($_POST['idPengaduan'],2);

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/penelaahan/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function ins_balas()
	{
		global $basedomain;

		$_POST['idUser'] = $this->admin['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		// db($_POST);
		$this->model->insert_balas($_POST);

		$this->model->upd_fase($_POST['idPengaduan'],3);
		    		
		if(isset($_FILES['myfile'])){
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

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/balas/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function ins_disposisi()
	{
		global $basedomain;

		$_POST['idUser'] = $this->admin['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		$_POST['n_status'] = 1;
		// db($_POST);
		$this->model->insert_disposisi($_POST);

		$this->model->upd_fase($_POST['idPengaduan'],4);

		if(isset($_FILES['myfile'])){
    		$upload = uploadFile('myfile');
    		//insert ke file
    		$idDisposisi = $this->model->getLatestId('bsn_disposisi');

    		$files['nama'] = $upload['full_name'];
    		$files['path'] = $upload['full_path'];
    		$files['type'] = 1;
    		$files['idDisposisi'] = $idDisposisi['id'];
    		$files['n_status'] = 1;

    		$this->model->insert_file($files);

    	}

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/disposisi/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function chg_status()
	{
		db($_POST);
		$this->model->updStat($_POST);
	}
	
}

?>
