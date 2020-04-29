<?php
/***********************************************************************************
 * This file takes in the table name and creates the html page that displays the   *
 * graphic info and will display the other info and fault data. It passes data to  *
 * Generic session that will then json encode a sql query data and then a          *
 * javascript file will take the canvas created here to graph the file.            *
 *                                                                                 *
 * Created by: Elijsha Baetiong                                                    *
 * Last Edited: 3/16/20                                                            *
 ***********************************************************************************/
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<?php
//This retrieves the job number from the website address
$tableName =$_GET["jobNum"];
$jobNum=str_replace("_","",$tableName);
echo "<title>".$jobNum."</title>";


//Create the arrays for info
$OEE_Array=array();
$faults_Array=array();
$partInfo_Array=array();

?>
<link rel="stylesheet" href="CSS files\generic.css"/>
<link rel="shortcut icon" href="images/icon1.ico"/>
<!--Refresh every 30 seconds
<meta http-equiv="refresh" content="30">
-->
</head>
<body>

<div class = "header">
<img src="../images/ta.png" alt="TA logo">
<?php
	echo "<h1>Job Number: ".$jobNum."</h1>";
//This section connects to an sql database and then pushes the correct column name into its
//correct array
	$link = mysqli_connect("localhost","webhost","pass","Demo_OEE");	
	if($link){
		$query ="Show columns from ".$tableName.";";
		$results=mysqli_query($link,$query);
		while($row=mysqli_fetch_row($results)){
			//optimize this later but wokrs for now
			if(strpos($row[0],"_OEE") or strpos($row[0],"_Ava") or strpos($row[0],"_Per") or strpos($row[0],"_Qual")){
				array_push($OEE_Array,$row[0]);
			}
			else if(strpos($row[0],"Fault") and !strpos($row[0],"_Sec")){
				array_push($faults_Array,$row[0]);
				//print_r($faults_Array);
			}
			else if($row[0]!="company" and $row[0]!="timestamp" and !(strpos($row[0],"_Sec"))) {
				array_push($partInfo_Array,$row[0]);
		
			}
		}
		//pass the OEE columns and table name to genericSession.php	
		$_SESSION["OEEArray"]=$OEE_Array;
		$_SESSION['table']=$tableName;
	}

?>
	
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="../../registration/cnc1.php">CNC</a></li>
			<li><a href="../../registration/cnc2.php">CNC Lathe</a></li>
			<li><a href="/registration/LiveGraph.php">Force Gauge</a></li>
		</ul>
	</nav>
</div>
<!-- Display the job # stats-->
<br>
<div class = "OEE_status">
	<table class="machineStat">
		<tr>
			<th>Machine Status:</th>
<?php
	//This section grabs the In_Cycle_B column from the last 5 mins and displays its most recent status 
	if($link){
		$query="select Main_In_Cycle_B from ".$tableName." where curdate()=date(timestamp)
		 and timestamp between DATE_SUB(Now(), INTERVAL 5 MINUTE) and now() limit 1;";
		if($result = mysqli_query($link,$query)){
			$answer=mysqli_fetch_row($result);
			if($answer[0]){
				echo "<td bgcolor='green'>Machine In-Cycle</td>";
			}
			else if(!$answer[0]){
				echo "<td bgcolor='yellow'>Machine Idle</td>";
			}
			else{
				echo "<td bgcolor='red'>Not In operation</td>";
			}
		}
		//this generates a query to retrieve the most recent OEE data
		$query="select ";
		$ind=0;
		while($ind<sizeof($OEE_Array)){
			if($ind!=sizeof($OEE_Array)-1){
				$query .=$OEE_Array[$ind].", ";
			}
			else{
				$query.=$OEE_Array[$ind]." ";
			}
			$ind++;
		}
		$query .= "from ".$tableName." where curdate()=date(timestamp)
		 and timestamp between DATE_SUB(Now(), INTERVAL 5 MINUTE) and now() limit 1;";
	}

?>
			
		</tr>
	</table>
	<br>
	<table>
		<tr>
<?php
	// this grabs the results from the query and displays it on the page
	$results=mysqli_query($link,$query);

		$row=mysqli_fetch_row($results);
		echo "<td class ='OEE'>".$row[0]."%</td>
			<td>=</td>
			<td class ='Avalibility'>".$row[1]."%</td>
			<td>X</td>
			<td class ='Performance'>".$row[2]."%</td>
			<td>X</td>
			<td class ='Quality'>".$row[3]."%</td>"
?>
		</tr>
		<br><br>
		<tr>
			<td>OEE</td>
			<td/>
			<td>Avalibility</td>
			<td/>
			<td>Performance</td>
			<td/>
			<td>Quality</td>
		</tr>
	</table>
