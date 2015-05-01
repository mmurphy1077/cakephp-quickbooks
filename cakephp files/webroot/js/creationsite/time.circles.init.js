$(document).ready(function(){
	$(".timer-element").TimeCircles({

	    "animation": "smooth",
	    "use_background": false,
	    //"bg_width": 1.2,
	    "fg_width": 0.0,
	    "circle_bg_color": "#cccccc",
	    "time": {
	        "Days": {
	            "show": false,
	        },
	        "Hours": {
	            "text": "Hours",
	            "color": "#cccccc",
	            "show": true
	        },
	        "Minutes": {
	            "text": "Minutes",
	            "color": "#cccccc",
	            "show": true
	        },
	        "Seconds": {
	            "text": "Seconds",
	            "color": "#cccccc",
	            "show": true
	        }
	    }
	});
});