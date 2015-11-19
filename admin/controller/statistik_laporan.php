<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class statistik_laporan extends Controller {
	
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
		$this->mhome = $this->loadModel('mhome');
	}
	
	public function index(){
		
		// uploadFile($data,$path=null,$ext){
		
		// $quizStatistic = $this->contentHelper->quizStatistic();
		// db($quizStatistic);

		return $this->loadView('statistik/statistik_pengaduan');

	}
	
	public function chart_default(){
		$end = date('Y-m-d');
		$klmpk_1= $this->mhome->select_klmpk_1_def($end);
		$klmpk_2= $this->mhome->select_klmpk_2_def($end);
		$klmpk_3= $this->mhome->select_klmpk_3_def($end);
		$klmpk_4= $this->mhome->select_klmpk_4_def($end);
		
		$newformat = array('klmpk_1'=>$klmpk_1,'klmpk_2'=>$klmpk_2,'klmpk_3'=>$klmpk_3,'klmpk_4'=>$klmpk_4);
		
		//pr($newformat);
		print json_encode($newformat);
		exit;

	}
	
	public function chart(){
	// pr($_POST);
	
		$start = $_POST['start'];
		$end = $_POST['end'];
		$komponen = $_POST['komponen'];
		if($start != '' && $end !='' && $komponen !=''){
			if($komponen == 1){
				//kelompok pengaduan
				$klmpk_1= $this->mhome->select_klmpk_1($start,$end);
				$klmpk_2= $this->mhome->select_klmpk_2($start,$end);
				$klmpk_3= $this->mhome->select_klmpk_3($start,$end);
				$klmpk_4= $this->mhome->select_klmpk_4($start,$end);
				
				$newformat = array('klmpk_1'=>$klmpk_1,'klmpk_2'=>$klmpk_2,'klmpk_3'=>$klmpk_3,'klmpk_4'=>$klmpk_4);
				
				//pr($newformat);
				print json_encode($newformat);
				exit;
		  }elseif($komponen == 3){
				//ruang lingkup laporan
				$sign="'";
				$select = $this->mhome->select_rlp();
				foreach($select as $val){
					$count = $this->mhome->select_rlp_cndtn($start,$end,$val['idKategori']);
					$dataKet[] = $sign.$val['ruang_lingkup'].$sign; 
					$dataJml[] = $count['jml']; 
				}
				$count =count($dataKet);
				$x= 0;
				for ($i=97; $i<=122; $i++) {
				$Letter[] = chr($i);
				$frmt [] = $sign.$Letter[$x].$sign;
				$x++;
				}
				
				$newArrayLetter = array_slice($Letter, 0, $count);
				foreach($newArrayLetter as $new){
					$newArrayLtr[] = $sign.$new.$sign;
				}
				
				$q=0;
				foreach($newArrayLetter as $value){
					$newkey[]= $value.":".$dataJml[$q];
				$q++;
				}
				
				$flaglabel=implode(",", $newArrayLtr);
				$label = implode(',',$dataKet);
				$key   = implode(',',$newkey);
				
				$NewKey ="[{ y: 'Ruang Lingkup Laporan', $key},]"; 
				$newSignStart="[";
				$newSignEnd="]";
				$Newflaglabel = $newSignStart.$flaglabel.$newSignEnd;
				$Newlabel = $newSignStart.$label.$newSignEnd;
				
				$newformat = array('label'=>$Newlabel,'key'=>$NewKey,'flag'=>$Newflaglabel);
				print json_encode($newformat);
				exit;
		 
			}elseif($komponen == 4){
				//sub ruang lingkup laporan
				$sign="'";
				$select = $this->mhome->select_rlp_sub();
				foreach($select as $val){
					$count = $this->mhome->select_rlp_sub_cndtn($start,$end,$val['idKategori']);
					$dataKet[] = $sign.$val['ruang_lingkup'].$sign; 
					$dataJml[] = $count['jml']; 
				}
				$count =count($dataKet);
				$x= 0;
				for ($i=97; $i<=122; $i++) {
				$Letter[] = chr($i);
				$frmt [] = $sign.$Letter[$x].$sign;
				$x++;
				}
				
				$newArrayLetter = array_slice($Letter, 0, $count);
				foreach($newArrayLetter as $new){
					$newArrayLtr[] = $sign.$new.$sign;
				}
				
				$q=0;
				foreach($newArrayLetter as $value){
					$newkey[]= $value.":".$dataJml[$q];
				$q++;
				}
				
				$flaglabel=implode(",", $newArrayLtr);
				$label = implode(',',$dataKet);
				$key   = implode(',',$newkey);
				
				$NewKey ="[{ y: 'Sub Ruang Lingkup Laporan', $key},]"; 
				$newSignStart="[";
				$newSignEnd="]";
				$Newflaglabel = $newSignStart.$flaglabel.$newSignEnd;
				$Newlabel = $newSignStart.$label.$newSignEnd;
				
				$newformat = array('label'=>$Newlabel,'key'=>$NewKey,'flag'=>$Newflaglabel);
				print json_encode($newformat);
				exit;
			
			}elseif($komponen == 5){
				//satuan unit kerja
				$sign="'";
				$select = $this->mhome->select_satker();
				foreach($select as $val){
					$count = $this->mhome->select_satker_cndtn($start,$end,$val['idSatker']);
					$dataKet[] = $sign.$val['nama_satker'].$sign; 
					$dataJml[] = $count['jml']; 
				}
				$count =count($dataKet);
				$x= 0;
				for ($i=97; $i<=122; $i++) {
				$Letter[] = chr($i);
				$frmt [] = $sign.$Letter[$x].$sign;
				$x++;
				}
				
				$newArrayLetter = array_slice($Letter, 0, $count);
				foreach($newArrayLetter as $new){
					$newArrayLtr[] = $sign.$new.$sign;
				}
				
				$q=0;
				foreach($newArrayLetter as $value){
					$newkey[]= $value.":".$dataJml[$q];
				$q++;
				}
				
				$flaglabel=implode(",", $newArrayLtr);
				$label = implode(',',$dataKet);
				$key   = implode(',',$newkey);
				
				$NewKey ="[{ y: 'Satuan Unit Kerja Terkait', $key},]"; 
				$newSignStart="[";
				$newSignEnd="]";
				$Newflaglabel = $newSignStart.$flaglabel.$newSignEnd;
				$Newlabel = $newSignStart.$label.$newSignEnd;
				
				$newformat = array('label'=>$Newlabel,'key'=>$NewKey,'flag'=>$Newflaglabel);
				print json_encode($newformat);
				exit;
			
			}elseif($komponen == 6){
				//Pejabat Terkait
				$sign="'";
				$select = array('Pejabat Eselon I','Pejabat Eselon II','Pejabat Eselon III','Pejabat Eselon IV');
				$kode_pjbt = 1;
				foreach($select as $val){
					$count = $this->mhome->select_pejabat_cndtn($start,$end,$kode_pjbt);
					$dataKet[] = $sign.$val.$sign; 
					$dataJml[] = $count['jml']; 
				$kode_pjbt++;
				}
				$count =count($dataKet);
				$x= 0;
				for ($i=97; $i<=122; $i++) {
				$Letter[] = chr($i);
				$frmt [] = $sign.$Letter[$x].$sign;
				$x++;
				}
				
				$newArrayLetter = array_slice($Letter, 0, $count);
				foreach($newArrayLetter as $new){
					$newArrayLtr[] = $sign.$new.$sign;
				}
				
				$q=0;
				foreach($newArrayLetter as $value){
					$newkey[]= $value.":".$dataJml[$q];
				$q++;
				}
				
				$flaglabel=implode(",", $newArrayLtr);
				$label = implode(',',$dataKet);
				$key   = implode(',',$newkey);
				
				$NewKey ="[{ y: 'Satuan Unit Kerja Terkait', $key},]"; 
				$newSignStart="[";
				$newSignEnd="]";
				$Newflaglabel = $newSignStart.$flaglabel.$newSignEnd;
				$Newlabel = $newSignStart.$label.$newSignEnd;
				
				$newformat = array('label'=>$Newlabel,'key'=>$NewKey,'flag'=>$Newflaglabel);
				print json_encode($newformat);
				exit;
			
			}elseif($komponen == 7){
				//Status Laporan
				$sign="'";
				$select = array('Aktif','Di Tindak Lanjuti','Tidak Di Tinjak Lanjuti','Selesai');
				$kode_pjbt = 1;
				foreach($select as $val){
					$count = $this->mhome->select_pengaduan_status($start,$end,$kode_pjbt);
					$dataKet[] = $sign.$val.$sign; 
					$dataJml[] = $count['jml']; 
				$kode_pjbt++;
				}
				$count =count($dataKet);
				$x= 0;
				for ($i=97; $i<=122; $i++) {
				$Letter[] = chr($i);
				$frmt [] = $sign.$Letter[$x].$sign;
				$x++;
				}
				
				$newArrayLetter = array_slice($Letter, 0, $count);
				foreach($newArrayLetter as $new){
					$newArrayLtr[] = $sign.$new.$sign;
				}
				
				$q=0;
				foreach($newArrayLetter as $value){
					$newkey[]= $value.":".$dataJml[$q];
				$q++;
				}
				
				$flaglabel=implode(",", $newArrayLtr);
				$label = implode(',',$dataKet);
				$key   = implode(',',$newkey);
				
				$NewKey ="[{ y: 'Status Laporan', $key},]"; 
				$newSignStart="[";
				$newSignEnd="]";
				$Newflaglabel = $newSignStart.$flaglabel.$newSignEnd;
				$Newlabel = $newSignStart.$label.$newSignEnd;
				
				$newformat = array('label'=>$Newlabel,'key'=>$NewKey,'flag'=>$Newflaglabel);
				print json_encode($newformat);
				exit;
			
			}elseif($komponen == 8){
				//Status Laporan
				$sign="'";
				$select = array('Sangat Puas','Puas','Tidak Puas','Sangat Tidak Puas');
				$kode_pjbt = 1;
				foreach($select as $val){
					$count = $this->mhome->select_pengaduan_survey($start,$end,$kode_pjbt);
					$dataKet[] = $sign.$val.$sign; 
					$dataJml[] = $count['jml']; 
				$kode_pjbt++;
				}
				$count =count($dataKet);
				$x= 0;
				for ($i=97; $i<=122; $i++) {
				$Letter[] = chr($i);
				$frmt [] = $sign.$Letter[$x].$sign;
				$x++;
				}
				
				$newArrayLetter = array_slice($Letter, 0, $count);
				foreach($newArrayLetter as $new){
					$newArrayLtr[] = $sign.$new.$sign;
				}
				
				$q=0;
				foreach($newArrayLetter as $value){
					$newkey[]= $value.":".$dataJml[$q];
				$q++;
				}
				
				$flaglabel=implode(",", $newArrayLtr);
				$label = implode(',',$dataKet);
				$key   = implode(',',$newkey);
				
				$NewKey ="[{ y: 'Status Laporan', $key},]"; 
				$newSignStart="[";
				$newSignEnd="]";
				$Newflaglabel = $newSignStart.$flaglabel.$newSignEnd;
				$Newlabel = $newSignStart.$label.$newSignEnd;
				
				$newformat = array('label'=>$Newlabel,'key'=>$NewKey,'flag'=>$Newflaglabel);
				print json_encode($newformat);
				exit;
			
			}
		}
	
	}
	
	
	


	
}

?>
