<?php 

require('function.php');
require('auth.php');

$u_id = $_GET['u_id'];
$img = getImg($u_id);
$pTweet = getPersonalTweet($u_id); 
if($pTweet[0]['u_id'] == $_SESSION['user_id']){
  $myFlg = true;
}elseif(empty($pTweet[0]['u_id'])){
  $myFlg = true;
}else{
  $myFlg = false;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>Like Twi</title>
</head>
<body>
  <main class="mainPage">
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>
    <!--サイドバー左-->
    <section class="sidebar sidebar-left">
      <h1><a class="title" href="index.php">Like Twi</a></h1>
      <div class="sidebar-group">
        <a class="sidebar-link" href="personalpage.php?u_id=<?php echo $_SESSION['user_id']?>"">プロフィール</a>
        <a class="sidebar-link" href="logout.php">ログアウト</a>
        <a class="sidebar-link" href="msg.php">メッセージ</a>
      </div>
    </section>

    <!--メイン-->
    <section class="mainPageCenter675">

      <!--プロフィールエリア-->
      <div class="prof-area">
        <img class="prof-img" src="<?php echo sanitize($img); ?>" alt="">
        <h1 class="prof-name">
          <?php if($myFlg == true){echo sanitize(getName($_SESSION['user_id']));}else{echo getName($u_id);}  ?>
        </h1>
        <?php if($myFlg == false): ?>
          <a class="prof-msg" href="msg.php?y_id=<?php echo $u_id; ?>">
            メッセージ
          </a>
        <?php else: ?>
          <a class="prof-msg" href="profEdit.php">
            プロフィール編集
          </a>
        <?php endif; ?>
      </div>

      <!--表示エリア-->
      <div class="display-area">
        <?php foreach($pTweet as $key => $value) : ?>
          <div class="tweets">
            <div class="top-area">
            <a id="username" name="u_id" href="personalpage.php?u_id=<?php echo $value['u_id'] ?>"><?php echo sanitize($value['username']) ?></a>
            <?php if($value['u_id'] == $_SESSION['user_id']): ?>
                <a href="delete.php?t_id=<?php echo $value['t_id']; ?>&pflg=1" class="delete">×</a>
            <?php endif; ?>
            </div>
            
            <div id="tweet"><?php echo sanitize($value['tweet']); ?></div>
            <br>
            <div class="btm-area">
              <div class="btm-group tweet-time"><?php echo sanitize($value['tweet_time']); ?></div>
            </div>
          </div>
          <br>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="main.js"></script>
</body>
</html>