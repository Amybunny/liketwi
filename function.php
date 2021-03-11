<?php 
ini_set('error_log','php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}

//================================
// 定数
//================================
define('MSG01','入力必須です');
define('SUC01','ツイートを削除しました');
define('SUC02','プロフィールを変更しました');



//================================
// バリデーション関数
//================================
//配列$err_msgを用意
$err_msg = array();

//バリデーション関数（未入力チェック）
function validRequired($str,$key){
  if(empty($str)){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
session_save_path("/var/tmp/"); 
ini_set('session.gc_maxlifetime',60*60*24*30);
ini_set('session.cookie_lifetime',60*60*24*30);
session_start();
session_regenerate_id();

//================================
// データベース
//================================
//DB接続関数
function dbConnect(){
  $dsn = 'mysql:dbname=liketwi;host=localhost;charset=utf8';
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
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました。');
    return 0;
  }
  //debug('クエリ成功。');
  return $stmt;
}

//ユーザーデータ登録関数
function registUser($username,$pass){
  $dbh = dbConnect();
  $data = array(':username'=>$username, ':pass'=>$pass);
  $sql = 'INSERT INTO users (username,pass) VALUES (:username,:pass)';
  $stmt = queryPost($dbh,$data,$sql);
  return $stmt;
}

//ツイート取得関数
function getTweet(){
  $dbh = dbConnect();
  $data = array();
  $sql = 'SELECT twitter.id as t_id , tweet,tweet_time,username,users.id as u_id FROM twitter join users on twitter.u_id = users.id ORDER BY `twitter`.`id` DESC';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetchAll();
}

//個人のツイート取得関数
function getPersonalTweet($u_id){
  $dbh = dbConnect();
  $data = array(':u_id' => $u_id);
  $sql = 'SELECT users.id as u_id, tweet,tweet_time,username from twitter join users on twitter.u_id = users.id where u_id = :u_id';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetchAll();
}

//画像取得関数
function getImg($u_id){
  $dbh = dbConnect();
  $data = array(':u_id' => $u_id);
  $sql = 'SELECT img from users where id = :u_id';
  $stmt = queryPost($dbh, $data, $sql);
  if($stmt){
    foreach($stmt as $key => $val){
      return $val['img'];
    }
  }

}

//DB登録関数
function registTweet($tweet){
  $dbh = dbConnect();
  $sql = 'INSERT INTO twitter (u_id,tweet,tweet_time) VALUES (:u_id,:tweet,:tweet_time)';
  $data = array(':u_id' =>$_SESSION['user_id'],':tweet' => $tweet, 'tweet_time' => date('Y-m-d H:i:s'));
  $stmt = queryPost($dbh,$data,$sql);
  return $stmt;
}

//ユーザー情報取得関数
function getUser($u_id){
  $dbh = dbConnect();
  $data = array(':u_id' => $u_id);
  $sql = 'SELECT id,email,username,img FROM users WHERE id = :u_id';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

//メッセージ取得関数
function getMsg($b_id){
  $dbh = dbConnect();
  $data = array(':b_id' => $b_id);
  $sql = 'SELECT messages.id as m_id,from_user,to_user,msg,users.id as u_id,username from messages join users on messages.from_user = users.id where board_id = :b_id order by messages.id asc';
  $stmt = queryPost($dbh,$data, $sql);
  return $stmt->fetchAll();
}

//メッセージ検索関数
function searchMsg($my_id,$y_id){
  $dbh = dbConnect();
  $data = array(':my_id'=>$my_id,':y_id'=>$y_id);
  $sql = 'SELECT id from board where user_a = :y_id and user_b = :my_id';
  $rst = queryPost($dbh, $data, $sql);
  $stmt = $rst->fetch(PDO::FETCH_ASSOC);

  if(!empty($stmt)){
    foreach($stmt as $key => $val){
      return $val;
    }
  }else{
    $sql = 'SELECT id from board where user_a = :my_id and user_b = :y_id';
    $rst = queryPost($dbh, $data, $sql);
    $stmt = $rst->fetch(PDO::FETCH_ASSOC);
    if(!empty($stmt)){
      foreach($stmt as $key => $val){
        return $val;
      }
    }else{
      $sql = 'INSERT into board (user_a,user_b) value (:my_id,:y_id)';
      $rst = queryPost($dbh, $data, $sql);
      $stmt = $dbh->lastInsertId();
      debug('$stmt:'.print_r($stmt,true));
      return $stmt;
    }
  }

}

//名前取得関数
function getName($id){
  $dbh = dbConnect();
  $data = array(':id' => $id);
  $sql = 'SELECT username from users where id = :id';
  $stmt = queryPost($dbh, $data, $sql);
  $rst = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach($rst as $key => $val){
    return $val;
  }
}



//メッセージインデックス関数
function indexMsg($u_id){
  $dbh = dbConnect();
  $data = array(':u_id'=>$u_id);
  $sql = 'SELECT * from board where user_a = :u_id or user_b = :u_id';
  $stmt = queryPost($dbh, $data, $sql);
  $rst =  $stmt->fetchAll();

   foreach($rst as $key => $val){
     if($val['user_a'] == $u_id){
       $i_id = $val['user_b'];
     }else{
       $i_id = $val['user_a'];
     }
     $rst[$key]['username'] = getName($i_id); 
   }
   return $rst;
}


//掲示板情報取得関数
function getInfo($b_id){
  $dbh = dbConnect();
  $data = array(':b_id'=>$b_id);
  $sql = 'SELECT * from board where id = :b_id';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

//ツイート削除関数
function deleteTweet($del_tid){
  $dbh = dbConnect();
  $data = array(':t_id' => $del_tid);
  $sql = 'DELETE from twitter where id = :t_id';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt;
}

//ツイート検索関数
function searchTweet($words){
  $dbh = dbConnect();
  $newwords = '%'.$words.'%';
  $data = array(':words'=>$newwords);
  $sql = 'SELECT twitter.id as t_id, u_id,tweet,tweet_time,username from twitter join users on users.id = twitter.u_id where tweet like :words';
  $stmt = queryPost($dbh, $data, $sql);
  return $stmt->fetchAll();
}

//================================
// その他
//================================
// サニタイズ
function sanitize($str){
  return htmlspecialchars($str,ENT_QUOTES);
}

//phpエラーメッセージ表示
function phpErrMsg($str){
  global $err_msg;
  if(!empty($err_msg[$str])) echo $err_msg[$str];
}

//フォーム入力保持関数
function getFormData($str,$flg = false){
  if($flg){
    $method = $_GET;
  }else{
    $method = $_POST;
  }
  global $dbFormData;

  if(isset($method[$str])){
    return $method[$str];
  }elseif(empty($method[$str]) && !empty($dbFormData[$str])){
    return $dbFormData[$str];
  }
}

//画像アップロード
function uploadImg($file,$key){
  error_log('画像アップロード処理開始');
  debug('$file',$file);

  if(isset($file['error']) && is_int($file['error'])){
    debug('$file[error]',$file['error']);
    try{
      switch($file['error']){
          case UPLOAD_ERR_OK:
              error_log('OK');
              break;
          case UPLOAD_ERR_NO_FILE:
              throw new RuntimeException('ファイルが選択されていません');
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
              throw new RuntimeException('ファイルサイズが大きすぎます');
          default:
              throw new RuntimeException('その他のエラーが発生しました');
      }

      $type = @exif_imagetype($file['tmp_name']);
      if(!in_array($type,[IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG],true)){
        throw new RuntimeException('画像形式が未対応です');
      }

      $path = 'upload/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      debug('$path:'.print_r($path,true));

      if(!move_uploaded_file($file['tmp_name'],$path)){
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }

      chmod($path,0644);
      error_log('ファイルは正常にアップロードされました');



      return $path;

    }catch(RuntimeException $e){
      error_log($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }
  }
}

//SESSIONを1回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    debug('$dataの中身：'.print_r($data,true));
    $_SESSION[$key] = '';
    return $data;
  }
}

//画像表示用関数
function showImg($path){
  if(empty($path)){
    return 'img/sample-img.png';
  }else{
    return $path;
  }
}


?>