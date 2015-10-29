<?php
class mpengaduan extends Database {
	
	var $prefix = "";
	var $salt = "";
	function __construct()
	{
		// $this->db = new Database;
		$this->salt = "ovancop1234";
		$this->token = str_shuffle('cmsaj23y4ywdni237yeisa');
		$this->date = date('Y-m-d H:i:s');

	}

    function insert_laporan($data)
    {   

        $res = $this->insert($data,'bsn_pengaduan');
        if ($res) return $res;
        return false;
    }

    function getLatestId()
    {
        $sql = "SELECT IDENT_CURRENT('bsn_pengaduan') AS id";
        $res = $this->fetch($sql,0);

        return $res;
    }

    function insert_file($data)
    {
        $res = $this->insert($data,'bsn_file');
        if ($res) return $res;
        return false;
    }

    function getPengaduan($id=false)
    {

        $sql = "SELECT *, CONVERT(VARCHAR(19),tanggal,106) AS tanggalformat FROM bsn_pengaduan WHERE idUser = '{$id}'";
        
        $res = $this->fetch($sql,1);
        
        return $res;
    }
}
?>
