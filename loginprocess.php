<?php
require "includes/sql_connect.php";

$userun = $_POST["username"];
$userpass = $_POST["password"];
$login = mysqli_query($connect, "SELECT * FROM users WHERE username='$userun'");

$record = mysqli_fetch_array($login,MYSQLI_ASSOC);
$success = password_verify ($userpass, $record["password"]);

if ($success)
{ 
    echo "Thank you for logging in, You will be redirected in 5 seconds";
    session_start();
    $requestnamesql = "SELECT * FROM userdetails where userdetid='".$record["uid"]."'";
    $requestname = mysqli_fetch_assoc(mysqli_query($connect,$requestnamesql));
    $_SESSION["loggedin"] = 1;
    $_SESSION["uid"] = $record["uid"];
    $_SESSION["fname"] = $requestname["firstnames"];
    $_SESSION["accesslevel"] = $record["accesspriv"];
    header("refresh:5;url=customerportal.php"); 
    exit ();/* Redirect browser if successful */
} else {
    header("Location: login.php?error=1");
    exit();
    /* Redirect browser if wrong username or password */;
};
/*
$updateonetime = mysqli_query($connect, "UPDATE users SET password='$hashedpassword' WHERE username='warddavid886@gmail.com'");
if(! $updateonetime )
{
  die('Could not update data: ' . mysqli_error());
}; */
?>
