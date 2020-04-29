<!DOCTYPE HTMLl
<!--
This is the home page for the Alpha Website.
This page allows us to choose between any of
the job numbers currently active. It auto
generates the buttons for you to select.
You can also cycle two either CNC and
do live graphing with the force gauge and
python script
Created by:Elijsha Baetiong 
Last edit:3/16/20
-->
<html>
<head>
<!-- include the respect styles and the shortcut icon-->
<link rel="stylesheet" href="..\CSS files\generic.css"/>
<link rel="shortcut icon" href="../images/icon1.ico"/>
<!--Refresh every 30 seconds
<meta http-equiv="refresh" content="30">
-->
<title>Home Page</title>
</head>  
<body>
<!-- simple header consisting of logo title and navigation-->
<div class = "header">
<img src="../images/ta.png" alt="TA logo">
<h1> Factory Status</h1>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="../../registration/cnc1.php">CNC</a></li>
			<li><a href="../../registration/cnc2.php">CNC Lathe</a></li>
			<li><a href="/registration/LiveGraph.php">Force Gauge</a></li>
		</ul>
	</nav>
</div>
<!-- This section connects to the SQL page and displays all 'Job numbers'
except the CNC's. It also removes the underscore for a better user interface.
If the button is clicked it uses the get method to give the generic page 
info on what page was selected-->
<div class ="main_disp">
<h2> Please Select The Job Number:</h2>
<?php

	$link = mysqli_connect("localhost","webhost","pass","Demo_OEE");
	if($link){
	
		$query ="Show Tables;";
		$results=mysqli_query($link,$query);
		while($row=mysqli_fetch_row($results)){
			if(stripos($row[0],"cnc")===false){
				echo "<form action='genericFile.php' method='get'>";
				echo "<button>
				<input type='hidden' name='jobNum' value='".$row[0]."'>"
				.str_replace("_","",$row[0])."</input></button>";
				echo "</form>";
			}
		}
	}
	
	echo "</div><footer>";
	echo "<sub> Edited by Elijsha Baetiong<br>
		update: 3/11/20
		<br> Alpha 1.0</sub>";
	echo "</footer>";
?>
</body>
</html>
