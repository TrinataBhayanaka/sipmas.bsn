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
	
}
?>