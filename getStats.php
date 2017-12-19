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

$dbcon=connectToDB();
$byLoc=$_REQUEST['groupLoc'];
$byType=$_REQUEST['groupType'];
if ($byLoc=='on'){
	if ($byType=='on'){
		$groupBy='CommunityArea,Type';
	}
	else{$groupBy='CommunityArea';}
}
elseif ($byType=='on'){
	$groupBy='Type';
}
$query="SELECT ".$groupBy.",AVG(CompletedOn-StartedOn) FROM 311Calls WHERE CommunityArea!='' AND Status LIKE '_Completed%' GROUP BY ".$groupBy;
$resultData = mysqli_query($dbcon, $query)
		or die('Show tables failed: ' . mysqli_error());
print $groupBy.",Average time to completion<br>";
while ($tuple = mysqli_fetch_row($resultData)) {
	print implode(',',$tuple).'<br>';
}
?>