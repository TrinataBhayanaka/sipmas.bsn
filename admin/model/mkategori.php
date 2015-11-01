<?php
class mkategori extends Database {
	
	function insert_data($kategori,$idParent,$n_status)
	{
		$query = "INSERT INTO bsn_kategori (ruang_lingkup,idParent,n_status)
				  VALUES ('".addslashes(html_entity_decode($kategori))."',$idParent,'$n_status')";
		// echo $query;'".addslashes(html_entity_decode($syaratkelulusan))."'
		$result = $this->query($query);
		// return $result;

	}	
		
	function select_data(){
		$query = "SELECT * FROM bsn_kategori where idParent is null and n_status = 1 order by idKategori asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	
	}	
		
	function edit_data($idKategori){
		$query = "SELECT ruang_lingkup FROM bsn_kategori WHERE  idKategori = '{$idKategori}' and n_status = 1 ";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}	
		
	function update_data($id,$kategori){
		$query = "UPDATE bsn_kategori
						SET 
							ruang_lingkup = '".addslashes(html_entity_decode($kategori))."'
						WHERE
							idKategori = '{$id}'";
		// echo $query;					
		$result = $this->query($query);					
	}
	
	function select_data_list_option($kategori){
		$query = "SELECT idKategori,ruang_lingkup FROM bsn_kategori where idKategori ='{$kategori}' and n_status = 1 ";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	
	}	
	
	function insert_data_sub($kategori,$subkategori,$n_status)
	{
		$query = "INSERT INTO bsn_kategori (ruang_lingkup,idParent,n_status)
				  VALUES ('".addslashes(html_entity_decode($subkategori))."','$kategori','$n_status')";
		$result = $this->query($query);
		
	}	

	function select_data_sub($id){
		$query = "SELECT * FROM bsn_kategori where idParent = '{$id}' and n_status = 1 ";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	
	}	

	function edit_data_sub($idKategori){
		$query = "SELECT * FROM bsn_kategori WHERE  idKategori = '{$idKategori}' and n_status = 1 ";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}	
		
	function get_data_kat($idKategori){
		$query = "SELECT ruang_lingkup FROM bsn_kategori WHERE  idKategori = '{$idKategori}' and n_status = 1 ";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}		
	
	function update_data_sub($idsubkategori,$subkategori){
		$query = "UPDATE bsn_kategori
						SET 
							ruang_lingkup = '".addslashes(html_entity_decode($subkategori))."'
						WHERE
							idKategori = '{$idsubkategori}'";
		// echo $query;					
		$result = $this->query($query);					
	}
	
	function update_data_sub_status($idSubKategori,$n_status){
		$query = "UPDATE bsn_kategori
						SET 
							n_status = '{$n_status}'
						WHERE
							idKategori = '{$idSubKategori}'";
		// echo $query;					
		$result = $this->query($query);					
	}
	
	function getcount($idKategori){
		$query = "SELECT count(1) as jml FROM bsn_kategori WHERE  idParent = '{$idKategori}' and n_status = 1 ";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}	
	function update_data_status($id,$n_status){
		$query = "UPDATE bsn_kategori
						SET 
							n_status = '{$n_status}'
						WHERE
							idKategori = '{$id}'";
		// echo $query;					
		$result = $this->query($query);					
	}	
}
?>