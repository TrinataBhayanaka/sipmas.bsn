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
		if($data[0]['disposisi']){
			if($this->admin['satker'] == $data[0]['disposisi']){
				$this->model->upd_nstatus($idPengaduan,1);
			}
		} else {
			$this->model->upd_nstatus($idPengaduan,1);
		}
		$data[0]['isi'] = html_entity_decode(htmlspecialchars_decode($data[0]['isi'], ENT_NOQUOTES));
		$data[0]['judul'] = html_entity_decode(htmlspecialchars_decode($data[0]['judul'], ENT_NOQUOTES));

		$sisaWaktu = $this->model->getStdWaktu();
		
		if($data[0]['status']==4){
			$data[0]['sisaWaktu'] = "-";
		} else {
			$endDate = date('Y-m-d', strtotime($data[0]['tanggal'].' +'.$sisaWaktu['value'].' day'));
			$nowDate = date("Y-m-d");
			$data[0]['sisaWaktu'] = dateDiff($nowDate,$endDate);
		}
		
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
		// pr($penelaahan);
		if($penelaahan)
		{
			$subLingkup = $this->model->getSubLingkup($penelaahan['kategori']);
			$this->view->assign('subLingkup',$subLingkup);			
		}
		$data = $this->model->getPengaduan($idPengaduan);
		// pr($data);
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
		global $basedomain;

		$idPengaduan = $_GET['id'];

		$penelaahan = $this->model->getPenelaahan($idPengaduan);
		if(!isset($penelaahan['idPenelaahan'])){
			echo "<script>alert('Silahkan lakukan penelaahan terlebih dahulu');window.location.href='".$basedomain."daftar_pengaduan/penelaahan/?id={$idPengaduan}'</script>";
			exit;
		}

		if(isset($_GET['req'])){
			$dataComment = $this->model->getCommentId($_GET['req']);
			$dataComment['isi'] = html_entity_decode(htmlspecialchars_decode($dataComment['isi'],ENT_NOQUOTES));
			$this->view->assign('dataComment',$dataComment);
		}

		$data = $this->model->getPengaduan($idPengaduan);
		$dataBalas = $this->model->getComment($idPengaduan);

		$sisaWaktu = $this->model->getStdWaktu('statusTindakLanjut');
		
		if($data[0]['status']==4){
			$data[0]['sisaWaktu'] = "-";
		} else {
			$endDate = date('Y-m-d', strtotime($data[0]['tanggal'].' +'.$sisaWaktu['value'].' day'));
			$nowDate = date("Y-m-d");
			$data[0]['sisaWaktu'] = dateDiff($nowDate,$endDate);
		}

		$this->view->assign('dataBalas',$dataBalas);
		$this->view->assign('dataPengaduan',$data[0]);
		$this->view->assign('id',$idPengaduan);

		return $this->loadView('pengaduan/balas');
	
	}
	
	public function disposisi(){
		global $basedomain;

		$idPengaduan = $_GET['id'];

		$penelaahan = $this->model->getPenelaahan($idPengaduan);
		if(!isset($penelaahan['idPenelaahan'])){
			echo "<script>alert('Silahkan lakukan penelaahan terlebih dahulu');window.location.href='".$basedomain."daftar_pengaduan/penelaahan/?id={$idPengaduan}'</script>";
			exit;
		}

		$data = $this->model->getPengaduan($idPengaduan);
		$dataDisposisi = $this->model->getDisposisi($idPengaduan);
		$satker = $this->model->getSatker();
		
		$sisaWaktu = $this->model->getStdWaktu('statusDisposisi');
		
		if($data[0]['status']==4){
			$data[0]['sisaWaktu'] = "-";
		} else {
			$endDate = date('Y-m-d', strtotime($data[0]['tanggal'].' +'.$sisaWaktu['value'].' day'));
			$nowDate = date("Y-m-d");
			$data[0]['sisaWaktu'] = dateDiff($nowDate,$endDate);
		}

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

		$_POST['kesimpulan'] = htmlentities(htmlspecialchars($_POST['kesimpulan'], ENT_QUOTES));
		$_POST['rekomendasi'] = htmlentities(htmlspecialchars($_POST['rekomendasi'], ENT_QUOTES));

		$this->model->insert_penelaahan($_POST);
		$this->model->upd_rLingkup($_POST['idPengaduan'],$_POST['kategori']);
		$this->model->upd_fase($_POST['idPengaduan'],2);

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/penelaahan/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function ins_balas()
	{
		global $basedomain,$CONFIG;

		//get FILES first
        foreach($_POST as $index => $string) {
            if (strpos($string, 'fileUploadKey|') !== FALSE){
                $matches[]=$string;
                unset($_POST[$index]);
            }
        }

        foreach ($matches as $key => $value) {
            $tmp[$key] = explode("|", $value);
            $files[$key]['nama'] = $tmp[$key][1];
            $files[$key]['path'] = $tmp[$key][2];
            $files[$key]['size'] = $tmp[$key][3];
        }

		$_POST['idUser'] = $this->admin['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		
		if($_POST['idComment'])
		{	
			if(!empty($files)){
		        $pathFile = $CONFIG['admin']['upload_path'];
		        foreach ($files as $key => $val) {
		            //copy & remove file
		            $moved = copy($pathFile."tmp/".$val['nama'],$pathFile.$val['nama']);
		            deleteFile($val['nama'],'tmp');

		            $data['nama'] = $val['nama'];
		            $data['path'] = $val['path'];
		            $data['size'] = $val['size'];
		            $data['type'] = 1;
		            $data['idComment'] = $_POST['idComment'];
		            $data['n_status'] = 1;

		            $this->model->insert_file($data);
		        }
		    }
		    
			$this->model->upd_balas($_POST);
		} else{
			$this->model->insert_balas($_POST);

			$this->model->upd_fase($_POST['idPengaduan'],5);
			$_POST['status'] = 2;
			$this->model->updStat($_POST);
			
			$idComment = $this->model->getLatestId('bsn_comment');

			if(!empty($files)){
		        $pathFile = $CONFIG['admin']['upload_path'];
		        foreach ($files as $key => $val) {
		            //copy & remove file
		            $moved = copy($pathFile."tmp/".$val['nama'],$pathFile.$val['nama']);
		            deleteFile($val['nama'],'tmp');

		            $data['nama'] = $val['nama'];
		            $data['path'] = $val['path'];
		            $data['size'] = $val['size'];
		            $data['type'] = 1;
		            $data['idComment'] = $idComment['id'];
		            $data['n_status'] = 1;

		            $this->model->insert_file($data);
		        }
		    }
		}

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/balas/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function ins_disposisi()
	{
		global $basedomain, $CONFIG;

		//get FILES first
        foreach($_POST as $index => $string) {
            if (strpos($string, 'fileUploadKey|') !== FALSE){
                $matches[]=$string;
                unset($_POST[$index]);
            }
        }

        foreach ($matches as $key => $value) {
            $tmp[$key] = explode("|", $value);
            $files[$key]['nama'] = $tmp[$key][1];
            $files[$key]['path'] = $tmp[$key][2];
            $files[$key]['size'] = $tmp[$key][3];
        }


		$_POST['idUser'] = $this->admin['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		$_POST['n_status'] = 1;
		
		$this->model->insert_disposisi($_POST);
		$this->model->upd_nstatus($_POST['idPengaduan'],2);
		$this->model->upd_fase($_POST['idPengaduan'],4);

		$idDisposisi = $this->model->getLatestId('bsn_disposisi');

		if(!empty($files)){
	        $pathFile = $CONFIG['admin']['upload_path'];
	        foreach ($files as $key => $val) {
	            //copy & remove file
	            $moved = copy($pathFile."tmp/".$val['nama'],$pathFile.$val['nama']);
	            deleteFile($val['nama'],'tmp');

	            $data['nama'] = $val['nama'];
	            $data['path'] = $val['path'];
	            $data['size'] = $val['size'];
	            $data['type'] = 1;
	            $data['idDisposisi'] = $idDisposisi['id'];
	            $data['n_status'] = 1;

	            $this->model->insert_file($data);
	        }
	    }

    	$userToEmail = $this->model->getAllUserSatker($_POST['tujuan']);
    	$dataPengaduan = $this->model->getPengaduan($_POST['idPengaduan']);

    	//kirim email
    	foreach ($userToEmail as $key => $val) {
    		$this->view->assign('name',$val['name']); 
	        $this->view->assign('judul',$dataPengaduan[0]['judul']);
	        $this->view->assign('tanggal',$dataPengaduan[0]['tanggalformat']);
	        $this->view->assign('idLaporan',$dataPengaduan[0]['idLaporan']);
	        $this->view->assign('id',$_POST['idPengaduan']);

	        $html = $this->loadView('pengaduan/emailTemplate');
	        $send = sendGlobalMail(trim($val['email']),$CONFIG['email']['EMAIL_FROM_DEFAULT'],$html);	
    	}

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/disposisi/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function chg_status()
	{
		global $basedomain;

		$this->model->updStat($_POST);
		$this->model->upd_fase($_POST['idPengaduan'],6);

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/detail/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function stsComment()
	{
		global $basedomain;

		$sts = $this->model->stsComment($_GET['req']);

		echo "<script>alert('Update Data Berhasil');window.location.href='".$basedomain."daftar_pengaduan/balas/?id={$_GET['chg']}'</script>";
		exit;
	}

	public function ajax_stsComment()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];

		$this->model->stsComment($id);

		echo 1;
		exit;
	}
	
	public function cetak(){
	
	global $basedomain;
	$idPengaduan = $_GET['id'];

	$penelaahan = $this->model->getPenelaahan($idPengaduan);
	if($penelaahan)
	{
		$subLingkup = $this->model->getSubLingkup($penelaahan['kategori']);
		$this->view->assign('subLingkup',$subLingkup);			
	}
	$data = $this->model->getPengaduan($idPengaduan);
	$rLingkup = $this->model->getRuangLingkup();
	$subrLingkup = $this->model->getSubLingkup($penelaahan['kategori']);
	$satker = $this->model->getSatker_condtn($penelaahan['satker']);
	$file = $this->model->getFile($idPengaduan,'idFile');

	$exTgl = explode('-',$penelaahan['tanggalformat']);
	$newFormatTgl = $exTgl[2]."/".$exTgl[1]."/".$exTgl[0]; 
	
	if($penelaahan['kelompok_pengaduan'] == 1){
		$KelompokPengaduan = 'Berkadar Pengawasan';
	}elseif($penelaahan['kelompok_pengaduan'] == 2){
		$KelompokPengaduan = 'Tidak Berkadar Pengawasan';
	}elseif($penelaahan['kelompok_pengaduan'] == 3){
		$KelompokPengaduan = 'Tidak Logis';
	}elseif($penelaahan['kelompok_pengaduan'] == 4){
		$KelompokPengaduan = 'Bukan Kewenangan BSN';
	}
	
	if($penelaahan['pejabat'] == 1){
		$NamaPejabat = 'Pejabat Eselon I';
	}elseif($penelaahan['pejabat'] == 2){
		$NamaPejabat = 'Pejabat Eselon II';
	}elseif($penelaahan['pejabat'] == 3){
		$NamaPejabat = 'Pejabat Eselon III';
	}elseif($penelaahan['pejabat'] == 4){
		$NamaPejabat = 'Pejabat Eselon IV';
	}
	
	foreach ($rLingkup as $val){
		if($val['idKategori'] == $penelaahan['kategori']){
			$kategori_ruang_lingkup = $val['ruang_lingkup'];
		}	
	}
	
	foreach ($subrLingkup as $subval){
			$kategori_sub_ruang_lingkup = $subval['ruang_lingkup'];
	}
	
	foreach ($satker as $satkerval){
		if($satkerval['idSatker'] == $penelaahan['satker']){
			$NamaSatker = $satkerval['nama_satker'];
		}
	}
	
	
		$this->reportHelper =$this->loadModel('reportHelper');

    	$this->view->assign('data',$data[0]); 
    	$this->view->assign('newFormatTgl',$newFormatTgl); 
    	$this->view->assign('KelompokPengaduan',$KelompokPengaduan); 
    	$this->view->assign('kategori_ruang_lingkup',$kategori_ruang_lingkup); 
    	$this->view->assign('kategori_sub_ruang_lingkup',$kategori_sub_ruang_lingkup); 
    	$this->view->assign('NamaSatker',$NamaSatker); 
    	$this->view->assign('NamaPejabat',$NamaPejabat); 
    	$this->view->assign('penelaahan',$penelaahan); 
		
		$html =$this->loadView('pengaduan/cetakpenelaahan');
		
		
		// echo $html;
		// exit;	
		$generate = $this->reportHelper->loadMpdf($html, 'pengaduan');
		
	}
	
	function uploadAjax()
    {
        global $app_domain;

        $output_dir = $app_domain."public_assets/tmp/";
        if(isset($_FILES["myfile"]))
        {
            $ret = array();
            
        //  This is for custom errors;  
        /*  $custom_error= array();
            $custom_error['jquery-upload-file-error']="File already exists";
            echo json_encode($custom_error);
            die();
        */
            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $upload = uploadFile('myfile','tmp');
                $ret[0] = $upload['full_name'];
                $ret[1] = $upload['real_name'];
                $ret[2] = formatSizeUnits($_FILES["myfile"]["size"]);
                
            }
            else  //Multiple files, file[]
            {
              $fileCount = count($_FILES["myfile"]["name"]);
              for($i=0; $i < $fileCount; $i++)
              {
                $upload = uploadFile('myfile','tmp');
                $ret[0] = $upload['full_name'];
                $ret[1] = $upload['real_name'];
                $ret[2] = formatSizeUnits($_FILES["myfile"]["size"]);
              }
            
            }
            echo json_encode($ret);
            exit;
         }
    }

    function deleteAjax()
    {
        global $app_domain;

        $output_dir = $app_domain."public_assets/tmp/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files    
            $filePath = $output_dir. $fileName;

            deleteFile($fileName,'tmp');

            echo "Deleted File ".$fileName."<br>";
            exit;
        }
    }
	
}

?>
