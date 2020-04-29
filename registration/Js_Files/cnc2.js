/**********************************************************************
 *This should be pretty much Identical to CNC1.js. Please refer to    *
 *That page for information on how that works/comments.               *
 *                                                                    *
 *Created By:Elijsha Baetiong                                         *
 *Last Edited: 3/16/20                                                *
 **********************************************************************/

$(document).ready(function(){
	$.ajax({
	url:"phpFiles/cnc2Graphing.php",
	method: "GET",
	success: function(data){
		console.log(data);
		var time =[];
		var motionTime = [];
		var powerTime=[];
		var avalibility=[];
	var mydata=JSON.parse(data);
	var i=0;	
		

		for(i=0;i<mydata.length;i++){
			time.push(mydata[i].timestamp);
			motionTime.push(mydata[i].motionTime - mydata[0].motionTime);
			powerTime.push(mydata[i].powerOnTime - mydata[0].powerOnTime);
			if(i==0){
				avalibility.push(0);
			}
			else{
				avalibility.push(motionTime[i]/powerTime[i]*100);
			}
		}
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
	var avab = document.getElementById("avab").getContext("2d");
	var barGraph = new Chart(avab,{
	type: 'line',
	data: chartdata,
		options: {
			title:{
				display:true,
				text: 'Avalibility'
			},
			legend:{
				display:false
			},
			scales:{
				yAxes:[{
					ticks:{
						beginAtZero: true
					},
					scaleLabel:{
						display:true,
						labelString:'Percentage (%)'
					}
				}],
			},
			responsive:true,
		}

	});
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
	error: function(data){
		console.log(data);
	}
	
	});
});
