<?php 
header("Content-Type: text/html; charset=ISO-8859-1");
$http = "http://";
$host = "localhost";
$folder = "/";
$user = "rachel";
$password = "12";
$database = "wisearch";

if(isset($_SERVER["HTTP_REFERER"])){if($_SERVER["HTTP_REFERER"]!=$http.$host.$folder){exit("{'success':1}"); } }else{exit("{'success':1}"); }

function connect (){
	$con = mysqli_connect($GLOBALS['host'],$GLOBALS['user'],$GLOBALS['password'],$GLOBALS['database']);	
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	return $con;
}
function fetch_data($data){
	while($row = mysqli_fetch_assoc($data)) {
	    $rows[] = $row;
	}
	return $rows;
}

?>