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
		
		$data = $this->model->getPengaduan();

		$this->view->assign('dataPengaduan',$data);
		
		return $this->loadView('pengaduan/daftar_pengaduan');

	}
	
	public function detail(){
		$idPengaduan = $_GET['id'];

		$data = $this->model->getPengaduan($idPengaduan);
		$file = $this->model->getFile($idPengaduan);

		$data[0]['isi'] = html_entity_decode($data[0]['isi']);

		$this->view->assign('id',$idPengaduan);
		$this->view->assign('file',$file);
		$this->view->assign('dataPengaduan',$data[0]);

		return $this->loadView('pengaduan/detail');

	}
	
	public function tindak_lanjut(){
		$idPengaduan = $_GET['id'];

		$this->view->assign('id',$idPengaduan);

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
		$file = $this->model->getFile($idPengaduan);

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
		
		$this->view->assign('penelaahan',$penelaahan);
		$this->view->assign('dataPengaduan',$data[0]);
		$this->view->assign('id',$idPengaduan);
		return $this->loadView('pengaduan/penelusuran');
	
	}
	
	public function balas(){
		$idPengaduan = $_GET['id'];

		$this->view->assign('id',$idPengaduan);

		return $this->loadView('pengaduan/balas');
	
	}
	
	public function disposisi(){
		$idPengaduan = $_GET['id'];

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
	
}

?>
