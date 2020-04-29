<!DOCTYPE html>
<!-- This should be pratically identically to CNC1.PHP
please refer to that one for more information.
Created By: Elijsha Baetiong
Last Edited: 3/16/20
-->
<html>
<head>
<title>CNC 2 data</title>
<link rel="stylesheet" href="\CSS files\cnc.css"/>
<link rel="shortcut icon" href="../images/icon1.ico"/>
<!---<meta http-equiv="refresh" content="30">--->
</head>
<body bgcolor="white">
<div class ="header">
<img src="images/ta.png" alt="TA logo">
<h1>CNC Lathe</h1>
<nav>
	<ul>
	<li><a href="index.php">Home</a></li>
	<li><a href="cnc1.php">CNC</a></li>
	<li><a href="cnc2.php">CNC Lathe</a></li>
	<li><a href="LiveGraph.php">Force Gauge</a></li>
	</ul>
</nav>

</div>

<?php include 'phpFiles/statusCheck.php'?>

<script type="text/javascript"  src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div class="display1">
<div class="graphs" style="margin-right:15%;display:inline-block;mpostion:relative;height: 10; width: 40vw"> 
<canvas id="avab"></canvas>
</div>
<div class="running" style="display:inline-block;position:relative;height:10; width:40vw">
<canvas id="run" style="display:center"></canvas>
</div>
<div class="upTime" align="center" style="margin:auto ;position:relative; height:10; width:40vw">
<canvas id="Up"></canvas>
</div>
</div>
<script type="text/javascript" src="Js_Files/cnc2.js"></script>
<div class="form" align="center">
<form action="#" method="post">
<select id="time" name="mintues" align="Right">                      
<option value="5">--Select time option--</option>
<option value="1">1</option>
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="30">30</option>
<option value="60">60</option>
</select>
Enter start date:
<input type="date" name="date"/>
Enter a start time:
<input type="time" name="time"/>
	<p>Enter stop date:
	<input type="date" name="endDate"/>
	Enter stop time:
	<input type="time" name="endTime"/>
	<input type="submit" name="submit" value="Submit" />
</form>
</div>
<div class="data">

<?php
session_start();
$date="";
	$time="";
$minutes = 60;
$endTime="";
$endDate="";
	if(isset($_POST['submit'])){
		$minutes = $_POST['mintues'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$endTime = $_POST['endTime'];
		$endDate = $_POST['endDate'];
	}
	if($date==""){
		$date=date("y/m/d");
	}

	$_SESSION['date']=$date;
	$_SESSION['time']=$time;
	$_SESSION['minute']=$minutes;
	$_SESSION['endDate']=$endDate;
	$_SESSION['endTime']=$endTime;
	$link = mysqli_connect("localhost", "webhost", "pass", "Demo_OEE");

	if($link) {
		if($time=="" and $endTime=="" and $endDate==""){
			$query=mysqli_query($link,"select * from cncLathe where extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." 07:00:00' and now()  limit 50;");
		}		
		else if($time=="" and $endTime==""){
        		$query=mysqli_query($link,"select * from cncLathe where  extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." 07:00:00' and '".$endDate." 23:59:59' limit 50;");
		}	
		else if ($endTime=="" and $endDate==""){
        		$query=mysqli_query($link,"select * from cncLathe where timestamp between '".$date." ".$time."' and now() and extract(minute from timestamp) mod ".$minutes."=0 limit 50;");
		}		
		else if($endDate==""){	
        		$query=mysqli_query($link,"select * from cncLathe where  extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$date." ".$endTime."' limit 50;");
		}
		else if($endTime==""){
        		$query=mysqli_query($link,"select  * from cncLathe where machineID=2 and extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$endDate." 23:59:59' limit 50;");
}
else{
	$query=mysqli_query($link,"select * from cncLathe where  extract(minute from timestamp) mod ".$minutes."=0 and timestamp between '".$date." ".$time."' and '".$endDate." ".$endTime."'limit 50;");
}
	echo" <table align ='center' style='relative'>
	<tr bgcolor='#FAA31B'>
	<th>Time Stamp</th>
	<th>Up time</th>
	<th>Run time</th>
	<th>Avalibility</th>
	<th>Total tool changes</th>
	<th>Tool in Use</th>
	<th>Total parts run</th>
	<th>Last Cycle Time</th>
	<th>Current cycle Time </th>
	<th>Machine status</th>
	</tr>	";
       $inital=1;
       $colors=1;
       $upTimeStart=0;
       $runTimeStart=0;
       while($array = mysqli_fetch_array($query)) {
	  if($colors== 1){
	     echo "<tr bgcolor='#EBEBEB'>";
	     $colors=2;
	  }
	  else{
	     echo "<tr bgcolor='#88C6ED'>";
	     $colors=1;
	  }
	  if($inital==1){
		$upTimeStart=$array['powerOnTime'];
		$runTimeStart=$array['motionTime'];
		$avalibility=0;
	  }
	  
	  $uptime=$array['powerOnTime']-$upTimeStart;

	  $runtime=$array['motionTime']-$runTimeStart;
          if($inital==1){
		 $avalibility=0; 
		  $inital=2;
	  }
	  else{
	  	$avalibility=round(($runtime/$uptime)*100,2);
	  }	
	  echo "<td>". $array['timestamp']." </td>";
	  echo "<td>". $uptime." </td>";
          echo "<td>". $runtime." </td>";
          echo "<td>". $avalibility."%</td>";
          echo "<td>". $array['toolChanges']." </td>";
	  echo "<td>". $array['toolInUse']." </td>";
          echo "<td>". $array['partsRun']." </td>";
	  echo "<td>". $array['lastCompletePart']." </td>";
          echo "<td>". $array['previousPart']." </td>";
	  echo "<td>". $array['status']."</td>";
	  echo "</tr>";
		
       }
	echo " </table>";
    }
		 
    else {
       echo "MySQL error :".mysqli_error();
    }
  ?>
</div>

</body>
</html>
