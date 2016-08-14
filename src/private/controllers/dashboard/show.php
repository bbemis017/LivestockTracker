<?php
require $MODELS.'db_connect.php';
require $MODELS.'account/Account.php';
require $MODELS.'role/Role.php';

$account = Account::getAccount();
if( $account === false)
	redirect_url('/account/login');

$role = Role::getFirstRole($account);
if( $role === false)
	echo "error";

$dash_content = 'dashboard/calendar.tpl';
$page_title = "dashboard";

if( isset($_GET['page']) && $_GET['page'] === "settings"){
	$dash_content = 'account/settings.tpl';
	require $CONTROLLERS.'account/settings.php';
}


db_close();

$smarty->assign('dash_content',$dash_content);
$smarty->assign('page_title',$page_title);
$smarty->assign('account', $account);
$smarty->assign('role',$role);
$smarty->assign('HOST',$HOST);
$smarty->assign('AUTH',$AUTH);

$smarty->display('dashboard/dashboard.tpl');
?>
