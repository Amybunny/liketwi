<?php 
if(!empty($_SESSION['login_date'])){
  $_SESSION['login_date'] = time();
  if(basename($_SERVER['PHP_SELF']) === 'login.php'){
    header("Location:index.php"); //マイページへ
  }
}else{
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
    header("Location:login.php"); //ログインページへ
  }
}