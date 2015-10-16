<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class daftar_usersipmas extends Controller {
	
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

		return $this->loadView('daftar_user/user_sipmas');

	}
	
	public function detail(){
		
		// uploadFile($data,$path=null,$ext){
		
		// $quizStatistic = $this->contentHelper->quizStatistic();
		// db($quizStatistic);

		return $this->loadView('daftar_user/detail');

	}

	
}

?>
