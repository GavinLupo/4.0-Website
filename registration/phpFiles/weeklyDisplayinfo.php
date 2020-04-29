<?php 
/*********************************************************************
 *This is a test file to demonstrate weekly reports based on PLC's  *
 *It techinally handles any of them but it becomes problamtic since  *
 *I am looking for OEE Data. Ideally we should try to optomize the   *
 *CNC data's to be able to handle this.                              *
 *                                                                   *
 *Created By: Elijsha Baetiong                                       *
 *Last Edited By:3/16/20                                             *
 *********************************************************************/
//start out creating the webpage and add stying for the tables
echo "<html>
<head>
<style type='text/css'>

.head table{
	align: center;
	width:100%;
}
.data h1{
	margin:auto;
	text-align:center;
}
.data table{
	border:2px solid black;
	text-align:center;
	margin: auto;
	align:center;
	border-collapse:collapse;	
}
.data td{
	border: 2px solid black;
	font:bold;
	padding:2px;
}

</style>
</head>
<body>";
//get the date and the begining of the week for sql query
$start_week=date('y/m/01');
$today=date('y/m/d');

/* This functions takes an array full of names of columns to get
 * max of each column (need to adjust to only get max OEE)
 * It then iterates through each and add the respective string 
 * and finally returns a string to get the right information
 * */

function weeklyMaxQuery($array){
	$query ="select date(timestamp), "; 
	$i=0;
	foreach($array as $row){
		if($i<count($array)-1){
			$query.="Max(".$row."), ";
		}
		else{
			$query.="Max(".$row.") ";
		}
		$i++;	
	}
	return $query;
 
}
//Almost identical to previous except it uses the Min function in the sql 
//string
function weeklyMinQuery($array){
	
	$query ="select date(timestamp), "; 
	$i=0;
	foreach($array as $row){
		if($i<count($array)-1){
			$query.="Min(".$row."), ";
		}
		else{
			$query.="Min(".$row.") ";
		}
		$i++;	
	}

	return $query;
	 
}

//Like the previous but uses the average function in the sql query
function weekAvgQuery($array){
	$query ="select "; 
	$i=0;
	foreach($array as $row){
		if($i<count($array)-1){
			$query.="avg(".$row."), ";
		}
		else{
			$query.="avg(".$row.") ";
		}
		$i++;	
	}

	return $query;
}
/* This function takes in the table name, a link to the database, and whether
 * to get the max or min values from the table. Instead of returning items
 * it creates the HTML table information and dispay it properly */
function bestOee($name,$link,$max){

	global $start_week,$today;
	//get the column names
	$query="show columns from ".$name.";";
	$results=mysqli_query($link,$query);
	$temp=[];
	while($row=mysqli_fetch_row($results)){
		//filter out for information we are looking for
		if(strcmp($row[0],'timestamp')!=0 and stripos($row[0],'OEE')!==False and stripos($row[0],'time')===False and stripos($row[0],'part')===False){
			//push into the array 
			array_push($temp,$row[0]);
		}
	}
	//get the query string depneding on what we are looking for
	if($max){
		$query=weeklyMaxQuery($temp);
	}
	else{
		$query=weeklyMinQuery($temp);	
	}
	//finish the query string from the start of the week to now
	$query.="from ".$name." where timestamp between '".$start_week."' and '".$today."';";
	$results=mysqli_query($link,$query);
	$row=mysqli_fetch_row($results);
	//create the table with the results from the query
	echo "<table class='weekly'>";
	echo "</tr><tr>";
	$i=0;
	$add=0;
	foreach($row as $item){
		//depending on the result make border green,yellow,red
		if($add>0){
			if($item>=90){
				echo "<td bgcolor='lime'>";
			}
			else if($item<90 and $item>50){
				echo "<td bgcolor='yellow'>";
			}
			else{
				echo "<td bgcolor='red'>";
			}
			echo round($item,2)."%</td>";
		}	
		else{
			//except if it is the date then just paste it
			echo "<td>".$item."</td>";
		}
		$add++;
	}
	echo "</tr><tr>";
	//now echo the row names for the user to see
	if($add>0){
		echo "<td>Time</td>";
	}
	foreach($temp as $row){
		echo "<td>".str_replace("_"," ",$row)."</td>";
	}
	//now we inform the user what job number they are looking at
	if($add>0){
		echo "</tr><tr> <td colspan=".($add).">";
		if($max){
			echo "Weekly Max OEE of <u>";	
		}
		else{
			echo "Weekly Min OEE of <u>";
		}
		echo str_replace("_","",$name)."</u></td>";

	}
	//finish the table and provide break
	echo "</table>";
 
	echo "<br>";

 
}
/* As this function implies it displays the weekly faults
 * It takes in the table name and a link to the database
 * It then prints out the fault data to a table */
