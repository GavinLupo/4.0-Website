<!DOCTYPE HTML>
<!----
This is a demonstration page to be able to show live graphing.
It does this by opening a websocket and getting the data from it
Created By:Elijsha Baetiong
Last Edit: 3/16/20
-->

<html>
<head>
<link rel="stylesheet" href="..\CSS files\generic.css"/>
<link rel="shortcut icon" href="../images/icon1.ico"/>
<!--Refresh every 30 seconds
<meta http-equiv="refresh" content="30">
-->
<!-- Javascript files needed to be able to graph the data-->
<script type="text/javascript"  src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script type="text/javascript" src="Js_Files/liveGraph.js"></script>

</head>  
<body>
<!-- simple header for title and navigation-->
<div class = "header">
<img src="../images/ta.png" alt="TA logo">
<h1>Live Graphing</h1>
	<nav>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="../../registration/cnc1.php">CNC</a></li>
			<li><a href="../../registration/cnc2.php">CNC Lathe</a></li>
			<li><a href="LiveGraph.php">Force Gauge</a></li>
		</ul>
	</nav>
</div>
<!-- Create the graph area to be populated and the buttons to set and clear it-->
<div class ="graphing" style="align:center;height: 45%; width:85%;">
<canvas id='graph'></canvas>
</div>
<button id="start">Start</button>
<button id="stop">Stop</button>
<button id="reset">Clear</button>

</body>
</html>
