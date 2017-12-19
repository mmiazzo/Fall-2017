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
$ID = $_REQUEST['weatherDate'];
$precip=$_REQUEST['precip'];
$snow=$_REQUEST['snow'];
$wind=$_REQUEST['wind'];
$minT=$_REQUEST['minT'];
$maxT=$_REQUEST['maxT'];

$dbcon=connectToDB();
$vquery="SELECT * FROM DailyWeather WHERE WeatherDate='$ID';";
$data = mysqli_query($dbcon, $vquery)
		or die('Validation Failed: ' . mysqli_error());
if($data -> num_rows>0){
	die("Date already exists!");
}
if($precip==null){
	$precip=0;
}
if($snow==null){
	$snow=0;
}
if ($wind==null){
	$wind=0;
}
if($minT==null or $maxT==null){
	die('must have min or max temperature');
} elseif($maxT<$minT){
	die('min Temperature is greater than max Temperature!');
}

$query="INSERT INTO DailyWeather (WeatherDate,WeatherType,RainFall,SnowFall,AvgWindSpeed,MinTemp,MaxTemp)
			VALUES('$ID',NULL,$precip,$snow,$wind,$minT,$maxT);";
if(mysqli_query($dbcon, $query)){
	print 'Update Complete!';
}else{
	print 'Update Failed';
}

?>