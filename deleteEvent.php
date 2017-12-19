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
$query='Select ID,Description FROM MajorEvents;';
$data = mysqli_query($dbcon, $query)
		or die('Query Failed ' . mysqli_error());
if($data -> num_rows==0){
	die("No Data to delete!");
}
print '<b>Select an event to delete</b>';
print '<form method=get action="delEvent.php">';
while ($tuple = mysqli_fetch_row($data)) {
		print "<input type='radio' value=$tuple[0] name='Event' id='Event'>$tuple[1]<br>";
	}
print '<input type="Submit" name="event" value="event">';
print '</form>';


?>