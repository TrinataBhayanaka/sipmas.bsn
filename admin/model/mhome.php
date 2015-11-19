<?php
class mhome extends Database {
	
		
	function select_data_inbox_pengaduan()
	{
		$query = "SELECT p.idLaporan,p.tanggal,p.judul,p.nama,p.n_status,u.name 
				FROM dbo.bsn_pengaduan as p
				inner join bsn_users as u on u.idUser = p.idUser";
		//pr($query);
		$result = $this->fetch($query,1);
		//pr($result);
		return $result;
	}

	function select_data_inbox_pengaduan_condtn($user)
	{
		$query = "SELECT p.idLaporan,p.tanggal,p.judul,p.nama,p.n_status,u.name 
				FROM dbo.bsn_pengaduan as p
				inner join bsn_users as u on u.idUser = p.idUser
				where p.disposisi = '{$user}' and p.satker = '{$user}'";
		//pr($query);
		$result = $this->fetch($query,1);
		//pr($result);
		return $result;
	}
	
	function select_data_km()
	{
		$query = "SELECT COUNT(1) AS total FROM dbo.bsn_pengaduan WHERE n_status = 0";
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
		$query = "SELECT COUNT(1) AS total FROM bsn_users WHERE  YEAR(register_date) = '{$yearid}' AND MONTH(register_date) = '{$monthid}' AND n_status IN (1)";
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
		$query = "SELECT COUNT(1) AS total FROM ck_activity_log WHERE idUser = 0 and YEAR(tanggal) = '{$yearid}' AND MONTH(tanggal) = '{$monthid}' AND n_status IN (1) ";
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
	
	function select_pengaduan_status($start,$end,$val)
	{
		$query = "select count(1) as jml from bsn_pengaduan as p 
				  where p.tanggal >='{$start}' and p.tanggal <='{$end}' 
				  and p.status = '{$val}'";
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
}
?>