<div class="jumbotron">
	<h1>アカウント登録 <small> New Account</h1>
	<hr />
	<br />
	<?=$this->Form->create(false, array('class' => 'form-horizontal', 'inputDefaults' => array(
			'label' => false, 'div' => false, 'error' => array('attributes' => array('wrap' => 'p', 'class' => 'text-error'))
		)));?>
		<div class="control-group">
			<label class="control-label" for="inputName">ユーザー名</label>
			<div class="controls"><?=$this->Form->input('inputName', array('value' => $name, 'class' => 'input-xlarge', 'id' => 'inputName', 'disabled' => 'true'));?></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputMail">メールアドレス</label>
			<div class="controls"><?=$this->Form->input('inputMail', array('value' => $mail, 'class' => 'input-xlarge', 'id' => 'inputMail', 'disabled' => 'true'));?></div>
		</div>
		<div id="err_pass1" class="control-group">
			<label class="control-label" for="pass1">パスワード</label>
			<div class="controls"><?=$this->Form->input('pass1', array('placeholder' => 'Password..', 'class' => 'input-xlarge', 'id' => 'pass1', 'type' => 'password'));?>
			 <span class="help-block">6文字以上、100文字以内で設定してください</span></div>
		</div>
		<div id="err_pass2" class="control-group">
			<label class="control-label" for="pass2">パスワード(確認)</label>
			<div class="controls"><?=$this->Form->input('pass2', array('placeholder' => 'Confirm password..', 'class' => 'input-xlarge', 'id' => 'pass2', 'type' => 'password'));?>
			 <span id="err_pass2_msg" class="help-block" style="display: none;">確認のパスワードが一致していません！</span></div>
		</div>
		<div id="err_agreed" class="control-group">
			<div class="controls">
				<label class="checkbox"><?=$this->Form->input('agreed', array('id' => 'agreed', 'type' => 'checkbox'));?>利用規約に同意します</label>
				<span id="err_agreed_msg" class="help-block" style="display: none;">続行するには、利用規約に同意していただく必要があります</span>
			</div>
		</div>
		<div class="controls"><?=$this->Form->submit('アカウントを登録する', array('id' => 'submit', 'class' => 'btn btn-large btn-primary', 'onClick' => 'return validate();'));?></div>
	<?=$this->Form->end();?>
</div>
<script type="text/javascript" charset="utf-8">
	function validate(){
		var error = false;

		// pass strength
		var tmp = $("#pass1").val().length;
		if (isNaN(tmp) || tmp < 6 || tmp > 100){
			$("#err_pass1").addClass("error");
			error = true;
		}else{
			$("#err_pass1").removeClass("error");
		}

		// compare
		var tmp = $("#pass1").val().length;
		var tmp2 = ($("#pass1").val() !== $("#pass2").val());
		if (isNaN(tmp) || tmp < 6 || tmp > 100 || tmp2){
			$("#err_pass2").addClass("error");
			if (tmp2){
				$("#err_pass2_msg").show();
			}
			error = true;
		}else{
			$("#err_pass2").removeClass("error");
		}
		if (!tmp2){
			$("#err_pass2_msg").hide();
		}

		// checkbox
		if (!$("#agreed").attr('checked')){
			$("#err_agreed").addClass("error");
			$('#err_agreed_msg').show();
			error = true;
		}else{
			$("#err_agreed").removeClass("error");
			$("#err_agreed_msg").hide();
		}

		return (!error);
	}
</script>