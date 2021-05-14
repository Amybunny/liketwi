<?php 

require('function.php');
require('auth.php');

if(!empty($_SESSION['user_id'])){
  $dbFormData = getUser($_SESSION['user_id']);
}

if(!empty($_POST)){
  if($_SESSION['user_id']==6){
    header("Location:index.php");
  }else{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $img= (!empty($_FILES['image']['name'])) ? uploadImg($_FILES['image'],'image') : '';
    $img = ( empty($img) && !empty($dbFormData['img']) ) ? $dbFormData['img'] : $img;

    $dbh = dbConnect();
    $data = array(':u_name'=>$username, ':email'=>$email, ':img'=>$img, ':u_id'=>$dbFormData['id']);
    $sql = 'UPDATE users SET username=:u_name, email=:email, img=:img where id =:u_id';
    $stmt = queryPost($dbh, $data, $sql);

    if($stmt){
      $_SESSION['msg_success'] = SUC02;
      header("Location:index.php");
    }else{
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
  <title>プロフィール編集</title>
</head>
<body>

  <main class="mainPage">

    <!--サイドバー左-->
    <section class="sidebar sidebar-left">
    <h1><a class="title" href="index.php">Like Twi</a></h1>
    <div class="sidebar-group">
      <a class="sidebar-link" name="u_id" href="personalpage.php?u_id=<?php echo $_SESSION['user_id']?>">プロフィール</a>
      <a class="sidebar-link" href="logout.php">ログアウト</a>
      <a class="sidebar-link" href="msg.php">メッセージ</a>
    </div>
    </section>

    <!--真ん中-->
    <form method="post" class="mainPageCenter675" enctype="multipart/form-data">
      <br>
      <h1>プロフィール編集</h1>

      <div class="form-container">
        <label>
          名前
          <input class="input-common" type="text" name="username" value="<?php echo getFormData('username'); ?>">
        </label>
      </div>
      <div class="form-container">
        <label>
          Email
          <input class="input-common" type="text" name="email" value="<?php echo getFormData('email');?>">
        </label>
      </div>

      <div class="imgDrop-container">
        <p class="img-label">画像</p>
        <label class="area-drop">
          <input type="hidden" name="MAX_FILE_SIZE" value="3145728"><!--スタイルに影響なし-->
          <input type="file" name="image" class="input-file"><!--画像をアップロードするフォーム-->
          <img src="<?php echo getFormData('img'); ?>" class="prev-img">
          ドラッグ＆ドロップ
        </label>
      </div>

      <div class="form-container">
        <input class="common-btn" type="submit" value="変更する">
      <div class="form-container">

    </form>

  </main>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="jquery-3.4.1.min.js"></script>
<script src="main.js"></script>
</body>
</html>
