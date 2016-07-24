var modal = $('#createModal');
var openForms = new Array();
var formType;

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
  openForms.push( getFormInfo('#createSpecies') );
  $('#createSpecies').show();

  selectStagesForm();
}

var numStages;

function selectStagesForm(){
  numStages = 1;

  updateStageList();

  //form setup and display
  openForms.push( getFormInfo('#selectStages',submitStagesForm) );
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
  for(var i = 0; i < openForms.length; i++){
    openForms[i].submit();
  }
  closeModal();
}

function submitCreateStageForm(){
  data = {
    'createStage' : 'true',
    'stageName' : $('#stageName').val(),
    'stageLength' : $('#stageLength').val()
  };
  sendAjax('/dashboard/ajax/','POST',temp,data);

}

function submitCreateSpeciesForm(){
  data = {
    'createSpecies' : true,
    'speciesName' : $('#speciesName').val()
  }
  sendAjax('/dashboard/ajax/','POST',temp,data);
}

function submitStagesForm(){

}

function temp(json){
  console.log("update stage list");
  console.log(json);
}

function sendAjax(url,type,successCall,data){
  console.log(url);
  url = '/livestocktracker' + url;
  console.log(url);
  data['ajax_request'] = true;
  $.ajax({
    type : type,
    url : url,
    data : data,
    success : successCall,
    error : function(error){
      console.log('error');
      console.log(error);
    }
  });
}

function updateStageList(){
  sendAjax('/dashboard/ajax/','POST', function (json){
      $('#selectStageName').empty();
      $('#selectStageName').append($("<option value='-1'>select</option>"));
      var stageList = JSON.parse( json.stageList );
      for(var i = 0; i < stageList.length; i++){
        $('#selectStageName').append($("<option value=" + stageList[i]['stage_id'] + ">" + stageList[i]['stage_name'] + "</option>"));
      }
    },
    { 'getStages' : true }
  );
}

function addStageOption(name){

}
