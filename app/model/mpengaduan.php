<?php
class mpengaduan extends Database {
	
	var $prefix = "";
	var $salt = "";
	function __construct()
	{
        parent::__construct();
		// $this->db = new Database;
		$this->salt = "ovancop1234";
		$this->token = str_shuffle('cmsaj23y4ywdni237yeisa');
		$this->date = date('Y-m-d H:i:s');

	}

    function insert_laporan($data)
    {   

        $res = $this->insert($data,'bsn_pengaduan');
        $latestId = $this->getLatestId();
        // db($latestId);
        $year = date("Y");
        $sql = "UPDATE bsn_pengaduan SET idLaporan = '{$latestId['id']}{$year}' WHERE idPengaduan = '{$latestId['id']}'";
        $res = $this->query($sql);
        if ($res) return $latestId;
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

    function getPengaduan($id=false,$idPengaduan=false)
    {

        if($idPengaduan) $cond = " AND idPengaduan = '{$idPengaduan}'"; else $cond = "";
        $sql = "SELECT *, CONVERT(VARCHAR(19),tanggal,106) AS tanggalformat FROM bsn_pengaduan WHERE idUser = '{$id}' {$cond}";
        
        $res = $this->fetch($sql,1);
        if ($res){

            foreach ($res as $key => $value) {
                $sqlSomment = "SELECT * FROM {$this->prefix}_comment WHERE idPengaduan = '{$value['idPengaduan']}'";
                // pr($sqlSomment);
                $resComment = $this->fetch($sqlSomment,0);
                $res[$key]['comment'][] = $resComment;
            }
            
        }
        return $res;
    }

    function getFile($id)
    {
        $sql = "SELECT * FROM bsn_file WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,1);

        return $res;
    }

    function getPenelaahan($id)
    {
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_penelaahan WHERE idPengaduan = '{$id}'";
        $res = $this->fetch($sql,0);
        
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
}
?>
