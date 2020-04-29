/*******************************************************************
 *This file is used to update the live graph webpage. It creates a *
 *Websocket and gets infromation from the socket and then updates  *
 *the graph.                                                       *
 *Future Updates:                                                  *
 *-Connected button                                                *
 *-select a 'blacbox' instead of singular websocket                *
 *-Max and min stats                                               *
 *Created by: Elijsha Baetiong                                     *
 *Last Edited: 3/16/20                                             *
 *******************************************************************/

//create websocket
var socket= new WebSocket('ws://192.168.206.28:8765');

var liveGraph;
var dps = []; // dataPoints
var index=[];
var fullData;
var dataNum=0;

//This function works as soon as the page gets loaded
//It creates the initial chart
window.onload = function () {

var chart = document.getElementById("graph").getContext("2d");
fullData={
	labels:index,
		datasets:[{
		label:'Newtons',
		data:dps,
		backgroundColor:'rgba(254,80,0,0.75)',
	}]
	};
liveGraph = new Chart(chart, {
	type: 'line',
		data:fullData,
		options:{
			title:{
				display:true,
				text: 'Force Gauge Mesurements'
			},
			
			legend:{
				display:true,
				position:'right'
			},
			scales:{
				yAxes:[{
					ticks:{
						beginAtZero:true
					},
					scaleLabel:{
						display:true,
							labelString:'Force(N)'
					}
				}],
				xAxes:[{
					ticks:{
						beginAtZero:true
					},
					scaleLabel:{
						display:true,
							labelString:'Number of Points'
					}
				}]
			}
	}
	
});

//These create click listeners for the respectective button and applys
//a certain function to them
document.getElementById("start").addEventListener("click",start);
document.getElementById("stop").addEventListener("click",stop);
document.getElementById("reset").addEventListener("click",resetGraph);

//This sends a start signal to tell the socket to start providing data
function start(){
	socket.send('start');

}

/*as the function implies it resets the graph
It does this by destroying and recreating the graph
it also deletes all the data for the graph and resets
them back to zero*/
function resetGraph(){
	
	liveGraph.destroy();
	dps.splice(0,dps.length);
	index.splice(0,index.length);
	console.log(index.length);
	console.log(dps.length);
	dataNum=0;
	liveGraph =new Chart(chart, {
	type: 'line',
		data:fullData,
		options:{
			title:{
				display:true,
				text: 'Force Gauge Mesurements'
			},
			
			legend:{
				display:true,
				position:'right'
			},
			scales:{
				yAxes:[{
					ticks:{
						beginAtZero:true
					},
					scaleLabel:{
						display:true,
							labelString:'Force(N)'
					}
				}],
				xAxes:[{
					ticks:{
						beginAtZero:true
					},
					scaleLabel:{
						display:true,
							labelString:'Number of Points'
					}
				}]
			}
	}
	});
	
}
//this function sends a stop signal to tell the socket No more data
function stop(){
	socket.send("stop");
	liveGraph.update();
}

socket.onopen = function(event){
	//make a button that shows connected
	
}

/*When we recieve a message
Ask for more data
update the number of points
Add the data to graph and update*/

socket.onmessage = function(event){
	socket.send("data");
	index.push(dataNum++);
	//console.log(event.data);
	updateChart(liveGraph,event.data);	
		
}
//Alert us when an error occurs
socket.onerror = function(err){
	alert("Error occured!");
}

//this function updates the chart by pushing more data
//then refreshing the chart
var updateChart = function (chart,data) {

	chart.data.datasets.forEach((dataset) => {
        	dataset.data.push(data);
        });
	chart.update();

}

}
