<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>{$page_title}</title>
		<!-- Bootstrap Core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"/>
		<!-- Custom CSS -->
		<link href="{$HOST}/public/css/dashboard.css" rel="stylesheet">
		<link href='{$HOST}/public/fullcalendar/fullcalendar.css' rel="stylesheet"/>
    <link href='{$HOST}/public/fullcalendar/fullcalendar.print.css' rel="stylesheet" media='print'/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<script src='{$HOST}/public/fullcalendar/moment.min.js'></script>
		<script src='{$HOST}/public/fullcalendar/fullcalendar.min.js'></script>
	</head>

	<body class="dashboard">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand font-title" href="/">Livestock Tracker</a>
			</div>

			<!-- Top Right Menu Items -->
			<ul class="nav navbar-right top-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {$account->username} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="/accounts/signout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
						</li>
					</ul>
				</li>
			</ul>

			<!--Side bar -->
			<div class=" collapse navbar-collapse">
				<ul class="nav navbar-nav side-nav">z
					<li id="dashboard">
						<a href="{$HOST}/dashboard"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
					</li>
					<li id="settings">
						<a href="{$HOST}/dashboard?page=settings"><i class="fa fa-fw fa-bar-chart-o"></i> Settings</a>
					</li>
					<li id="sites">
						<a href="https://github.com/bbemis017/LivestockTracker/issues" target="_blank"><i class="fa fa-fw fa-bar-chart-o"></i> Bugs</a>
					</li>
				</ul>
			</div>	<!-- end side bar -->

		</nav>	<!-- /.navbar-collapse -->

		<div class="container-fluid dash-content-block">
			<div class="row">
				{if $dash_content != ''}
					{include file=$dash_content}
				{/if}
			</div>
		</div>


		<!-- scripts -->

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<script src='{$HOST}/public/js/calendar.js'></script>
	</body>
</html>
