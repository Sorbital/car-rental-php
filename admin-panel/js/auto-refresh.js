// ===PAGE RELOAD====
$(document).ready(function(){
	var timerContainer = $("#timer");
	//=====================
	var time = 1;  // enter in minutes, change here to change time
	//=====================
	var timer_value = 1000 * 60 * time;	// convert time to milliseconds
	var ETA = timer_value;
	timerContainer.text( ((ETA/1000)/60).toFixed(2) );
	setInterval(updateTimerContainer, 1000);
	setInterval(updatePage, timer_value);
		function updateTimerContainer(){
		ETA-=1000;  // update by 1 second
		timerContainer.text( ((ETA/1000)/60).toFixed(2) );
	}
	function updatePage(){
		location.reload(true);
	}
});
// ===PAGE RELOAD====
/* don't forget to add following to mark-up
 *  <span>
 *   <span id="timer"></span> Minute(s) to reload
 *  </span>
 */