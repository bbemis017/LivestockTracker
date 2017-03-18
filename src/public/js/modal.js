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


function addSelectStage(selected=-1){
  var newElement = $('#stage1').clone();

  numStages++;

  newElement.attr("id","stage" + numStages);

  newElement.find("#selectStageRank").text(numStages);
  newElement.find("#deleteSelectStage").show();
  newElement.find("#selectStageName").val(selected);

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
	$('#cancelStageBtn').show();
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
  formAction = '';
  editId = -1;
  for(var i = 0; i < openForms.length; i++){
    //TODO: clear data from form
    openForms[i]['element'].hide();
  }
  openForms = new Array();
  modal.modal('hide');

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

  //remove old stages
  var stages = $('#selectStages');
  for(var i = numStages; i > 1; i--){
	  stages.find('#stage'+i).remove();
  }
  numStages = 1;

  selectedStages = [];
  var stage1 = $('#stage1');
  stage1.find('#selectStageName').val("-1");
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
		$('#cancelStageBtn').hide();
	}
}

function submitForm(){
  var data = {};
  for(var i = 0; i < openForms.length; i++){
    data = $.extend( data, openForms[i].submit() );
  }
  //console.log(data);
  sendAjax("/dashboard/ajax/","POST", formResponse,data);
}

function submitCreateStageForm(){
	var data = {
		"stageName" : $('#stageName').val(),
		"stageLength" : $('#stageLength').val()
	};

	if( formAction === 'edit'){
		data['editStage'] = 'true';
		data['stage_id'] = editId;
	}
	else{
		data['createStage'] = 'true';
	}

	return data;
}

function submitCreateSpeciesForm(){
	var data = {
		"speciesName" : $('#speciesName').val()
	};
	if( formAction === 'edit'){
		data['editSpecies'] = "true";
		data['speciesId'] = editId;
	}
	else {
		data['createSpecies'] = "true";
	}

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
	console.log(json);
	if( (json.createGroup && json.createGroup === 'true' )
	  || ( json.updateGroup && json.updateGroup === 'true') ){
		$('#calendar').fullCalendar('refetchEvents');
	}
	if( formType === 'Species' && formAction === 'edit'){
		updateSpecies();
	}
	closeModal();
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
	var data = { "getStages" : "true" };

	//if editing, only get the stages associated with species
	if( formAction === 'edit' && editId >= 0){
		data['getSpeciesStageList'] = 'true';
		data['speciesId'] = editId;
	}

	sendAjax("/dashboard/ajax/","POST", function (json){
		var stages = $('.stageList');

		if( typeof json.stageList === 'undefined')
			return;

		var stageList = JSON.parse( json.stageList );

		var stage = $(stages[0]);
		stage.empty();
		stage.append( $("<option value='-1'>select</option>") );

		for(var i = 0; i < stageList.length; i++){
			stage.append( $("<option value=" + stageList[i]['stage_id'] + ">" + stageList[i]['stage_name'] + "</option>") );
		}

		//add existing stages for species
		if( typeof json.speciesStageList !== 'undefined'){
			var speciesStageList = JSON.parse( json.speciesStageList );
			for( var i = 0; i < speciesStageList.length; i++){
				if( i > 0){
					addSelectStage(speciesStageList[i]['stage_id']);
				}
				else{
					$('#stage1').find('#selectStageName').val(speciesStageList[i]['stage_id']);
				}
			}
		}

	}, data );
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

function editSpecies(speciesId, speciesName){
	$('#speciesName').val(speciesName);
	formType = 'Species';
	formAction = 'edit';
	editId = speciesId;
	createSpeciesForm();
	modal.modal('show');
}
