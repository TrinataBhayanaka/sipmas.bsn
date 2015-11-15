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

            $sisaWaktu = $this->getStdWaktu();
            
            if($val['status']==4){
                $res[$key]['sisaWaktu'] = "-";
            } else {
                $endDate = date('Y-m-d', strtotime($val['tanggal'].' +'.$sisaWaktu['baik'].' day'));
                $nowDate = date("Y-m-d");
                $res[$key]['sisaWaktu'] = dateDiff($nowDate,$endDate);
            }
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

            $sisaWaktu = $this->getStdWaktu();
            
            if($val['status']==4){
                $res[$key]['sisaWaktu'] = "-";
            } else {
                $endDate = date('Y-m-d', strtotime($val['tanggal'].' +'.$sisaWaktu['baik'].' day'));
                $nowDate = date("Y-m-d");
                $res[$key]['sisaWaktu'] = dateDiff($nowDate,$endDate);
            }
        }
        
        return $res;
    }

    function getFile($id,$field=false)
    {
        $sql = "SELECT * FROM bsn_file WHERE {$field} = '{$id}'";
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

        $res['kesimpulan'] = html_entity_decode(htmlspecialchars_decode($res['kesimpulan'],ENT_NOQUOTES));
        $res['rekomendasi'] = html_entity_decode(htmlspecialchars_decode($res['rekomendasi'],ENT_NOQUOTES));

        return $res;
    }

    function upd_fase($id,$fase=false)
    {
        $sql = "SELECT fase FROM bsn_pengaduan WHERE idPengaduan = '{$id}'";
        $data = $this->fetch($sql,0);

        if($data['fase'] < $fase)
        {
            $sql = "UPDATE bsn_pengaduan SET fase = '{$fase}' WHERE idPengaduan = '{$id}'";
            $res = $this->query($sql);
        }

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
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_comment WHERE idPengaduan = '{$id}' ORDER BY tanggal DESC";
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name,email,hp FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['isi'] = html_entity_decode(htmlspecialchars_decode($val['isi'],ENT_NOQUOTES));
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
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_comment WHERE idPengaduan = '{$id}' ORDER BY tanggal DESC";
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name,email,type FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['isi'] = html_entity_decode(htmlspecialchars_decode($val['isi'],ENT_NOQUOTES));
            $res[$key]['nameUser'] = $user['name'];
            $res[$key]['emailUser'] = $user['email'];
            $res[$key]['typeUser'] = $user['type'];

            $file = $this->getFile($val['idComment'],'idComment');
            $res[$key]['files'] = $file;
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
        $sql = "SELECT *, CONVERT(VARCHAR(10),tanggal,20) AS tanggalformat, CONVERT(VARCHAR(19),tanggal,106) AS tanggalstd FROM bsn_disposisi WHERE idPengaduan = '{$id}' ORDER BY tanggal DESC";
        $res = $this->fetch($sql,1);

        foreach($res as $key => $val)
        {
            $sql = "SELECT name FROM bsn_users WHERE idUser = '{$val['idUser']}'";
            $user = $this->fetch($sql,0);

            $res[$key]['isi'] = html_entity_decode(htmlspecialchars_decode($val['isi'],ENT_NOQUOTES));
            $res[$key]['nameUser'] = $user['name'];
            
            $sql = "SELECT nama_satker FROM bsn_satker WHERE idSatker = '{$val['tujuan']}'";
            $satker = $this->fetch($sql,0);
            $res[$key]['nameSatker'] = $satker['nama_satker'];

            $sql = "SELECT * FROM bsn_file WHERE idDisposisi = '{$val['idDisposisi']}'";
            $file = $this->fetch($sql,1);
            $res[$key]['files'] = $file;
        }

        return $res;
    }

    function updStat($data)
    {
        $sql = "UPDATE bsn_pengaduan SET status='{$data['status']}' WHERE idPengaduan = '{$data['idPengaduan']}'";
        $res = $this->query($sql);

        return $res;
    }

    function upd_nstatus($id,$value)
    {
        $sql = "UPDATE bsn_pengaduan SET n_status = {$value} WHERE idPengaduan = '{$id}'";
        $res = $this->query($sql);

        return $res;
    }

    function upd_rLingkup($id,$kategori)
    {
        $sql = "UPDATE bsn_pengaduan SET ruangLingkup = '{$kategori}' WHERE idPengaduan = '{$id}'";
        $res = $this->query($sql);

        return $res;
    }

    function getStdWaktu()
    {
        $sql = "SELECT TOP(1) * FROM bsn_standar_waktu";
        $res = $this->fetch($sql,0);

        return $res;
    }

}
?>
