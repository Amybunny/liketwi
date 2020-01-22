  //フォームのバリデーション
  const MSG_EMPTY = '入力必須です';
  
  $valid_text = $('.valid_text');
  $valid_pass = $('.valid_pass');
  $valid_pass_retype = $('.valid_pass_retype');

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