function displayWeeklyFaults($name,$link){
	global $start_week,$today;
	//get the column names from the table
	$query="show columns from ".$name.";";
	$results=mysqli_query($link,$query);
	$temp=[];
	while($row=mysqli_fetch_row($results)){
		//get only the fault ones based on our naming convention
		if(stripos($row[0],'fault')!==False and stripos($row[0],'sec')===False ){
			array_push($temp,$row[0]);
		}
	}
	//get the averages and put the table up
	$query=weekAvgQuery($temp);
	$query.="from ".$name." where timestamp between '".$start_week."' and '".$today."';";
	$results=mysqli_query($link,$query);
	$row=mysqli_fetch_row($results);
	echo "<table class='weekly'>";
	echo "<tr>";
	$add=0;
	foreach($row as $item){
		if($item<5){
			echo "<td bgcolor='lime'>";
		}
		else if($item>5 and $item<15){
			echo "<td bgcolor='yellow'>";
		}
		else{
			echo "<td bgcolor='red'>";
		}
		echo round($item,2)."</td>";
		$add++;
	}
	echo "</tr><tr>";
	//Make the column names look nicer for the user expierence
	foreach($temp as $col){
		echo "<td>".str_replace("_"," ",$col)."</td>";
	}
	echo "</tr><tr>";
	if($add>0){
		echo "<td colspan='".count($temp)."'>Average Faults of <u>".str_replace("_","",$name)."</u></td>";
	}
	//finish the table and create a break
	echo "</tr></table>";
	echo "<br>"; 
}
/* Pretty much identical to the last function although now we get 
 * the Average of the OEE data and use that to display it*/
function displayWeeklyInfo($name,$link){
	global $start_week,$today;
	$query="show columns from ".$name.";";
	$results=mysqli_query($link,$query);
	$temp=[];
	while($row=mysqli_fetch_row($results)){
		if(strcmp($row[0],'timestamp')!=0 and stripos($row[0],'OEE')!==False 
			and stripos($row[0],'time')===False and stripos($row[0],'part')===False){
				array_push($temp,$row[0]);
		}
	}
	$query=weekAvgQuery($temp);	
	$query.="from ".$name." where timestamp between '".$start_week."' and '".$today."';";
	$results=mysqli_query($link,$query);
	$row=mysqli_fetch_row($results);
	echo "<table class='weekly'>";
	echo "</tr><tr>";
	$i=0;
	$add=0;
	foreach($row as $item){
		if($item>=90){
			echo "<td bgcolor='lime'>";
		}
		else if($item<90 and $item>50){
			echo "<td bgcolor='yellow'>";
		}
		else{
			echo "<td bgcolor='red'>";
		}
		echo round($item,2)."%</td>";
		$add++;
	}
	echo "</tr><tr>";
	foreach($temp as $row){
		echo "<td>".str_replace("_"," ",$row)."</td>";
	}

	if($add>0){
		echo "</tr><tr> <td colspan=".($add).">Weekly OEE of <u>".str_replace("_","",$name)."</u></td>";
	}
	echo "</table>";
 
	echo "<br>";
	 
}

//This is where we create the header of this page
echo "<div class ='head'>
	<table>
	<tr>
	<td><img style='margin:auto' align='left' src=../images/ta.png></td>
	<td align='center'><h1>T.A. Weekly Report</h1><td>
	<td align='right'><sup style='font-size=10px'><pre>company name #\n Automatically generated\n on ".$today."</pre><sup>
	</td></tr>
	</table></div>";

//Then we connect to the database
$link =mysqli_connect("localhost","webhost","pass","Demo_OEE");
$query = "Show Tables;";
$results=mysqli_query($link,$query);
$tables=[];
//for each table in the database push into the array
while($row=mysqli_fetch_row($results)){
	array_push($tables,$row[0]);
}
echo "<br><div class='data'><br>";
//for each table display all of their info
foreach($tables as $col){
	displayWeeklyInfo($col,$link);
	displayWeeklyFaults($col,$link);
	bestOee($col,$link,1);
	bestOee($col,$link,0);
}
//end the webpage
echo "</div>";
echo "</body> </html>";
?>
