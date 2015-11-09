<?php

class statistik extends Controller {
	
	var $models = FALSE;
	var $view;
	var $reportHelper;
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
		$getUserLogin = $this->isUserOnline();
		$this->user = $getUserLogin[0];
    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
		$this->mstatistik = $this->loadModel('mstatistik');
		$this->mpengaduan = $this->loadModel('mpengaduan');
	}
	
	function index(){
		
		$dataPengaduan = $this->mpengaduan->getPengaduan($this->user['idUser']);
		$this->view->assign('user',$this->user);
		return $this->loadView('statistik/view_statistik');
		
    }
	
	function chart (){
		$years = date('Y');
		$month = date('m');
		$date  = date('d');
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		$newformatdate= strftime( "%B", strtotime($month_rev));
		
		$proses= $this->mstatistik->select_data_proses($years,$month);
		$selesai= $this->mstatistik->select_data_selesai($years,$month);
		
		$newformat = array('proses'=>$proses,'selesai'=>$selesai,'month'=>$newformatdate,'years'=>$years);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}
	
	function chartDefault (){
		// $years = date('Y');
		$years = '';
		$month = date('m');
		$date  = date('d');
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		// $newformatdate= strftime( "%B", strtotime($month_rev));
		$newformatdate= '';
		
		$proses= $this->mstatistik->select_data_proses_default();
		$selesai= $this->mstatistik->select_data_selesai_default();
		
		$newformat = array('proses'=>$proses,'selesai'=>$selesai,'month'=>$newformatdate,'years'=>$years);
		// pr($newformat);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}

	public function chart_condtn(){
		
		$month = $_POST['monthid'];
		$years = $_POST['yearid'];
		$date  = date('d');
		
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		$newformatdate= strftime( "%B", strtotime($month_rev));
		
		$proses= $this->mstatistik->select_data_proses($years,$month);
		$selesai= $this->mstatistik->select_data_selesai($years,$month);
		
		$newformat = array('proses'=>$proses,'selesai'=>$selesai,'month'=>$newformatdate,'years'=>$years);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}

	public function chart_bar(){
		$years = date('Y');
		$month = date('m');
		$date  = date('d');
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		$newformatdate= strftime( "%B", strtotime($month_rev));
		
		// $newformatdate= $month;
		
		$aktif= $this->mstatistik->select_data_a($years,$month);
		// pr($aktif);
		$ditinjak_lanjuti= $this->mstatistik->select_data_dl($years,$month);
		// pr($ditinjak_lanjuti);
		$tidak_ditinjak_lanjuti= $this->mstatistik->select_data_tdl($years,$month);
		// pr($tidak_ditinjak_lanjuti);
		$non_aktif= $this->mstatistik->select_data_na($years,$month);
		// pr($non_aktif);
		$newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif,'month'=>$newformatdate,'years'=>$years);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}	

	public function chart_bar_condtn(){
		
		$month = $_POST['monthid_v2'];
		$years = $_POST['yearid_v2'];
		$date  = date('d');
		
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		$newformatdate= strftime( "%B", strtotime($month_rev));
		
		$aktif= $this->mstatistik->select_data_a($years,$month);
		// pr($aktif);
		$ditinjak_lanjuti= $this->mstatistik->select_data_dl($years,$month);
		// pr($ditinjak_lanjuti);
		$tidak_ditinjak_lanjuti= $this->mstatistik->select_data_tdl($years,$month);
		// pr($tidak_ditinjak_lanjuti);
		$non_aktif= $this->mstatistik->select_data_na($years,$month);
		// pr($non_aktif);
		$newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif,'month'=>$newformatdate,'years'=>$years);
		// $newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}

	public function chart_bar_default(){
		
		$month = $_POST['monthid_v2'];
		//$years = $_POST['yearid_v2'];
		$years = '';
		$date  = date('d');
		
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		//$newformatdate= strftime( "%B", strtotime($month_rev));
		$newformatdate= '';
		
		$aktif= $this->mstatistik->select_data_a_default();
		// pr($aktif);
		$ditinjak_lanjuti= $this->mstatistik->select_data_dl_default();
		// pr($ditinjak_lanjuti);
		$tidak_ditinjak_lanjuti= $this->mstatistik->select_data_tdl_default();
		// pr($tidak_ditinjak_lanjuti);
		$non_aktif= $this->mstatistik->select_data_na_default();
		// pr($non_aktif);
		$newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif,'month'=>$newformatdate,'years'=>$years);
		// $newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}


	
}

?>
