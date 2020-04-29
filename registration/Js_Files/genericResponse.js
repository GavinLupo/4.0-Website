/**********************************************************************
 *This page is used for the generic creation of the PLC job numbers   *
 *It gets the JSON encoded information from genericSession.php and    *
 *Then populates the respective graphs with the information           *
 *Created By: Elijsha Baetiong                                        *
 *Last Edited: 3/16/20                                                *
 * ********************************************************************/
$(document).ready(function(){
	$.ajax({
	url:"../genericSession.php",
	method:"GET",
	success:function(data){
	var time=[];
	var OEE=[];
	var avabName;
	var perfName;
	var qualName;
	var oeeName;
	var avab=[];
	var perf=[];
	var qual=[];
	var names=[];
	//Parse the json data
	mydata=JSON.parse(data);
	//Get the names of the data
	names.push(mydata[0]);
	//then push the data into the proper arrays
	for(var j=0;j<names[0].length;j++){
		//depending on if the column includes(case sensitive)
		//a keyword then it setups up that name to use later
		if(names[0][j].includes("Performance")){
			perfName=names[0][j];
		}
		else if(names[0][j].includes("Quality")){
			qualName=names[0][j];
		}
		else if(names[0][j].includes("Availability")){
			avabName=names[0][j];
		}
		else{
			oeeName=names[0][j];
		}
	}
	//now we push the Names in the correct spot
	for(var i=1;i<mydata.length;i++){
		OEE.push(mydata[i][oeeName]);
		avab.push(mydata[i][avabName]);
		perf.push(mydata[i][perfName]);
		qual.push(mydata[i][qualName]);
		time.push(mydata[i].timestamp);
	}
	//Create a json object that stores the data and tells it how to 
	//display it on the graph
	var oeeData = {
	labels:time,
		datasets:[{
		label: 'Overall Equipment Efficency',
	 	backgroundColor:'rgba(0,255,0,0.75)',
		borderColor: 'rgba(0,0,0,0.75)',
		hoverBackgroundColor:'rgba(0,0,0,1)',
		hoverBorderColor: 'rgba(200,200,200,200,1)',
		data:OEE 
		}]
	};
	//Now we get the element from the html page 
	var ctx = document.getElementById("OEE").getContext("2d");
	//Now we can create the graph
	var oeeGraph = new Chart(ctx,{
	type: 'line', // set it up as a line graph
	data: oeeData,//plop in the json object
		options: {
			title:{
				//have it display a title
				display:true,
				text: 'Overal Equipment Efficency'
			},
			legend:{
				//hide the legend
				display:false
			},
			scales:{
				yAxes:[{
					ticks:{
						//have it start at zero
						beginAtZero: true
					},
					scaleLabel:{
						//turn the yaxis title on
						display:true,
						labelString:'Percentage (%)'
					}
				}],
			},
			responsive:true,//make it responsive to 
		}

	});
	
	//The rest of the file is repetitive and does the same function as above but 
	//with the other three remaining graph info
	var avabData = {
	labels:time,
		datasets:[{
		label: 'Avalibility',
	 	backgroundColor:'rgba(255,0,0,0.75)',
		borderColor: 'rgba(0,0,0,0.75)',
		hoverBackgroundColor:'rgba(0,0,0,1)',
		hoverBorderColor: 'rgba(200,200,200,200,1)',
		data:avab 
		}]
	};
	var run = document.getElementById("Avab").getContext("2d");
	var avabGraph = new Chart(run,{
	type: 'line',
	data: avabData,
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
						beginAtZero:true
					},
					scaleLabel:{
						display:true,
						labelString:"Percentage(%)"
					}
				}],

			},
			responsive:true,

		}

	});

	var perfData = {
		labels:time,
		datasets:[
			{
			label: 'Performance',
		 	backgroundColor:'rgba(255,165,0,0.90)',
			borderColor: 'rgba(0,0,0,0.75)',
			hoverBackgroundColor:'rgba(0,0,0,1)',
			hoverBorderColor: 'rgba(200,200,200,200,1)',
			data:perf 
			}
		]
	};
	var up = document.getElementById("Per").getContext("2d");
	var perfGraph = new Chart(up,{
	type: 'line',
	data: perfData,
		options: {
			title:{
				display:true,
				text: 'Performance'
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
						labelString:"Percentage(%)"
					},
				}],
	
			},
			responsive:true,
			maintainAspectRatio:true
		}

	});

	var qualData = {
		labels:time,
		datasets:[
			{
			label: 'Quality',
		 	backgroundColor:'rgba(0,0,255,0.90)',
			borderColor: 'rgba(0,0,0,0.75)',
			hoverBackgroundColor:'rgba(0,0,0,1)',
			hoverBorderColor: 'rgba(200,200,200,200,1)',
			data:qual 
			}
		]
	};
	var up = document.getElementById("Qual").getContext("2d");
	var qualGraph = new Chart(up,{
	type: 'line',
	data: qualData,
		options: {
			title:{
				display:true,
				text: 'Quality'
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
						labelString:"Percentage(%)"
					},
				}],
	
			},
			responsive:true,
			maintainAspectRatio:true
		}

	});

	}

	});
});
