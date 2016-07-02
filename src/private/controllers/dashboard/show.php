<?php
  require $MODELS.'db_connect.php';
  require $MODELS.'account/Account.php';

  $account = Account::getAccount();
  if( $account === false)
    redirect_url('/account/login');

  $smarty->assign('site_title','Livestock Tracker');
  $smarty->display('dashboard/dashboard.tpl');
?>
