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
		$this->mkategori = $this->loadModel('mkategori');
	}
	
	public function index(){
		
		$dataUser['table'] = "bsn_users";
		$dataUser['condition'] = array('type'=>'1,3', 'n_status'=>'0,1,2');
		$dataUser['in'] = array('n_status','type');
		$getUser = $this->userHelper->fetchData($dataUser);
		
		if ($getUser){
			foreach ($getUser as $key => $value) {
				$dataSatker['table'] = "bsn_satker";
				$dataSatker['condition'] = array('n_status'=>1, 'idSatker'=>$value['satker']);
				$getSatker = $this->userHelper->fetchData($dataSatker);
				$getUser[$key]['nama_satker'] = $getSatker[0];
			}

			// //pr($getUser);
			$this->view->assign('user', $getUser);
		}

		return $this->loadView('pengaturan/pengaturan_admin');

	}
	
	public function waktukriteria(){

		$ws="";
		$wp1="";
		$wp2="";
		if($_GET['open']){
			if($_GET['open']=="ws"){
				$ws="in";
			}elseif($_GET['open']=="wp1"){
				$wp1="in";
			}elseif($_GET['open']=="wp2"){
				$wp2="in";
			}else{
				$ws="in";
			}
			
		}else{
			$ws="in";
		}

		$arraySW['table'] = "bsn_waktu_kriteria";
		$arraySW['condition'] = array('type'=>1);

		$arrayWP1['table'] = "bsn_waktu_kriteria";
		$arrayWP1['condition'] = array('type'=>2,);

		$arrayWP2['table'] = "bsn_waktu_kriteria";
		$arrayWP2['condition'] = array('type'=>3);

		$dataSW = $this->contentHelper->fetchData($arraySW);
		$dataWP1 = $this->contentHelper->fetchData($arrayWP1);
		$dataWP2 = $this->contentHelper->fetchData($arrayWP2);
// //pr($dataSW);
		$queryws="";
		$querywp1="";
		$querywp2="";

		$table="_waktu_kriteria";

		if(!$dataSW){
			// $this->view->assign('queryws',"update");
			$insertSW[]=array('name' => "Baik",'value'=>0,'type'=>1,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertSW[]=array('name' => "Normal",'value'=>0,'type'=>1,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertSW[]=array('name' => "Buruk",'value'=>0,'type'=>1,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);

			foreach ($insertSW as $keySW => $valueSW) {

				$dataSW = $this->contentHelper->simpanData(1,$valueSW,$table);

			}

			$dataSW = $this->contentHelper->fetchData($arraySW);
		}
		if(!$dataWP1){
			// $this->view->assign('querywp1',"update");
			$insertWP1[]=array('name' => "Pencatatan",'value'=>0,'type'=>2,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertWP1[]=array('name' => "Penelaahan",'value'=>0,'type'=>2,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertWP1[]=array('name' => "Tindak Lanjut",'value'=>0,'type'=>2,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertWP1[]=array('name' => "Pengarsipan",'value'=>0,'type'=>2,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);

			foreach ($insertWP1 as $keyWP1 => $valueWP1) {

				$dataWP1 = $this->contentHelper->simpanData(1,$valueWP1,$table);

			}

			$dataWP1 = $this->contentHelper->fetchData($arrayWP1);
		}
		if(!$dataWP2){
			// $this->view->assign('querywp2',"update");
			$insertWP2[]=array('name' => "Pencatatan",'value'=>0,'type'=>3,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertWP2[]=array('name' => "Penelaahan",'value'=>0,'type'=>3,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertWP2[]=array('name' => "Tindak Lanjut",'value'=>0,'type'=>3,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);
			$insertWP2[]=array('name' => "Pengarsipan",'value'=>0,'type'=>3,'create_date'=>date('Y-m-d H:i:s'),'status'=>0);

			foreach ($insertWP2 as $keyWP2 => $valueWP2) {

				$dataWP2 = $this->contentHelper->simpanData(1,$valueWP2,$table);

			}

		$dataWP2 = $this->contentHelper->fetchData($arrayWP2);
		}
		// //pr($dataSW[0]);
		// //pr($dataWP1);
		// //pr($dataWP2);
		// exit;
		$this->view->assign('ws', $ws);
		$this->view->assign('wp1', $wp1);
		$this->view->assign('wp2', $wp2);

		$this->view->assign('dataSW', $dataSW);
		$this->view->assign('dataWP1', $dataWP1);
		$this->view->assign('dataWP2', $dataWP2);

		return $this->loadView('pengaturan/waktu_kriteria');
	}


	public function simpanWaKri(){
		global $basedomain;
		// //pr($_POST);
		// exit;
		if($_GET['open']){
			if($_GET['open']=="ws"){
				$open="ws";
			}elseif($_GET['open']=="wp1"){
				$open="wp1";
			}elseif($_GET['open']=="wp2"){
				$open="wp2";
			}else{
				$open="ws";
			}
			
		}else{
			$open="ws";
		}

		if($_POST['id']){

			foreach ($_POST['id'] as $keyid => $valueid) {
				$dataupd[]=array('id'=>$valueid,'value'=>$_POST['value'][$keyid],'create_date'=>date('Y-m-d H:i:s'));
			}
			// //pr($dataupd);
			$table="_waktu_kriteria";

			$data = $this->contentHelper->simpanData(3,$dataupd,$table);
				
			
			redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
		}else{
			redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
		}
	}
	public function deleteWaker(){
		global $basedomain;
		// //pr($_GET);
		$id = $_GET['id'];
		$type = $_GET['type'];
		if($_GET['open']){
			if($_GET['open']=="ws"){
				$open="ws";
			}elseif($_GET['open']=="wp1"){
				$open="wp1";
			}elseif($_GET['open']=="wp2"){
				$open="wp2";
			}else{
				$open="ws";
			}
			
		}else{
			$open="ws";
		}
		if ($id != ''){
			$table="bsn_waktu_kriteria";
			$condition=" id='".$id."' AND type='".$type."'";
			$delete = $this->contentHelper->delete($table,$condition);
		}else{

			redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
		}

		redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
	}
	public function statusWaker(){
		global $basedomain;
		// pr($_GET);
		$id = $_GET['id'];
		$type = $_GET['type'];
		if($_GET['open']){
			if($_GET['open']=="ws"){
				$open="ws";
			}elseif($_GET['open']=="wp1"){
				$open="wp1";
			}elseif($_GET['open']=="wp2"){
				$open="wp2";
			}else{
				$open="ws";
			}
			
		}else{
			$open="ws";
		}
		if ($id != ''){
			$table="_waktu_kriteria";
			// $condition=" id='".$id."' AND type='".$type."'";
			$delete = $this->contentHelper->updStatus($table,$id,$type);
		}else{

			redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
		}

		redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
	}
	public function tambahWaktu(){
		global $basedomain;
		// //pr($_POST);

		if($_GET['open']){
			if($_GET['open']=="ws"){
				$open="ws";
			}elseif($_GET['open']=="wp1"){
				$open="wp1";
			}elseif($_GET['open']=="wp2"){
				$open="wp2";
			}else{
				$open="ws";
			}
			
		}else{
			$open="ws";
		}
		if($_POST){
			$table="_waktu_kriteria";
			$data= array(
				'name' => $_POST['name'], 
				'value' => $_POST['value'],
				'type' => $_POST['type'],
				);

			$data = $this->contentHelper->simpanData(1,$data,$table);
		}else{
			redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);
		}	
		redirect($basedomain.'pengaturan_admin/waktukriteria/?open='.$open);

	}
	public function ubahkonten(){
	
		
		
		return $this->loadView('pengaturan/ubah_konten');

	}
	public function simpanContent(){
		global $basedomain;
		// pr($_POST);
		// pr($_REQUEST);
		if($_POST['id']){

					// $_POST['create_date'] = ;
					// $_POST['n_status']=1;
				if($_POST['query']=="update"){
					$data=array(
						'id' => $_POST['id'], 
						'title' => htmlspecialchars($_POST['title'],ENT_QUOTES), 
						'description' => htmlspecialchars($_POST['description'],ENT_QUOTES), 
						'create_date' => date('Y-m-d'), 
						);
					$result = $this->contentHelper->simpanData(2,$data,"_content");
				}elseif($_POST['query']=="insert"){
					$data=array(
						'id' => $_POST['id'], 
						'title' => htmlspecialchars($_POST['title'],ENT_QUOTES), 
						'description' =>  htmlspecialchars($_POST['description'],ENT_QUOTES), 
						'type' => $_POST['type'], 
						'category' => $_POST['category'], 
						'create_date' => date('Y-m-d'), 
						'n_status' => 1, 
						);

					$result = $this->contentHelper->simpanData(1,$data,"_content");
				}
			}
			// pr($data);
			// exit;
		if ($result){
            print json_encode(array('status'=>true));
        }else{
            print json_encode(array('status'=>false));
        }
        
        exit;
				// redirect($basedomain . 'pengaturan_admin/ubahkonten');

	}

	public function selectubahkonten(){
	
		// pr($_POST);
		$dataContent['table'] = "bsn_content";
		$dataContent['condition'] = array('type'=>$_POST['type'], 'category'=>1,'n_status'=>1);

		$data = $this->contentHelper->fetchData($dataContent);
// //pr($data);
        if ($data){
            print json_encode(array('status'=>true,'idhidden'=>$data[0]['id'], 'data'=>htmlspecialchars_decode($data[0]['description'],ENT_QUOTES),'judul'=>htmlspecialchars_decode($data[0]['title'],ENT_QUOTES)));
        }else{
            print json_encode(array('status'=>false));
        }
        
        exit;

	}
	public function ruanglingkup(){
	
		$select = $this->mkategori->select_data();
		// exit;
		if($select){
		foreach ($select as $k => $val) {
			$select_list[$k] = $val;
			$i = 0;
			$val_idKategori =$val['idKategori'];	
				$select_sub = $this->mkategori->select_data_sub($val_idKategori);
					$select_list[$k]['sub'] = $select_sub;
			}
		}	
			// //pr($select_list);
		$this->view->assign('data',$select);
		
	return $this->loadView('pengaturan/kategori_ruang_lingkup');
	}

	public function edit(){
		
		global $basedomain;

		$id = _g('id');
		if ($_POST['token']){

			// check if exist
			if (!$_POST['id']){
				$check['table'] = "bsn_users";
				$check['condition'] = array('type'=>'1,3', 'username'=>$_POST['username'], 'email'=>$_POST['email'], 'n_status'=>1);
				$check['in'] = array('type');
				$checkUser = $this->userHelper->fetchData($check);
				if ($checkUser){
					echo "<script>alert('User sudah ada'); window.location.href='{$basedomain}pengaturan_admin';</script>";
					exit;
				}
			}
			

			if ($_POST['id']){
				$dataUser['table'] = "bsn_users";
				$dataUser['condition'] = array('type'=>'1,3', 'id'=>$id);
				$dataUser['in'] = array('type');
				$getUser = $this->userHelper->fetchData($dataUser);
				$salt = $getUser[0]['salt'];
			}else{
				$salt = $this->token;
				$_POST['salt'] = $salt;
			}
			
			if ($_POST['pass']!='') $_POST['password'] = sha1($_POST['pass'] . $salt);
			$_POST['register_date'] = date('Y-m-d');
			$_POST['login_count'] = 0;
			$_POST['n_status'] = 1;
			
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
			// //pr($getUser);
			if ($getUser){

				$this->view->assign('user', $getUser[0]);
			}
			
		}

		$dataSatker['table'] = "bsn_satker";
		$dataSatker['condition'] = array('n_status'=>'1');
		$getSatker = $this->userHelper->fetchData($dataSatker);
		// //pr($getSatker);
		$this->view->assign('satker', $getSatker);
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
		
		// //pr($_POST);
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
		
		// //pr($_POST);
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
		
		// //pr($_POST);
		global $basedomain;
		$kategori =$_POST['idKategori'];
			$select = $this->mkategori->select_data_list_option($kategori);
			// //pr($select);
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
		
		// //pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$idKategori =$_POST['idKategori'];
		
		if ($idKategori != ''){
			$editSub = $this->mkategori->edit_data_sub($idKategori);
			// //pr($editSub);
			$idParent = $editSub['idParent'];
			$getKat = $this->mkategori->get_data_kat($idParent);
			// //pr($getKat);
			// echo json_encode($edit);
			$newformat = array('kategori'=>$getKat,'subkategori'=>$editSub);
			print json_encode($newformat);
		}
		exit;
	}
	
	public function ajax_update_kategori_sub(){
		
		// //pr($_POST);
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
		
		// //pr($_POST);
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
		
		// //pr($_POST);
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
		
		// //pr($_POST);
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
