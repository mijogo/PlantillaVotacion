var lineChartData = {
			labels : ["00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30"],
			datasets : [
				{
					fillColor : "rgba(71,134,195,0.1)",
					strokeColor : "rgba(71,134,195,1)",
					pointColor : "rgba(71,134,195,1)",
					pointStrokeColor : "#fff",
					data : [0,10,12,15,22,28,40,45,55,56,60,61,62,62,72,75]
				},
				{
					fillColor : "rgba(77,162,140,0.1)",
					strokeColor : "rgba(77,162,140,1)",
					pointColor : "rgba(77,162,140,1)",
					pointStrokeColor : "#fff",
					data : [0,2,4,6,12,13,13,15,18,25,30,32,35,38,45,48]
				},
				{
					fillColor : "rgba(204,51,153,0.1)",
					strokeColor : "rgba(204,51,153,1)",
					pointColor : "rgba(204,51,153,1)",
					pointStrokeColor : "#fff",
					data : [0,5,15,16,17,20,25,33,33,35,42,45,49,56,58,60]
				}
			]
			
		}

	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData);