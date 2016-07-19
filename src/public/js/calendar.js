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

function getFormInfo(element,fields,submitFunc){
  var form = {
    'element' : $(element) , 'fields' : fields,
    'submit' : submitFunc
  };
  return form;
}

function createSpeciesForm(){
  //form setup and display
  var createSpeciesForm = $('#createSpecies');
  var fields = new Array('#speciesName');
  openForms.push( getFormInfo('#createSpecies',fields) );
  createSpeciesForm.show();

  selectStagesForm();
}

var numStages;

function selectStagesForm(){
  numStages = 1;

  //form setup and display
  var fields = new Array(); //TODO: figure out how to do this one
  openForms.push( getFormInfo('#selectStages' , fields) );
  selectStagesForm.show();
  $('#createStageBtn').show();
}

function createStageForm(){

  //form setup and display
  var fields = new Array( $('#stageName'), $('#stageLength') );
  openForms.push( getFormInfo('#createStage',fields,submitCreateStageForm) );
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
  console.log("submit create stage");
  data = {
    'createStage' : 'true',
    'stageName' : $('#stageName').val(),
    'stageLength' : $('#stageLength').val()
  };
  sendAjax('/dashboard/ajax/','POST',updateStageList,data);

}

function updateStageList(json){
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
