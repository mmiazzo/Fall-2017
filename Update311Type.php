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
$ID = $_REQUEST['311ID'];
$Type=$_REQUEST['TypesUpdate'];

if($Type=='AVC'){
	$Type="'Abandoned Vehicle Complaint'";
} elseif($Type=='PotHole'){
	$Type="'Pot Hole In Street'";
} elseif($Type=='Graffiti'){
	$Type="'Graffiti Removal'";
}
$dbcon=connectToDB();
$vquery="SELECT * FROM 311Calls WHERE ServiceRequestNumber='$ID';";
$data = mysqli_query($dbcon, $vquery)
		or die('Validation Failed: ' . mysqli_error());
if($data -> num_rows==0){
	die("Invalid Service Request Number!");
}
$query='UPDATE 311Calls SET Type="'.$Type.'" WHERE ServiceRequestNumber="'.$ID.'";';
if(mysqli_query($dbcon, $query)){
	print 'Update Complete!';
}else{
	print 'Update Failed';
}

?>