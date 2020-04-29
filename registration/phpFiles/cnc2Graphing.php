<?php
/******************************************************************
 *This file is almost Identical to cnc1Graphing.php and therefore *
 *you may be able to optimize this down to one page. However you  *
 *should look at the other for more information on the project    *
 *                                                                *
 *Created by: Elijsha Baetiong                                    *
 *Last edited: 3/16/20                                            *
 ******************************************************************/
session_start();
$date=$_SESSION['date'];
$time=$_SESSION['time'];
$minutes=$_SESSION['minute'];
$endTime=$_SESSION['endTime'];
$endDate=$_SESSION['endDate'];

$link = mysqli_connect('localhost','webhost','pass','Demo_OEE');

if($time=="" and $endTime=="" and $endDate==""){
		$query=mysqli_query($link,"select timestamp,motionTime,powerOnTime from cncLathe where  extract(minute from timestamp) mod ".$minutes ."=0 and timestamp between '".$date." 07:00:00' and now() limit 50;");
}
else if($time=="" and $endTime==""){
	$query=mysqli_query($link,"select timestamp,motionTime,powerOnTime from cncLathe where  extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." 07:00:00' and '".$endDate." 23:59:59' limit 50;");
}
else if ($endTime=="" and $endDate==""){
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from cncLathe where  timestamp between '".$date." ".$time."' and now() and extract(minute from timestamp) mod ".$minutes."=0 limit 50;");
}
else if($endDate==""){
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from cncLathe where  extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$date." ".$endTime."' limit 50;");
}
else if($endTime==""){
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from cncLathe where  extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$endDate." 23:59:59' limit 50;");
}
else{
	$query=mysqli_query($link,"select  timestamp,motionTime,powerOnTime from cncLathe where   extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$endDate." ".$endTime."' limit 50;");


}
$data = array();
foreach($query as $row):
	$data[] = $row;
endforeach;
mysqli_close($link);

print json_encode($data);
?>
