<?php
	$username=$_POST['username'];
	$password=md5($_POST['password']);

	$login=$_SERVER['HTTP_REFERER'];
	
	if((!$username) or (!$password))
	{
	header("Location:$login");

	}
	$conn=@mysql_connect("localhost","rachel","12") or die("Cannot Connect");
	$rs=@mysql_select_db("wisearch",$conn) or die("DB ERROR");
	$sql="select * from user where id=\"$username\" and password=\"$password\"";
   
	$rs=mysql_query($sql,$conn) or die ("Could not execute");

	$rows=mysql_numrows($rs);

	if($rows!=0)
	{
    header("Location:profile.html");
	//echo("<h2> $username ok welcome </h2>");
	}
	else
	{
	 header("Location:$login");
    echo("<h2> $username not welcome </h2>");
	exit();
	}
	
?>