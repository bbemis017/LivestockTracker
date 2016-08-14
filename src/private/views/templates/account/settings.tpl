<div class="settings">
	<h1 align="center">Settings</h1>
	<br/><br/><br/>

	<div class="row">
		<div class="col-sm-6 col-sm-offset-2 account-info">
			<div class="row">
				<div class="titles col-xs-3">
					<span>username:</span>
					<span>email:</span>
				</div>
				<div class="col-xs-2">
					<span>{$role->account->username}</span>
					<span>{$role->account->email}</span>
				</div>
			</div>

			<br/><br/><br/>

			<table class="table">
				<tr>
					<th>Organization</th>
					<th>Role</th>
				</tr>
				<tr>
					<td>{$role->org->name}</td>
					<td>{$AUTH[$role->auth]}</td>
				</tr>
			</table>
		</div>
	</div>
</div>
