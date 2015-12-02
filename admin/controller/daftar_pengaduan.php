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
		global $basedomain;

		$_POST['idUser'] = $this->admin['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		
		if($_POST['idComment'])
		{	
			$this->model->upd_balas($_POST);
		} else{
			$this->model->insert_balas($_POST);

			$this->model->upd_fase($_POST['idPengaduan'],5);
			$_POST['status'] = 2;
			$this->model->updStat($_POST);
			    		
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
		}

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."daftar_pengaduan/balas/?id={$_POST['idPengaduan']}'</script>";
		exit;
	}

	public function ins_disposisi()
	{
		global $basedomain, $CONFIG;

		$_POST['idUser'] = $this->admin['idUser'];
		$_POST['isi'] = htmlentities(htmlspecialchars($_POST['isi'], ENT_QUOTES));
		$_POST['tanggal'] = date("Y-m-d");
		$_POST['n_status'] = 1;
		
		$this->model->insert_disposisi($_POST);
		$this->model->upd_nstatus($_POST['idPengaduan'],2);
		$this->model->upd_fase($_POST['idPengaduan'],4);

		if(!empty($_FILES['myfile']['name'])){
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
	$file = $this->model->getFile($idPengaduan);

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
		$html ="
			<style>
			#header {
				background-color:#84C726;
				color:white;
				text-align:left; padding:5px;	
			}
			#lamp {
				width:100%;
				border:1px solid #d4d4d4;
			}
			table.lamp {
				width:100%;
				border:1px solid #d4d4d4;
				
			}
			table.lamp th, td { 
				padding:10px;
			}
			table.lamp th {
				width:40px;
			}
			table.master, td{
				font-family: Times;
				font-size: 14px;
			}
			</style>
			<div id=\"header\"><h3>{$data[0]['judul']}</h3></div>
				<table class=\"lamp\">
					<tr><td>
					<table style=\"text-align: ; margin-left: auto; margin-right: auto; width: 100%;\" border=\"0\"  cellpadding=\"0\" cellspacing=\"0\">
						<tr>
							<td width=\"30%\">Tanggal Pengaduan</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$data[0]['tanggalformat']}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Tanggal Terima</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$newFormatTgl}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Kelompok Pengaduan</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$KelompokPengaduan}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Ruang Lingkup Laporan</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$kategori_ruang_lingkup}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Sub Ruang Lingkup Laporan</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$kategori_sub_ruang_lingkup}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Satuan Unit Kerja Terkait</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$NamaSatker}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Pejabat Terkait</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$NamaPejabat}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Substansi Pengaduan</td>
							<td width=\"70%\" style=\"border: 1px solid black;\">&nbsp;{$data[0]['judul']}</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Kesimpulan</td>
							<td width=\"70%\"  style=\"border: 1px solid black;\">{$penelaahan['kesimpulan']}
							</td>
						</tr>
						<tr>
							<td width=\"30%\">&nbsp;</td>
							<td width=\"70%\">&nbsp;</td>
						</tr>
						<tr>
							<td width=\"30%\">Rekomendasi</td>
							<td width=\"70%\"  style=\"border: 1px solid black;\">{$penelaahan['rekomendasi']}
							</td>
						</tr>
						</table></td>
						</tr>
						</table>";
		
		
		// echo $html;
		// exit;	
		$generate = $this->reportHelper->loadMpdf($html, 'pengaduan');
		
	}
	
	
	
}

?>
