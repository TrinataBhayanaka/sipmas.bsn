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
            $sql = "SELECT name,email,hp FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['nameUser'] = $user['name'];
            $res[$key]['emailUser'] = $user['email'];
            $res[$key]['hpUser'] = $user['hp'];
        }
        
        return $res;
    }

    function getPengaduanSatker($satker=false)
    {

        if($satker) $cond = " WHERE disposisi = '{$satker}'"; else $cond = "";
        $sql = "SELECT *, CONVERT(VARCHAR(19),tanggal,106) AS tanggalformat FROM bsn_pengaduan {$cond}";
        
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name,email,hp FROM bsn_users WHERE idUser = '{$val['idUser']}'";
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
	 function getSatker_condtn($satker)
    {
        $sql = "SELECT * FROM bsn_satker where idSatker = '{$satker}'";
        $res = $this->fetch($sql,1);

        return $res;   
    }

    function getAdmUsr()
    {
        $sql = "SELECT * FROM bsn_users WHERE type='1'";
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
        $sql = "UPDATE bsn_pengaduan SET fase = '{$fase}' WHERE idPengaduan = '{$id}'";
        $res = $this->query($sql);

        return true;
    }

    function insert_balas($data)
    {
        $res = $this->insert($data,'bsn_comment');
        if ($res) return $res;
        return false;
    }

    function insert_file($data)
    {
        $res = $this->insert($data,'bsn_file');
        if ($res) return $res;
        return false;
    }

    function getLatestId($table)
    {
        $sql = "SELECT IDENT_CURRENT('{$table}') AS id";
        $res = $this->fetch($sql,0);

        return $res;
    }

    function getBalas($id)
    {
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_comment WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name,email,hp FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['isi'] = html_entity_decode($val['isi']);
            $res[$key]['nameUser'] = $user['name'];
            $res[$key]['emailUser'] = $user['email'];
            $res[$key]['hpUser'] = $user['hp'];
        }

        return $res;
    }

    function getTglBalas($id)
    {
        $sql = "SELECT TOP (1) CONVERT(VARCHAR(19),tanggal,106) AS tanggalformat FROM bsn_comment WHERE idPengaduan = '{$id}' ORDER BY tanggal ASC";
        $res = $this->fetch($sql,0);
        
        return $res;
    }

    function getComment($id)
    {
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_comment WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name,email,type FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['isi'] = html_entity_decode($val['isi']);
            $res[$key]['nameUser'] = $user['name'];
            $res[$key]['emailUser'] = $user['email'];
            $res[$key]['typeUser'] = $user['type'];
        }

        return $res;
    }

    function insert_disposisi($data)
    {
        $res = $this->insert($data,'bsn_disposisi');

        $sql = "UPDATE bsn_pengaduan SET disposisi = '{$data['tujuan']}' WHERE idPengaduan = '{$data['idPengaduan']}'";
        $res = $this->query($sql);
        if ($res) return $res;
        return false;
    }

    function getDisposisi($id)
    {
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_disposisi WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['isi'] = html_entity_decode($val['isi']);
            $res[$key]['nameUser'] = $user['name'];
            
            $sql = "SELECT nama_satker FROM bsn_satker WHERE idSatker = '{$val['tujuan']}'";
            $satker = $this->fetch($sql,0);
            $res[$key]['nameSatker'] = $satker['nama_satker'];
        }

        return $res;
    }

    function updStat($data)
    {
        $sql = "UPDATE bsn_pengaduan SET status='{$data['status']}' WHERE idPengaduan = '{$data['idPengaduan']}'";
        $res = $this->query($sql);

        return $res;
    }

}
?>
