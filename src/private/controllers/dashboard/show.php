<?php
require $MODELS.'account/Account.php';
require $MODELS.'role/Role.php';

$account = Account::getAccount();
if( $account === false)
	redirect_url('/account/login');

$role = Role::getFirstRole($account);
if( $role === false)
	echo "error";

$modal_content = 'dashboard/modal.tpl';
$dash_content = 'dashboard/calendar.tpl';
$page_title = "dashboard";
$show_calendar = true;

if( isset($_GET['page']) && $_GET['page'] === "settings"){
	$dash_content = 'account/settings.tpl';
	$show_calendar = false;
}
else if( isset($_GET['page']) && $_GET['page'] === "allSpecies"){
	$dash_content = 'dashboard/allSpecies.tpl';
	$show_calendar = false;
}
else if( isset($_GET['page']) && $_GET['page'] === "allStages" ){
	$dash_content = 'dashboard/allStages.tpl';
	$show_calendar = false;
}

db_close();

$smarty->assign('show_calendar', $show_calendar);
$smarty->assign('dash_modal', $modal_content);
$smarty->assign('dash_content',$dash_content);
$smarty->assign('page_title',$page_title);
$smarty->assign('account', $account);
$smarty->assign('role',$role);
$smarty->assign('HOST',$HOST);
$smarty->assign('AUTH',$AUTH);
$smarty->assign('VERSION',$VERSION);

$smarty->display('dashboard/dashboard.tpl');
?>
