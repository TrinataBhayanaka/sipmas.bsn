<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class pengaturan_admin extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		// $this->validatePage();
		$this->view->assign('app_domain',$app_domain);
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->marticle = $this->loadModel('marticle');
		$this->mquiz = $this->loadModel('mquiz');
		$this->mcourse = $this->loadModel('mcourse');
	}
	
	public function index(){
		
		// uploadFile($data,$path=null,$ext){
		
		// $quizStatistic = $this->contentHelper->quizStatistic();
		// db($quizStatistic);

		return $this->loadView('pengaturan/pengaturan_admin');

	}
	
	public function waktukriteria(){
	
	return $this->loadView('pengaturan/waktu_kriteria');
	}
	
	public function ubahkonten(){
	
	return $this->loadView('pengaturan/ubah_konten');
	}
	
	public function ruanglingkup(){
	
	return $this->loadView('pengaturan/kategori_ruang_lingkup');
	}

	public function edit(){
	
	return $this->loadView('pengaturan/edit_admin');
	}
	
}

?>
