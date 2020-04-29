/*****************************************************
 *This File is similar to cnc2.js This file takes in *
 *the json encoded data from cnc1Graphing.php. After *
 *some filtering it then displays that data to a few *
 *charts on cnc1.php.                                *
 *Created By: Elijsha Baetiong                       *
 *Last Edited: 3/16/20                               *
 * ***************************************************/
$(document).ready(function(){
	$.ajax({
	url:"phpFiles/cnc1Graphing.php",
	method: "GET",
	success: function(data){
	
	var time =[];
	var motionTime = [];
	var powerTime=[];
	var avalibility=[];
	var mydata=JSON.parse(data);
	var i=0;	
		
	//for the size of the data push the correct information into their respective arrays
	for(i=0;i<mydata.length;i++){
		time.push(mydata[i].timestamp);
		//get the motion time from the start to the end
		motionTime.push(mydata[i].motionTime - mydata[0].motionTime);
		powerTime.push(mydata[i].powerOnTime - mydata[0].powerOnTime);
		//probably setup avalibility
		if(i!=0)
			avalibility.push(motionTime[i]/powerTime[i]*100);
		else
			avalibility.push(0);
	}
	//this setups the chart data for avaliblity 
	var chartdata = {
		labels:time,
			datasets:[{
			label: 'avalibility',
		 	backgroundColor:'rgba(255,255,0,0.75)',
			borderColor: 'rgba(0,0,0,0.75)',
			hoverBackgroundColor:'rgba(0,0,0,1)',
			hoverBorderColor: 'rgba(200,200,200,200,1)',
			data: avalibility
			}

		]
	};
	//grab the avaliblity canvas and put the chart on it
	var ctx = document.getElementById("avab").getContext("2d");
	var barGraph = new Chart(ctx,{
	type: 'line',
	data: chartdata,
		options: {
			title:{
				//display chart title "Avalibility"
				display:true,
				text: 'Avalibility'
			},
			legend:{
				//hide the legend
				display:false
			},
			scales:{
				yAxes:[{
					ticks:{
						//start at zero
						beginAtZero: true
					},
					scaleLabel:{
						//Set scale to display perecentage
						display:true,
						labelString:'Percentage (%)'
					}
				}],
			},
			//Make it responsive to screen size
			responsive:true,
		}

	});

	//Follows the same as above but for runtime
	var chartdata = {
		labels:time,
			datasets:[{
			label: 'Run Time',
		 	backgroundColor:'rgba(255,20,147,0.5)',
			borderColor: 'rgba(0,0,0,0.75)',
			hoverBackgroundColor:'rgba(0,0,0,1)',
			hoverBorderColor: 'rgba(200,200,200,200,1)',
			data:motionTime 
			}

		]
	};
	var run = document.getElementById("run").getContext("2d");
	var barGraph = new Chart(run,{
	type: 'line',
	data: chartdata,
		options: {
			title:{
				display:true,
				text: 'Run time'
			},
			legend:{
				display:false
			},
			scales:{
				yAxes:[{
					ticks:{
						beginAtZero:true
					},
					scaleLabel:{
						display:true,
						labelString:"# of mintues"
					}
				}],

			},
			responsive:true,

		}

	});
	//Now for uptime
	var chartdata = {
		labels:time,
		datasets:[
			{
			label: 'upTime',
		 	backgroundColor:'rgba(50,205,50,0.5)',
			borderColor: 'rgba(0,0,0,0.75)',
			hoverBackgroundColor:'rgba(0,0,0,1)',
			hoverBorderColor: 'rgba(200,200,200,200,1)',
			data:powerTime 
			}
		]
	};
	var up = document.getElementById("Up").getContext("2d");
	var barGraph = new Chart(up,{
	type: 'line',
	data: chartdata,
		options: {
			title:{
				display:true,
				text: 'Up time'
			},
			legend:{
				display:false
			},
			scales:{
				yAxes:[{
					ticks:{
						beginAtZero:true,
					},
					scaleLabel:{
						display:true,
						labelString:"# of minutes"
					},
				}],
	
			},
			responsive:true,
			maintainAspectRatio:true
		}

	});

	},
	// if we get an error log the data to view if it is correct
	error: function(data){
		console.log(data);
	}
	
	});
});
