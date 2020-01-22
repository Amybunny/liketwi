$(function(){



  //メッセージ表示
  var $jsShowMsg = $('#js-show-msg');
  var msg = $jsShowMsg.text();
  console.log(msg);
  if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
    $jsShowMsg.slideToggle('slow');
    setTimeout(function(){ $jsShowMsg.slideToggle('slow')}, 5000);
  }


  //フォームのバリデーション
  const MSG_EMPTY = '入力必須です';
  
  $valid_text = $('.valid-text');
  $valid_pass = $('.valid-pass');
  $valid_pass_retype = $('.valid-pass-retype');

  function displayErrMsg($str){
    $str.on('keyup', function(e){
      var form_g = $(this).closest('.form-group');
      if($(this).val().length === 0){
        form_g.find('.msg-area').text(MSG_EMPTY);
      }else{
        form_g.find('.msg-area').text('');
      }
    });
  }

  displayErrMsg($valid_text);
  displayErrMsg($valid_pass);
  displayErrMsg($valid_pass_retype);

  //画像ライブプレビュー
  //DOMを変数に入れる
  var $dropArea = $('.area-drop');
  console.log($dropArea);
  var $fileInput = $('.input-file');
  console.log($fileInput);

  //フォームにドラッグしたら点線を表示
  $dropArea.on('dragover',function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border','3px #ccc dashed');
  });

  //ドラッグを離したら点線を消す
  $dropArea.on('dragleave',function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border','none');
  });

  //画像を表示させる
  $fileInput.on('change',function(e){
    $dropArea.css('border','none').css('background','none');
    var file = this.files[0],
        $img = $(this).siblings('.prev-img'),
        fileReader = new FileReader();
    console.log(file);
    console.log($img);
    console.log(fileReader);

    fileReader.onload = function(event){
      $img.attr('src',event.target.result).show();
    };

    fileReader.readAsDataURL(file);
  });

  //ツイートエリア
  $countUp = $('#tweet-edit-area');
  $countView = $('#js-count-view');
  $btn = $('.tweet-submit-btn');
  $val = $countUp.val().length;

  if($val === 0){
    $btn.prop("disabled",true);
  }
  
  $countUp.on('keyup', function(e){
    $countView.html($(this).val().length);
    $val = $countUp.val().length;
    if($val == 0){
      $btn.prop("disabled",true).removeClass('active');
    }else if($val >70){
      $countView.addClass('err');
      $btn.prop("disabled",true).removeClass('active');
    }else{
      $countView.removeClass('err');
      $btn.prop("disabled",false).addClass('active');
    }
  });





});