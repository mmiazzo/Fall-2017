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

$dType = $_REQUEST['dataRet'];
$tbl=$_REQUEST['tbltype'];
$binSz=$_REQUEST['binSize'];
function validateBin($binSz){
	if ($binSz==NULL){
		echo 'Invalid bin size!';
		die;
	}
}
if ($dType=='DRange'){
	getDowBins($tbl);
} elseif ($dType=='tempRange'){
	validateBin($binSz);
	getWeatherBins($binSz,$tbl);
} elseif ($dType=='incomeRange'){
	validateBin($binSz);
	getIncomeBins($binSz,$tbl);
} elseif ($dType=="Location"){
	getByLocation($tbl);
}
function getWeatherBins($size,$tbl){
	$dbcon=connectToDB();
	$queryrange="SELECT MIN((minTemp+maxTemp)/2),MAX((minTemp+maxTemp)/2) FROM DailyWeather";
	$resultData = mysqli_query($dbcon, $queryrange)
		or die('Temperature binning failed: ' . mysqli_error());
	$ran=mysqli_fetch_row($resultData);

	$bins=range($ran[0]-2*$size,$ran[1]+2*$size,$size);
	$numBins=count($bins)-1;
	if ($tbl=='311Calls'){
		$key='StartedOn';
	} else {
		$key='CrimesOn';
	}
	
	for ($i = 0; $i < $numBins; $i++){
		$binStart=$bins[$i];
		$binEnd=$bins[$i+1];
		$subQuery="Select WeatherDate FROM DailyWeather WHERE (minTemp+maxTemp)/2 BETWEEN ".$binStart." AND ".$binEnd;
		$query='SELECT COUNT(*) FROM '.$tbl.' WHERE '.$key.' IN ('.$subQuery.')';
		$result=mysqli_query($dbcon, $query)
			or die('Show tables failed: ' . mysqli_error());
		$binData[$binStart.'/'.$binEnd]=mysqli_fetch_row($result)[0];
	}
	echo "bin Range===># of datapoints<br>";
    foreach($binData as $key=>$item) {
        echo $key.'===>'.$item.'<br>';
    }
}	
function getIncomeBins($size,$tbl){
	$dbcon=connectToDB();
	$queryrange="SELECT MIN(MedianIncome),MAX(MedianIncome) FROM CommunityArea";
	$resultData = mysqli_query($dbcon, $queryrange)
		or die('Income binning failed: ' . mysqli_error());
	$ran=mysqli_fetch_row($resultData);

	$Incbins=range($ran[0]-$size,$ran[1]+$size,$size);
	$numBins=count($Incbins)-1;
	if ($numBins>20){
		echo 'too many bins!';
		die();
	}
	if ($tbl=='311Calls'){
		$key='CommunityArea';
	} else {
		$key='Location';
	}
	for ($i = 0; $i < $numBins; $i++){
		$binStart=$Incbins[$i];
		$binEnd=$Incbins[$i+1];
		$subQuery="Select NameAreaNumber FROM CommunityArea WHERE MedianIncome BETWEEN ".$binStart." AND ".$binEnd;
		$query='SELECT COUNT(*) FROM '.$tbl.' WHERE '.$key.' IN ('.$subQuery.')';
		$result=mysqli_query($dbcon, $query)
			or die('Bin Query Failed: ' . mysqli_error());
		$binData[$binStart.'/'.$binEnd]=mysqli_fetch_row($result)[0];
	}	
	echo "bin Range===># of datapoints<br>";
    foreach($binData as $key=>$item) {
        echo $key.'===>'.$item.'<br>';
    }
}
function getByLocation($tbl){
	$dbcon=connectToDB();
	if ($tbl=='311Calls'){
		$key='CommunityArea';
	} else {
		$key='Location';
	}
	$query="Select ".$key.",COUNT(*) FROM ".$tbl." WHERE ".$key."!='' GROUP BY ".$key;
	$result=mysqli_query($dbcon, $query)
		or die('Bin Query Failed: ' . mysqli_error());
	while ($tuple = mysqli_fetch_row($result)) {
		$binData[$tuple[0]]=$tuple[1];
	}
	echo "bin Range===># of datapoints<br>";
    foreach($binData as $key=>$item) {
        echo $key.'===>'.$item.'<br>';
    }
}
function getDowBins($tbl){
	$dbcon=connectToDB();
	if ($tbl=='311Calls'){
		$key='CommunityArea';
		$dKey='StartedOn';
	} else {
		$key='Location';
		$dKey='CrimesOn';
	}
	$query="Select DOW,COUNT(*) FROM (SELECT *,DAYOFWEEK(".$dKey.") AS DOW FROM ".$tbl.") AS DoWTbL WHERE DOW!='' GROUP BY DOW";
	$result=mysqli_query($dbcon, $query)
		or die('Bin Query Failed: ' . mysqli_error());
	while ($tuple = mysqli_fetch_row($result)) {
		$binData[$tuple[0]]=$tuple[1];
	}
	echo "bin Range===># of datapoints<br>";
    foreach($binData as $key=>$item) {
        echo $key.'===>'.$item.'<br>';
    }
}
?>