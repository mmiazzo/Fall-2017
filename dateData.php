<?php

if (isset($_REQUEST['EventDates'])) {
    $Dates=$_REQUEST['EventDates'];
	$DateSplit=explode("|",$Dates);
	GetDateRange('311Calls','StartedOn',$DateSplit[0],$DateSplit[1]);
}

// Getting the input parameter (user):
$dType = $_REQUEST['dataRet'];
$tbl=$_REQUEST['tbltype'];
if($tbl=="311Calls"){
	$dateKey='StartedOn';
} else{
	$dateKey='CrimesOn';
}
if ($dType=='DRange'){
	$StartDate=$_REQUEST["startDate"];
	$EndDate=$_REQUEST["endDate"];
	GetDateRange($tbl,$dateKey,$StartDate,$EndDate);
} elseif ($dType=='event'){
	getEventDates();
} elseif ($dType=='tempRange'){
	$StartTemp=$_REQUEST['startTemp'];
	$EndTemp=$_REQUEST['endTemp'];
	$query=getWeatherDates($StartTemp,$EndTemp);
	getInSubset($tbl,$dateKey,$query);
} elseif ($dType=='incomeRange'){
	if ($tbl=='311Calls'){
		$locKey='CommunityArea';
	}
	else{
		$locKey='Location';$
	}
	$StartInc=$_REQUEST["startInc"];
	$EndInc=$_REQUEST["endInc"];
	$query=getLocationIncome($StartInc,$EndInc);
	getInSubset($tbl,$locKey,$query);
} 
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

function getLocationIncome($StartInc,$EndInc){
	if ($StartInc==NULL || $EndInc==NULL || $StartInc>$EndInc){
		print "invalid Incomes!";
	}
	return 'SELECT NameAreaNumber FROM CommunityArea WHERE MedianIncome BETWEEN '.$StartInc.' AND '.$EndInc;
}


function GetDateRange($tbl,$DateKey,$startDate,$endDate){
	if ($startDate==NULL || $endDate==NULL){
		echo "invalid date range!";
	}
	// Connection parameters 
	$dbcon=connectToDB();
	$queryData="SELECT * FROM ".$tbl." WHERE ".$DateKey." BETWEEN '".$startDate."' AND '".$endDate."';";
	$queryCount="SELECT Count(*) FROM ".$tbl." WHERE ".$DateKey." BETWEEN '".$startDate."' AND '".$endDate."';";
	print $queryData;
	$resultData = mysqli_query($dbcon, $queryData)
		or die('Show tables failed: ' . mysqli_error());
	$resultCount = mysqli_query($dbcon, $queryCount)
		or die('Show tables failed: ' . mysqli_error());
	printData($resultData,$resultCount,$tbl);
}

function printData($Data,$Count,$tbl){
	print 'ding!';
	$dbcon=connectToDB();
	$query = 'select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME="'.$tbl.'"';
	$resHead = mysqli_query($dbcon, $query)
		or die('Show tables failed: ' . mysqli_error());
	print "Number of results: ".mysqli_fetch_row($Count)[0].'<br><hr>';
	while ($Headtuple = mysqli_fetch_row($resHead)) {
		print implode(',',$Headtuple).',';
	}
	print '<br>';
	while ($tuple = mysqli_fetch_row($Data)) {
		print implode(',',$tuple).'<br>';
	}
}


function getinSubset($tbl,$keyParam,$subQuery){
	$dbcon=connectToDB();
	print 'dinger';
	$queryData='SELECT * FROM '.$tbl.' WHERE '.$keyParam.' IN ('.$subQuery.');';
	$queryCount='SELECT Count(*) FROM '.$tbl.' WHERE '.$keyParam.' IN ('.$subQuery.');';
	print $queryData;
	$resultData = mysqli_query($dbcon, $queryData)
		or die('Subquery Data failed: ' . mysqli_error());
	$resultCount = mysqli_query($dbcon, $queryCount)
		or die('Count Data failed: ' . mysqli_error());
	printData($resultData,$resultCount,$tbl);
}

function getEventDates(){
	$dbcon=connectToDB();
	print "<b>You picked events!</b>";
	$query = 'SELECT ID,Description,EventStartDate,EventEndDate FROM MajorEvents';
	$result = mysqli_query($dbcon, $query)
		or die('Show tables failed: ' . mysqli_error());

	print "The Events in $database database are:<br>";
	print '<form method=get action="dateData.php">';
	while ($tuple = mysqli_fetch_row($result)) {
		print '<input type="radio" value='.$tuple[2].'|'.$tuple[3].' name="EventDates" id="EventDates">'.$tuple[1].'<br>';
	}
	print '<input type="Submit" name="event" value="event">';
	print '</form>';
}
function getFields($tbl,$dType){
	$dbcon=connectToDB();
	print "<b>Select the data you want to retrieve</b>";
	$query = 'select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME="'.$tbl.'"';

	$result = mysqli_query($dbcon, $query)
		or die('Show tables failed: ' . mysqli_error());

	print "List of Attributes $database database are:<br>";
	print '<form method=get action="dateData.php">';
	while ($tuple = mysqli_fetch_row($result)) {
		print '<input type="checkbox" value='.$tuple[0].' name="Attribute" id="Attribute">'.$tuple[0].'<br>';
	}
	print '<button type="submit" name="Go!" value='.$tbl.'|'.$dType.'>Go!</button>';
	print '</form>';
}

function getWeatherDates($StartTemp,$EndTemp){
	if ($StartTemp==NULL || $EndTemp==NULL || $StartTemp>$EndTemp){
		print "invalid Temperatures!";
	}
	return 'SELECT WeatherDate FROM DailyWeather WHERE (minTemp+maxTemp)/2 BETWEEN '.$StartTemp.' AND '.$EndTemp;
}
?>