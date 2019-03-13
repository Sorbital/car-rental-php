$(function () {
	var d1 = [[1, 5], [2, 8], [3, 2], [4, 3], [5, 4], [6, 5], [7, 6], [8, 4], [9, 4], [10, 4], [11, 4], [12, 4]];
	var d2 = [[1, 5], [2, 8], [3, 2], [4, 3], [5, 4], [6, 5], [7, 6], [8, 4], [9, 4], [10, 4], [11, 4], [12, 4]];
    	
	var plot = $.plotAnimator (
		$("#animated_3"), 
		[
			{ 
				data: d2, 
				points: { 
					show: true, 
					fill: true, 
					radius: 2,
					fillColor: "#ffffff"
				},
				//label: "Bars" 
			}, 
			{ 
				data: d1, 
				animator: {
					steps: 136, 
					duration: 2500, 
					start:0
				}, 
				lines: { 
					show: true, 
					fill: false,
					lineWidth: 2
				},
				//label: "Evolution" 
			}
		],
		{
			colors: ["#555555", "#bf2222"]
		}
	);


});



