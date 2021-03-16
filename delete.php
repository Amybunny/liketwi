<?php 

require('function.php');
require('auth.php');

$username = getName($_SESSION['user_id']);

if(!empty($_POST)){
  $t_id = $_POST['delete'];
  debug('$t_id:'.print_r($t_id,true));
  $deleteFlg = deleteTweet($t_id);
  if($deleteFlg){
    debug('削除しました');
    debug('トップページへ遷移します');
    $_SESSION['msg_success'] = SUC01;
    header("Location:index.php");
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>削除ページ</title>
</head>
<body>
  <main class="mainPage">
    <!--サイドバー左-->
    <section class="sidebar sidebar-left">
      <h1><a class="title" href="index.php">Like Twi</a></h1>
      <div class="sidebar-group">
        <p style="text-align:center;"> <?php echo $username; ?> </p>
        <a class="sidebar-link" name="u_id" href="personalpage.php?u_id=<?php echo $_SESSION['user_id']?>">プロフィール</a>
        <a class="sidebar-link" href="logout.php">ログアウト</a>
        <a class="sidebar-link" href="msg.php">メッセージ</a>
      </div>
    </section>

    <section class="mainPageCenter675">
      <div class="delete-form">
        <p class="question">本当に削除しますか？</p>
        <form class="wrap" action="" method="post">
          <input type="hidden" name="delete" value="<?php echo $_GET['t_id']; ?>">
          <a href="index.php" class="btn cansel-btn">キャンセル</a>
          <input class="btn submit-btn" type="submit" value="削除">
        </form>
      </div>
    </section>
  </main>
</body>
</html>
