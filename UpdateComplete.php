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
$endDate=$_REQUEST['endDate'];
if($endDate==null){
	die("Invalid EndDate");
}
$dbcon=connectToDB();
$query="SELECT Status,StartedOn FROM 311Calls WHERE ServiceRequestNumber='$ID';";
$data = mysqli_query($dbcon, $query)
		or die('Show tables failed: ' . mysqli_error());
if($data -> num_rows==0){
	die("Invalid Service Request Number!");
}
$vals=mysqli_fetch_row($data);
$OldStatus=$vals[0];
$StartDate=$vals[1];
if(strtotime($endDate) < strtotime($StartDate)){
	die("endDate is before startDate!");
}

if ($OldStatus=="'Completed'" or $OldStatus=="'Completed - Dup'") {
	print "ding";
	die("Service Request $ID already complete!");
} elseif($OldStatus=="'Open'"){
	print "dang";
	$query="UPDATE 311Calls SET Status='Completed' WHERE ServiceRequestNumber='$ID'";
} elseif($OldStatus=="'Open-Dup'"){
	print 'dong';
	$query="UPDATE 311Calls SET Status='Completed - Dup' WHERE ServiceRequestNumber='$ID'";
} else {
	print 'what';
}
$complete=mysqli_query($dbcon, $query)
	or die('Show tables failed: ' . mysqli_error());

?>