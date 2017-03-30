loadSpecies();

function updateSpecies(){
	$('.allSpecies tr.species-row').remove();
	loadSpecies();
}

function loadSpecies(){
	data = { "getSpecies" : 'true' };
	sendAjax("/dashboard/ajax/","POST",function(json){
		var speciesList = JSON.parse( json.speciesList );
		for( var i = 0; i < speciesList.length; i++){
			addSpeciesRow( speciesList[i] );
		}
	}, data);
}

function addSpeciesRow(species){
	var lastTr = $('#speciesTable tr:last');
	var parameters = '(' + species['species_id'] + ',"' + species['species_name'] + '")';
	var html = "<tr class='species-row'>" +
					"<td>" + species['species_name'] + "</td>" +
					"<td>" +
						"<button type='button' class='btn btn-warning' onclick='editSpecies" + parameters + ";\'>Edit</button>" +
					"</td>" +
				"</tr>";
	lastTr.after(html);
}

function editSpecies(speciesId, speciesName){
	$('#speciesName').val(speciesName);
	formType = 'Species';
	formAction = 'edit';
	editId = speciesId;
	createSpeciesForm();
	modal.modal('show');
}
