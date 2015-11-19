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
		$this->view->assign('app_domain',$app_domain);
	}

	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->userHelper = $this->loadModel('userHelper');
	}
	
	public function index(){
		
		$dataUser['table'] = "bsn_users";
		$dataUser['condition'] = array('type'=>2);
		$getUser = $this->userHelper->fetchData($dataUser);
		
		if ($getUser){

			foreach ($getUser as $key => $value) {
				$dataAdu['table'] = "bsn_pengaduan";
				$dataAdu['condition'] = array('idUser'=>$value['idUser']);
				$getPengaduan = $this->userHelper->fetchData($dataAdu);
				if ($getPengaduan)$getUser[$key]['jumlah_pengaduan'] = count($getPengaduan);
				else $getUser[$key]['jumlah_pengaduan'] = 0;
				// $getUser[$key]['pengaduan'] = $getPengaduan;
			}
			
			$this->view->assign('user', $getUser);
		}

		return $this->loadView('daftar_user/user_sipmas');

	}
	
	public function detail(){
		
		$id = _g('id');
		$dataUser['table'] = "bsn_users";
		$dataUser['condition'] = array('type'=>2, 'idUser'=>$id);
		$getUser = $this->userHelper->fetchData($dataUser);
		
		if ($getUser){

			foreach ($getUser as $key => $value) {
				$dataAdu['table'] = "bsn_pengaduan";
				$dataAdu['condition'] = array('idUser'=>$value['idUser']);
				$getPengaduan = $this->userHelper->fetchData($dataAdu);
				if ($getPengaduan){
					$getUser[$key]['jumlah_pengaduan'] = count($getPengaduan);
					$getUser[$key]['pengaduan'] = $getPengaduan;
				}else{
					$getUser[$key]['jumlah_pengaduan'] = 0;
				}
				
			}
			// pr($getUser);
			$this->view->assign('user', $getUser[0]);
		}

		return $this->loadView('daftar_user/detail');

	}

	
}

?>
