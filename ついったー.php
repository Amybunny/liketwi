<?php 

ini_set('error_log','php.log');

$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}

//DB接続関数
function dbConnect(){
  $dsn = 'mysql:dbname=otameshi;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  $dbh = new PDO($dsn,$user,$password,$options);
  return $dbh;
}

//SQL実行関数
function queryPost($dbh,$data,$sql){
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  return $stmt;
}

//ツイート取得関数
function getTweet($i){
  $dbh = dbConnect();
  $data = array(':id'=>$i);
  $sql = 'SELECT * FROM twitter WHERE id=:id';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetchAll();
}

//DB登録関数
function registTweet($tweet){
  $dbh = dbConnect();
  $sql = 'INSERT INTO twitter (tweet,tweet_time) VALUES (:tweet,:tweet_time)';
  $data = array(':tweet' => $tweet, 'tweet_time' => date('Y-m-d H:i:s'));
  $stmt = queryPost($dbh,$data,$sql);
  return $stmt;
}

//件数取得関数
function getQuantity(){
  $dbh = dbConnect();
  $data = array();
  $sql = 'SELECT id FROM twitter';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetchAll();
}

//DB登録処理
if(!empty($_POST)){
  $tweet = $_POST['tweet'];
  registTweet($tweet);
  debug('ツイートした内容：'.$tweet);
};



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>ついったー</title>
</head>
<body>
  <br>
  <h1>今何してる？</h1>

  <form method="post">
    <textarea name="tweet"></textarea>
    <input type="submit" value="送信">
  </form>

  <div class="display-area">
    <?php 
    //データ取得処理
    $quantity = getQuantity();
    $num = count($quantity);
    //var_dump($num);

    for($i=$num;$i>=1;$i--){
      $tweetData = getTweet($i);
      //debug('取得したデータ：'.print_r($tweetData,true));
      foreach($tweetData as $key => $value){ 
    ?>

      <div class="tweets">
        <div id="tweet"><?php echo $value['tweet']; ?></div>
        <br>
        <div id="tweet_time"><?php echo $value['tweet_time']; ?></div>
      </div>
      <br>

    <?php 
      }
    }
    ?>
  </div>

</body>
</html>


