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
		global $basedomain;
		// pr($basedomain);
		// uploadFile($data,$path=null,$ext){
		
		// $quizStatistic = $this->contentHelper->quizStatistic();
		// db($quizStatistic);

		return $this->loadView('statistik/statistik_pengaduan');

	}
	
	function custom()
	{
		// pr($_POST);

		$getData = $this->mhome->customReport();
		if ($getData){

		}
		// pr($getData);
		
		return $getData;
	}

	function generateReport()
	{
		
		// $waktu=date("d-m-y_h:i:s");
		// $filename ="customReport-$waktu.xls";
		// header('Content-type: application/ms-excel');
		// header('Content-Disposition: attachment; filename='.$filename);
		// $count = count($html);
		$getData = $this->custom();
		pr($getData);
		$this->view->assign('data',$getData);
		$html = $this->loadView('statistik/custom_report');
		
		echo $html;
		exit;
		for ($i = 0; $i < $count; $i++) {
	           echo "$html[$i]";
	           
     	}
	}

	public function chart_default(){
		global $basedomain;
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
		global $basedomain;
		// pr($basedomain);
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
				// $select = array('Aktif','Di Tindak Lanjuti','Tidak Di Tinjak Lanjuti','Selesai');
				// $kode_pjbt = 1;
				/*foreach($select as $val){
					$count = $this->mhome->select_pengaduan_status($start,$end,$kode_pjbt);
					$dataKet[] = $sign.$val.$sign; 
					$dataJml[] = $count['jml']; 
				$kode_pjbt++;
				}*/
				$flag_p = '1';
				$kode_proses = '1,2,3';
				$val_proses = 'proses';
				$count_proses = $this->mhome->select_pengaduan_status($start,$end,$kode_proses,$flag_p);
				$dataJml[] = $count_proses['jml'];
				$dataKet[] = $sign.$val_proses.$sign;
				
				$flag_s = '2';
				$val_selesai = 'selesai';
				$kode_selesai = '4';
				$count_selesai = $this->mhome->select_pengaduan_status($start,$end,$kode_selesai,$flag_s);
				$dataJml[] = $count_selesai['jml'];
				$dataKet[] = $sign.$val_selesai.$sign;
				
				// pr($dataKet);
				// pr($dataJml);
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
	
	public function report(){
	// pr($_POST);
	// echo "masuk";
	// exit;
	$tipe 	= $_POST['tipe'];
	
	if($_POST['start'] != ''){
		$start	= $_POST['start'];
	}else{
		$minTgl = $this->mhome->minTgl();
		$expl = explode("-",$minTgl['tanggal']);
		$start	= $expl[2]."-".$expl[1]."-".$expl[0];
	}
		
	if($_POST['end'] != ''){
		$end 	= $_POST['end'];
	}else{
		$end	= date('Y-m-d');;
	}	
	// exit;
	if($tipe == 1){
		if($start != '' && $end != ''){
			$get_satker = $this->mhome->get_satker($start,$end);
			// pr($get_satker);
			// exit;
			foreach ($get_satker as $key=>$val){
				$implode_id = array();
				$data[] = $val;
				$nama_satker= $this->mhome->nama_satker($val['disposisi']);
				$data[$key]['nama_satker']= $nama_satker['nama_satker'];
				$jml_pengaduan = $this->mhome->jml_pengaduan($val['disposisi'],$start,$end);
				$data[$key]['jml_pengaduan']= $jml_pengaduan['jml'];
				$id_pengaduan = $this->mhome->id_pengaduan($val['disposisi'],$start,$end);
				// pr($id_pengaduan);
				$count = count($id_pengaduan);
				for ($i = 0; $i <$count; $i++){
					$implode_id[] = $id_pengaduan[$i]['idPengaduan'];
				}
				$id_fix = implode(',',$implode_id);
				$data[$key]['id_fix']= $id_fix;
				
				$klmp_1 = $this->mhome->get_klmp_1($data[$key]['id_fix']);
				$klmp_2 = $this->mhome->get_klmp_2($data[$key]['id_fix']);
				$klmp_3 = $this->mhome->get_klmp_3($data[$key]['id_fix']);
				$klmp_4 = $this->mhome->get_klmp_4($data[$key]['id_fix']);
				
				$data[$key]['klmp_1']= $klmp_1['jml'];
				$data[$key]['klmp_2']= $klmp_2['jml'];
				$data[$key]['klmp_3']= $klmp_3['jml'];
				$data[$key]['klmp_4']= $klmp_4['jml'];
				
				$status_proses = $this->mhome->status_proses($data[$key]['disposisi']);
				$data[$key]['status_proses']= $status_proses['jml'];
				$status_selesai = $this->mhome->status_selesai($data[$key]['disposisi']);
				$data[$key]['status_selesai']= $status_selesai['jml'];
				
				$count_pengaduan_blm_selesai = $this->mhome->count_pengaduan_blm_selesai($data[$key]['id_fix']);
				$persentase_penelahaan_1 =round(($count_pengaduan_blm_selesai['jml']/$data[$key]['jml_pengaduan']) * 100,2);
				$data[$key]['persentase_penelahaan']= $persentase_penelahaan_1;
				
				$count_tindaklanjut_blm_selesai = $this->mhome->count_tindaklanjut_blm_selesai($data[$key]['id_fix']);
				$persentase_penelahaan_2 =round(($count_tindaklanjut_blm_selesai['jml']/$data[$key]['jml_pengaduan']) * 100,2);
				$data[$key]['persentase_tindaklanjut']= $persentase_penelahaan_2;
				
				$count_sgt_puas = $this->mhome->count_sgt_puas($data[$key]['id_fix']);
				$count_puas = $this->mhome->count_puas($data[$key]['id_fix']);
				$count_tidak_puas = $this->mhome->count_tidak_puas($data[$key]['id_fix']);
				$count_sgt_tidak_puas = $this->mhome->count_sgt_tidak_puas($data[$key]['id_fix']);
				$data[$key]['count_sgt_puas']= $count_sgt_puas['jml'];
				$data[$key]['count_puas']= $count_puas['jml'];
				$data[$key]['count_tidak_puas']= $count_tidak_puas['jml'];
				$data[$key]['count_sgt_tidak_puas']= $count_sgt_tidak_puas['jml'];
				// exit;
			}
			$this->reportHelper =$this->loadModel('reportHelper');
				
			$head = "<div style=\"width: ; text-align: center;\">
					 <table style=\"text-align: ; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
						<thead>
							<tr>
								<td rowspan = \"2\" style=\"text-align: center;\">No</td>
								<td rowspan = \"2\" style=\"text-align: center;\">Unit kerja</td>
								<td rowspan = \"2\" style=\"text-align: center;\">Jumlah Pengaduan</td>
								<td colspan =\"4\" style=\"text-align: center;\">Kelompok Aduan</td>
								<td colspan =\"2\" style=\"text-align: center;\">Status</td>
								<td colspan =\"2\" style=\"text-align: center;\">Presentase Pengaduan <br>Jatuh Tempo (%)</td>
								<td colspan =\"4\" style=\"text-align: center;\">Tingkat Kepuasan Masyarakat</td>
							</tr>
							<tr>
								<td style=\"text-align: center;\">a</td>
								<td style=\"text-align: center;\">b</td>
								<td style=\"text-align: center;\">c</td>
								<td style=\"text-align: center;\">d</td>
								<td style=\"text-align: center;\">Selesai</td>
								<td style=\"text-align: center;\">Dalam Proses</td>
								<td style=\"text-align: center;\">Penelaahan</td>
								<td style=\"text-align: center;\">Tindak Lanjut</td>
								<td style=\"text-align: center;\">Sangat Puas</td>
								<td style=\"text-align: center;\">Puas</td>
								<td style=\"text-align: center;\">Tidak Puas</td>
								<td style=\"text-align: center;\">Sangat Tidak Puas</td>
							</tr>
						</thead>
						<tbody>";
				$no = 1;				
				foreach ($data as $val){	
				$body.="<tr>
							<td style=\"text-align: center;\">$no</td>
							<td>$val[nama_satker]</td>
							<td style=\"text-align: center;\">$val[jml_pengaduan]</td>
							<td style=\"text-align: center;\">$val[klmp_1]</td>
							<td style=\"text-align: center;\">$val[klmp_2]</td>
							<td style=\"text-align: center;\">$val[klmp_3]</td>
							<td style=\"text-align: center;\">$val[klmp_4]</td>
							<td style=\"text-align: center;\">$val[status_selesai]</td>
							<td style=\"text-align: center;\">$val[status_proses]</td>
							<td style=\"text-align: center;\">$val[persentase_penelahaan]</td>
							<td style=\"text-align: center;\">$val[persentase_tindaklanjut]</td>
							<td style=\"text-align: center;\">$val[count_sgt_puas]</td>
							<td style=\"text-align: center;\">$val[count_puas]</td>
							<td style=\"text-align: center;\">$val[count_tidak_puas]</td>
							<td style=\"text-align: center;\">$val[count_sgt_tidak_puas]</td>
						</tr>"; 
				$no++;		
				}			
				$footer="</tbody>
					</table>
				</div>";
			$html = $head.$body.$footer;
			$generate = $this->reportHelper->loadMpdf($html, 'format1',2);			
		}
		// pr($data);
		// pr($html);
		// exit;
		
	}elseif($tipe == 2){
		if($start != '' && $end != ''){
			$get_satker_single = $this->mhome->get_satker_single($start,$end);
			foreach ($get_satker_single as $key=>$val){
				$data[] = $val;
				$select_tgl_penelahaan = $this->mhome->select_tgl_penelahaan($val['idPengaduan']);
				$data[$key]['tanggalpenelaahan']=$select_tgl_penelahaan['tanggalpenelaahan'];
				$select_tgl_tindak_lanjut = $this->mhome->select_tgl_tindak_lanjut($val['idPengaduan']);
				$data[$key]['tanggaltindaklanjut']=$select_tgl_tindak_lanjut['tanggaltindaklanjut'];
				$nama_satker = $this->mhome->nama_satker($val['disposisi']);
				$data[$key]['nama_satker']=$nama_satker['nama_satker'];
				$tngkt_kepuasan = $this->mhome->tngkt_kepuasan($val['idPengaduan']);
				$data[$key]['survey']=$tngkt_kepuasan['n_status'];
				$type = 2;
				$statusDisposisi = 1;
				$masa = $this->mhome->masa($type,$statusDisposisi);
				$tglJatuhTempo=$this->mhome->tglJatuhTempo($masa['value'],$val['idPengaduan']);
				$data[$key]['tglJatuhTempo']=$tglJatuhTempo['tglJatuhTempo'];
				$survey=$this->mhome->survey($val['idPengaduan']);
				$data[$key]['survey']=$survey['n_status'];
			}
			// pr($data);
			// exit;
			$this->reportHelper =$this->loadModel('reportHelper');
			$head = "<div style=\"width: ; text-align: center;\">
					 <table style=\"text-align: ; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
						<thead>
							<tr>
								<td style=\"text-align: center;\">No</td>
								<td style=\"text-align: center;\">Unit kerja</td>
								<td style=\"text-align: center; width: 90px;\" >Tanggal <br>Masuk</td>
								<td style=\"text-align: center; width: 90px;\">Tanggal <br>Penelaahan</td>
								<td style=\"text-align: center; width: 100px;\">Tanggal <br>Tindak Lanjut</td>
								<td style=\"text-align: center; width: 90px;\">Tanggal <br>Jatuh Tempo</td>
								<td style=\"text-align: center; width: 90px;\">Status</td>
								<td style=\"text-align: center;\">Tingkat Kepuasan Masyarakat</td>
							</tr>
						</thead>
						<tbody>";
				$no = 1;				
				foreach ($data as $val){
				if($val['survey'] == 1){
					$ket = "Sangat Puas";
				}elseif($val['survey'] == 2){
					$ket = "Puas";
				}elseif($val['survey'] == 3){
					$ket = "Tidak Puas";
				}elseif($val['survey'] == 4){
					$ket = "Sangat Tidak Puas";
				}
				
				if($val['status'] == 1 || $val['status'] == 2 || $val['status'] == 3){
					$status = "Proses";
				}elseif($val['survey'] == 4){
					$status = "Selesai";
				}
				$body.="<tr>
							<td style=\"text-align: center;\">$no</td>
							<td>$val[nama_satker]</td>
							<td style=\"text-align: center; width: 60px; \">$val[tanggalmasuk]</td>
							<td style=\"text-align: center; width: 60px;\">$val[tanggalpenelaahan]</td>
							<td style=\"text-align: center; width: 60px;\">$val[tanggaltindaklanjut]</td>
							<td style=\"text-align: center; width: 60px;\">$val[tglJatuhTempo]</td>
							<td style=\"text-align: center; width: 90px;\">$status</td>
							<td style=\"text-align: ;\">$ket</td>
						</tr>"; 
				$no++;		
				}			
				$footer="</tbody>
					</table>
				</div>";
			$html = $head.$body.$footer;
			// pr($html);
			// exit;
			$generate = $this->reportHelper->loadMpdf($html, 'format2',2);		
			
		}
	}elseif($tipe == 3){
		if($start != '' && $end != ''){
		/*
		Berkadar Pengawasan (kelompok_pengaduan = 1)
		tidak Berkadar Pengawasan (kelompok_pengaduan = 2)
		susbtansinya tidak logis (kelompok_pengaduan = 3)
		Bukan Kewenangan BSN (kelompok_pengaduan = 4)
		*/
		$kelompok = array("0"=>1,"1"=>2,"2"=>3,"3"=>4);
		$getidPengaduan = $this->mhome->getidPengaduan($start,$end);
		$count = count($getidPengaduan);
		for ($i = 0; $i <$count; $i++){
			$implode_id[] = $getidPengaduan[$i]['idPengaduan'];
		}
		$idFix = implode(',',$implode_id);
		// pr($idFix);
		// exit;
		foreach ($kelompok as $key=>$value){
			$tglPnlhnJthTmp =array();
			$persentasePnlhn = 0;
			$persentaseTndkLnjt = 0;
			// $getjmlPengaduan = $this->mhome->getjmlPengaduan($idFix,$value);
			$data[$key]['KlmpkId']=$value;
			//tanggal Pengaduan
			$getTglPengaduan = $this->mhome->getTglPengaduan($idFix,$value);
			$data[$key]['TglPengaduan']=$getTglPengaduan;
			
			//tanggal Penelahaan
			$getTglPenelahaan = $this->mhome->getTglPenelahaan($idFix,$value);
			$data[$key]['TglPenelahaan']=$getTglPenelahaan;
			
			$type = 2;
			$statusPenelaahan = 1;
			$masa = $this->mhome->masa($type,$statusPenelaahan);
			$param_masa = "P".$masa['value']."D";
			foreach($data[$key]['TglPengaduan'] as $val){
				$date = new DateTime($val['tanggalpengaduan']);
				$date->add(new DateInterval($param_masa));
				$tglPnlhnJthTmp[] = $date->format('d-m-Y') ;
				
			}
			//tanggal Jatuh Tempo Penelahaan
			$data[$key]['TglPenelahaanJthTempo']=$tglPnlhnJthTmp;
			// pr($data);
			$i= 0;
			$arrmatch 	= array();
			$arrnomatch	= array();
			$match = 0;
			$nomatch = 0;
			foreach ($data[$key]['TglPenelahaan'] as $tgl){
				$tempTglPnlhan = $tgl['tanggalpenelaahan'];
				$tempTglJthTempo = $data[$key]['TglPenelahaanJthTempo'][$i];
				if($tempTglPnlhan <= $tempTglJthTempo){
					$match 		= $match + 1;
					$arrmatch[]	= $match;
				}else{
					$nomatch 		= $nomatch + 1;
					$arrnomatch[]	= $nomatch;
				}
				$i++;
			}
			//jml laporan pengaduan(Penelahaan) yang tidak Jatuh Tempo 
			$data[$key]['Match_Penelahaan']=$arrmatch;
			//jml laporan pengaduan(Penelahaan) yang tidak Jatuh Tempo 
			$data[$key]['NoMatch_Penelahaan']=$arrnomatch;
			$valNoMatchPnlhn = end($data[$key]['NoMatch_Penelahaan']);
			$jmlPengaduanPnlhn = end($data[$key]['Match_Penelahaan']) + end($data[$key]['NoMatch_Penelahaan']);
			
			if($valNoMatchPnlhn != '' && $jmlPengaduanPnlhn != ''){
				$persentasePnlhn = round(($valNoMatchPnlhn / $jmlPengaduanPnlhn)*100,2);
			}
			$data[$key]['persentasePnlhn']=$persentasePnlhn;
			
			$getIdfromPenelahaan = $this->mhome->getIdfromPenelahaan($idFix,$value);
			// pr($getIdfromPenelahaan);
			$implode_id_2 = array();
			$count2 = count($getIdfromPenelahaan);
			for ($i = 0; $i <$count2; $i++){
				$implode_id_2[] = $getIdfromPenelahaan[$i]['idPengaduan'];
			}
			$idFixPnlhn = implode(',',$implode_id_2);
			// pr($idFixPnlhn);
			if($idFixPnlhn){
				$getTglTindakLanjut = $this->mhome->getTglTindakLanjut($idFixPnlhn);
			}
			$data[$key]['TglTindakLanjut']=$getTglTindakLanjut;
			
			//tanggal Jatuh Tempo Tindak Lanjut
			$data[$key]['TglTindakLanjutJthTempo']=$tglPnlhnJthTmp;
			
			$x= 0;
			$arrmatch_2 	= array();
			$arrnomatch_2	= array();
			$match_2 = 0;
			$nomatch_2 = 0;
			foreach ($data[$key]['TglTindakLanjut'] as $tgl){
				$tempTglTindakLanjut = $tgl['tanggaltindaklanjut'];
				$tempTglTindakLanjutJthTempo = $data[$key]['TglTindakLanjutJthTempo'][$i];
				if($tempTglTindakLanjut <= $tempTglTindakLanjutJthTempo){
					$match_2 		= $match_2 + 1;
					$arrmatch_2[]	= $match_2;
				}else{
					$nomatch_2 		= $nomatch_2 + 1;
					$arrnomatch_2[]	= $nomatch_2;
				}
				$x++;
			}
			//jml laporan pengaduan(Penelahaan) yang tidak Jatuh Tempo 
			$data[$key]['Match_TindakLanjut']=$arrmatch_2;
			//jml laporan pengaduan(Penelahaan) yang tidak Jatuh Tempo 
			$data[$key]['NoMatch_TindakLanjut']=$arrnomatch_2;
			$valNoMatchTndkLnjt = end($data[$key]['NoMatch_TindakLanjut']);
			$jmlPengaduanTndkLnjt = end($data[$key]['Match_TindakLanjut']) + end($data[$key]['NoMatch_TindakLanjut']);
			if($valNoMatchTndkLnjt != '' && $jmlPengaduanTndkLnjt !=''){
				$persentaseTndkLnjt = round(($valNoMatchTndkLnjt / $jmlPengaduanTndkLnjt)*100,2);
			}
			$data[$key]['persentaseTndkLnjt']=$persentaseTndkLnjt;
		}
		// pr($data);
		// exit;
			$this->reportHelper =$this->loadModel('reportHelper');
			$head = "<div style=\"width: ; text-align: center;\">
					 <table style=\"text-align: ; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
						<thead>
							<tr>
								<td rowspan =\"2\" style=\"text-align: center; width: 10px;\">No</td>
								<td rowspan =\"2\" style=\"text-align: center; width: 90px; \">Kelompok Pengaduan</td>
								<td colspan =\"2\" style=\"text-align: center; width: 60px; \" >Presentase Jatuh Tempo (%)</td>
								<td rowspan =\"2\" style=\"text-align: center; width: 60px; \">Tingkat Kepuasan Masyarakat </td>
							</tr>
							<tr>
								<td style=\"text-align: center;\">Penelaahan</td>
								<td style=\"text-align: center;\">Tindak Lanjut</td>
							</tr>
						</thead>
						<tbody>";
				$no = 1;				
				foreach ($data as $val){
				if($val['KlmpkId'] == 1){
					$ket = "Berkadar Pengawasan";
				}elseif($val['KlmpkId'] == 2){
					$ket = "Tidak Berkadar Pengawasan";
				}elseif($val['KlmpkId'] == 3){
					$ket = "Susbtansinya Tidak Logis";
				}elseif($val['KlmpkId'] == 4){
					$ket = "Bukan Kewenangan BSN";
				}
				
				$body.="<tr>
							<td style=\"text-align: center;\">$no</td>
							<td style=\"text-align: ; width: 90px; \">$ket</td>
							<td style=\"text-align: center; width: 60px; \">$val[persentasePnlhn]</td>
							<td style=\"text-align: center; width: 60px;\">$val[persentaseTndkLnjt]</td>
							<td style=\"text-align: ;\"></td>
						</tr>"; 
				$no++;		
				}			
				$footer="</tbody>
					</table>
				</div>";
			$html = $head.$body.$footer;
			// pr($html);
			// exit;
			$generate = $this->reportHelper->loadMpdf($html, 'format3');	
		}
		
		
	}
	
	exit;
	}
	


	
}

?>
