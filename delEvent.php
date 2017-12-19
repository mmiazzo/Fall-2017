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
$ID=$_REQUEST['Event'];
$dbcon=connectToDB();
$query="DELETE FROM MajorEvents WHERE ID=$ID;";
if(mysqli_query($dbcon, $query)){
	print 'Deletion Successful!';
}else{
	print "Deletion Failed!";
}

?>