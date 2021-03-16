<?php 

require('function.php');

//debug('「「「「「「「「「「「「「「「ログアウトページ」」」」」」」」」」」」」」」');
debug('ログアウトします。');

//セッションを削除（ログアウトする）
session_destroy();
debug('ログアウトしました。ログインページへ遷移します。');
//ログインページへ
header("Location:login.php");