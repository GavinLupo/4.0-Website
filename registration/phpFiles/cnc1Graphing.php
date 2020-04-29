<?php
/******************************************************************
 *This file is to retrieve the information that we want to graph  *
 *for cnc1. It retrieves its information from a session and then  *
 *prints a json encoded answer                                    *
 *                                                                *
 *Created by: Elijsha Baetiong                                    *
 *Edited last: 3/16/20                                            *
 ******************************************************************/
//start the session and retrieve information
session_start();
$date=$_SESSION['date'];
$time=$_SESSION['time'];
$minutes=$_SESSION['minute'];
$endTime=$_SESSION['endTime'];
$endDate=$_SESSION['endDate'];

//conect to the database
$link = mysqli_connect('localhost','webhost','pass','Demo_OEE');

//depending on the information get the query
if($time=="" and $endTime=="" and $endDate==""){
		$query=mysqli_query($link,"select timestamp,motionTime,powerOnTime from completeData where machineID=1 and extract(minute from timestamp) mod ".$minutes ."=0 and timestamp between '".$date." 07:00:00' and now() limit 50;");
}
else if($time=="" and $endTime==""){
	$query=mysqli_query($link,"select timestamp,motionTime,powerOnTime from completeData where machineID=1 and extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." 07:00:00' and '".$endDate." 23:59:59' limit 50;");
}
else if ($endTime=="" and $endDate==""){
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from completeData where machineID=1 and timestamp between '".$date." ".$time."' and now() and extract(minute from timestamp) mod ".$minutes."=0 limit 50;");
}
else if($endDate==""){
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from completeData where machineID=1 and extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$date." ".$endTime."' limit 50;");
}
else if($endTime==""){
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from completeData where machineID=1 and extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$endDate." 23:59:59' limit 50;");
}
else{
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from completeData where machineID=1  and extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$endDate." ".$endTime."' limit 50;");
}

$data = array();

foreach($query as $row):
	$data[] = $row;
endforeach;
mysqli_close($link);
//print the json encoded data
print json_encode($data);
?>
