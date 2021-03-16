<?php 

require('function.php');
//debug('「「「「「「「「「「「「「「「ログインページ」」」」」」」」」」」」」」」');
//require('auth.php');

if(!empty($_POST)){
  //debug('ログインページのPOST送信があります。');
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  //debug('入力されたパスワード：'.$pass);

  validRequired($email,'email');
  validRequired($pass,'pass');

  if(empty($err_msg)){

    $dbh = dbConnect();
    $data = array(':email' => $email);
    $sql = 'SELECT pass,id  FROM users WHERE email = :email';
    $stmt = queryPost($dbh , $data, $sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    debug('クエリ結果の中身：'.print_r($result,true));

    if(!empty($result) && password_verify($pass,array_shift($result))){
      debug('パスワードがマッチしました。');
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $result['id'];

      debug('セッション変数の中身：'.print_r($_SESSION,true));

      debug('トップページへ遷移します。');
      header("Location:index.php");
    }else{
      debug('パスワードがアンマッチです。');
    }
  }  
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>ログイン</title>
</head>
<body>

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

  <script src="jquery-3.4.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="main.js"></script>
</body>
</html>