<?php 

require('function.php');

if(!empty($_GET["guest"])){
  $email = "guest@guest.com";
  $pass = "guest";

  $dbh = dbConnect();
  $data = array(':email' => $email);
  $sql = 'SELECT pass,id  FROM users WHERE email = :email';
  $stmt = queryPost($dbh , $data, $sql);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if(!empty($result) && password_verify($pass,array_shift($result))){
    $sesLimit = 60*60;
    $_SESSION['login_date'] = time();
    $_SESSION['login_limit'] = $sesLimit;
    $_SESSION['user_id'] = $result['id'];

    header("Location:index.php");
  }else{
    header("Location:login.php");
  }
}

if(!empty($_POST)){
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  
  validRequired($email,'email');
  validRequired($pass,'pass');

  if(empty($err_msg)){

    $dbh = dbConnect();
    $data = array(':email' => $email);
    $sql = 'SELECT pass,id  FROM users WHERE email = :email';
    $stmt = queryPost($dbh , $data, $sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($result) && password_verify($pass,array_shift($result))){
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $result['id'];

      header("Location:index.php");
    }else{
      header("Location:login.php");
    }
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
    <title>ログイン</title>
</head>
<body>
  <div class="common-btn active"><a href="login.php?guest=1">ゲストログイン</a></div>
  <div class="common-btn active"><a href="signup.php">登録する</a></div>
  <div class="login-area">
    <h1 class="common-h1">ログイン</h1>
    <form class="common-form" method="post">
      <div class="form-group">
        <div class="msg-area"><?php phpErrMsg('email'); ?></div>
        <input type="text" name="email" class="input-common valid-text" placeholder="メールアドレス">
      </div>
      <div class="form-group">
        <div class="msg-area"><?php phpErrMsg('pass'); ?></div>
        <input type="password" name="pass" class="input-common valid-pass" placeholder="パスワード">
      </div>
      <input type="submit" class="common-btn active" value='ログイン'>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="main.js"></script>
</body>
</html>