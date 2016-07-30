var modal = $('#createModal');
var openForms = new Array();
var formType;

var numStages;

$('#modalClose').click(closeModal);
$('#newStage').click(createStageForm);
$('#modalCreateBtn').click(submitForm);

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
    }
  });

});

function addSelectStage(e){

  var newElement = $('#stage1').clone();
  numStages++;
  newElement.attr("id","stage" + numStages);

  newElement.find("#selectStageRank").text(numStages);

  $('#stageAddSection').before( newElement);
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

function createSpeciesForm(){
  //form setup and display
  openForms.push( getFormInfo('#createSpecies', submitCreateSpeciesForm ) );
  $('#createSpecies').show();

  selectStagesForm();
}


function selectStagesForm(){
  numStages = 1;

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
  sendAjax("/dashboard/ajax/","POST",temp,data);
  closeModal();
}

function submitCreateStageForm(){
  var data = {
    "createStage" : "true",
    "stageName" : $('#stageName').val(),
    "stageLength" : $('#stageLength').val()
  };
  //sendAjax("/dashboard/ajax/","POST",temp,data);
  return data;
}

function submitCreateSpeciesForm(){
  var data = {
    "createSpecies" : "true",
    "speciesName" : $('#speciesName').val()
  };
  //sendAjax('/dashboard/ajax/','POST',temp,data);
  return data;
}

function submitStagesForm(){
  var stages = [];
  for(var i = 0; i < numStages; i++){
    var val = $('#stage' + (i+1) ).find('#selectStageName').val();
    if( val >= 0){
      stages.push(val);
    }
  }
  var data = {
    "selectStage" : true,
    "stages" : JSON.stringify(stages)
  };
  console.log(data);
  //sendAjax('/dashboard/ajax/','POST',temp,data);
  return data;
}

function temp(json){
  console.log("update stage list");
  console.log(json);
}

function sendAjax(url,type,successCall,data){
  console.log(url);
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
      $('#selectStageName').empty();
      $('#selectStageName').append($("<option value='-1'>select</option>"));
      console.log( json.stageList );
      var stageList = JSON.parse( json.stageList );
      for(var i = 0; i < stageList.length; i++){
        $('#selectStageName').append($("<option value=" + stageList[i]['stage_id'] + ">" + stageList[i]['stage_name'] + "</option>"));
      }
    },
    { "getStages" : "true" }
  );
}
