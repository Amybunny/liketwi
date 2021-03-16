<?php 

require('function.php');
//debug('「「「「「「「「「「「「「「「トップページ」」」」」」」」」」」」」」」');
require('auth.php');

$userData = getUser($_SESSION['user_id']);
//debug('ユーザーデータ：'.print_r($userData,true));
$username = getName($_SESSION['user_id']);
//debug('$username:'.print_r($username,true));

//DB登録処理
if(!empty($_POST['tweet'])){
  $tweet = $_POST['tweet'];
  registTweet($tweet);
  debug('ツイートした内容：'.$tweet);
};

if(!empty($_POST['words'])){
  $words = $_POST['words'];
  //debug('$words:'.print_r($words,true));
  $tweetData = searchTweet($words);
  //debug('$tweetData:'.print_r($tweetData,true));
}else{
  $tweetData = getTweet();
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>liketwi</title>
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
        <p style="text-align:center;"> <?php echo $username; ?> </p>
        <a class="sidebar-link" name="u_id" href="personalpage.php?u_id=<?php echo $_SESSION['user_id']?>">プロフィール</a>
        <a class="sidebar-link" href="logout.php">ログアウト</a>
        <a class="sidebar-link" href="msg.php">メッセージ</a>
        <form class="search-form" method="post">
          <input class="search-area" type="text" name="words" placeholder="キーワード検索">
          <input class="search-submit-btn" type="submit" value="検索">
        </form>
      </div>
    </section>

    <!--メイン-->
    <section id="topPageSection" class="mainPageCenter675">
      <br>
      <h1>今何してる？</h1>

      <!--ツイートエリア-->
      <form class="tweet-form" method="post">
        <textarea id="tweet-edit-area" name="tweet"></textarea>
        <p class="counter-text"><span class="" id="js-count-view">0</span>/70文字</p>
        <input class="tweet-submit-btn common-btn" type="submit" value="送信">
      </form>

      <!--表示エリア-->
      <div class="display-area">


        <?php foreach($tweetData as $key => $value) { ?>
          <div class="tweets">
            <div class="top-area">
              <a id="username" name="u_id" href="personalpage.php?u_id=<?php echo $value['u_id'] ?>"><?php echo sanitize($value['username']) ?></a>
              <?php if($value['u_id'] == $_SESSION['user_id']): ?>
                <a href="delete.php?t_id=<?php echo $value['t_id']; ?>" class="delete">×</a>
              <?php endif; ?>
            </div>
            
            <div id="tweet"><?php echo sanitize($value['tweet']); ?></div>
            <br>
            <div class="btm-area">
              <div class="btm-group tweet-time"><?php echo sanitize($value['tweet_time']); ?></div>
            </div>
          </div>
          <br>

      <?php } ?>

    </section>
    
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="jquery-3.4.1.min.js"></script>
  <script src="main.js"></script>
</body>
</html>


