<?php
function connectToDB(){
	$host = 'mpcs53001.cs.uchicago.edu';
	$username = 'mmiazzo';
	$password = 'In,iotcf1';
	$database = $username.'DB';
	$dbcon = mysqli_connect($host, $username, $password, $database)
	   or die('Could not connect: ' . mysqli_connect_error());
	print 'Connected successfully!<br>';
	return $dbcon;
}
$ID = "'".$_REQUEST['CaseID']."'";
$dbcon=connectToDB();
$vquery='Select * FROM ReportedCrimes WHERE CaseID="'.$ID.'";';
$vdata = mysqli_query($dbcon, $vquery)
		or die('Query Failed ' . mysqli_error());
if($vdata -> num_rows==0){
	die("Invalid Service Request Number!");
}
$delQuery='DELETE FROM ReportedCrimes WHERE CaseID="'.$ID.'";';
if(mysqli_query($dbcon, $delQuery)){
	print 'Deletion successful';
} else{
	print 'Deletion Failed: ' . mysqli_error();
}


?>