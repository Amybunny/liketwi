<?php 

if(!empty($_SESSION['login_date'])){
  //debug('authログイン済みユーザーです。');
  //debug('authログインしているユーザー：'.$_SESSION['user_id']);

  $_SESSION['login_date'] = time();
  if(basename($_SERVER['PHP_SELF']) === 'login.php'){
    debug('トップページへ遷移します。');
    header("Location:index.php"); //マイページへ
  }
}else{
  debug('auth未ログインユーザーです。');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
    header("Location:login.php"); //ログインページへ
  }
}

?>