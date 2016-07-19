<!--create modal-->
<div id="createModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content" role="document">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="modalTitle" class="modal-title"></h4>
      </div>

      <div class="modal-body">

        <div hidden id="createSpecies" class="form-group">
          <h3>Species</h3>

          <label for="speciesName" class="control-label">Name:</label>
          <input id="speciesName" class="form-control" type="text" placeholder="Name"/>

        </div>

        <div hidden id="selectStages" class="form-group">
          <h3>Select Stages</h3>
          <div id="stage" class="row">
            <div class="col-xs-9">
              <select id="selectStageName" class="form-control">
                <option>select</option>
              </select>
              <button id="addSelectStage" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></button>
            </div>
            <div class="col-xs-1">
              <span id="selectStageRank">1</span>
            </div>
            <div class="col-xs-2">
              <button id="deleteSelectStage" class="btn btn-danger pull-right"><span class="glyphicon glyphicon-minus"></span></button>
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
