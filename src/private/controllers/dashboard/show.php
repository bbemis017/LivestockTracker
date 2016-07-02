<?php
  require $MODELS.'db_connect.php';
  require $MODELS.'account/Account.php';

  $account = Account::getAccount();
  if( $account === false)
    redirect_url('/account/login');


  $dash_content = 'dashboard/calendar.tpl';

  $smarty->assign('dash_content',$dash_content);
  $smarty->assign('page_title','dashboard');
  $smarty->assign('account', $account);

  $smarty->display('dashboard/dashboard.tpl');
?>
