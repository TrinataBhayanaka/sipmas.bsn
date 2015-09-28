
<?php
// Server in the this format: <computer>\<instance name> or 
// <server>,<port> when using a non default port number
$server = '192.168.56.101\SQLEXPRESS';

// Connect to MSSQL
$link = mssql_connect($server, 'sa', 'root123root');

if (!$link) {
    die('Something went wrong while connecting to MSSQL');
}else{
	echo "berhasil coy";
}
?>
