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

    function getPengaduan($idPengaduan=false)
    {

        if($idPengaduan) $cond = " WHERE idPengaduan = '{$idPengaduan}'"; else $cond = "";
        $sql = "SELECT *, CONVERT(VARCHAR(19),tanggal,106) AS tanggalformat FROM bsn_pengaduan {$cond}";
        
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name,email,hp FROM users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['nameUser'] = $user['name'];
            $res[$key]['emailUser'] = $user['email'];
            $res[$key]['hpUser'] = $user['hp'];
        }
        
        return $res;
    }

    function getFile($id)
    {
        $sql = "SELECT * FROM bsn_file WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,1);

        return $res;
    }

    function getRuangLingkup()
    {
        $sql = "SELECT * FROM bsn_kategori WHERE idParent is NULL";
        $res = $this->fetch($sql,1);

        return $res;
    }

    function getSubLingkup($rLingkup)
    {
        $sql = "SELECT * FROM bsn_kategori WHERE idParent = '{$rLingkup}'";
        $res = $this->fetch($sql,1);

        return $res;   
    }

    function getSatker()
    {
        $sql = "SELECT * FROM bsn_satker";
        $res = $this->fetch($sql,1);

        return $res;   
    }

    function insert_penelaahan($data)
    {
        $res = $this->insert($data,'bsn_penelaahan');
        if ($res) return $res;
        return false;
    }

    function getPenelaahan($id)
    {
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_penelaahan WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,0);

        return $res;
    }

    function upd_fase($id,$fase=false)
    {
        $sql = "UPDATE bsn_pengaduan SET fase = '{$fase}'";
        $res = $this->query($sql);

        return true;
    }

}
?>
