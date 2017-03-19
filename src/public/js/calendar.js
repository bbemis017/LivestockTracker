
function clickGroup(){
  formType = 'Group';
  console.log("group");
  createGroupForm();
  modal.modal('show');
}

function clickSpecies(){
  formType = 'Species';
  console.log("species");
  createSpeciesForm();
  modal.modal('show');
}

function clickStage(){
  formType = 'Stage';
  console.log("stage");
  createStageForm();
  modal.modal('show');
}

//initialize calendar when page loads
$(document).ready(function(){
  $('#calendar').fullCalendar({
    customButtons: {
      Group: {
        text: 'Group',
        click: clickGroup
      },
      Species: {
        text: 'Species',
        click: clickSpecies
      },
      Stage: {
        text: 'Stage',
        click: clickStage
      }
    },
    header: {
      left: 'prev,next today Group,Species,Stage',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
  	},
	events: getEvents,
	eventClick: editEvent
  });

});

function editEvent(calEvent, jsEvent, view){

	editId = calEvent.id;

	formType = 'Group';
	formAction = 'edit';

	sendAjax("/dashboard/ajax/","POST",function(json){
		console.log(json);

		$('#groupName').val( json.groupName );
		$('#groupSize').val( json.groupSize );
		$('#groupSpeciesName').val( json.groupSpecies );
		$('#startDate').val( json.groupStart );

		createGroupForm();
		modal.modal('show');
	},
	{ "getGroup" : "true", "groupId" : editId}
	);
}

function getEvents(start, end, timezone, callback){
	$.ajax({
		"url": "/livestocktracker/dashboard/ajax/",
		"type": "POST",
		"dataType": "json",
		"data": {
			"ajax_request" : "true",
			"calendarData" : "true",
			"calendar_start" : start.format(),
			"calendar_end" : end.format()
		},
		"success": function(doc) {
			var events = [];
			if(doc.result){
				$.map( doc.result, function( r ) {
					events.push({
						"id" : r.id,
						"title": r.title,
						"start": r.date_start,
						"end": r.date_end,
						"allDay": 'true',
						"color" : r.color
					});
				});
			}
			callback(events);
		}
	});
}
