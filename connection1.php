<?php
 
 
$db=mysqli_connect('localhost','root','') or die('Failed to connect to MySQL');
    mysqli_select_db($db,'uhack') or die('cannot select db');
 
function NewUser()
{
    $name = $_POST['name'];
    $userName = $_POST['ID'];
    
    $password =  $_POST['password'];
    $query = "INSERT INTO user (user_id,name,password) VALUES ('$userName','$name','$password')";
    
    $data = mysqli_query ($query)or die(mysqli_error());
    if($data)
    {
    echo "YOUR REGISTRATION IS COMPLETED...";
    }
}
 
function SignUp()
{
if(!empty($_POST['ID']))   //checking the 'user' name which is from Sign-Up.html, is it empty or have some text
{
    $query = mysqli_query("SELECT * FROM user WHERE user_ID = '$_POST[ID]' AND password = '$_POST[password]'") or die(mysqli_error());
 exit($query);
    if(!$row = mysqli_fetch_array($query) or die(mysqli_error()))
    {
        NewUser();
    }
    else
    {
        echo "SORRY...YOU ARE ALREADY REGISTERED USER...";
    }
}
}
if(isset($_POST['submit']))
{
    SignUp();
}
?>

