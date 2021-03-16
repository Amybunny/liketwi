<?php

require('function.php');
//debug('「「「「「「「「「「「「「「「ユーザー登録ページ」」」」」」」」」」」」」」」');

if(!empty($_POST)){
  //変数にユーザー情報を代入
  $username = $_POST['username'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_retype = $_POST['pass-retype'];

  validRequired($username,'username');
  validRequired($email,'email');
  validRequired($pass,'pass');
  validRequired($pass_retype,'pass-retype');

  if(empty($err_msg)){

    //DB挿入
    $dbh = dbConnect();
    $data = array(':username'=>$username, ':email'=>$email, ':pass'=>password_hash($pass, PASSWORD_DEFAULT));
    $sql = 'INSERT INTO users (username,email,pass) VALUES (:username,:email,:pass)';
    $stmt = queryPost($dbh,$data,$sql);
    debug('ユーザー登録しました。');
    debug('ユーザー名：'.$username);

    //セッション
    if($stmt){
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $dbh->lastInsertId();
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      header("Location:index.php");
    }

  }else{
    debug('ユーザー登録できませんでした');
  }

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>「いま」を伝えよう</title>
</head>
<body>

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

  <script src="jquery-3.4.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="main.js"></script>
</body>
</html>