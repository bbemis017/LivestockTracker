var modal = $('#createModal');
var openForms = new Array();
var formType, formAction, editId;
var selectedStages;

var numStages;

$('#modalClose').click(closeModal);
$('#newStage').click(newStageClick);
$('#modalCreateBtn').click(submitForm);
$('#cancelStageBtn').click(newStageCancel);
$('#createStageBtn').click(createStageClick);

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

function addSelectStage(e){

  var newElement = $('#stage1').clone();

  numStages++;

  newElement.attr("id","stage" + numStages);

  newElement.find("#selectStageRank").text(numStages);
  newElement.find("#deleteSelectStage").show();

  $('#stageAddSection').before( newElement);

  selectedStages.push( $('#stage' + numStages).find("#selectStageName") );
}

function deleteSelectStage(element){
    var stage = element.parent().parent();
    selectedStages.splice( selectedStages.indexOf(stage) , 1);
    stage.remove();
}

function newStageClick(){
    $('#newStage').hide();
    $('#modalCreateBtn').prop('disabled',true);
    createStageForm();
}

function createStageClick(){
	var data = submitCreateStageForm();
	sendAjax("/dashboard/ajax/","POST",function(json){
		updateStageList();
		//addSelectStage($('#stage'+numStages));
		newStageCancel();
	},data);
}

function newStageCancel(){
    $('#createStage').hide();
    openForms.splice( openForms.indexOf( getFormInfo('#selectStages',submitStagesForm ) ) , 1);
    $('#newStage').show();
    $('#modalCreateBtn').prop('disabled',false);

}

function closeModal(){
  formType = '';
  for(var i = 0; i < openForms.length; i++){
    //TODO: clear data from form
    openForms[i]['element'].hide();
  }
  openForms = new Array();
  modal.modal('hide');

}

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

function getFormInfo(element,submitFunc){
  var form = {
    'element' : $(element),
    'submit' : submitFunc
  };
  return form;
}

function createGroupForm(){

	openForms.push( getFormInfo('#createGroup', submitCreateGroupForm ) );
	updateSpeciesList();
	$('#createGroup').show();

}

function createSpeciesForm(){
  //form setup and display
  openForms.push( getFormInfo('#createSpecies', submitCreateSpeciesForm ) );
  $('#createSpecies').show();
  $('#newStage').show();
  selectStagesForm();
}


function selectStagesForm(){
  numStages = 1;

  selectedStages = [];
  var stage1 = $('#stage1');
  stage1.find('#deleteSelectStage').hide();
  selectedStages.push( stage1.find('#selectStageName') );

  updateStageList();

  //form setup and display
  openForms.push( getFormInfo('#selectStages',submitStagesForm ) );
  $('#selectStages').show();
  $('#createStageBtn').show();
}

function createStageForm(){

  //form setup and display
  openForms.push( getFormInfo('#createStage',submitCreateStageForm) );
  $('#createStage').show();
  if( formType === 'Stage'){
    $('#createStageBtn').hide();
  }
}

function submitForm(){
  var data = {};
  for(var i = 0; i < openForms.length; i++){
    data = $.extend( data, openForms[i].submit() );
  }
  //console.log(data);
  sendAjax("/dashboard/ajax/","POST", formResponse,data);
  closeModal();
}

function submitCreateStageForm(){
  var data = {
    "createStage" : "true",
    "stageName" : $('#stageName').val(),
    "stageLength" : $('#stageLength').val()
  };

  return data;
}

function submitCreateSpeciesForm(){
  var data = {
    "createSpecies" : "true",
    "speciesName" : $('#speciesName').val()
  };

  return data;
}

function submitCreateGroupForm(){
	var data = {
		"groupName" : $('#groupName').val(),
		"groupSize" : $('#groupSize').val(),
		"groupSpecies" : $('#groupSpeciesName option:selected').val(),
		"groupStart" : $("#startDate").val()
	};
	if( formAction === 'edit'){
		data['editGroup'] = "true";
		data['groupId'] = editId;
	}
	else{
		data['createGroup'] = "true";
	}
	return data;
}

function submitStagesForm(){
  var stages = [];
  for( var i = 0; i < selectedStages.length; i++){
      var val = selectedStages[i].val();
      if( val >= 0){
          stages.push(val);
      }
  }
  var data = {
    "selectStage" : true,
    "stages" : JSON.stringify(stages)
  };
  return data;
}

function formResponse(json){
	//console.log(json);
	if( (json.createGroup && json.createGroup === 'true' )
	  || ( json.updateGroup && json.updateGroup === 'true') ){
		$('#calendar').fullCalendar('refetchEvents');
	}
}

function sendAjax(url,type,successCall,data){
  url = "/livestocktracker" + url;
  console.log(url);
  data['ajax_request'] = 'true';
  console.log(data);
  $.ajax({
    "type" : type,
    "url" : url,
    "data" : data,
    "success" : successCall,
    "error" : function(error){
      console.log('error');
      console.log(error);
    }
  });
}

function updateStageList(){
  sendAjax("/dashboard/ajax/","POST", function (json){
	  var stages = $('.stageList');

	  if( typeof json.stageList === 'undefined')
	  	return;

	  var stageList = JSON.parse( json.stageList );
	  for( var j = 0; j < stages.length; j++){
		var stage = $(stages[j]);
		stage.empty();
      	stage.append( $("<option value='-1'>select</option>") );

      	for(var i = 0; i < stageList.length; i++){
        	stage.append( $("<option value=" + stageList[i]['stage_id'] + ">" + stageList[i]['stage_name'] + "</option>") );
      	}
	  }
    },
    { "getStages" : "true" }
  );
}

function updateSpeciesList(){
	sendAjax("/dashboard/ajax/","POST", function (json) {
		var species = $('#groupSpeciesName');

		var speciesList = JSON.parse( json.speciesList );

		species.append( $("<option value='-1'>select</option>") );
		species.empty();

		for( var i = 0; i < speciesList.length; i++) {
			species.append( $("<option value=" + speciesList[i]['species_id'] + ">" + speciesList[i]['species_name'] + "</option>") );
		}
	},
	{ "getSpecies" : "true" }
	);
}
