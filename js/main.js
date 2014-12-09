$( document ).ready(function() {

	weekdayHideShow = "hide";

	$(document).on("click", ".weekdaybody .weekday.no-bg", function(e){
		switch(weekdayHideShow){
			case "hide": 
				weekdayHideShow = "show"
		
				$(".weekdaybody").css("display","none"); 

				$(this).parent(".weekdaybody").css({
					"display" : "block", 
					"width"   : "85%"
				});
				break;

			case "show": 
				weekdayHideShow = "hide"

				$(".weekdaybody").css("display","block"); 

				$(".weekdaybody").css({
					"display" : "block", 
					"width"   : "13%"
				});
				break;
		}
	});


	$(function(){
		 $("#createCalendar").click(function() {
			$(".add-event").css("opacity","0"); 
			$(".delete-event").css("opacity","0"); 
			$(".add-event-button").css("opacity","0"); 	

			
			setTimeout(
				  function() 
				  {
				    $(".add-event").css("display","none"); 
				    $(".delete-event").css("display","none"); 
				    $(".add-event-button").css("display","none"); 

					$(".add-calendar").css("opacity","1"); 
					$(".delete-calendar").css("opacity","1"); 
					$(".add-calendar-button").css("opacity","1"); 	
					$(".add-calendar").css("display","block"); 
					$(".delete-calendar").css("display","block"); 
					$(".add-calendar-button").css("display","block"); 					    
				  }, 500);
		 });
	});

	$(function(){
		 $("#createEvent").click(function() {
			$(".add-calendar").css("opacity","0"); 
			$(".delete-calendar").css("opacity","0"); 
			$(".add-calendar-button").css("opacity","0"); 					
			
			setTimeout(
				  function() 
				  {
				    $(".add-calendar").css("display","none"); 
				    $(".delete-calendar").css("display","none"); 
				    $(".add-calendar-button").css("display","none"); 

					$(".add-event").css("opacity","1"); 
					$(".delete-event").css("opacity","1"); 
					$(".add-event-button").css("opacity","1"); 	
					$(".add-event").css("display","block"); 
					$(".delete-event").css("display","block"); 
					$(".add-event-button").css("display","block"); 					    
				  }, 500);
		 });
	});	

	$(function(){
	    $("#add-event").submit(function(e) {       
	    	e.preventDefault();

	    	$eventTitle 	= $("#add-event #title").val();
	    	$eventLoc   	= $("#add-event #location").val();
	    	$eventDateStart = $("#add-event #dateStart").val() + " " + $("#add-event #startTime").val() + ".00";
	    	$eventDateEnd   = $("#add-event #dateEnd").val() + " " + $("#add-event #endTime").val() + ".00";
	    	$eventDes 	    = $("#add-event #description").val();
	    	$eventType	    = $("#add-event #type").val();
	    	$eventCal	    = $("#add-event #calendar").val();

			$.ajax({
			   url: 'newEventAjax.php',
			   data: {
				  	title : $eventTitle,
				 	location : $eventLoc,
					startDate : $eventDateStart,
				  	endDate : $eventDateEnd,
					type : $eventType,
				 	calendar : $eventCal,
 					description : $eventDes 
			   },
			   error: function() {
			      alert('An error has occurred. Please try again.');
			   },
			   success: function(data) {
			   	  alert(data);
			   },
			   type: 'POST'
			});
	    });

	$(function(){
	    $("#add-calendar").submit(function(e) {       
	    	e.preventDefault();

	    	$calendarTitle 			= $("#add-calendar #title").val();
	    	$calendarsharedto	  	= $("#add-calendar #sharedto").val();

	    	if($calendarsharedto == "Share With: (comma separated)"){
				$privatePublicYesNo = "2";
				$calendarsharedto = " ";
	    	}else{
	    		$privatePublicYesNo = "1";
	    	}
	    	

			$.ajax({
			   url: 'newCalendarAjax.php',
			   data: {
				  	title : $calendarTitle,
				 	sharedto : $calendarsharedto,
				 	privatPublic : $privatePublicYesNo
			   },
			   error: function() {
			      alert('An error has occurred. Please try again.');
			   },
			   success: function(data) {
			   	  alert(data);
			   },
			   type: 'POST'
			});

			$(".loading").css("opacity","1");
			$(".loading").css("display","block");

			setTimeout(
				  function() 
				  {
				  		$(".loading").css("opacity","0");
						$(".loading").css("display","none");			    
				  }, 2000);

	    });
	});

});
});
