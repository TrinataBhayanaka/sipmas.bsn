<?php
class mstatistik extends Database {
	
	
	function select_data_proses($years,$month)
	{

		$filter = "";
		if ($years AND $years !=3) $filter .= " AND YEAR(tanggal) = '{$years}'";
		if ($month AND $month !=15) $filter .= " AND MONTH(tanggal) = '{$month}'";

		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status in(1,2,3) {$filter}";
		logFile($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	function select_data_proses_default()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status in(1,2,3)";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_selesai($years,$month)
	{
		$filter = "";
		if ($years AND $years !=3) $filter .= " AND YEAR(tanggal) = '{$years}'";
		if ($month AND $month !=15) $filter .= " AND MONTH(tanggal) = '{$month}'";

		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE status = '4' {$filter}";
		logFile($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}

	function select_data_selesai_default()
	{
		$query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE  status = '4'";
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	/*
	tabel : dbo.bsn_pengaduan & dbo.bsn_survey 
	SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
		inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
	where u.n_status = '1' and YEAR(tanggal) = '2015' AND MONTH(tanggal) = '10'
	*/
	
	function select_data_a($years,$month)
	{

		$filter = "";
		if ($years AND $years !=3) $filter .= " AND YEAR(tanggal) = '{$years}'";
		if ($month AND $month !=15) $filter .= " AND MONTH(tanggal) = '{$month}'";

		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '1'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '1' {$filter}";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	function select_data_a_default()
	{
		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '1'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '1'";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_dl($years,$month)
	{

		$filter = "";
		if ($years AND $years !=3) $filter .= " AND YEAR(tanggal) = '{$years}'";
		if ($month AND $month !=15) $filter .= " AND MONTH(tanggal) = '{$month}'";


		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '2'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '2' {$filter}";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}

	function select_data_dl_default()
	{
		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '2'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '2' ";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	
	function select_data_tdl($years,$month)
	{
		$filter = "";
		if ($years AND $years !=3) $filter .= " AND YEAR(tanggal) = '{$years}'";
		if ($month AND $month !=15) $filter .= " AND MONTH(tanggal) = '{$month}'";

		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '3'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '3' {$filter}";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
	
	function select_data_tdl_default()
	{
		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '3'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '3'";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}

	function select_data_na($years,$month)
	{
		
		$filter = "";
		if ($years AND $years !=3) $filter .= " AND YEAR(tanggal) = '{$years}'";
		if ($month AND $month !=15) $filter .= " AND MONTH(tanggal) = '{$month}'";

		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '4'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '4' {$filter}";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}	

	function select_data_na_default()
	{
		// $query = "SELECT COUNT(1) AS total FROM bsn_pengaduan WHERE YEAR(tanggal) = '{$years}' AND MONTH(tanggal) = '{$month}' AND status = '4'";
		$query = "SELECT count(p.idPengaduan) as total FROM dbo.bsn_pengaduan as p
				  inner join dbo.bsn_survey as u on u.survey = p.idPengaduan
				  where u.n_status = '4' ";
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		return $result;
	}
}
?>