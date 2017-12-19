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
$Sports = $_REQUEST['SportsRelated'];
$Description=$_REQUEST['description'];
$Political=$_REQUEST['isPolitical'];
$Chicago=$_REQUEST['InChicago'];
$Start=$_REQUEST['StartDate'];
$End=$_REQUEST['EndDate'];
$CID=$_REQUEST['CID'];

if(strtotime($End)<strtotime($Start)){
	die("end date before start date!");
}
if (empty($Start) or empty($End)){
	die("Cannot have empty start and end dates!");
}
if(!empty($Chicago) and empty($CID)){
	die("Event in chicago but no Community ID attached");
} elseif(empty($Chicago) and !empty($CID)){
	$CID="NULL";
}
if(empty($CID)){
	$CID="NULL";
}
if($Sports=='Sports'){
	$Sports=1;
} else{
	$Sports=0;
}
if(strlen($Description)>200){
	die("Description too long!");
}
if($Political=='isPolitical'){
	$Political=1;
}else{
	$Political=0;
}
if($Chicago=="InChicago"){
	$Chicago=1;
} else{
	$Chicago=0;
}
$query="INSERT INTO MajorEvents (IsSportsRelated,Description,IsPolitical,IsInChicago,EventStartDate,EventEndDate,Location)
			VALUES($Sports,'$Description',$Political,$Chicago,'$Start','$End',$CID);";
print $query;
$dbcon=connectToDB();
$data = mysqli_query($dbcon, $query)
		or die('Show tables failed: ' . mysqli_error());

print 'Update complete! attempt to do a lookup by event to see';

?>