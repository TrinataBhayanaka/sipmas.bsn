<?php
class contentHelper extends Database {
	
	var $prefix = "lelang";

	function tracking($postTracking,$type){
// pr($_POST);
        if($type=='1'){
        	$query = "SELECT P.*,Usr.name FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.judul LIKE '%{$postTracking}%'";
    	}elseif($type=='2'){

    	}elseif($type=='3'){
    		$query = "SELECT P.*,Usr.name FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.satker LIKE '%{$postTracking}%'";
    	}elseif($type=='4'){
    		
    	}elseif($type=='5'){
    		$query = "SELECT P.*,Usr.name FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.status='{$postTracking}'";
    	}elseif($type=='6'){

    		 $query = "SELECT P.*,Usr.name FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE Usr.email='{$postTracking}' OR Usr.name LIKE '%{$postTracking}%'";	
    	}

        $result = $this->fetch($query,1); 

        
        // pr($result);
        // exit;
        return $result;

    }

    function getContent($type=1,$category=1){
        $query = "SELECT * FROM bsn_content WHERE type='{$type}' AND category='{$category}' AND n_status='1'";
        // pr($query);
        $result = $this->fetch($query,1);

        return $result;
    }
    function updContent($id){
		$query = "UPDATE bsn_content
						SET 
							description = '{$_POST['content']}'
						WHERE
							id = '{$id}'";
		// echo $query;					
		$result = $this->query($query);					
	}	
	function getData()
	{
		$sql = "SELECT * FROM code_activity";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function getMessage()
	{
		$sql = "SELECT m.*, um.name,um.email FROM my_message m LEFT JOIN user_member um ON m.receive = um.id ";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function saveMessage($data)
	{
		foreach ($data as $key => $val){
			$tmpfield[] = $key;
			$tmpdata[] = "'$val'";
		}
		// from,to,subject,content,createdate,n_status
		$tmpfield[] = 'fromwho';
		$tmpfield[] = 'createdate';
		$tmpfield[] = 'n_status';
		
		$date = date('Y-m-d H:i:s');
		$tmpdata[] = 0;
		$tmpdata[] = "'{$date}'";
		$tmpdata[] = 1;
		
		$field = implode(',',$tmpfield);
		$data = implode(',',$tmpdata);
		
		$sql = "INSERT INTO my_message ({$field}) 
				VALUES ({$data})";
		// pr($sql);
		// exit;
		$res = $this->query($sql);
		if ($res) return true;
		return false;
	}
	
	function get_user($data)
	{
		$query = "SELECT * FROM user_member WHERE email = '{$data}'";
		
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function importData($name=null)
	{
		$query = "INSERT INTO import (name,n_status) VALUES ('{$name}', 1)";
		// pr($query);
		$result = $this->query($query);
		
		return $result;
	}
	
	function getRegistrant($n_status=1, $debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"user",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	function getCourse($n_status=1, $debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"kursus",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	function getOnlineUser($n_status=1, $debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"user",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) AND is_online = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	function getDownloadEbook($n_status=1,$debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"file",
                'field'=>"SUM(downloadCount) AS total",
                'condition' => "n_status IN ({$n_status}) {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	function quizStatistic()
	{
		$sql = array(
                'table'=>"nilai AS n, grup_kursus AS gk",
                'field'=>"COUNT(n.idUser) AS total, gk.namagrup",
                'joinmethod'=>'LEFT JOIN',
                'join'=>'n.idGroupKursus = gk.idGrup_kursus',
                'condition' => "n.n_status = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
	}
	function saveData($data=array(), $table="_users", $debug=false)
    {

        if ($table == "_users"){

            if ($data['id']) $id = " idUser = {$data['id']}";  
            else $id = "";
        } 
        else $id = " id = {$data['id']}";
        if ($id){

            $run = $this->save("update", "{$this->prefix}{$table}", $data, $id, $debug);

        }else{
            $data['createDate'] = date('Y-m-d H:i;s');
            $run = $this->save("insert", "{$this->prefix}{$table}", $data, false, $debug);
    
        }

        if ($run) return true;
        return false;
    }
    function simpanData($query,$data=array(), $table="_content", $debug=false)
    {
    	// pr($query);
    	// pr($data);exit;
    	$id="id =".$data['id'];
        if ($query==2){
            $run = $this->save("update", "{$this->prefix}{$table}", $data, $id, $debug);

        }else{
          	
            $run = $this->save("insert", "{$this->prefix}{$table}", $data, false, $debug);
    
        }

        if ($run) return true;
        return false;
    }
      function fetchData($data=array(),$debug=false)
    {

        $table = $data['table'];
        $condition = $data['condition'];
        $oderby = $data['oderby'];
        $additional = $data;

        $fetch = $this->fetchSingleTable($table, $condition, $oderby, $additional, $debug);
        if ($fetch) return $fetch;
        return false;
    }

	

}
?>