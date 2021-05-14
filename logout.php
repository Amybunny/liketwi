<?php 

require('function.php');
debug('ログアウトします。');
session_destroy();
debug('ログアウトしました。ログインページへ遷移します。');
header("Location:login.php");