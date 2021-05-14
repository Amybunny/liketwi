<?php

require('function.php');

var_dump($_GET);

if(!empty($_POST)){
  $username = $_POST['username'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_retype = $_POST['pass-retype'];

  validRequired($username,'username');
  validRequired($email,'email');
  validRequired($pass,'pass');
  validRequired($pass_retype,'pass-retype');

  if(empty($err_msg)){

    $dbh = dbConnect();
    $data = array(':username'=>$username, ':email'=>$email, ':pass'=>password_hash($pass, PASSWORD_DEFAULT));
    $sql = 'INSERT INTO users (username,email,pass) VALUES (:username,:email,:pass)';
    $stmt = queryPost($dbh,$data,$sql);

    if($stmt){
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $dbh->lastInsertId();
      header("Location:index.php");
    }

  }else{
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>「いま」を伝えよう</title>
</head>
<body>
  <div class="common-btn active"><a href="login.php?guest=1">ゲストログイン</a></div>
  <div class="common-btn active"><a href="login.php">ログイン</a></div>
  <div class="signup-area">
    <h1 class="common-h1">「いま」を伝えよう</h1>
    <form class="common-form" method="post">
      <div class="form-group">
        <div class="msg-area"><?php phpErrMsg('username'); ?></div>
        <input type="text" name="username" class="input-common valid-text" placeholder="お名前">
      </div>
      <div class="form-group">
        <div class="msg-area"><?php phpErrMsg('email'); ?></div>
        <input type="text" name="email" class="input-common valid-text" placeholder="メールアドレス">
      </div>
      <div class="form-group">
        <div class="msg-area"><?php phpErrMsg('pass'); ?></div>
        <input type="password" name="pass" class="input-common valid-pass" placeholder="パスワード">
      </div>
      <div class="form-group">
        <div class="msg-area"><?php phpErrMsg('pass-retype'); ?></div>
        <input type="password" name="pass-retype" class="input-common valid-pass-retype" placeholder="パスワード（再入力）">
      </div>
      <input type="submit" class="common-btn active" value="登録">
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="main.js"></script>
</body>
</html>