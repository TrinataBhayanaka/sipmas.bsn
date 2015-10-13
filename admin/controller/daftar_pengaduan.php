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

		return $this->loadView('pengaduan/daftar_pengaduan');

	}
	
	public function detail(){
		
		// uploadFile($data,$path=null,$ext){
		
		// $quizStatistic = $this->contentHelper->quizStatistic();
		// db($quizStatistic);

		return $this->loadView('pengaduan/detail');

	}
	
	public function tindak_lanjut(){
	
		return $this->loadView('pengaduan/tindak_lanjut');
	
	}
	
	public function penelaahan(){
	
		return $this->loadView('pengaduan/penelaahan');
	
	}
	
	public function penelusuran(){
	
		return $this->loadView('pengaduan/penelusuran');
	
	}
	
	public function balas(){
	
		return $this->loadView('pengaduan/balas');
	
	}
	
	public function disposisi(){
	
		return $this->loadView('pengaduan/disposisi');
	
	}


	
}

?>
