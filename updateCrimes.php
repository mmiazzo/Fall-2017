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
$vquery='Select ArrestMade FROM ReportedCrimes WHERE CaseID="'.$ID.'";';
$vdata = mysqli_query($dbcon, $vquery)
		or die('Validation Failed: ' . mysqli_error());
if($vdata -> num_rows==0){
	die("Invalid CaseID!");
} elseif(mysqli_fetch_row($data)[0]==1){
	die("Case already marked with arrest");
}
$query='UPDATE ReportedCrimes SET ArrestMade=1 WHERE CaseID="'.$ID.'";';
if(mysqli_query($dbcon, $query)){
	print 'Update Complete!';
}else{
	print 'Update Failed';
}

?>