<div class="form" align="center">
<!-- This section creates a form for user to enter dates and time-->
<form action="#" method="post">
<select id="time" name="mintues" align="Right">                      
<option value="60">--Select time option--</option>
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
<?php
	//this section takes in the form data and generates it into a useful conditional section
	//of the SQL query
	$time="";
	$minutes = 60;
	$endTime="";
	$endDate="";
	$endQuery=""; //everything after 'where' in sql statement
	$endMod="";
	if(isset($_POST['submit'])){
		$minutes = $_POST['mintues'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$endTime = $_POST['endTime'];
		$endDate = $_POST['endDate'];
	}
	if($date==""){
		$date=date("y/m/d"); //set date to current date
	}
	if($time=="" and $endTime=="" and $endDate==""){
		$endQuery= "timestamp between '".$date." 07:00:00' and now()";
	}
	else if($time=="" and $endTime==""){
		$endQuery="timestamp between '".$date." 00:00:00' and '".$endDate." 23:59:59' ";
	}
	else if ($endTime=="" and $endDate==""){
		$endQuery="timestamp between '".$date." ".$time."' and now() ";

	}
	else if($endDate==""){
		$endQuery="timestamp between '".$date." ".$time."' and 
		CONCAT(convert(date(now()),CHAR),' ','".$endTime."') ";
	}
	else{
		$endQuery="timestamp between '".$date." ".$time."' and
		 '".$endDate." ".$endTime."' ";
	}
	$endMod=" and extract(minute from timestamp) mod ".$minutes."=0 ";
	$_SESSION['endQuery']=$endQuery.$endMod;
?>
</div>
<br><br>
<!--This creates the Canvas for the javascript file to populate. From testing it appears
that you ned to have a div surronding the canvas for it to work-->
<script type="text/javascript"  src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div class="OEE" style="margin-right:15%;display:inline-block;mpostion:relative;height: 10; width: 40vw"> 
<canvas id="OEE"></canvas>
</div>
<div class="Avab" style="display:inline-block;position:relative;height:10; width:40vw">
<canvas id="Avab" style="display:center"></canvas>
</div>
<div class="Per" style="margin-right:15%;display:inline-block;mpostion:relative;height: 10; width: 40vw"> 
<canvas id="Per"></canvas>
</div>
<div class="Qual" style="display:inline-block;position:relative;height:10; width:40vw">
<canvas id="Qual" style="display:center"></canvas>
</div>
<table class="faults">
<?php
	//This generates the info table for the machine anything with
	//-output
	//-parts
	//-Min
	//Will take the max and the min for the part to get the information for the day
	$query="select ";
	$ind=0;
	while($ind<sizeof($partInfo_Array)){
		if(strpos($partInfo_Array[$ind],"Parts") or strpos($partInfo_Array[$ind],"Output")
		  or strpos($partInfo_Array[$ind],"Min")and $ind!=sizeof($partInfo_Array)-1){ 
			$query .="max(".$partInfo_Array[$ind].")-min(".$partInfo_Array[$ind]."), ";
		}
		else if(strpos($partInfo_Array[$ind],"Parts") or strpos($partInfo_Array[$ind],"Output")
		  or strpos($partInfo_Array[$ind],"Min")){
			$query .="max(".$partInfo_Array[$ind].")-min(".$partInfo_Array[$ind].") ";
		}
		else if($ind!=sizeof($partInfo_Array)-1){
			$query .=$partInfo_Array[$ind].", ";
		}
		else{
			$query.=$partInfo_Array[$ind]." ";
		}
		$ind++;
	}
	$query .= "from ".$tableName." where ";
	$query .=$endQuery." ORDER BY timestamp desc limit 1;";
	$results=mysqli_query($link,$query);
	$row=mysqli_fetch_row($results);
	echo "<tr>
	<td colspan='".sizeof($partInfo_Array)."'>Machine Info</td>
	</tr>
	<tr>";
	foreach($row as $x){
		echo "<td>".$x."</td>";
	}
	echo "</tr><tr>";
	//probably parse this a little better 
	foreach($partInfo_Array as $x){
		$x=str_replace("OEE_Current_","",$x);
		$x=str_replace("_"," ",$x);
		$x=str_replace("Main","",$x);
		$x=str_replace(" B","",$x);
		echo "<td>".$x."</td>";
	}
	echo "</tr>";

?>
	</table>
	<br><br>
	<table class="faults">
<?php
	//similar to the previous section althoughh it takes the differnce of all of them to 
	//generate the days length of faults
		$query="select ";
		$ind=0;
		while($ind<sizeof($faults_Array)){
			if($ind!=sizeof($faults_Array)-1){
				$query .="max(".$faults_Array[$ind].")-min(".$faults_Array[$ind]."
				), ";
			}
			else{
				$query.="max(".$faults_Array[$ind].")-min(".$faults_Array[$ind].") ";
			}
			$ind++;
		}
		$query .= "from ".$tableName." where ";
		$query .=$endQuery." ORDER BY timestamp desc;";
		$results=mysqli_query($link,$query);
		$row=mysqli_fetch_row($results);
		echo "<tr>
			<td colspan='".sizeof($faults_Array)."'>Part Faults</td>
		</tr>
		<tr>";
		foreach($row as $x){
			echo "<td>".$x."</td>";
		}
		echo "</tr><tr>";
		//probably parse this a little better 
		foreach($faults_Array as $x){
			$x=str_replace("OEE_Current_","",$x);
			$x=str_replace("_"," ",$x);
			echo "<td>".$x."</td>";
		}
		echo "</tr>";

?>
	</table>

<script type="text/javascript"  src="Js_Files/genericResponse.js"></script>

</body>
</html>
