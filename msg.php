<?php 

require('function.php');
require('auth.php');

$my_id = $_SESSION['user_id'];
$my_img = getImg($my_id);
$index = indexMsg($my_id);
$username = getName($_SESSION['user_id']);

if(!empty($_GET)){
  if(!empty($_GET['y_id'])){
    $y_id = $_GET['y_id'];
    $b_id = searchMsg($my_id,$y_id);
  }elseif(!empty($_GET['b_id'])){
    $b_id = $_GET['b_id'];
    $boardData = getInfo($b_id);
    if($boardData['user_a'] == $my_id){
      $y_id = $boardData['user_b'];
    }else{
      $y_id = $boardData['user_a'];
    }
  }
  $y_img = getImg($y_id);
  $viewmsg = getMsg($b_id);
  debug('$viewmsg:'.print_r($viewmsg,true));
  $p_name = getName($y_id);
}

if(!empty($_POST)){
  $msg = $_POST['send-msg'];

  if(!empty($msg)){
    $dbh = dbConnect();
    $data = array(':b_id'=>$b_id, ':my_id'=>$_SESSION['user_id'], ':y_id'=>$y_id, ':msg'=>$msg);
    $sql = 'INSERT into messages (board_id,from_user,to_user,msg) VALUES (:b_id,:my_id,:y_id,:msg)';
    $stmt = queryPost($dbh, $data, $sql);
    if($stmt){
      $_POST=array();
      header("Location:".$_SERVER['PHP_SELF'].'?b_id='.$b_id);
    }
  }
  
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css" type="text/css">
  <title>メッセージ</title>
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

    <section class="sidebar sidebar-center">
      <?php 
        foreach($index as $key => $val):
      ?>
        <a class="p_name" href="msg.php?b_id=<?php echo $val['id']; ?>"><?php echo $val['username']; ?></a>
      <?php 
        endforeach;
      ?>
    </section>

    <!--メイン-->
    <section class="mainPageCenter675 area-bord" id="js-scroll-bottom">

      <h1 class="msg-info">
        <?php
          if(!empty($p_name)){
            echo sanitize($p_name); 
          }
        ?>
      </h1>

      <div id="msg-area">

        <?php 
          if(!empty($viewmsg)):
          foreach($viewmsg as $key => $val):
          if(!empty($val['from_user']) && $val['from_user'] == $y_id):
        ?>

          <div class="msg-cnt msg-left">
            <img class="avatar" src="<?php echo showImg(sanitize($y_img)); ?>">
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              <?php if(!empty($val['msg'])) echo sanitize($val['msg']); ?>
            </p>
          </div>

        <?php 
          else:
        ?>

          <div class="msg-cnt msg-right">
            <img class="avatar" src="<?php echo showImg(sanitize($my_img)); ?>">
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              <?php if(!empty($val['msg'])) echo sanitize($val['msg']); ?>
            </p>
          </div>

        <?php
          endif;
          endforeach; 
          else:
        ?>

          <p id="info-txt">選択したメッセージはありません</p>

        <?php 
          endif; 
        ?>

      </div>

      <form method="post" class="area-send-msg">
        <?php if(!empty($b_id)): ?>
        <textarea name="send-msg" class="msg-textarea"></textarea>
        <input type="submit" value="送信" class="common-btn">
        <?php endif; ?>
      </form>


    </section>

    <script src="jquery-3.4.1.min.js"></script>
    <script>
      $(function(){
        $('#js-scroll-bottom').animate({scrollTop: $('#js-scroll-bottom')[0].scrollHeight}, 'fast');
      });
    </script>

  </main>
</body>
</html>