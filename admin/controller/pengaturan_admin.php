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
		$this->token = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
	}
	public function loadmodule()
	{
		$this->userHelper = $this->loadModel('userHelper');
        $this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function index(){
		
		$dataUser['table'] = "bsn_users";
		$dataUser['condition'] = array('type'=>1, 'n_status'=>'0,1,2');
		$dataUser['in'] = array('n_status');
		$getUser = $this->userHelper->fetchData($dataUser);
		
		if ($getUser){

			$this->view->assign('user', $getUser);
		}

		return $this->loadView('pengaturan/pengaturan_admin');

	}
	
	public function waktukriteria(){
	
	return $this->loadView('pengaturan/waktu_kriteria');
	}
	
	public function ubahkonten(){
	
			// pr($_POST);
		if($_POST['id']){
			// pr($_POST);exit;
			$dataupd=$this->contentHelper->updContent($_POST['id']);
			 // exit;
		}
// 		$data=$this->contentHelper->getContent(2,1);
// // pr($data);
//         $this->view->assign('data',$data[0]);

		return $this->loadView('pengaturan/ubah_konten');

	}

	public function selectubahkonten(){
	
		// if($_POST['id']){
		// 	// pr($_POST);
		// 	$dataupd=$this->contentHelper->updContent($_POST['id']);
			 // exit;
		// }
		$data=$this->contentHelper->getContent($_POST['type'],1);
// pr($data);
        if ($data){
            print json_encode(array('status'=>true,'idhidden'=>$data[0]['id'], 'data'=>$data[0]['description'],'judul'=>$data[0]['title']));
        }else{
            print json_encode(array('status'=>false));
        }
        
        exit;

	}
	public function ruanglingkup(){
	
		$select = $this->mkategori->select_data();
		/*foreach ($select as $k => $val) {
			$select_list[$k] = $val;
			$i = 0;
			$val_idKategori =$val['idKategori'];	
				$select_sub = $this->mkategori->select_data_sub($val_idKategori);
					$select_list[$k]['sub'] = $select_sub;
			}*/
			// pr($select_list);
		$this->view->assign('data',$select);
		
	return $this->loadView('pengaturan/kategori_ruang_lingkup');
	}

	public function edit(){
		
		global $basedomain;

		$id = _g('id');
		if ($_POST['token']){

			if ($_POST['pass']) $_POST['password'] = $this->token . $_POST['pass'] . $this->token;
			$_POST['register_date'] = date('Y-m-d');
			$_POST['login_count'] = 0;
			$_POST['n_status'] = 1;
			$_POST['id'] = $_POST['idUser'];

			$getUser = $this->userHelper->saveData($_POST,"_users");
			
			if ($getUser){
				redirect($basedomain . 'pengaturan_admin');
			}else{
				redirect($basedomain . 'pengaturan_admin/edit');
			}
		}

		if ($id){

			$dataUser['table'] = "bsn_users";
			$dataUser['condition'] = array('type'=>'1,3', 'n_status'=>'1', 'idUser'=>$id);
			$dataUser['in'] = array('type');
			$getUser = $this->userHelper->fetchData($dataUser);
			// pr($getUser);
			if ($getUser){

				$this->view->assign('user', $getUser[0]);
			}
			
		}
		return $this->loadView('pengaturan/edit_admin');
	}
	
	function updateData()
	{
		global $basedomain;

		$id = _g('id');
		$dataUser['id'] = $id;
		$dataUser['n_status'] = -1;
		$getUser = $this->userHelper->saveData($dataUser);
		
		redirect($basedomain . 'pengaturan_admin');
		
	}

	public function ajax_insert_kategori(){
		
		global $basedomain;
		$kategori =$_POST['kategori'];
		$idParent = 'NULL';
		$n_status = 1;
		
		if ($kategori != '' ){
			$insert = $this->mkategori->insert_data($kategori,$idParent,$n_status);
			// echo json_encode($data);
		}
		exit;
	}
	
	public function ajax_edit_kategori(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$idKategori =$_POST['idKategori'];
		
		if ($idKategori != ''){
			$edit = $this->mkategori->edit_data($idKategori);
			echo json_encode($edit);
		}
		exit;
	}
	
	public function ajax_update_kategori(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$id = $_POST['id'];
		$kategori =$_POST['kategori'];
		if ($id != '' && $kategori != ''){
			$update = $this->mkategori->update_data($id,$kategori);
		}
		exit;
		
	}
	
	public function ajax_select_list(){
		
		// pr($_POST);
		global $basedomain;
		$kategori =$_POST['idKategori'];
			$select = $this->mkategori->select_data_list_option($kategori);
			// pr($select);
			echo json_encode($select);
		exit;
	}
	
	public function ajax_insert_sub_kategori(){
		
		global $basedomain;
		$kategori =$_POST['kategori'];
		$subkategori = $_POST['subkategori'];
		$n_status = '1';
		if ($kategori != '' ){
			$insert = $this->mkategori->insert_data_sub($kategori,$subkategori,$n_status);
			// echo json_encode($data);
		}
		exit;
	}
	
	public function ruanglingkup_sub(){
		$id =$_GET['id'];
		$select_sub = $this->mkategori->select_data_sub($id);
		
		$select = $this->mkategori->select_data();
		
		$this->view->assign('id',$id);
		$this->view->assign('data',$select);
		$this->view->assign('data_sub',$select_sub);
		
	return $this->loadView('pengaturan/kategori_ruang_lingkup_sub');
	}
	
	public function ajax_edit_kategori_sub(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$idKategori =$_POST['idKategori'];
		
		if ($idKategori != ''){
			$editSub = $this->mkategori->edit_data_sub($idKategori);
			// pr($editSub);
			$idParent = $editSub['idParent'];
			$getKat = $this->mkategori->get_data_kat($idParent);
			// pr($getKat);
			// echo json_encode($edit);
			$newformat = array('kategori'=>$getKat,'subkategori'=>$editSub);
			print json_encode($newformat);
		}
		exit;
	}
	
	public function ajax_update_kategori_sub(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$idsubkategori = $_POST['idsubkategori'];
		$subkategori =$_POST['subkategori'];
		if ($idsubkategori != '' && $subkategori != ''){
			$update = $this->mkategori->update_data_sub($idsubkategori,$subkategori);
		}
		exit;
		
	}
	
	public function ajax_delete_sub(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$idSubKategori = $_POST['idSubKategori'];
		$n_status = 2;
		if ($idSubKategori != ''){
			$update = $this->mkategori->update_data_sub_status($idSubKategori,$n_status);
		}
		exit;
		
	}
	
	public function count_sub(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$idKategori =$_POST['idKategori'];
		
		if ($idKategori != ''){
			$getcount = $this->mkategori->getcount($idKategori);
			echo json_encode($getcount);
		}
		exit;
	}
	
	public function ajax_update_status(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$id = $_POST['Newkategori'];
		$n_status = 2;
		if ($id != ''){
			$update = $this->mkategori->update_data_status($id,$n_status);
		}
		exit;
		
	}
	
}

?>
