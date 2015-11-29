<?php
class mhome extends Database {
	
		
	function select_data_inbox_pengaduan()
	{
		$query = "SELECT p.idPengaduan,p.idLaporan,p.tanggal,p.judul,p.nama,p.n_status,u.name 
				FROM dbo.bsn_pengaduan as p
				inner join bsn_users as u on u.idUser = p.idUser";
		//pr($query);
		$result = $this->fetch($query,1);
		//pr($result);
		return $result;
	}

	function select_data_inbox_pengaduan_condtn($user)
	{
		$query = "SELECT p.idPengaduan,p.idLaporan,p.tanggal,p.judul,p.nama,p.n_status,u.name 
				FROM dbo.bsn_pengaduan as p
				inner join bsn_users as u on u.idUser = p.idUser
				where p.disposisi = '{$user}'";
		//pr($query);
		$result = $this->fetch($query,1);
		//pr($result);
		return $result;
	}
	
	function select_data_km()
	{
		$query = "SELECT COUNT(*) AS total FROM dbo.bsn_pengaduan";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_st()
	{
		$query = "SELECT COUNT(1) AS total FROM dbo.bsn_pengaduan WHERE n_status = 1 ";
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_data_bt()
	{
		$query = "SELECT COUNT(1) AS total FROM dbo.bsn_pengaduan WHERE n_status = 2 ";
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_data_proses($years,$month)
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status  in (1,2,3)";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_selesai($years,$month)
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status  = '4'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	
	function select_data_a($years,$month)
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '1'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	function select_data_a_default()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status = '1'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_dl($years,$month)
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '2'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	function select_data_dl_default()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status = '2'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_tdl($years,$month)
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '3'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	function select_data_tdl_default()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status = '3'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_na($years,$month)
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '4'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	function select_data_na_default()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status = '4'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_register_user()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_users WHERE  n_status IN (1)";
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function select_data_register_user_condt($monthid,$yearid)
	{

		$filter = "";
		if ($yearid) $filter .= " AND YEAR(register_date) = '{$yearid}'";
		if ($monthid) $filter .= " AND MONTH(register_date) = '{$monthid}'";

		$query = "SELECT COUNT(1) AS total FROM bsn_users WHERE  n_status IN (1) {$filter}";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_data_visitor_user()
	{
		$query = "SELECT COUNT(1) AS total FROM ck_activity_log WHERE idUser = 0 and n_status IN (1) ";
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function select_data_visitor_user_condt($monthid,$yearid)
	{

		$filter = "";
		if ($yearid) $filter .= " AND YEAR(tanggal) = '{$yearid}'";
		if ($monthid) $filter .= " AND MONTH(tanggal) = '{$monthid}'";

		$query = "SELECT COUNT(1) AS total FROM ck_activity_log WHERE n_status IN (1) {$filter}";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_1($start,$end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 1
				  and p.tanggal >='{$start}' and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_2($start,$end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 2
				   and p.tanggal >='{$start}' and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_3($start,$end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 3
				   and p.tanggal >='{$start}' and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_4($start,$end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 4
				   and p.tanggal >='{$start}' and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_1_def($end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 1
				  and p.tanggal <='{$end}'and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_2_def($end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 2
				  and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_3_def($end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 3
				  and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_klmpk_4_def($end)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where pn.kelompok_pengaduan = 4
				  and p.tanggal <='{$end}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_rlp()
	{
		$query = "SELECT idKategori,ruang_lingkup FROM bsn_kategori where n_status = 1 and idParent is null";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function select_rlp_cndtn($start,$end,$val)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where 
				  p.tanggal >='{$start}' and p.tanggal <='{$end}' and 
				  pn.kategori='{$val}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_rlp_sub()
	{
		$query = "SELECT idKategori,ruang_lingkup FROM bsn_kategori where n_status = 1 and idParent is not null";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function select_rlp_sub_cndtn($start,$end,$val)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where 
				  p.tanggal >='{$start}' and p.tanggal <='{$end}' and 
				  pn.sub_kategori='{$val}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_satker()
	{
		$query = "SELECT idSatker,nama_satker FROM bsn_satker where n_status = 1";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function select_satker_cndtn($start,$end,$val)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where 
				  p.tanggal >='{$start}' and p.tanggal <='{$end}' and 
				  pn.satker='{$val}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	function select_pejabat_cndtn($start,$end,$val)
	{
		$query = "select count(1) as jml from bsn_penelaahan as pn inner join 
				  bsn_pengaduan as p on p.idPengaduan = pn.idPengaduan where 
				  p.tanggal >='{$start}' and p.tanggal <='{$end}' and 
				  pn.pejabat='{$val}' and pn.n_status = 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_pengaduan_status($start,$end,$val,$flag)
	{
		if($flag == 1){
			$query = "select count(1) as jml from bsn_pengaduan as p 
				  where p.tanggal >='{$start}' and p.tanggal <='{$end}' 
				  and p.status in ({$val})";
		}else{
			$query = "select count(1) as jml from bsn_pengaduan as p 
				  where p.tanggal >='{$start}' and p.tanggal <='{$end}' 
				  and p.status = '{$val}'";
			
		}
		
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_pengaduan_survey($start,$end,$val)
	{
		$query = "select count(1) as jml from bsn_pengaduan as p inner join 
					bsn_survey as s on s.survey = p.idPengaduan where 
					p.tanggal >='{$start}' and p.tanggal <='{$end}' 
					and s.n_status='{$val}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	
	function get_satker($start,$end){
		$query = "SELECT p.disposisi FROM bsn_pengaduan as p 
					where p.tanggal >= '{$start}' and p.tanggal <= '{$end}'	
					group by p.disposisi";
		// pr($query);			
		$result = $this->fetch($query,1);
		
		return $result;
	}

	function nama_satker($val){
			$query = "SELECT s.nama_satker FROM bsn_satker as s where s.idSatker = '{$val}' and n_status = 1";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
	
	function jml_pengaduan($val,$start,$end){
			$query = "SELECT count(p.idPengaduan) as jml FROM bsn_pengaduan as p
					where p.tanggal >= '{$start}' and p.tanggal <= '{$end}' and p.disposisi = '{$val}'";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}
			
	function id_pengaduan($val,$start,$end){
			$query = "SELECT p.idPengaduan FROM bsn_pengaduan as p 
					 inner join bsn_penelaahan as pe on pe.idPengaduan = p.idPengaduan	
					 where p.disposisi = '{$val}' and p.tanggal >= '{$start}' and p.tanggal <= '{$end}'";
			// pr($query);
			$result = $this->fetch($query,1);
			
			return $result;
		}
		
	function get_klmp_1($val){
			$query = "SELECT count(idPenelaahan) as jml FROM bsn_penelaahan where idPengaduan in ({$val}) and kelompok_pengaduan = 1";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
		
	function get_klmp_2($val){
			$query = "SELECT count(idPenelaahan) as jml FROM bsn_penelaahan where idPengaduan in ({$val}) and kelompok_pengaduan = 2";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	

	function get_klmp_3($val){
			$query = "SELECT count(idPenelaahan) as jml FROM bsn_penelaahan where idPengaduan in ({$val}) and kelompok_pengaduan = 3";
			// pr($query); 
			$result = $this->fetch($query);
			
			return $result;
		}
	function get_klmp_4($val){
			$query = "SELECT count(idPenelaahan) as jml FROM bsn_penelaahan where idPengaduan in ({$val}) and kelompok_pengaduan = 4";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}
	function status_proses($val){
			$query = "select count(idPengaduan) as jml FROM bsn_pengaduan where status in (1,2,3) and disposisi = '{$val}'";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
	function status_selesai($val){
			$query = "select count(idPengaduan) as jml FROM bsn_pengaduan where status = 4 and disposisi = '{$val}'";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
	function count_pengaduan_blm_selesai($val){
			$query = "select count(idPengaduan) as jml from bsn_pengaduan where idPengaduan in ({$val}) and status in(1,2,3) and fase = 1 ";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
			
	function count_tindaklanjut_blm_selesai($val){
			$query = "select count(idPengaduan) as jml from bsn_pengaduan where idPengaduan in ({$val}) and status in(1,2,3) and fase in (2,4) ";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}
		
	function count_sgt_puas($val){
			$query = "select count(idSurvey) as jml from bsn_survey where survey in ({$val}) and n_status = 1";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
	
	function count_puas($val){
			$query = "select count(idSurvey) as jml from bsn_survey where survey in ({$val}) and n_status = 2";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}
		
	function count_tidak_puas($val){
			$query = "select count(idSurvey) as jml from bsn_survey where survey in ({$val}) and n_status = 3";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}
		
	function count_sgt_tidak_puas($val){
			$query = "select count(idSurvey) as jml from bsn_survey where survey in ({$val}) and n_status = 4";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
			
	function get_satker_single($start,$end){
		$query = "SELECT p.disposisi,p.idPengaduan,CONVERT(VARCHAR(19),p.tanggal,105) AS tanggalmasuk,p.status FROM bsn_pengaduan as p 
					where p.tanggal >= '{$start}' and p.tanggal <= '{$end}'";
		$result = $this->fetch($query,1);
		
		return $result;
	}	
	
	function select_tgl_penelahaan($id){
			$query = "select CONVERT(VARCHAR(19),tanggal,105) AS tanggalpenelaahan from bsn_penelaahan  where idPengaduan = '{$id}' and n_status = 1";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
	function select_tgl_tindak_lanjut($id){
			$query = "select CONVERT(VARCHAR(19),tanggal,105) AS tanggaltindaklanjut from bsn_comment  where idPengaduan = '{$id}' and n_status = 1";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}
  function tngkt_kepuasan($id){
			$query = "select n_status from bsn_survey  where survey = '{$id}' ";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}		
  function masa($type,$statusDisposisi){
		$query = "select value from bsn_waktu_kriteria  where statusTindakLanjut = '{$statusDisposisi}' and type = '{$type}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function tglJatuhTempo($masa,$id){
		$query = "SELECT CONVERT(VarChar(19), DATEADD(day, {$masa}, tanggal), 105) as tglJatuhTempo from bsn_pengaduan where  idPengaduan = '{$id}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	
	}
	function getidPengaduan($start,$end){
		/*$query = "SELECT pl.idPengaduan from bsn_pengaduan as p 
				 inner join bsn_penelaahan as pl on pl.idPengaduan = p.idPengaduan
				 where pl.tanggal >= '{$start}' and pl.tanggal <='{$end}'";*/
				 
		$query = "SELECT p.idPengaduan from bsn_pengaduan as p 
				 where p.tanggal >= '{$start}' and p.tanggal <='{$end}'";		 
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function getTglPenelahaan($idfix,$val){
		$query = "SELECT CONVERT(VARCHAR(19),tanggal,105) AS tanggalpenelaahan from bsn_penelaahan where 
				kelompok_pengaduan ='{$val}' and idPengaduan in ({$idfix})";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function getTglPengaduan($idfix,$val){
		$query = "SELECT CONVERT(VARCHAR(19),p.tanggal,105) AS tanggalpengaduan from bsn_pengaduan as p 
				inner join bsn_penelaahan as pl on pl.idPengaduan = p.idPengaduan
				where pl.kelompok_pengaduan ='{$val}' and pl.idPengaduan in ({$idfix})";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function getIdfromPenelahaan($idfix,$val){
		$query = "SELECT  pl.idPengaduan from bsn_pengaduan as p 
				inner join bsn_penelaahan as pl on pl.idPengaduan = p.idPengaduan
				where pl.kelompok_pengaduan ='{$val}' and pl.idPengaduan in ({$idfix})";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function getTglTindakLanjut($idfix){
		$query = "SELECT CONVERT(VARCHAR(19),p.tanggal,105) AS tanggaltindaklanjut from bsn_comment as p 
				inner join bsn_penelaahan as pl on pl.idPengaduan = p.idPengaduan
				where p.idPengaduan in ({$idfix})";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function minTgl(){
		$query = "SELECT  CONVERT(VARCHAR(19),p.tanggal,105) as tanggal FROM bsn_pengaduan as p";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function survey($val){
			$query = "select n_status from bsn_survey where survey in ({$val})";
			// pr($query);
			$result = $this->fetch($query);
			
			return $result;
		}	
	
	
}
?>