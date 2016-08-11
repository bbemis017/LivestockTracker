<!--create modal-->
<div id="createModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content" role="document">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="modalTitle" class="modal-title"></h4>
      </div>

      <div class="modal-body">

		<div hidden id="createGroup" class="form-group">
			<h3>Group</h3>

			<label for="groupName" class="control-label">Name: </label>
			<input id="groupName" class="form-control" type="text" placeholder="Name"/>
			<br/>

			<label for="groupSize" class="control-label">Size: </label>
			<input id="groupSize" class="form-control" type="text" placeholder="200"/>
			<br/>

			<label for="groupSpeciesName" class="control-label">Species: </label>
			<select id="groupSpeciesName" class="form-control"></select>

		</div>

        <div hidden id="createSpecies" class="form-group">
          <h3>Species</h3>

          <label for="speciesName" class="control-label">Name:</label>
          <input id="speciesName" class="form-control" type="text" placeholder="Name"/>

        </div>

        <div hidden id="selectStages" class="form-group">
          <h3>Select Stages</h3>
          <div id="stage1" class="row">
            <div class="col-xs-9">
              <select id="selectStageName" class="form-control stageList">
                <option>select</option>
              </select>
            </div>
            <div class="col-xs-1">
              <span id="selectStageRank">0</span>
            </div>
            <div class="col-xs-2">
              <button id="deleteSelectStage" onclick="deleteSelectStage($(this));" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
          </div>
          <div id="stageAddSection" class="row">
            <div class="col-xs-9">
              <button id="addSelectStage" class="btn btn-success pull-right" onclick="addSelectStage($(this));"><span class="glyphicon glyphicon-plus"></span></button>
            </div>
          </div>
          <button id="newStage" type="button" class="btn btn-default btn-md">New Stage</button>
        </div>

        <div hidden id="createStage" class="form-group">
          <h3>Create Stage</h3>

          <label for="stageName" class="control-label">Name:</label>
          <input id="stageName" class="form-control" type="text" placeholder="Name"/>

          <label for="stageLength" class="control-label">Length:</label>
          <input id="stageLength" class="form-control" type="text" placeholder="10 days"/>


          <button id="createStageBtn" type="button" class="btn btn-success pull-right">Create Stage</button>
          <button id="cancelStageBtn" type="button" class="btn btn-sm btn-danger pull-right">Cancel</button>
          </br>
        </div>

      </div>

      <div class="modal-footer">
        <button id="modalClose" type="button" class="btn btn-default">Close</button>
        <button id="modalCreateBtn" type="button" class="btn btn-primary">Create</button>
      </div>
    </div>
  </div>
</div>  <!-- end of modal creation stuff -->

<!--calendar stuff-->
<div class="col-md-8 col-md-offset-2 calendar">
  <div id='calendar'></div>
</div>
