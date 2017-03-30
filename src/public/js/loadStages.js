loadStages();

function updateStages(){
	$('.allStages tr.stage-row').remove();
	loadStages();
}

function loadStages(){
	data = { 'getStages' : 'true' };
	sendAjax("/dashboard/ajax/","POST",function(json){
		var stageList = JSON.parse( json.stageList );
		for( var i = 0; i < stageList.length; i++){
			addStageRow( stageList[i] );
		}
	},data);
}

function addStageRow(stage){
	var lastTr = $('#stagesTable tr:last');
	var parameters = '(' + stage['stage_id'] + ',"' + stage['stage_name'] + '",' + stage['stage_length'] + ')';
	var html = "<tr class='stage-row'>" +
					"<td>" + stage['stage_name'] + "</td>" +
					"<td>" + stage['stage_length'] + "</td>" +
					"<td>" +
						"<button type='button' class='btn btn-warning' onclick='editStage" + parameters + ";\'>Edit</button>" +
					"</td>" +
				"</tr>";
	lastTr.after(html);
}

function editStage(stageId, stageName, stageLength){
	console.log('edit');
	$('#stageName').val(stageName);
	$('#stageLength').val(stageLength);
	formType = 'Stage';
	formAction = 'edit';
	editId = stageId;
	createStageForm();
	modal.modal('show');
}
